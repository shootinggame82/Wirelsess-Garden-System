/*
 * Bevattnings mottagaren sköter koppling mellan sensorer och nätet.
 * Den ska kunna ta emot alla sensorer och sen är det upp till servern att styra om dom är tillagda eller inte.
 * Jordsensorer skall börja på nr 45 och pump sändarna skall ligga på 25 pump sändarna kommer endast att lyssna efter på och av signal från mottagaren.
 */
#include <Arduino.h>
#include "WiFiEsp.h"
#include <ArduinoJson.h>
#include <SPI.h>
#include "SdFat.h"
#include "nRF24L01.h"
#include "RF24.h"
#include "DHT.h"
#define WIFIAKTIV
//Här erhåller vi värden från jordsensorerna
struct package
{
        int X=1; // Detta är sändarens id nr
        int Y=1; // Detta är jordens fuktighetsvärde
        float Z=1; //Detta är jordens temperatur
        float F=1; //Detta är voltem
};
typedef struct package Package;
Package data;

struct paketet
{
        int O=0; // Detta är sändarens id nr
        int P=0; // Detta är jordens fuktighetsvärde
};
typedef struct paketet Package1;
Package1 info;

struct viltpaket
{
        int A=0; // Detta är sändarens id nr
        int B=0; // Detta är vilt status
        int C=0; // Om ska köras manuellt
};
typedef struct viltpaket Package2;
Package2 vpaket;

struct vilt
{
        int D=0; // Detta är sändarens id nr
        int E=0; // Detta är om vilt är aktiverat
        float F=1; // Detta är volten
};

typedef struct vilt Package3;
Package3 sendback;

struct sirenpaketet
{
        int I=1; // Detta är sändarens id nr
        int J=0; // Detta är statusen på sirenen (1 för på 0 för av)
        int K=5000; // Detta är statusen på sirenen (1 för på 0 för av)
};
typedef struct sirenpaketet Package4;
Package4 sirenen;

struct packageregn
{
        int L=1501; // Detta är sändarens id nr
        int M=0; // Detta är regnfuktighet (Räknas sen ut i huvudenheten)
        int N=0; //Detta är om det regnar
        float O=1; //Detta är volten
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
//Inställningar för Wifi
char ssid[] = "OLSSONS"; //SSID
char pass[] = "7554AO82"; //Lösenord till nätverket

char server[] = "olsson1982.asuscomm.com"; //Server att ansluta till
unsigned long lastConnectionTime = 0;                 // last time you connected to the server, in milliseconds
const unsigned long postingInterval = 30000L; // delay between updates, in milliseconds

RF24 myRadio (7, 8); //Anslutning för nRF24L01
const uint64_t addresses[6] = { 0xF0F0F0F0E1LL, 0xABCDABCD71LL, 0x3A3A3A3AD2LL, 0xF0F0F0F0C3LL, 0xF0F0F0F0E3LL, 0xF0F0F0F0C1LL };
#ifdef WIFIAKTIV
WiFiEspClient client;

int status = WL_IDLE_STATUS;
#endif
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


const char *filename = "config.txt";

#define COMMON_ANODE

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
        //Börja läsa config filen för wifi inloggning
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

        delay(10000);
        /*
         * Vi behöver ladda wifi modulen och det gör vi med följande:
         */
         #ifdef WIFIAKTIV
        Serial1.begin(9600); //För att kommunisera med WIFI modulen
        WiFi.init(&Serial1); //Koppla den till Serial1

        // Kontrollerar så modulen fungerar
        if (WiFi.status() == WL_NO_SHIELD) {
                Serial.println("Kan inte hitta wifi shielden");
                setColor(255, 0, 0);
                // Don't continue
                while (true);

        }

        // Försöker ansluta till nätverket
        while (status != WL_CONNECTED) {
                setColor(255, 255, 0);
                Serial.print("Ansluter till SSID: ");
                Serial.println(ssid);

                // Ansluter
                status = WiFi.begin(ssid, pass);
        }

        Serial.println("Ansluten till wifi");
        setColor(0, 255, 0);
        //printWifiStatus();
        #endif

        //Inställningar för radion
        delay(100);
        myRadio.begin();
        myRadio.setPALevel(RF24_PA_LOW);
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

        setColor(0, 0, 255);
        //  Serial.println();
        client.stop();
        // close any connection before send a new request
        // this will free the socket on the WiFi shield
        if (!client.connected()) {
                Serial.println();
                Serial.println("Kopplar bort server...");
                client.stop();

        }
        delay(50);

