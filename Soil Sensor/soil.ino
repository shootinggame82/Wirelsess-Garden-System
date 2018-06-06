/*
 * Arduino wireless Soil sensor
 * When we reading the soil sensor we firs power up it thru an transistor, we never have power on it
 * when we don't use it.
 * Setup to check every hour
 * Define witch sensor to use before uploading.
 * And define TEST if you need serial printing when testing.
 * If you want to change how often it checks, you do it in the counterHandler()
 * Use an ATMEGA328P-PU chip to save the battery, running an arduino on battery without modification is not good.
 */
#include <Arduino.h>
#include <avr/sleep.h>
#include <avr/power.h>
#include <avr/wdt.h>
#include <SPI.h>
#include "nRF24L01.h"
#include "RF24.h"
#include <OneWire.h>
#include <DallasTemperature.h>
void enterSleep();
#define SENS2 //Define the sensor here
#define TEST_EJ // Change to TEST if you need the Serial printing
#define BATTERI //Change this if you don't want battery testing

//For sleep mode
volatile int f_wdt = 1;
int counter = 0;
int packetCounter = 0;

#ifdef SENS1
struct package
{
        int X=4501; // Sensor ID number
        int Y=1; // Soil value
        float Z=1; //Soil temp
        float F=1; //Battery Level
};
int skickar = 4501;
#endif
#ifdef SENS2
struct package
{
        int X=4502;  // Sensor ID number
        int Y=1; // Soil value
        float Z=1; //Soil temp
        float F=1; //Battery Level
};
#endif
#ifdef SENS3
struct package
{
        int X=4503;  // Sensor ID number
        int Y=1; // Soil value
        float Z=1; //Soil temp
        float F=1; //Battery Level
};
#endif
#ifdef SENS4
struct package
{
        int X=4504;  // Sensor ID number
        int Y=1; // Soil value
        float Z=1; //Soil temp
        float F=1; //Battery Level
};
#endif
#ifdef SENS5
struct package
{
        int X=4505;  // Sensor ID number
        int Y=1; // Soil value
        float Z=1; //Soil temp
        float F=1; //Battery Level
};
#endif
#ifdef SENS6
struct package
{
        int X=4506;  // Sensor ID number
        int Y=1; // Soil value
        float Z=1; //Soil temp
        float F=1; //Battery Level
};
#endif
#ifdef SENS7
struct package
{
        int X=4507;  // Sensor ID number
        int Y=1; // Soil value
        float Z=1; //Soil temp
        float F=1; //Battery Level
};
#endif
#ifdef SENS8
struct package
{
        int X=4508;  // Sensor ID number
        int Y=1; // Soil value
        float Z=1; //Soil temp
        float F=1; //Battery Level
};
#endif
#ifdef SENS9
struct package
{
        int X=4509;  // Sensor ID number
        int Y=1; // Soil value
        float Z=1; //Soil temp
        float F=1; //Battery Level
};
#endif
typedef struct package Package;
Package data;
//Pin setup for RGB Led (I used an anode led)
int redPin = 6;
int greenPin = 5;
int bluePin = 3;
//Settings for the soil sensor
/*
 * The soil sensor will get power thru an NPN transistor that is trigged by pin 10 and then read thru Analog 0
 */
int sensorVCC = 10;
int jordSensor = A0;
//Settings for temp sensor
#define ONE_WIRE_BUS 2
#define TEMPERATURE_PRECISION 9
OneWire oneWire(ONE_WIRE_BUS);
DallasTemperature sensors(&oneWire);
DeviceAddress jordtemp1;
RF24 myRadio (7, 8); //Connections for NRF24L01
const uint64_t addresses[2] = { 0xF0F0F0F0E1LL, 0xABCDABCD71LL };

#define COMMON_ANODE //If you have Common Anode RGB Led (VCC instead of GND)

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

void counterHandler()
{
        // Increment the sleep counter
        counter++;

        // Should be 450 for 1 hour (450 * 8 = 3600 seconds = 1 hour)
        // In sleep mode they wake up every 8 secound
        // Use 1 for debugging purposes

        if(counter == 450) { //Change here the check time
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
        myRadio.openWritingPipe( addresses[0]);
        delay(100);
}

void setupTermometer()
{
        //Settings for temp sensor
        sensors.begin();
        Serial.print(sensors.getDeviceCount(), DEC);
        if (sensors.isParasitePowerMode()) Serial.println("ON");
        else Serial.println("OFF");

        if (!sensors.getAddress(jordtemp1, 0)) Serial.println("Unable to find address for Device 0");
        sensors.setResolution(jordtemp1, TEMPERATURE_PRECISION);
        //Settings for soil sensor
        pinMode(sensorVCC, OUTPUT);
        digitalWrite(sensorVCC, LOW);
        //Settings for RGB led
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
        data.F = readVcc();
        if (data.F < 3200) //Change here the warn level on the battery in millivolt
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

void jordSensorRun() {
        /*
         * Checks the soil sensor.
         * First we need to power it.
         */
        setColor(80, 0, 80); //Change colour to purple
        digitalWrite(sensorVCC, HIGH);   //Enable power to soil sensor
        delay(100); //Delay so we know we have power
        data.Y = analogRead(jordSensor); //Read the analog sensor
  #ifdef TEST
        Serial.print("Soil Sensor: ");
        Serial.println(data.Y);
  #endif
        digitalWrite(sensorVCC, LOW); //Shut down the power
        delay(100);
        setColor(0, 0, 0); //No more colour on the RGB LED

}

void printTemperature(DeviceAddress deviceAddress)
{
        data.Z = sensors.getTempC(deviceAddress);
 #ifdef TEST
        Serial.print("Temp C: ");
        Serial.print(data.Z);
        Serial.print(" Temp F: ");
        Serial.println(DallasTemperature::toFahrenheit(data.Z));
 #endif
}

//For the temp sensor
void printAddress(DeviceAddress deviceAddress)
{
        for (uint8_t i = 0; i < 8; i++)
        {
                if (deviceAddress[i] < 16) Serial.print("0");
                Serial.print(deviceAddress[i], HEX);
        }
}

void jordTempRun() {
/*
 * This function check the temp sensor
 * And publish it in package Z
 * */
        setColor(80, 0, 80);
 #ifdef TEST
        Serial.print("Checking temperature");
 #endif
        sensors.requestTemperatures();
        printTemperature(jordtemp1);
 #ifdef TEST
        Serial.println("Temp done");
 #endif
        delay(100);
        setColor(0, 0, 0);
}

void loop() {
  #ifdef BATTERI
        printVolts();
  #endif
        jordSensorRun();   //First check the soil sensor
        jordTempRun(); //Then the temp
        myRadio.write(&data, sizeof(data));         //And now we sending it to the Base system.
#ifdef TEST
        Serial.print("Sensor: ");
        Serial.println(data.X);
        Serial.print("Soil: ");
        Serial.println(data.Y);
        Serial.print("Temp: ");
        Serial.println(data.Z);
        Serial.print("Volt: ");
        Serial.println(data.F);
#endif
        enterSleep(); //And go to sleep
}
