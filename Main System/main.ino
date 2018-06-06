/*
 * This is the main system that controls everything, it's needed to be online to communicate whith an server. 
 * This must be running on Arduino MEGA, and the ESP-01 baudrate must be set to 9600
 * The wifi settings is setup thru an SD card for easy change (no need to upload new code each time you change the WIFI)
 * This system has also an automatic reset if the ESP gets problem to communicate to the system. After 10 times of error it will
 * automatic restart the arduino. (This can also be done from the webpage)
 */
#include "WiFiEsp.h" //For the ESP
#include <ArduinoJson.h> //We are using JSON for getting info from SD and web
#include <SPI.h>
#include "SdFat.h" //The SD module uses SoftwareSPI so we need SdFat library
#include "nRF24L01.h"
#include "RF24.h"
#include "DHT.h"
#define WIFIAKTIV //If you are using WIFI 
//Setup some package for the NRF
struct package
{
        int X=1; 
        int Y=1; 
        float Z=1; 
        float F=1; 
};
typedef struct package Package;
Package data;

struct paketet
{
        int O=0; 
        int P=0; 
};
typedef struct paketet Package1;
Package1 info;

struct viltpaket
{
        int A=0; 
        int B=0; 
        int C=0; 
};
typedef struct viltpaket Package2;
Package2 vpaket;

struct vilt
{
        int D=0; 
        int E=0; 
        float F=1; 
};

typedef struct vilt Package3;
Package3 sendback;

struct sirenpaketet
{
        int I=1; 
        int J=0; 
        int K=5000; 
};
typedef struct sirenpaketet Package4;
Package4 sirenen;

struct packageregn
{
        int L=1501; 
        int M=0; 
        int N=0; 
        float O=1; 
};

typedef struct packageregn Package5;
Package5 regn;

#define DHTPIN 22 // Defines pin number to which the sensor is connected
#define DHTTYPE DHT22
DHT dht(DHTPIN, DHTTYPE);

const uint8_t SOFT_MISO_PIN = 40;
const uint8_t SOFT_MOSI_PIN = 41;
const uint8_t SOFT_SCK_PIN  = 42;
const uint8_t SD_CHIP_SELECT_PIN = 43;

SdFatSoftSpi<SOFT_MISO_PIN, SOFT_MOSI_PIN, SOFT_SCK_PIN> SD;

int skickar;
//Settings for WIFI (No need to change if you are using the SD card to setup the wifi
char ssid[] = "SSID_HERE"; //SSID
char pass[] = "PASS_HERE"; //Password

char server[] = "192.168.0.12"; //The server to connect to (Where you host you server)
unsigned long lastConnectionTime = 0;  // last time you connected to the server, in milliseconds
const unsigned long postingInterval = 30000L; // delay between updates, in milliseconds 

RF24 myRadio (7, 8); //Connection for nRF24L01
const uint64_t addresses[6] = { 0xF0F0F0F0E1LL, 0xABCDABCD71LL, 0x3A3A3A3AD2LL, 0xF0F0F0F0C3LL, 0xF0F0F0F0E3LL, 0xF0F0F0F0C1LL };
#ifdef WIFIAKTIV
WiFiEspClient client;

int status = WL_IDLE_STATUS;
#endif
//A lot of variables :)
int sensor = 0;
int pumpfukt = 0;
int aktuellPump = 0;
int aktiveraPump = 0;
int datanormal = 1;
float volten = 0;
float jordtemp = 0;
int pumpar[40];
int pumpstatus[40];
int nuvarandePump = 0;
int nuvarandeVilt = 0;
int nuvarandeLarm = 0;
float nuvarandeVolt = 0;
int torrjord = 1040;
int blotjord = 526;

int torrregn = 1023;
int blotregn = 0;
int regnfukt = 0;

int redPin = 11;
int greenPin = 10;
int bluePin = 9;

int lastemp = 0;
int sparatemp = 0;
int omstart = 0;
int raknafel = 0;
int sirenlarm = 0;

float h1;
// Read temperature as Celsius (the default)
float t1;
// Read temperature as Fahrenheit (isFahrenheit = true)
float f1;
float hif1;
// Compute heat index in Celsius (isFahreheit = false)
float hic1;


const char *filename = "config.txt"; //Filename for the config file in sd card

#define COMMON_ANODE //If RGB Led is common anode (VCC instead of GND)