        // if there's a successful connection
        if (client.connect(server, 8030)) {
                Serial.println("Ansluter...");


                // send the HTTP PUT requestt1h1hic1
                String testar = "GET /varden.php?spara="+String(sparatemp)+"&temp="+String(t1)+"&fukt="+String(h1)+"&heat="+String(hic1)+" HTTP/1.1";

                //char test[] = output_value1;
                //client.println(F("GET /jordsensor.php?sen1=1&val1="+test+" HTTP/1.1"));
                Serial.print(testar);
                client.println(testar);
                client.println(F("Host: olsson1982.asuscomm.com"));
                client.println(F("Content-Type: application/json"));
                client.println("Connection: close");
                client.println();

                // note the time that the connection was made
                lastConnectionTime = millis();
                sparatemp = 0;
                setColor(0, 255, 0);
        }
        else {
                //Vid för många fel gör en omstart
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
        //delay(2000);
        //Avläser temperatur sensorn
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

                //    char c = client.read();
                //    Serial.write(c);

                String line = client.readStringUntil('\r');
                //Serial.print(line);

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
                        Serial.print("Aktuell pump: ");
                        Serial.println(pumpdata);
                        Serial.print("Status: ");
                        Serial.println(aktiveraddata);
                        info.O = pumpdata.toInt();
                        info.P = aktiveraddata.toInt();
                        if (info.P == 1) {
                                delay(5);
                                myRadio.openWritingPipe(addresses[1]);
                                myRadio.stopListening();
                                myRadio.write(&info, sizeof(info));
                                Serial.println("Skickar pumpdata aktiv");
                                myRadio.startListening();

                        } else if (info.P == 0) {
                                delay(5);
                                myRadio.openWritingPipe(addresses[1]);
                                myRadio.stopListening();
                                myRadio.write(&info, sizeof(info));
                                Serial.println("Skickar pumpdata inaktiv");
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
                        //pumpar = root["pump"].asArray();
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
                                        Serial.println("Skickar pumpdata aktiv");
                                        myRadio.startListening();

                                } else {
                                        delay(5);
                                        myRadio.openWritingPipe(addresses[1]);
                                        myRadio.stopListening();
                                        myRadio.write(&info, sizeof(info));
                                        Serial.println("Skickar pumpdata inaktiv");
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
                                                //Endast om larm gått skall det tjuta.
                                                delay(5);
                                                myRadio.openWritingPipe(addresses[5]); //Skickar på annan adress
                                                myRadio.stopListening();
                                                myRadio.write(&sirenen, sizeof(sirenen));
                                                Serial.println("Skickar siren aktiv");
                                                myRadio.startListening();
                                                myRadio.openWritingPipe(addresses[1]);
                                        }

                                } else {
                                        delay(5);
                                        myRadio.openWritingPipe(addresses[5]); //Skickar på annan adress
                                        myRadio.stopListening();
                                        myRadio.write(&sirenen, sizeof(sirenen));
                                        Serial.println("Skickar siren inaktiv");
                                        myRadio.startListening();
                                        myRadio.openWritingPipe(addresses[1]);
                                }

                        }

                        if (lastemp == 1) {
                                //Skicka med att spara temp värden
                                sparatemp = 1;
                                readTempSensor();

                        }

                        if (omstart == 1) {
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
                        Serial.println("Skickar pumpdata aktiv");
                        myRadio.startListening();
                        aktiveraPump = 0;

                } else if (info.P == 0) {
                        delay(5);
                        myRadio.openWritingPipe(addresses[1]);
                        myRadio.stopListening();
                        myRadio.write(&info, sizeof(info));
                        Serial.println("Skickar pumpdata inaktiv");
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
                        while (myRadio.available())
                        {
                                myRadio.read( &info, sizeof(info) );
                                if (info.O > 2500 && info.O < 3000 ) {
                                        Serial.print("Pump av: ");
                                        Serial.println(info.O);
                                        nuvarandePump = info.O;
                                        datanormal = 1;
                                        client.stop();
                                        if (!client.connected()) {
                                                Serial.println();
                                                Serial.println("Kopplar bort server...");
                                                client.stop();

                                        }
                                        delay(50);

                                        // if there's a successful connection
                                        if (client.connect(server, 8030)) {
                                                Serial.println("Ansluter...");


                                                // send the HTTP PUT request
                                                String testar = "GET /pump.php?pump="+String(nuvarandePump)+" HTTP/1.1";

                                                //char test[] = output_value1;
                                                //client.println(F("GET /jordsensor.php?sen1=1&val1="+test+" HTTP/1.1"));
                                                Serial.print(testar);
                                                client.println(testar);
                                                client.println(F("Host: olsson1982.asuscomm.com"));
                                                client.println(F("Content-Type: application/json"));
                                                client.println("Connection: close");
                                                client.println();

                                                // note the time that the connection was made
                                                //  lastConnectionTime = millis();
                                        }
                                        else {
                                                //Vid för många fel gör en omstart
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
                        while (myRadio.available())
                        {
                                myRadio.read( &sendback, sizeof(sendback) );
                                if (sendback.D > 6500 && sendback.D < 7000 ) {
                                        setColor(102, 0, 102);
                                        Serial.print("Vilt noterat: ");
                                        Serial.println(sendback.D);
                                        nuvarandeVilt = sendback.D;
                                        nuvarandeLarm = sendback.E;
                                        nuvarandeVolt = sendback.F;
                                        datanormal = 0;
                                        client.stop();
                                        if (!client.connected()) {
                                                Serial.println();
                                                Serial.println("Kopplar bort server...");
                                                client.stop();

                                        }
                                        delay(50);

                                        // if there's a successful connection
                                        if (client.connect(server, 8030)) {
                                                Serial.println("Ansluter...");


                                                // send the HTTP PUT request
                                                String testar = "GET /vilt.php?sensor="+String(nuvarandeVilt)+"&vilt="+String(nuvarandeLarm)+"&volt="+String(nuvarandeVolt)+" HTTP/1.1";

                                                //char test[] = output_value1;
                                                //client.println(F("GET /jordsensor.php?sen1=1&val1="+test+" HTTP/1.1"));
                                                Serial.print(testar);
                                                client.println(testar);
                                                client.println(F("Host: olsson1982.asuscomm.com"));
                                                client.println(F("Content-Type: application/json"));
                                                client.println("Connection: close");
                                                client.println();

                                                // note the time that the connection was made
                                                //  lastConnectionTime = millis();

                                        }
                                        else {
                                                //Vid för många fel gör en omstart
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
                        while (myRadio.available())
                        {
                                myRadio.read( &regn, sizeof(regn) );
                                if (regn.L > 1500 && regn.L < 2000 ) {
                                        setColor(102, 0, 102);
                                        Serial.print("Rensensor avläst: ");
                                        Serial.println(regn.L);
                                        Serial.println(regn.N);
                                        Serial.println(regn.M);
                                        Serial.println(regn.O);
                                        regnfukt = regn.M;
                                        regn.M =   map(regnfukt,torrregn,blotregn,0,100);
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
                                                Serial.println("Kopplar bort server...");
                                                client.stop();

                                        }
                                        delay(50);

                                        // if there's a successful connection
                                        if (client.connect(server, 8030)) {
                                                Serial.println("Ansluter...");


                                                // send the HTTP PUT request
                                                String testar = "GET /regn.php?sensor="+String(regn.L)+"&regnar="+String(regn.N)+"&varde="+String(regn.M)+"&volt="+String(regn.O)+" HTTP/1.1";

                                                //char test[] = output_value1;
                                                //client.println(F("GET /jordsensor.php?sen1=1&val1="+test+" HTTP/1.1"));
                                                Serial.print(testar);
                                                client.println(testar);
                                                client.println(F("Host: olsson1982.asuscomm.com"));
                                                client.println(F("Content-Type: application/json"));
                                                client.println("Connection: close");
                                                client.println();

                                                // note the time that the connection was made
                                                //  lastConnectionTime = millis();

                                        }
                                        else {
                                                //Vid för många fel gör en omstart
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
                        while (myRadio.available())
                        {

                                myRadio.read( &data, sizeof(data) );
                                //Serial.print(skickar);
                                /*    Serial.print("Sensor: ");
                                    Serial.println(data.X);
                                    Serial.print("Fukt: ");
                                    Serial.println(data.Y); */

                                if (data.X > 4500 && data.X < 5000) {

                                        setColor(80, 0, 80);
                                        Serial.println("En Jordsensor");
                                        pumpfukt = data.Y;
                                        sensor = data.X;
                                        jordtemp = data.Z;
                                        volten = data.F;
                                        pumpfukt = map(pumpfukt,torrjord,blotjord,0,100);
                                        if (pumpfukt > 100) {
                                                pumpfukt = 100;
                                        }
                                        if (pumpfukt < 0) {
                                                pumpfukt = 0;
                                        }
                                        Serial.print("Fuktighet: ");
                                        Serial.println(pumpfukt);
                                        Serial.print("Volt: ");
                                        Serial.println(volten);
                                        Serial.print("Sensor: ");
                                        Serial.println(sensor);
                                        Serial.print("Temp: ");
                                        Serial.println(jordtemp);
                                        datanormal = 1;
                                        client.stop();
                                        if (!client.connected()) {
                                                Serial.println();
                                                Serial.println("Kopplar bort server...");
                                                client.stop();

                                        }
                                        delay(50);

                                        // if there's a successful connection
                                        if (client.connect(server, 8030)) {
                                                Serial.println("Ansluter...");


                                                // send the HTTP PUT request
                                                String testar = "GET /sensorer.php?sensor="+String(sensor)+"&jordfukt="+String(pumpfukt)+"&jordtemp="+String(jordtemp)+"&volt="+String(volten)+" HTTP/1.1";

                                                //char test[] = output_value1;
                                                //client.println(F("GET /jordsensor.php?sen1=1&val1="+test+" HTTP/1.1"));
                                                Serial.print(testar);
                                                client.println(testar);
                                                client.println(F("Host: olsson1982.asuscomm.com"));
                                                client.println(F("Content-Type: application/json"));
                                                client.println("Connection: close");
                                                client.println();

                                                // note the time that the connection was made
                                                //  lastConnectionTime = millis();
                                        }
                                        else {
                                                //Vid för många fel gör en omstart
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

        }/* else if ( myRadio.available(addresses[3])) {
                while (myRadio.available(addresses[3]))
                {
                        myRadio.read( &info, sizeof(info) );
                        Serial.print("Avaktivera pump: ");
                        Serial.println(info.O);
                }
            } */

        if (millis() - lastConnectionTime > postingInterval) {
                datanormal = 0;
                readTempSensor();
                httpRequest();

        }

}
