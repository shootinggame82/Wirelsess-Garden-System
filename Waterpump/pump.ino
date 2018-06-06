/*
 * Relä boxarna inväntar rätt sensor nummer för att sköta på och av
 * Huvud enheten skickar ut sensor nummer och om dom skall slås på eller av.
 * När relä boxen får signalen så kommer den att utföra önskad funktion.
 * Relä styrningarna börjar på 25
 */
#include <Arduino.h>
#include <SPI.h>
#include "nRF24L01.h"
#include "RF24.h"
#define PUMP3
#define BATTERI_NEJ
#ifdef PUMP1
int sendID = 2501;
#endif
#ifdef PUMP2
int sendID = 2502;
#endif
#ifdef PUMP3
int sendID = 2503;
#endif
//Här erhåller vi värden från jordsensorerna
struct package
{
        int X=sendID; // Detta är sändarens id nr
        int Y=0; // Detta är jordens fuktighetsvärde
        float Z=0; //Detta är jordens temperatur
        float F=0; //Detta är voltem
};
typedef struct package Package1;
Package1 data;
struct paketet
{
        int O=1; // Detta är sändarens id nr
        int P=0; // Detta är statusen på pumpen (1 för på 0 för av)
};
typedef struct paketet Package;
Package info;

RF24 myRadio (7, 8); //Anslutning för nRF24L01
const uint64_t addresses[3] = { 0xF0F0F0F0E1LL, 0xABCDABCD71LL, 0xF0F0F0F0C3LL };

int redPin = 6;
int greenPin = 5;
int bluePin = 3;

int relaPin = 10;

unsigned long lastConnectionTime = 0;                 // last time you connected to the server, in milliseconds
const unsigned long postingInterval = 300000; // delay between updates, in milliseconds

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
        digitalWrite(relaPin, HIGH);

        delay(100);
        myRadio.begin();
        myRadio.setPALevel(RF24_PA_LOW);
        myRadio.setDataRate( RF24_1MBPS );
        myRadio.setRetries(15, 15);
        myRadio.setChannel(125);
        myRadio.openReadingPipe(1, addresses[1]);
        myRadio.openWritingPipe(addresses[2]);
        myRadio.startListening();
        delay(100);
        setColor(0, 255, 0);
}

void printVolts()
{
        /*
         * Denna funktion avläser batteriet och om det sjunker under önskad nivå så ska röd färg lysa på dioden. Lägg till printVolts() i loopen om ni vill använda batteri varnare.
         */
        int sensorValue = analogRead(A0); //Via motstånd läser vi av hur många volt det är
        float voltage = sensorValue * (5.00 / 1023.00) * 2; //konverterar till korrekt volt
        if (voltage < 6.50) //Ställ in lägsta nivå för larm av batteri.
        {
                setColor(255, 0, 0);
        }
}

void autoOff() {
        /*
         * Pumpen stängs automatiskt av om den har varit aktiv i 10 minuter
         * Vi skickar tillbaka ett värde som ska meddela servern om att pumpen är avstängd.
         */
        if (info.P == 1) {

                myRadio.openWritingPipe(addresses[2]);
                info.P = 0;
                digitalWrite(relaPin, HIGH);
                setColor(0, 255, 0);
                myRadio.stopListening();
                myRadio.write(&info, sizeof(info));
                Serial.println("Skickar pumpdata inaktiv 10 min");
                delay(1000);
                myRadio.write(&info, sizeof(info));
                myRadio.startListening();
                lastConnectionTime = millis();
                Serial.println("Avslutar autoOff");
        }

}

void loop() {
  #ifdef BATTERI
        printVolts();
  #endif
        /*      if (info.P == 1) {

                      if (millis() - lastConnectionTime > postingInterval) {
                              autoOff();

                      }
              } */
        if ( myRadio.available(addresses[1]));
        {
                while (myRadio.available(addresses[1]))
                {
                        myRadio.read( &info, sizeof(info) );
                        //  Serial.println(info.O);

                        if (info.O == sendID) {
                                /*
                                 * Vi jämför ID nummer och kontrollerar så det är vårat, om det är det utför uppgiften
                                 */

                                if (info.P == 0) {
                                        digitalWrite(relaPin, HIGH);
                                        setColor(0, 255, 0);
                                        Serial.println("Pump inaktiverad");
                                        info.P = 0;
                                } else if (info.P == 1) {
                                        digitalWrite(relaPin, LOW);
                                        setColor(0, 0, 255);
                                        Serial.println("Pump aktiverad");
                                        info.P = 1;
                                }

                        }
                }

        }




}
