# Arduino Wireless Garden System

This is the GitHub for an Wireless system to observe and water your garden or your cultivation.
It's an wireless system that uses 2.4 GHz to send and recive depending on what to do, also uses WIFI to publish to internet.

It's a module system so it can easy expand if you need to control more cultivations. And thru internet you can control it.

# The Base system is built with:
* Arduino Mega
* ESP-01 Module
* NRF24L01 Module
* DHT Humanid Sensor
The base system is handling the communication with all modules

# The soil sensors is built with:
* ATMEGA328P-PU Chip
* Soil Mouisture sensor
* Temp Sensor
* NRF24L01 module
* Li-Po charger module
We are using the ATMEGA chip sins the sensors are running on LI-PO battery. Each sensor has an unique number and transmitting the temperature, soil mouisture and battery level and they check the soil every hour.

# The rain sensor is built with:
* ATMEGA328P-PU Chip
* Rain sensor
* NRF24L01 module
* Li-Po charger module
We are using the ATMEGA chip sins the sensors are running on LI-PO battery. Each sensor has an unique number and transmitting if it's raining, and how wet the rain is, and battery level.

# The Animal sensor is built with:
* ATMEGA328P-PU Chip
* Pir Sensor module
* NRF24L01 module
* Li-Po charger module
We are using the ATMEGA chip sins the sensors are running on LI-PO battery. Each sensor has an unique number and transmitting if there are any movments in the area. These sensors are in sleep until movment occures.

# The Animal Siren is built with:
* Arduino Nano
* 12v Alarm Siren
* NRF24L01 module
If the Animal sensors is activated to alarm, when they see movments, the base system will send out to trigger the siren. Each siren has a uniqe number.

# The water pump is built with:
* Arduino Nano
* Relay Board
* NRF24L01 module
If it's need to water, the base system will activate the pump. Each pump has uniqe number.

You get the most importetnt php and mysql script, that is needed to get the system running, but you need to design and make the website self.
