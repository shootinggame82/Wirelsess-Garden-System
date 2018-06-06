/*
 * The wireless waterpump is simple, when reciving signal, the pump system will trigger an relay
 * Use it with an pump or other water system
 * Define the sensor
 */
#include <SPI.h>
#include "nRF24L01.h"
#include "RF24.h"
#define PUMP1 //Define sensor here
#ifdef PUMP1
int sendID = 2501;
#endif
#ifdef PUMP2
int sendID = 2502;
#endif
#ifdef PUMP3
int sendID = 2503;
#endif
//The information from the base system

struct paketet
{
        int O=1; // The ID number
        int P=0; // If the pump is going to be on or off
};
typedef struct paketet Package;
Package info;

RF24 myRadio (7, 8); //Connection for the NRF
const uint64_t addresses[3] = { 0xF0F0F0F0E1LL, 0xABCDABCD71LL, 0xF0F0F0F0C3LL };
//RGB Led Pins
int redPin = 6;
int greenPin = 5;
int bluePin = 3;
//Relay Pin
int relaPin = 10;

#define COMMON_ANODE //If you are using Common Anode RGB led (VCC instead of GND on RGB Led)


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



void loop() {
  
        if ( myRadio.available(addresses[1]));
        {
                while (myRadio.available(addresses[1]))
                {
                        myRadio.read( &info, sizeof(info) );

                        if (info.O == sendID) {
                                /*
                                 * If its the correct pump id number
                                 */

                                if (info.P == 0) {
                                        digitalWrite(relaPin, HIGH);
                                        setColor(0, 255, 0);
                                        Serial.println("Pump inactivated");
                                        info.P = 0;
                                } else if (info.P == 1) {
                                        digitalWrite(relaPin, LOW);
                                        setColor(0, 0, 255);
                                        Serial.println("Pump activated");
                                        info.P = 1;
                                }

                        }
                }

        }




}
