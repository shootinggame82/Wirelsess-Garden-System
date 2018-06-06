/*
 * Siren mottagaren används för att aktivera en kraftig siren som skall skrämma iväg vilda djur.
 * Rörelse sensorerna registrerar rörelse och om dom är i ett aktivt läge så kommer huvud enheten att aktivera
 * sirenen under en kort tid.
 * Sirenerna börjar på 85XX och fortsätter till 90000
 * Triggar ett relä som aktiverar en kraftig siren.
 */
#include <Arduino.h>
#include <SPI.h>
#include "nRF24L01.h"
#include "RF24.h"

#define SIREN1
#ifdef SIREN1
int sendID = 8501;
#endif
#ifdef SIREN2
int sendID = 8502;
#endif

struct paketet
{
        int I=1; // Detta är sändarens id nr
        int J=0; // Detta är statusen på sirenen (1 för på 0 för av)
        int K=5000;
};
typedef struct paketet Package;
Package sirenen;

RF24 myRadio (7, 8); //Anslutning för nRF24L01
const uint64_t addresses[4] = { 0xF0F0F0F0E1LL, 0xABCDABCD71LL, 0xF0F0F0F0C3LL, 0xF0F0F0F0C1LL };

int redPin = 6;
int greenPin = 5;
int bluePin = 3;

int relaPin = 10;

#define COMMON_ANODE

void setColor(int red, int green, int blue) {
  #ifdef COMMON_ANODE
        red = 255 - red;
        green = 255 - green;
        blue = 255 - blue;
#endif
        analogWrite(redPin, red);
        analogWrite(greenPin, green);
        analogWrite(bluePin, blue);
}

void setup() {
        Serial.begin(9600);
        pinMode(redPin, OUTPUT);
        pinMode(greenPin, OUTPUT);
        pinMode(bluePin, OUTPUT);

        pinMode(relaPin, OUTPUT);
        digitalWrite(relaPin, LOW);

        delay(100);
        myRadio.begin();
        myRadio.setPALevel(RF24_PA_LOW);
        myRadio.setDataRate( RF24_1MBPS );
        myRadio.setRetries(15, 15);
        myRadio.setChannel(125);
        myRadio.openReadingPipe(1, addresses[3]);
        myRadio.startListening();
        delay(100);
        setColor(0, 255, 0);
}

void sirenRun() {
        //Kollar om läget är att starta eller stänga av siren. Sirenen körs kortare stunder bara.
        if (sirenen.J == 1) {
                digitalWrite(relaPin, HIGH);
                setColor(0, 0, 255);
                delay(sirenen.K);
                sirenen.J = 0;
                setColor(0, 255, 0);
        } else if (sirenen.J == 0) {
                digitalWrite(relaPin, LOW);
                setColor(0, 255, 0);
        }

}

void loop() {
        if ( myRadio.available(addresses[3]));
        {
                while (myRadio.available(addresses[3]))
                {
                        myRadio.read( &sirenen, sizeof(sirenen) );
                        //  Serial.println(info.O);

                        if (sirenen.I == sendID) {
                                /*
                                 * Vi jämför ID nummer och kontrollerar så det är vårat, om det är det utför uppgiften
                                 */

                                if (sirenen.J == 0) {
                                        digitalWrite(relaPin, LOW);
                                        setColor(0, 255, 0);
                                        Serial.println("Sirenen inaktiverad");
                                        sirenen.J = 0;
                                } else if (sirenen.J == 1) {

                                        Serial.println("Siren aktiverad");
                                        digitalWrite(relaPin, HIGH);
                                        setColor(0, 0, 255);
                                        delay(sirenen.K);
                                        digitalWrite(relaPin, LOW);
                                        setColor(0, 255, 0);
                                        sirenen.J = 0;
                                }

                        }
                }

        }
}
