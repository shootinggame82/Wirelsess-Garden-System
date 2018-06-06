/*
 * The wireless siren sensor will be activated only when the animal sensor is in active mode an register som movements.
 * The base system will send an activate signal and for how long it will be running.
 * Define the right siren if you have more than one.
 */
#include <SPI.h>
#include "nRF24L01.h"
#include "RF24.h"

#define SIREN1 //Define siren here
#ifdef SIREN1
int sendID = 8501;
#endif
#ifdef SIREN2
int sendID = 8502;
#endif

struct paketet
{
        int I=1; // The id number for the siren
        int J=0; // If the siren is on or off
        int K=5000; //For how long in millisecounds it will sound.
};
typedef struct paketet Package;
Package sirenen;

RF24 myRadio (7, 8); //Connection for nRF24L01
const uint64_t addresses[4] = { 0xF0F0F0F0E1LL, 0xABCDABCD71LL, 0xF0F0F0F0C3LL, 0xF0F0F0F0C1LL };
//RGB Led pins
int redPin = 6;
int greenPin = 5;
int bluePin = 3;
//Relay pin
int relaPin = 10;

#define COMMON_ANODE //If you have Common anode RGB Led (VCC instead of GND)

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


void loop() {
        if ( myRadio.available(addresses[3]));
        {
                while (myRadio.available(addresses[3]))
                {
                        myRadio.read( &sirenen, sizeof(sirenen) );
                        

                        if (sirenen.I == sendID) {
                                /*
                                 * Check if it the right id number an update status
                                 */

                                if (sirenen.J == 0) {
                                        digitalWrite(relaPin, LOW);
                                        setColor(0, 255, 0);
                                        Serial.println("Siren off");
                                        sirenen.J = 0;
                                } else if (sirenen.J == 1) {

                                        Serial.println("Siren on");
                                        digitalWrite(relaPin, HIGH);
                                        setColor(0, 0, 255);
                                        delay(sirenen.K); //The system is transmitting how long it will be on.
                                        digitalWrite(relaPin, LOW);
                                        setColor(0, 255, 0);
                                        sirenen.J = 0;
                                }

                        }
                }

        }
}
