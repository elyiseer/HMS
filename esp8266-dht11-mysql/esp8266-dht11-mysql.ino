//Tech Trends Shameer
//Store the temperature and humidity value in database and display values in website

#include <ESP8266WiFi.h>
#include <ESP8266HTTPClient.h>
#include <WiFiClient.h>

//-------------------------------------------------------------------
#include <DHT.h>
#define DHT11_PIN 2
#define DHTTYPE DHT11

DHT dht(DHT11_PIN, DHTTYPE);
int g;
//-------------------------------------------------------------------
//enter WIFI credentials
const char* ssid     = "NON FAMILY";
const char* password = "expecttheunexpected";
//-------------------------------------------------------------------
//enter domain name and path
//http://www.example.com/sensordata.php
const char* SERVER_NAME = "http://fl7xm.localto.net/hms/sensordata.php";

//PROJECT_API_KEY is the exact duplicate of, PROJECT_API_KEY in config.php file
//Both values must be same
String PROJECT_API_KEY = "5fsdsf2ev5FF";
//-------------------------------------------------------------------
//Send an HTTP POST request every 30 seconds
unsigned long lastMillis = 0;
long interval = 1000;
//-------------------------------------------------------------------

/*
 * *******************************************************************
   setup() function
 * *******************************************************************
*/
void setup() {

  //-----------------------------------------------------------------
  Serial.begin(115200);
  Serial.println("esp32 serial initialize");
  //-----------------------------------------------------------------
  dht.begin();
  Serial.println("initialize DHT11");
  //-----------------------------------------------------------------
  WiFi.begin(ssid, password);
  Serial.println("Connecting");
  while (WiFi.status() != WL_CONNECTED) {
    delay(500);
    Serial.print(".");
  }
  Serial.println("");
  Serial.print("Connected to WiFi network with IP Address: ");
  Serial.println(WiFi.localIP());

  Serial.println("Timer set to 5 seconds (timerDelay variable),");
  Serial.println("it will take 5 seconds before publishing the first reading.");
  //-----------------------------------------------------------------
}


/*
 * *******************************************************************
   setup() function
 * *******************************************************************
*/
void loop() {

  //-----------------------------------------------------------------
  //Check WiFi connection status
  if (WiFi.status() == WL_CONNECTED) {
    if (millis() - lastMillis > interval) {
      //Send an HTTP POST request every interval seconds
      upload_temperature();
      lastMillis = millis();
    }
  }
  //-----------------------------------------------------------------
  else {
    Serial.println("WiFi Disconnected");
  }
  //-----------------------------------------------------------------

  delay(1000);
}


void upload_temperature()
{
  //--------------------------------------------------------------------------------
  //Sensor readings may also be up to 2 seconds 'old' (its a very slow sensor)
  //Read temperature as Celsius (the default)
  float t = dht.readTemperature();
  float h = dht.readHumidity();
  float g = analogRead(A0);

  if (isnan(h) || isnan(t)) {
    Serial.println(F("Failed to read from DHT sensor!"));
    return;
  }else if (isnan(g)){
    Serial.println("Failed to read from MQ-2 sensor!");
    return;
  }

  //Compute heat index in Celsius (isFahreheit = false)
  float hic = dht.computeHeatIndex(t, h, false);
  //--------------------------------------------------------------------------------
  //°C
  String humidity = String(h, 2);
  String temperature = String(t, 2);
  String gas = String(g/1023*100,2);
  String heat_index = String(hic, 2);

  Serial.println("Temperature: " + temperature);
  Serial.println("Humidity: " + humidity);
  Serial.println("Gas: " + gas);
  //Serial.println(heat_index);
  Serial.println("--------------------------");
  //--------------------------------------------------------------------------------
  //HTTP POST request data
  String temperature_data;
  temperature_data = "api_key=" + PROJECT_API_KEY;
  temperature_data += "&temperature=" + temperature;
  temperature_data += "&humidity=" + humidity;
  temperature_data += "&gas=" + gas;

  Serial.print("temperature_data: ");
  Serial.println(temperature_data);
  //--------------------------------------------------------------------------------

  WiFiClient client;
  HTTPClient http;

  http.begin(client, SERVER_NAME);
  // Specify content-type header
  http.addHeader("Content-Type", "application/x-www-form-urlencoded");
  // Send HTTP POST request
  int httpResponseCode = http.POST(temperature_data);
  //--------------------------------------------------------------------------------
  // If you need an HTTP request with a content type:
  //application/json, use the following:
  //http.addHeader("Content-Type", "application/json");
  //temperature_data = "{\"api_key\":\""+PROJECT_API_KEY+"\",";
  //temperature_data += "\"temperature\":\""+temperature+"\",";
  //temperature_data += "\"humidity\":\""+humidity+"\"";
  //temperature_data += "}";
  //int httpResponseCode = http.POST(temperature_data);
  //--------------------------------------------------------------------------------
  // If you need an HTTP request with a content type: text/plain
  //http.addHeader("Content-Type", "text/plain");
  //int httpResponseCode = http.POST("Hello, World!");
  //--------------------------------------------------------------------------------
  Serial.print("HTTP Response code: ");
  Serial.println(httpResponseCode);

  // Free resources
  http.end();
}