void (*resetFunc)(void) = 0;


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
        Serial.begin(115200);
        pinMode(redPin, OUTPUT);
        pinMode(greenPin, OUTPUT);
        pinMode(bluePin, OUTPUT);
        //Start by reading the SD Card
        if (!SD.begin(SD_CHIP_SELECT_PIN)) {
                Serial.println("initialization failed!");
                return;

                setColor(255, 0, 0);
        }
        Serial.println("initialization done.");

        File file = SD.open(filename);
        Serial.println(filename);

        // Allocate the memory pool on the stack.
        // Don't forget to change the capacity to match your JSON document.
        // Use arduinojson.org/assistant to compute the capacity.
        StaticJsonBuffer<512> jsonBuffer;

        // Parse the root object
        JsonObject &root = jsonBuffer.parseObject(file);

        if (!root.success())
                Serial.println(F("Failed to read file, using default configuration"));

        // Copy values from the JsonObject to the Config

        strlcpy(ssid,             // <- destination
                root["ssid"] | "WIFI", // <- source
                sizeof(ssid));    // <- destination's capacity

        strlcpy(pass,                     // <- destination
                root["losen"] | "LOSEN",    // <- source
                sizeof(pass));

        // Close the file (File's destructor doesn't close the file)
        file.close();

        delay(1000);
        /*
         * We need to load the WIFI module so we do it now
         */
         #ifdef WIFIAKTIV
        Serial1.begin(9600); //The baurate that the ESP-01 has
        WiFi.init(&Serial1); //Connect it to Arduino Megas Serial 1

        // Check if it works
        if (WiFi.status() == WL_NO_SHIELD) {
                Serial.println("No Wifi Shield found");
                setColor(255, 0, 0);
                // Don't continue
                while (true);

        }

        // Trying to connect to WIFI
        while (status != WL_CONNECTED) {
                setColor(255, 255, 0);
                Serial.print("Connect to SSID: ");
                Serial.println(ssid);

                // Connecting
                status = WiFi.begin(ssid, pass);
        }

        Serial.println("Connecting to wifi");
        setColor(0, 255, 0);
        //printWifiStatus();
        #endif

        //Settings for the NRF24L01
        delay(100);
        myRadio.begin();
        myRadio.setPALevel(RF24_PA_LOW); //Use low for testing
        myRadio.setDataRate( RF24_1MBPS );
        myRadio.setRetries(15, 15);
        myRadio.setChannel(125);
        myRadio.openReadingPipe(1, 0xF0F0F0F0E1LL);
        myRadio.openReadingPipe(2, 0xF0F0F0F0C3LL);
        myRadio.openReadingPipe(3, 0xF0F0F0F0E3LL);
        myRadio.openReadingPipe(4, 0xF0F0F0F0D2LL);
        myRadio.openWritingPipe(addresses[1]);
        myRadio.startListening();
        delay(100);


        dht.begin();
}

void httpRequest()
{
/*
* This runs every 30 secounds to connect to the server to check if something is needed to be done.
*/
        setColor(0, 0, 255);
        client.stop();
        // close any connection before send a new request
        // this will free the socket on the WiFi shield
        if (!client.connected()) {
                Serial.println();
                Serial.println("Disconecting server...");
                client.stop();

        }
        delay(50);

        // if there's a successful connection
        if (client.connect(server, 80)) { //Don't forget to add the correct port
                Serial.println("Connecting...");


                // send the HTTP PUT request we are passing some values to the server also
                String testar = "GET /varden.php?spara="+String(sparatemp)+"&temp="+String(t1)+"&fukt="+String(h1)+"&heat="+String(hic1)+" HTTP/1.1";

                Serial.print(testar);
                client.println(testar);
                client.println(F("Host: 192.168.0.12")); //Change the host here
                client.println(F("Content-Type: application/json"));
                client.println("Connection: close");
                client.println();

                // note the time that the connection was made
                lastConnectionTime = millis();
                sparatemp = 0;
                setColor(0, 255, 0);
        }
        else {
                //Restart the arduino if there are to many errors
                raknafel = raknafel + 1;
                if (raknafel >= 10 ) {
                        setColor(255, 165, 0);
                        delay(1000);
                        resetFunc();
                }
                // if you couldn't make a connection
                Serial.println("Connection failed");

                setColor(255, 0, 0);
                return;

        }
}

