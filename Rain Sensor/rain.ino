/*
* This is the rain sensor for the garden system.
* It uses ATMEGA chip for low power use.
* Define the correct sensor number, normaly you don't need more than one.
* If you need Serial print for testing, Define TEST
*/
#define SENS1 //Define the sensor
#define TEST_EJ //Define TEST for serial print
#define BATTERI //Change this if you don't want to check battery level
#include <avr/sleep.h>
#include <avr/power.h>
#include <avr/wdt.h>
#include <SPI.h>
#include "nRF24L01.h"
#include "RF24.h"

void enterSleep();
//For sleep mode
volatile int f_wdt = 1;
int counter = 0;
int packetCounter = 0;
//For rain sensor
int nRainIn = A0; //Analog reading pin
int nRainDigitalIn = 4; //Digital reading pin
int sensorVCC = 9; //Power pin to sensor
int nRainVal;
boolean bIsRaining = false;
String strRaining;
//RGB Led pin
int redPin = 6;
int greenPin = 5;
int bluePin = 3;

#ifdef SENS1
struct package
{
        int L=1501; // The sensor id number
        int M=0; // How wet the rain is
        int N=0; //If it's raining
        float O=1; //Battery volt
};
#endif
#ifdef SENS2
struct package
{
        int L=1502; // The sensor id number
        int M=0; // How wet the rain is
        int N=0; //If it's raining
        float O=1; //Battery volt
};
#endif

typedef struct package Package;
Package regn;

#define COMMON_ANODE

RF24 myRadio (7, 8); //Anslutning för nRF24L01
const uint64_t addresses[2] = { 0xF0F0F0F0E1LL, 0xF0F0F0F0D2LL };

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

void counterHandler()
{
        // Increment the sleep counter
        counter++;

        // Should be 225 for 30 minutes (225 * 8 = 1800 seconds = 30 minutes)
        // Use 1 for debugging purposes

        if(counter == 225) { //Change checking time here
                // Reset the counter to 0
                counter = 0;

                // Power up components
                power_all_enable();

                // Power up the radio
                myRadio.powerUp();

                // Wait for radio to power up
                delay(2);
        } else {
                // Sleep time isn't over yet, sleep some more
                enterSleep();
        }
}

void enterSleep()
{
        // Start the watchdog timer
        f_wdt = 0;

        // Power down the radio
        myRadio.powerDown();

        // Enter sleep
        sleep_enable();
        sleep_mode();

        // Wake from sleep
        sleep_disable();

        // Increment the interrupt counter
        counterHandler();
}

ISR(WDT_vect)
{
        // Stop the watchdog timer
        f_wdt = 1;
}

void setupWDT()
{
        // Setup the Watchdog timer for an interruption every 8 seconds

        MCUSR &= ~(1<<WDRF);
        WDTCSR |= (1<<WDCE) | (1<<WDE);
        WDTCSR = 1<<WDP0 | 1<<WDP3;
        WDTCSR |= _BV(WDIE);
}

void setupRadio()
{
        delay(100);
        myRadio.begin();
        myRadio.setPALevel(RF24_PA_LOW);
        myRadio.setDataRate( RF24_1MBPS );
        myRadio.setRetries(15, 15);
        myRadio.setChannel(125);
        myRadio.openWritingPipe( addresses[1]);
        delay(100);
}

void setupTermometer()
{
        //Settings for rain sensor
        pinMode(nRainDigitalIn,INPUT);
  pinMode(sensorVCC, OUTPUT);
        digitalWrite(sensorVCC, LOW);

        pinMode(redPin, OUTPUT);
        pinMode(greenPin, OUTPUT);
        pinMode(bluePin, OUTPUT);
}

void setup() {
  Serial.begin(9600);
        // Disable Brown out detection (uses power)
        sleep_bod_disable();

// Sleep mode setup
        set_sleep_mode(SLEEP_MODE_PWR_DOWN);

// Watchdog timer setup
        setupWDT();

// RF Radio setup
        setupRadio();

// Thermometer setup
        setupTermometer();

        setColor(0, 0, 0);
}

void printVolts()
{
        /*
         * Checks the battery level and flashes red if low power.
         */
        //int sensorValue = analogRead(A2); //Via motstånd läser vi av hur många volt det är
        regn.O = readVcc ();
        if (regn.O < 3200) //In millivolt when we are going to warn on low power
        {
                setColor(255, 0, 0);
                delay(500);
                setColor(255, 0, 0);
                delay(500);
                setColor(255, 0, 0);
                delay(500);
                setColor(255, 0, 0);
                delay(500);
                setColor(255, 0, 0);
                delay(500);
                setColor(255, 0, 0);
                delay(500);
        }
}

void raincheck() {
  setColor(80, 0, 80); //Change colour to purple
        digitalWrite(sensorVCC, HIGH);   //Power the sensor up
        delay(100); //Wait until we got power
        nRainVal = analogRead(nRainIn);
  bIsRaining = !(digitalRead(nRainDigitalIn));
  if(bIsRaining){
    strRaining = "YES";
    regn.N = 1;
    regn.M = nRainVal;
    setColor(255, 0, 0);
    delay(100);
    setColor(0, 0, 0);
  }
  else{
    strRaining = "NO";
    regn.N = 0;
    regn.M = nRainVal;
    setColor(0, 255, 0);
    delay(100);
    setColor(0, 0, 0);
  }
  digitalWrite(sensorVCC, LOW); //Closing the power off
}

void loop() {
  #ifdef BATTERI
        printVolts();
  #endif

  raincheck();
  myRadio.write(&regn, sizeof(regn)); //Sending the data
  
  enterSleep();
}
