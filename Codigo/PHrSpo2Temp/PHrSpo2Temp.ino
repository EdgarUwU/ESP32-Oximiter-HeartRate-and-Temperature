#include <WiFi.h>
#include <HTTPClient.h>
#include <DFRobot_MAX30102.h>
#include <Wire.h>   
#include <Adafruit_GFX.h>  
#include <Adafruit_SSD1306.h> 
#define ANCHO 128    
#define ALTO 64      
#include <OneWire.h>
#include <DallasTemperature.h>

#define OLED_RESET 4      
Adafruit_SSD1306 oled(ANCHO, ALTO, &Wire, OLED_RESET); 

DFRobot_MAX30102 particleSensor;
const int oneWireBus = 4;  
float temp = 0;   

OneWire oneWire(oneWireBus);
DallasTemperature sensors(&oneWire);
const char* ssid     = "INFINITUM3350";
const char* password = "tWtXHcKkB7";
const char* serverName = "http://192.168.1.65/datos/control/conexion.php";

String apiKeyValue = "tPmAT5Ab3j7F9";

String sensorLocation = "UnipoliDgo";
void setup()
{
  WiFi.begin(ssid, password);
  Serial.println("Connecting");
  while(WiFi.status() != WL_CONNECTED) { 
    delay(500);
    Serial.print(".");
  }
  Serial.println("");
  Serial.print("Connected to WiFi network with IP Address: ");
  Serial.println(WiFi.localIP());
   Wire.begin();         
  oled.begin(SSD1306_SWITCHCAPVCC, 0x3C); 
   sensors.begin();
  //Init serial
  Serial.begin(115200);
  while (!particleSensor.begin()) {
    Serial.println("MAX30102 was not found");
    delay(1000);
  }
  particleSensor.sensorConfiguration(/*ledBrightness=*/50, /*sampleAverage=*/SAMPLEAVG_4, \
                        /*ledMode=*/MODE_MULTILED, /*sampleRate=*/SAMPLERATE_100, \
                        /*pulseWidth=*/PULSEWIDTH_411, /*adcRange=*/ADCRANGE_16384);
}

int32_t SPO2;
int8_t SPO2Valid; 
int32_t heartRate; 
int8_t heartRateValid; 

void loop()
{
  WiFiClient client; 
  Serial.println(F("Espera 4 segundos"));
  particleSensor.heartrateAndOxygenSaturation(/**SPO2=*/&SPO2, /**SPO2Valid=*/&SPO2Valid, /**heartRate=*/&heartRate, /**heartRateValid=*/&heartRateValid);
  //Print result 
  Serial.print(F("heartRate="));
  Serial.print(heartRate, DEC);
  Serial.print(F(", heartRateValid="));
  Serial.print(heartRateValid, DEC);
  Serial.print(F("; SPO2="));
  Serial.print(SPO2, DEC);
  Serial.print(F(", SPO2Valid="));
  Serial.println(SPO2Valid, DEC);
  temp = sensors.getTempCByIndex(0);
  if (heartRateValid == 0 || SPO2Valid == 0){
    Serial.println("No detectado..");
     oled.clearDisplay();      
  oled.setTextColor(WHITE);   
  oled.setCursor(0, 0); 
    oled.setTextSize(1.5); 
    oled.print("Calculando..");
  }else{
  mandar_datos();
  pantalla(); 
}
oled.display();
}
void pantalla(){
  oled.clearDisplay();      
  oled.setTextColor(WHITE);  
  oled.setCursor(0, 0); 
  oled.setTextSize(2);     
  oled.print("HR: ");  
  oled.setTextSize(2);      
  oled.println(heartRate);
  oled.print("SPO2: ");
  oled.println(SPO2);
  oled.print("T: ");
  oled.print(temp);
oled.display();
}
void mandar_datos(){
  WiFiClient client; 
  HTTPClient http;    
    http.begin(client, serverName);
    http.addHeader("Content-Type", "application/x-www-form-urlencoded");
    
    // Prepare your HTTP POST request data
    String httpRequestData = "api_key=" + apiKeyValue + "&location="+ sensorLocation
                          + "&ritmo_cardiaco=" + heartRate + "&oxigenacion=" + SPO2 + "&temperatura=" + temp + "";
    Serial.print("httpRequestData: ");
    Serial.println(httpRequestData);
    int httpResponseCode = http.POST(httpRequestData);   
    if (httpResponseCode>0) {
      Serial.print("HTTP Response code: ");
      Serial.println(httpResponseCode);
    }
    else {
      Serial.print("Error code: ");
      Serial.println(httpResponseCode);
    }
    http.end();
  }
