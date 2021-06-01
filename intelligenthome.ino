#include <Arduino.h>
#include <ESP8266WiFi.h>
#include <ESP8266WiFiMulti.h>
#include <ESP8266HTTPClient.h>
#include <WiFiClient.h>

#define SERVER_IP "192.168.0.18"
#ifndef STASSID
#define STASSID "CGA2121_bBu8qzh"
#define STAPSK  "VozdeAngel61110#$456"
#endif
#define USER "admin"
#define PASS "123"

#define PIR 15  //GPIO15=D8
#define LED 16  //GPIO16=D0

ESP8266WiFiMulti WiFiMulti;
String sesion="n",jwt;

void setup() {
  Serial.begin(115200);
  Serial.println();
  Serial.println();
  Serial.println();

  for (uint8_t t = 4; t > 0; t--) {
    Serial.printf("[SETUP] WAIT %d...\n", t);
    Serial.flush();
    delay(1000);
  }

  WiFi.mode(WIFI_STA);
  WiFiMulti.addAP(STASSID, STAPSK);

  pinMode(PIR, INPUT);
  pinMode(LED, OUTPUT);
}

void loop() {
  if ((WiFiMulti.run() == WL_CONNECTED)) {
    int x = digitalRead(PIR);
    
    if(x == HIGH) digitalWrite(LED, HIGH);
    else digitalWrite(LED, LOW);
    
    if(sesion == "y") post();
    else login();
  }
  delay(30000);
}

void login(){
    WiFiClient client;
    HTTPClient http;
    Serial.print("[HTTP] begin...\n");
    if (http.begin(client, "http://pw4.kyared.com/equipo1/intelligenthome/login.php?user="USER"&pass="PASS)) {
      Serial.print("[HTTP] GET...\n");
      int httpCode = http.GET();
      if (httpCode > 0) {
        Serial.printf("[HTTP] GET... code: %d\n", httpCode);
        if (httpCode == HTTP_CODE_OK || httpCode == HTTP_CODE_MOVED_PERMANENTLY) {
          String payload = http.getString();
          Serial.println(payload);
          
          int inicio = payload.indexOf("login");
          int fin =  payload.indexOf(',',inicio);
          sesion =  payload.substring(inicio + 8, fin - 1);
          Serial.println("sesion: " + sesion);
          inicio = payload.indexOf("token");
          fin =  payload.indexOf('}',inicio);
          jwt = payload.substring(inicio + 8, fin - 1);
          Serial.println("El token es: " + jwt);
        }
      } else {
        Serial.printf("[HTTP] GET... failed, error: %s\n", http.errorToString(httpCode).c_str());
      }
      http.end();
    } else {
      Serial.printf("[HTTP} Unable to connect\n");
    }
}

void post(){
   WiFiClient client;
    HTTPClient http;

    Serial.print("[HTTP] begin...\n");
    http.begin(client, "http://pw4.kyared.com/equipo1/intelligenthome/sensores.php"); //HTTP
    http.addHeader("Content-Type", "application/x-www-form-urlencoded");
    http.addHeader("Authorization", jwt);

    int x = digitalRead(PIR);
    
    Serial.print("[HTTP] POST...\n");
    int httpCode = http.POST("tipo=P&valor=" + (String)x);
    if (httpCode > 0) {
      Serial.printf("[HTTP] POST... code: %d\n", httpCode);
      if (httpCode == HTTP_CODE_OK) {
        const String& payload = http.getString();
        Serial.println("received payload:\n<<");
        Serial.println(payload);
        Serial.println(">>");
      }
    } else {
      Serial.printf("[HTTP] POST... failed, error: %s\n", http.errorToString(httpCode).c_str());
    }

    http.end();
}
