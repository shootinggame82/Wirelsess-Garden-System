#define SENS1
#define TEST
#define BATTERI
#include <avr/sleep.h>
#include <avr/power.h>
#include <avr/wdt.h>
#include <SPI.h>
#include "nRF24L01.h"
#include "RF24.h"

void enterSleep();
//För sömn läget
volatile int f_wdt = 1;
int counter = 0;
int packetCounter = 0;
int nRainIn = A0;
int nRainDigitalIn = 4;
int sensorVCC = 9;
int nRainVal;
boolean bIsRaining = false;
String strRaining;
int redPin = 6;
int greenPin = 5;
int bluePin = 3;

#ifdef SENS1
struct package
{
        int L=1501; // Detta är sändarens id nr
        int M=0; // Detta är regnfuktighet (Räknas sen ut i huvudenheten)
        int N=0; //Detta är om det regnar
        float O=1; //Detta är volten
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

        // Should be 75 for 10 minutes (75 * 8 = 600 seconds = 10 minutes)
        // Use 1 for debugging purposes

        if(counter == 225) { //1 timme
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
        //Inställningar för temp sensor
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
         * Denna funktion avläser batteriet och om det sjunker under önskad nivå så ska röd färg lysa på dioden. Lägg till printVolts() i loopen om ni vill använda batteri varnare.
         */
        //int sensorValue = analogRead(A2); //Via motstånd läser vi av hur många volt det är
        regn.O = readVcc ();
        if (regn.O < 3200) //Ställ in lägsta nivå för larm av batteri.
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
  setColor(80, 0, 80); //Ändrar färgen på rgb till lila för avläsning
        digitalWrite(sensorVCC, HIGH);   //Aktiverar ström till jordsensorn
        delay(100); //För att säkerhetsställa att den har ström
        nRainVal = analogRead(nRainIn);
  bIsRaining = !(digitalRead(nRainDigitalIn));
  if(bIsRaining){
    strRaining = "YES";
    regn.N = 1;
    regn.M = nRainVal;
    setColor(255, 0, 0);
    delay(1000);
    setColor(0, 0, 0);
  }
  else{
    strRaining = "NO";
    regn.N = 0;
    regn.M = nRainVal;
    setColor(0, 255, 0);
    delay(1000);
    setColor(0, 0, 0);
  }
  digitalWrite(sensorVCC, LOW); //Stänger av ström och väntar en sekund
}

void loop() {
  #ifdef BATTERI
        printVolts();
  #endif

  raincheck();
  myRadio.write(&regn, sizeof(regn));
  
  enterSleep();
}
