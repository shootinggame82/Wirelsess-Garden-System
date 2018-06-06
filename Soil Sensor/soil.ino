/*
 * Arduino trådlös jordsensor & tempgivare
 * Mäter jordens värde & temp och skickar iväg till basenheten
 * Skickar med viss mellanrum, varje sensor har ett unikt id nummer som referens
 * Förprogrammering i koden för enklare uppdatering.
 * Defina bara vilken sensor som skall uppdateras.
 * Jord sensorena börjar på nr 45
 * RGB Led för indikering av vad som sker.
 *
 * Hela systemet består av en bas enhet som sköter koppling till servern och tar emot värden.
 * Sensorerna ställs in på servern.
 * Relä enheterna tar emot signal från basenheten och är direkt ansluten till ström. Dom aktiverar pumparna eller kran ventiler beroende på vad man har.
 * Relä enheterna börjar sitt ID nr på 25
 * Definiera TEST för att aktivera serial rapporter annars så ange DRIFT
 * Vi sätter arduinon i sömn och väcker den var 10 minut.
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
#define SENS2
#define TEST_EJ
#define BATTERI

//För sömn läget
volatile int f_wdt = 1;
int counter = 0;
int packetCounter = 0;

#ifdef SENS1
struct package
{
        int X=4501; // Detta är sändarens id nr
        int Y=1; // Detta är jordens fuktighetsvärde (Räknas sen ut i huvudenheten)
        float Z=1; //Detta är jordens temperatur
        float F=1; //Detta är volten
};
int skickar = 4501;
#endif
#ifdef SENS2
struct package
{
        int X=4502; // Detta är sändarens id nr
        int Y=1; // Detta är jordens fuktighetsvärde (Räknas sen ut i huvudenheten)
        float Z=1; //Detta är jordens temperatur
        float F=1; //Detta är volten
};
#endif
#ifdef SENS3
struct package
{
        int X=4503; // Detta är sändarens id nr
        int Y=1; // Detta är jordens fuktighetsvärde (Räknas sen ut i huvudenheten)
        float Z=1; //Detta är jordens temperatur
        float F=1; //Detta är volten
};
#endif
#ifdef SENS4
struct package
{
        int X=4504; // Detta är sändarens id nr
        int Y=1; // Detta är jordens fuktighetsvärde (Räknas sen ut i huvudenheten)
        float Z=1; //Detta är jordens temperatur
        float F=1; //Detta är volten
};
#endif
#ifdef SENS5
struct package
{
        int X=4505; // Detta är sändarens id nr
        int Y=1; // Detta är jordens fuktighetsvärde (Räknas sen ut i huvudenheten)
        float Z=1; //Detta är jordens temperatur
        float F=1; //Detta är volten
};
#endif
#ifdef SENS6
struct package
{
        int X=4506; // Detta är sändarens id nr
        int Y=1; // Detta är jordens fuktighetsvärde (Räknas sen ut i huvudenheten)
        float Z=1; //Detta är jordens temperatur
        float F=1; //Detta är volten
};
#endif
#ifdef SENS7
struct package
{
        int X=4507; // Detta är sändarens id nr
        int Y=1; // Detta är jordens fuktighetsvärde (Räknas sen ut i huvudenheten)
        float Z=1; //Detta är jordens temperatur
        float F=1; //Detta är volten
};
#endif
#ifdef SENS8
struct package
{
        int X=4508; // Detta är sändarens id nr
        int Y=1; // Detta är jordens fuktighetsvärde (Räknas sen ut i huvudenheten)
        float Z=1; //Detta är jordens temperatur
        float F=1; //Detta är volten
};
#endif
#ifdef SENS9
struct package
{
        int X=4509; // Detta är sändarens id nr
        int Y=1; // Detta är jordens fuktighetsvärde (Räknas sen ut i huvudenheten)
        float Z=1; //Detta är jordens temperatur
        float F=1; //Detta är volten
};
#endif
typedef struct package Package;
Package data;
//Inställning för RGB Led som ska fungera som en status indikation på sensorns uppgift.
int redPin = 6;
int greenPin = 5;
int bluePin = 3;
//Inställning för avläsning av jordsensor
/*
 * Jordsensorn får först ström via en npn transistor som styrs via en digital pinne.
 * Där efter så avläses via en analog pinne.
 */
