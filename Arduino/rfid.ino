#include <SPI.h>
#include <MFRC522.h>
#include <ESP8266WiFi.h>
#include <ESP8266HTTPClient.h>
#include <Servo.h>
#include <ArduinoJson.h>

#define RST_PIN D2
#define SS_PIN D3
#define SERVO_PIN D4

const char* ssid = "your wifi name";
const char* password = "your wifi password";
const char* serverName = "http://your-ip:port /api/proses-transaksi";

enum GateState {
  GATE_CLOSED,
  GATE_WAITING,
  GATE_OPEN
};

MFRC522 rfid(SS_PIN, RST_PIN);
WiFiClient wifiClient;
Servo myservo;
GateState currentState = GATE_CLOSED;

void setup() {
  Serial.begin(115200);
  SPI.begin();
  rfid.PCD_Init();

  WiFi.begin(ssid, password);
  Serial.println("Mengkoneksikan Ke Wifi....");
  while (WiFi.status() != WL_CONNECTED) {
    delay(500);
    Serial.print(".");
  }
  Serial.println("\nBerhasil Terkoneksi ke WiFi");

  myservo.attach(SERVO_PIN);
  myservo.write(0);
  Serial.println("Gerband Dalam Keadan Tutup");
}

void checkTransactionStatus(String uid) {
  HTTPClient http;
  for(int i = 0; i < 10; i++) {
    http.begin(wifiClient, serverName);
    http.addHeader("Content-Type", "application/x-www-form-urlencoded");
    String checkData = "uid=" + uid;
    int checkCode = http.POST(checkData);
    
    if (checkCode > 0) {
      String checkResponse = http.getString();
      StaticJsonDocument<200> checkDoc;
      DeserializationError checkError = deserializeJson(checkDoc, checkResponse);
      
      if (!checkError && 
          strcmp(checkDoc["status"], "success") == 0 && 
          strcmp(checkDoc["gate_status"], "open") == 0) {
        currentState = GATE_OPEN;
        Serial.println("===========================================");
        Serial.println("Transaksi Sukses, Gerbang Dibuka");
        Serial.println("Membuka Gerbang...");
        myservo.write(180);
        delay(5000);
        currentState = GATE_CLOSED;
        Serial.println("5 detik berlalu, menutup gerbang...");
        myservo.write(0);
        Serial.println("Gerbang Ditutup, Siap Untuk Transaksi Selanjutnya!!!");
        Serial.println("===========================================");
        http.end();
        return;
      }
    }
    http.end();
    delay(1000);
  }
  Serial.println("Transaksi Gagal, Gerbang Tetap Ditutup");
}

void loop() {
  if (currentState == GATE_CLOSED) {
    myservo.write(0);
  }

  if (!rfid.PICC_IsNewCardPresent() || !rfid.PICC_ReadCardSerial()) {
    return;
  }

  String uid = "";
  for (byte i = 0; i < rfid.uid.size; i++) {
    uid += String(rfid.uid.uidByte[i], HEX);
  }
  uid.toUpperCase();
  Serial.println("\nKartu Terdeteksi - UID: " + uid);

  if (WiFi.status() == WL_CONNECTED) {
    HTTPClient http;
    http.begin(wifiClient, serverName);
    http.addHeader("Content-Type", "application/x-www-form-urlencoded");
    
    String postData = "uid=" + uid;
    int httpCode = http.POST(postData);

    if (httpCode > 0) {
      String response = http.getString();
      Serial.println("Server response: " + response);

      StaticJsonDocument<200> doc;
      DeserializationError error = deserializeJson(doc, response);

      if (!error) {
        const char* status = doc["status"];
        const char* gateStatus = doc["gate_status"];

        if (strcmp(status, "success") == 0) {
          if (strcmp(gateStatus, "open") == 0) {
            currentState = GATE_OPEN;
            Serial.println("===========================================");
            Serial.println("Transaksi Sukses, Membuka Gerbang");
            myservo.write(180);
            delay(5000);
            currentState = GATE_CLOSED;
            Serial.println("5 Detik Berlalu, Menutup Gerbang...");
            myservo.write(0);
            Serial.println("Gerbang Ditutup, Siap Untuk Transaksi Selanjutnya!!!");
            Serial.println("===========================================");
          } 
          else if (strcmp(gateStatus, "waiting") == 0) {
            currentState = GATE_WAITING;
            Serial.println("Menunggu Konfirmasi Transaksi...");
            checkTransactionStatus(uid);
          }
        } else {
          currentState = GATE_CLOSED;
          Serial.println("Akses Ditolak, Kartu Tidak Terdaftar");
        }
      }
    }
    http.end();
  }
  delay(2000);
}