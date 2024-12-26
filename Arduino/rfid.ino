#include <SPI.h>
#include <MFRC522.h>
#include <ESP8266WiFi.h>
#include <ESP8266HTTPClient.h>

#define RST_PIN D2   // Reset pin untuk MFRC522
#define SS_PIN D4    // Slave select pin untuk MFRC522

const char* ssid = "namaWifi"; // Nama Wi-Fi
const char* password = "passwordWifi";         // Password Wi-Fi

// Laravel API URL
const char* serverName = "http://ip-mu:port/api/proses-transaksi";

// Inisialisasi RFID
MFRC522 rfid(SS_PIN, RST_PIN);

// Objek WiFiClient untuk koneksi HTTP
WiFiClient wifiClient;

void setup() {
  Serial.begin(115200);       // Serial monitor untuk debugging
  SPI.begin();                // Inisialisasi SPI bus
  rfid.PCD_Init();            // Inisialisasi MFRC522
  
  // Hubungkan ke Wi-Fi
  WiFi.begin(ssid, password);
  Serial.println("Menghubungkan ke Wi-Fi...");
  while (WiFi.status() != WL_CONNECTED) {
    delay(500);
    Serial.print(".");
  }
  Serial.println("\nTerhubung ke Wi-Fi");
}

void loop() {
  // Periksa apakah kartu RFID berada di dekat reader
  if (!rfid.PICC_IsNewCardPresent() || !rfid.PICC_ReadCardSerial()) {
    return; // Tidak ada kartu yang terdeteksi
  }

  // Baca UID dari kartu
  String uid = "";
  for (byte i = 0; i < rfid.uid.size; i++) {
    uid += String(rfid.uid.uidByte[i], HEX);
  }
  uid.toUpperCase(); // Ubah UID menjadi huruf kapital
  Serial.println("UID Terbaca: " + uid);

  // Kirim UID ke server
  if (WiFi.status() == WL_CONNECTED) {
    HTTPClient http;
    http.begin(wifiClient, serverName); // Gunakan WiFiClient dan URL
    http.addHeader("Content-Type", "application/x-www-form-urlencoded");
    
    // Data yang akan dikirimkan ke API
    String postData = "uid=" + uid;

    // Kirim data
    int httpResponseCode = http.POST(postData);

    // Tampilkan respons dari server
    if (httpResponseCode > 0) {
      String response = http.getString();
      Serial.println("Response: " + response);
    } else {
      Serial.println("Error dalam mengirim data: " + String(httpResponseCode));
    }
    http.end(); // Tutup koneksi HTTP
  } else {
    Serial.println("Wi-Fi tidak terhubung!");
  }

  delay(3000); // Tunggu beberapa saat sebelum membaca kartu berikutnya
}