int sensorVCC = 10;
int jordSensor = A0;
//Inställning för temperatur sensor
#define ONE_WIRE_BUS 2
#define TEMPERATURE_PRECISION 9
OneWire oneWire(ONE_WIRE_BUS);
DallasTemperature sensors(&oneWire);
DeviceAddress jordtemp1;
RF24 myRadio (7, 8); //Anslutning för nRF24L01
const uint64_t addresses[2] = { 0xF0F0F0F0E1LL, 0xABCDABCD71LL };

#define COMMON_ANODE

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

        // Should be 75 for 10 minutes (75 * 8 = 600 seconds = 10 minutes)
        // Use 1 for debugging purposes

        if(counter == 450) { //1 timme
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
        //Inställningar för temp sensor
        sensors.begin();
        Serial.print(sensors.getDeviceCount(), DEC);
        if (sensors.isParasitePowerMode()) Serial.println("ON");
        else Serial.println("OFF");

        if (!sensors.getAddress(jordtemp1, 0)) Serial.println("Unable to find address for Device 0");
        sensors.setResolution(jordtemp1, TEMPERATURE_PRECISION);
        //Inställningar för jord sensor
        pinMode(sensorVCC, OUTPUT);
        digitalWrite(sensorVCC, LOW);
        //Inställningar för RGB led
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
         * Denna funktion avläser batteriet och om det sjunker under önskad nivå så ska röd färg lysa på dioden. Lägg till printVolts() i loopen om ni vill använda batteri varnare.
         */
        //int sensorValue = analogRead(A2); //Via motstånd läser vi av hur många volt det är
        //data.F = sensorValue * (5.00 / 1023.00) * 2; //konverterar till korrekt volt
        data.F = readVcc();
        if (data.F < 3200) //Ställ in lägsta nivå för larm av batteri.
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
         * Denna avläser jordsensorn och registrerar värdet på jordens fuktighet och lägger in det i Y som sedan kommer att skickas iväg.
         * Vi börjar att aktivera ström till sensorn.
         */
        setColor(80, 0, 80); //Ändrar färgen på rgb till lila för avläsning
        digitalWrite(sensorVCC, HIGH);   //Aktiverar ström till jordsensorn
        delay(100); //För att säkerhetsställa att den har ström
        data.Y = analogRead(jordSensor); //Avläser jordensorn och sparar värdet
  #ifdef TEST
        Serial.print("Jordsensor: ");
        Serial.println(data.Y);
  #endif
        digitalWrite(sensorVCC, LOW); //Stänger av ström och väntar en sekund
        delay(1000);
        setColor(0, 0, 0); //Stänger av RGB dioden för att spara på ström

}

void printTemperature(DeviceAddress deviceAddress)
{
        data.Z = sensors.getTempC(deviceAddress);
 #ifdef TEST
        Serial.print("Temp C: ");
        Serial.print(data.Z);
        Serial.print(" Temp F: ");
        Serial.println(DallasTemperature::toFahrenheit(data.Z)); // Visar i Fahrenheit
 #endif
}

//Dessa funktioner är för temperatur givaren
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
 * Denna funktion avläser jordens temperatur via temperatur sensorn.
 * Värdet registreras i Z som sedan kommer att skickas.
 * */
        setColor(80, 0, 80);
 #ifdef TEST
        Serial.print("Kollar temperatur");
 #endif
        sensors.requestTemperatures();
        printTemperature(jordtemp1);
 #ifdef TEST
        Serial.println("Temp klar");
 #endif
        delay(1000);
        setColor(0, 0, 0);
}

void loop() {
  #ifdef BATTERI
        printVolts();
  #endif
        jordSensorRun();   //Börja med avläsning av jordsensorn
        jordTempRun(); //Börja med avläsning av jord tempen
        //  if (myRadio.write(&data, sizeof(data))) {
        myRadio.write(&data, sizeof(data));         //Skicka iväg uppgifter till mottagaren
#ifdef TEST
        //Serial.println(skickar);
        Serial.print("Sensor: ");
        Serial.println(data.X);
        Serial.print("Jordsensor: ");
        Serial.println(data.Y);
        Serial.print("Jordtemp: ");
        Serial.println(data.Z);
        Serial.print("Volt: ");
        Serial.println(data.F);
#endif
        enterSleep();
}