void readTempSensor() {
        //Check the humidity sensor
        h1 = dht.readHumidity();
        // Read temperature as Celsius (the default)
        t1 = dht.readTemperature();
        // Read temperature as Fahrenheit (isFahrenheit = true)
        f1 = dht.readTemperature(true);

        // Check if any reads failed and exit early (to try again).
        if (isnan(h1) || isnan(t1) || isnan(f1)) {
                Serial.println("Failed to read from DHT sensor!");
                return;


        }

        // Compute heat index in Fahrenheit (the default)
        hif1 = dht.computeHeatIndex(f1, h1);
        // Compute heat index in Celsius (isFahreheit = false)
        hic1 = dht.computeHeatIndex(t1, h1, false);

        Serial.print("Humidity: ");
        Serial.print(h1);
        Serial.print(" %\t");
        Serial.print("Temperature: ");
        Serial.print(t1);
        Serial.print(" *C ");
        Serial.print(f1);
        Serial.print(" *F\t");
        Serial.print("Heat index: ");
        Serial.print(hic1);
        Serial.print(" *C ");
        Serial.print(hif1);
        Serial.println(" *F");

}

void loop() {
        delay(10);
        #ifdef WIFIAKTIV
        String json = "";
        boolean httpBody = false;

        while (client.available()) {


                String line = client.readStringUntil('\r');

                if (!httpBody && line.charAt(1) == '{') {
                        httpBody = true;
                }
                if (httpBody) {
                        json += line;
                }
                if (datanormal == 1) {
                        StaticJsonBuffer<400> jsonBuffer;
                        Serial.println(sensor);
                        Serial.println(pumpfukt);
                        Serial.println("Got data:");
                        Serial.println(json);
                        JsonObject& root1 = jsonBuffer.parseObject(json);
                        String pumpdata = root1["pump"];
                        String aktiveraddata = root1["aktivera"];
                        info.O = pumpdata.toInt();
                        info.P = aktiveraddata.toInt();
                        if (info.P == 1) {
                                delay(5);
                                myRadio.openWritingPipe(addresses[1]);
                                myRadio.stopListening();
                                myRadio.write(&info, sizeof(info));
                                myRadio.startListening();

                        } else if (info.P == 0) {
                                delay(5);
                                myRadio.openWritingPipe(addresses[1]);
                                myRadio.stopListening();
                                myRadio.write(&info, sizeof(info));
                                myRadio.startListening();
                        }

                } else {
                        StaticJsonBuffer<512> jsonBuffer;
                        Serial.println("Got data:");
                        Serial.println(json);
                        JsonObject& root = jsonBuffer.parseObject(json);

                        String torrjorddata = root["torrjord"];
                        String blotjorddata = root["blotjord"];
                        String torregndata = root["torregn"];
                        String blotregndata = root["blotregn"];
                        String lastempdata = root["lastemp"];
                        String omstartdata = root["omstart"];
                        String sirentiddata = root["sirentid"];
                        torrjord = torrjorddata.toInt();
                        blotjord = blotjorddata.toInt();
                        torrregn = torregndata.toInt();
                        blotregn = blotregndata.toInt();
                        lastemp = lastempdata.toInt();
                        omstart = omstartdata.toInt();
                        sirenen.K = sirentiddata.toInt();
                        JsonObject& intArray = root["pumpar"];
                        int arraySize =  root["pumpar"].size();
                        JsonObject& statusArray = root["status"];
                        int arraySize2 =  root["status"].size();
                        JsonObject& viltArray = root["vilt"];
                        int arraySize3 =  root["vilt"].size();
                        JsonObject& viltSArray = root["vstatus"];
                        int arraySize4 =  root["vstatus"].size();
                        JsonObject& viltMArray = root["manuell"];
                        int arraySize5 =  root["manuell"].size();
                        JsonObject& viltSIArray = root["siren"];
                        int arraySize6 =  root["siren"].size();

                        for (int i = 0; i< arraySize; i++) {

                                info.O=root["pumpar"][i];
                                info.P=root["status"][i];
                                Serial.println(info.O);
                                Serial.println(info.P);
                                if (info.P == 1) {
                                        delay(5);
                                        myRadio.openWritingPipe(addresses[1]);
                                        myRadio.stopListening();
                                        myRadio.write(&info, sizeof(info));
                                        myRadio.startListening();

                                } else {
                                        delay(5);
                                        myRadio.openWritingPipe(addresses[1]);
                                        myRadio.stopListening();
                                        myRadio.write(&info, sizeof(info));
                                        myRadio.startListening();
                                }

                        }

                        for (int i = 0; i< arraySize3; i++) {

                                vpaket.A=root["vilt"][i];
                                vpaket.B=root["vstatus"][i];
                                vpaket.C=root["manuell"][i];
                                sirenen.I=root["siren"][i];
                                sirenen.J=root["manuell"][i];
                                Serial.println(vpaket.A);
                                Serial.println(vpaket.B);
                                Serial.println(vpaket.C);
                                if (vpaket.B == 1) {
                                        if (sirenen.J == 1) {
                                                //If the alarm is going to turn on
                                                delay(5);
                                                myRadio.openWritingPipe(addresses[5]); //Change transmitt address
                                                myRadio.stopListening();
                                                myRadio.write(&sirenen, sizeof(sirenen));
                                                myRadio.startListening();
                                                myRadio.openWritingPipe(addresses[1]);
                                        }

                                } else {
                                        delay(5);
                                        myRadio.openWritingPipe(addresses[5]);
                                        myRadio.stopListening();
                                        myRadio.write(&sirenen, sizeof(sirenen));
                                        myRadio.startListening();
                                        myRadio.openWritingPipe(addresses[1]);
                                }

                        }

                        if (lastemp == 1) {
                                //If we are going to read the temp on base system
                                sparatemp = 1;
                                readTempSensor();

                        }

                        if (omstart == 1) {
                                //If an manuel reset is requested.
                                setColor(255, 165, 0);
                                delay(1000);
                                resetFunc();
                        }
                }

        }
        #endif



        if (aktiveraPump == 1) {

                if (info.P == 1) {
                        delay(5);
                        myRadio.openWritingPipe(addresses[1]);
                        myRadio.stopListening();
                        myRadio.write(&info, sizeof(info));
                        myRadio.startListening();
                        aktiveraPump = 0;

                } else if (info.P == 0) {
                        delay(5);
                        myRadio.openWritingPipe(addresses[1]);
                        myRadio.stopListening();
                        myRadio.write(&info, sizeof(info));
                        myRadio.startListening();
                        aktiveraPump = 0;
                }
        }

        uint8_t pipeNr;


        if ( myRadio.available(&pipeNr))
        {
                Serial.print("Pipe: ");
                Serial.println(pipeNr);


                if (pipeNr == 2) {
                        //The waterpump
                        while (myRadio.available())
                        {
                                myRadio.read( &info, sizeof(info) );
                                if (info.O > 2500 && info.O < 3000 ) {
                                        nuvarandePump = info.O;
                                        datanormal = 1;
                                        client.stop();
                                        if (!client.connected()) {
                                                Serial.println();
                                                Serial.println("Disconnecting server...");
                                                client.stop();

                                        }
                                        delay(50);

                                        // if there's a successful connection
                                        if (client.connect(server, 80)) { //Check port
                                                Serial.println("Connecting...");


                                                // send the HTTP PUT request
                                                String testar = "GET /pump.php?pump="+String(nuvarandePump)+" HTTP/1.1";

                                                Serial.print(testar);
                                                client.println(testar);
                                                client.println(F("Host: 192.168.0.12")); //Change host
                                                client.println(F("Content-Type: application/json"));
                                                client.println("Connection: close");
                                                client.println();

                                        }
                                        else {
                                                //To many problems, restart
                                                raknafel = raknafel + 1;
                                                if (raknafel >= 10 ) {
                                                        setColor(255, 165, 0);
                                                        delay(1000);
                                                        resetFunc();
                                                }
                                                // if you couldn't make a connection
                                                Serial.println("Connection failed");

                                                setColor(255, 0, 0);
                                                return;
                                        }
                                }
                        }
                } else if (pipeNr == 3) {
                        //Animal Sensor
                        while (myRadio.available())
                        {
                                myRadio.read( &sendback, sizeof(sendback) );
                                if (sendback.D > 6500 && sendback.D < 7000 ) {
                                        setColor(102, 0, 102);
                                        nuvarandeVilt = sendback.D;
                                        nuvarandeLarm = sendback.E;
                                        nuvarandeVolt = sendback.F;
                                        datanormal = 0;
                                        client.stop();
                                        if (!client.connected()) {
                                                Serial.println();
                                                Serial.println("Disconnecting server...");
                                                client.stop();

                                        }
                                        delay(50);

                                        // if there's a successful connection
                                        if (client.connect(server, 80)) {
                                                Serial.println("Connecting...");


                                                // send the HTTP PUT request
                                                String testar = "GET /vilt.php?sensor="+String(nuvarandeVilt)+"&vilt="+String(nuvarandeLarm)+"&volt="+String(nuvarandeVolt)+" HTTP/1.1";

                                                client.println(testar);
                                                client.println(F("Host: 192.168.0.12")); //Change host
                                                client.println(F("Content-Type: application/json"));
                                                client.println("Connection: close");
                                                client.println();

                                                

                                        }
                                        else {
                                                //If to many errors, restart
                                                raknafel = raknafel + 1;
                                                if (raknafel >= 10 ) {
                                                        setColor(255, 165, 0);
                                                        delay(1000);
                                                        resetFunc();
                                                }
                                                // if you couldn't make a connection
                                                Serial.println("Connection failed");

                                                setColor(255, 0, 0);
                                                return;
                                        }
                                        setColor(0, 255, 0);
                                }
                        }
                } else if (pipeNr == 4) {
                        //Rain Sensor
                        while (myRadio.available())
                        {
                                myRadio.read( &regn, sizeof(regn) );
                                if (regn.L > 1500 && regn.L < 2000 ) {
                                        setColor(102, 0, 102);
                                        regnfukt = regn.M;
                                        regn.M =   map(regnfukt,torrregn,blotregn,0,100); //Map to percent how wet it is
                                        if (regn.M > 100) {
                                                regn.M = 100;
                                        }
                                        if (regn.M < 0) {
                                                regn.M = 0;
                                        }
                                        datanormal = 0;
                                        client.stop();
                                        if (!client.connected()) {
                                                Serial.println();
                                                Serial.println("Disconnecting server...");
                                                client.stop();

                                        }
                                        delay(50);

                                        // if there's a successful connection
                                        if (client.connect(server, 80)) { //Change port
                                                Serial.println("Connecting...");


                                                // send the HTTP PUT request
                                                String testar = "GET /regn.php?sensor="+String(regn.L)+"&regnar="+String(regn.N)+"&varde="+String(regn.M)+"&volt="+String(regn.O)+" HTTP/1.1";

                                                
                                                Serial.print(testar);
                                                client.println(testar);
                                                client.println(F("Host: 192.168.0.12")); //Change host
                                                client.println(F("Content-Type: application/json"));
                                                client.println("Connection: close");
                                                client.println();


                                        }
                                        else {
                                                //If to many errors restart
                                                raknafel = raknafel + 1;
                                                if (raknafel >= 10 ) {
                                                        setColor(255, 165, 0);
                                                        delay(1000);
                                                        resetFunc();
                                                }
                                                // if you couldn't make a connection
                                                Serial.println("Connection failed");

                                                setColor(255, 0, 0);
                                                return;
                                        }
                                        setColor(0, 255, 0);
                                }
                        }
                } else {
                        //Soil sensor
                        while (myRadio.available())
                        {

                                myRadio.read( &data, sizeof(data) );
                                

                                if (data.X > 4500 && data.X < 5000) {

                                        setColor(80, 0, 80);
                                        pumpfukt = data.Y;
                                        sensor = data.X;
                                        jordtemp = data.Z;
                                        volten = data.F;
                                        pumpfukt = map(pumpfukt,torrjord,blotjord,0,100); //Map to percent how wet it is
                                        if (pumpfukt > 100) {
                                                pumpfukt = 100;
                                        }
                                        if (pumpfukt < 0) {
                                                pumpfukt = 0;
                                        }
                                        datanormal = 1;
                                        client.stop();
                                        if (!client.connected()) {
                                                Serial.println();
                                                Serial.println("Disconnecting server...");
                                                client.stop();

                                        }
                                        delay(50);

                                        // if there's a successful connection
                                        if (client.connect(server, 80)) { //Change port
                                                Serial.println("Connecting...");


                                                // send the HTTP PUT request
                                                String testar = "GET /sensorer.php?sensor="+String(sensor)+"&jordfukt="+String(pumpfukt)+"&jordtemp="+String(jordtemp)+"&volt="+String(volten)+" HTTP/1.1";

                                                client.println(testar);
                                                client.println(F("Host: 192.168.0.12")); //Change host
                                                client.println(F("Content-Type: application/json"));
                                                client.println("Connection: close");
                                                client.println();

                                        }
                                        else {
                                                //If to many errors restart
                                                raknafel = raknafel + 1;
                                                if (raknafel >= 10 ) {
                                                        setColor(255, 165, 0);
                                                        delay(1000);
                                                        resetFunc();
                                                }
                                                // if you couldn't make a connection
                                                Serial.println("Connection failed");

                                                setColor(255, 0, 0);
                                                return;
                                        }


                                        setColor(0, 255, 0);
                                }

                        }
                }

        }

        if (millis() - lastConnectionTime > postingInterval) {
                datanormal = 0;
                readTempSensor();
                httpRequest();
                /*
                * This runs the normal check every 30 secound
                */

        }

}
