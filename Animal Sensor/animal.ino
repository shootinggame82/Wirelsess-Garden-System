/*
* Arduino Animal Sensor. Define the sensor you are going to upload to so you get the correct ID number
* A simple led is used to inform about movement and low power.
* You need the NRF library and the LowPower library to make it work (check the library folder on my github)
* If you are testing and need serial printing define TEST and if you want battery check define BATTERI
*/
// **** INCLUDES *****
#include "LowPower.h"
#include <SPI.h>
#include "nRF24L01.h"
#include "RF24.h"

#define SENS2 //Define the correct sesor here
#define TEST_EJ //Change to TEST if you need serial printing
#define BATTERI // If this is defined BATTERI it will check the battery level.

// Use pin 2 as wake up pin
const int wakeUpPin = 2;
const int LedPin = 5;

volatile int lastPIRsensorState = 1;  // previous sensor state
volatile int PIRsensorState = 0;   // current state of the button

 #ifdef SENS1
int sendID = 6501;
 #endif
 #ifdef SENS2
int sendID = 6502;
 #endif
 #ifdef SENS3
int sendID = 6503;
 #endif
#ifdef SENS4
int sendID = 6504;
 #endif
#ifdef SENS5
int sendID = 6505;
 #endif

 struct vilt
{
        int D=sendID; // The animal sensors id number
        int E=0; // If there pir sensor see movements
        float F=1; // Battery level
};

typedef struct vilt Package1;
Package1 sendback;

RF24 myRadio (7, 8); //Connections for the NRF24L01
const uint64_t addresses[4] = { 0xF0F0F0F0E1LL, 0xABCDABCD71LL, 0x3A3A3A3AD2LL, 0xF0F0F0F0E3LL };

long readVcc() {
  // Read 1.1V reference against AVcc
  // set the reference to Vcc and the measurement to the internal 1.1V reference
  #if defined(__AVR_ATmega32U4__) || defined(__AVR_ATmega1280__) || defined(__AVR_ATmega2560__)
    ADMUX = _BV(REFS0) | _BV(MUX4) | _BV(MUX3) | _BV(MUX2) | _BV(MUX1);
  #elif defined (__AVR_ATtiny24__) || defined(__AVR_ATtiny44__) || defined(__AVR_ATtiny84__)
    ADMUX = _BV(MUX5) | _BV(MUX0);
  #elif defined (__AVR_ATtiny25__) || defined(__AVR_ATtiny45__) || defined(__AVR_ATtiny85__)
    ADMUX = _BV(MUX3) | _BV(MUX2);
  #else
    ADMUX = _BV(REFS0) | _BV(MUX3) | _BV(MUX2) | _BV(MUX1);
  #endif  

  delay(2); // Wait for Vref to settle
  ADCSRA |= _BV(ADSC); // Start conversion
  while (bit_is_set(ADCSRA,ADSC)); // measuring

  uint8_t low  = ADCL; // must read ADCL first - it then locks ADCH  
  uint8_t high = ADCH; // unlocks both

  long result = (high<<8) | low;

  result = 1125300L / result; // Calculate Vcc (in mV); 1125300 = 1.1*1023*1000
  return result; // Vcc in millivolts
}

void wakeUp()
{
    // Just a handler for the pin interrupt.
    PIRsensorState = !lastPIRsensorState;
}

void printVolts()
{
        /*
         * This function checks the battery level (checked thru the AVcc on the ATMEGA chip
         */
        sendback.F = readVcc ();
        if (sendback.F < 3200) //In millivolt when to alarm about low power
        {
                digitalWrite(LedPin, HIGH);
                delay(500);
                digitalWrite(LedPin, LOW);
                delay(500);
                digitalWrite(LedPin, HIGH);
                delay(500);
                digitalWrite(LedPin, LOW);
        }
}

void setup()
{
    // Configure wake up pin as input.
    // This will consumes few uA of current.
    #ifdef TEST
    Serial.begin(9600);
    #endif
    pinMode(0, INPUT); 
    pinMode(LedPin, OUTPUT);
    #ifdef TEST
    Serial.print("Loading.");
    #endif
    for( int i = 1; i <= 20; i++){  
      #ifdef TEST
      Serial.print(".");
      #endif
    digitalWrite(LedPin, HIGH); 
    delay(100);         
    digitalWrite(LedPin, LOW); 
    delay(100); 
 }
 #ifdef TEST
 Serial.print(". Pir Sensor Ready");
 #endif  
 delay(100);
        myRadio.begin();
        myRadio.setPALevel(RF24_PA_LOW); //Low is best when testing
        myRadio.setDataRate( RF24_1MBPS );
        myRadio.setRetries(15, 15);
        myRadio.setChannel(125);
        myRadio.openReadingPipe(1, addresses[2]);
        myRadio.openWritingPipe(addresses[3]);
        delay(100);
}

void loop() 
{
    // Allow wake up pin to trigger interrupt on low.
    
    attachInterrupt(0, wakeUp, CHANGE);
    
    // Enter power down state with ADC and BOD module disabled.
    // Wake up when wake up pin is low.
    LowPower.powerDown(SLEEP_FOREVER, ADC_OFF, BOD_OFF); 
    
    // Disable external pin interrupt on wake up pin.
    detachInterrupt(0); 
    
    // Check the sensor and transmitt if needed
    
    
    if (PIRsensorState != lastPIRsensorState){

  if (PIRsensorState == 1) {
     digitalWrite(LedPin, LOW);
     #ifdef TEST
     Serial.print("Sleeping-");            // enable for debugging
     Serial.println(PIRsensorState);   // read status of interrupt pin
     #endif
     sendback.E = 0;
     myRadio.powerDown();
  }
  
  else {
    myRadio.powerUp();

      // Wait for radio to power up
     delay(2);       
     digitalWrite(LedPin, HIGH); 
     sendback.E = 1;
     #ifdef TEST
     Serial.print("Awake-");    // enable for debugging
    Serial.println(PIRsensorState);  // read status of interrupt pin   enable for debugging
    #endif
    #ifdef BATTERI
     printVolts();
     #endif
     delay(50);
     myRadio.stopListening();
     myRadio.write(&sendback, sizeof(sendback));
     
      }
  }

   lastPIRsensorState = PIRsensorState;    // reset lastinterrupt state
   delay(50);
}
