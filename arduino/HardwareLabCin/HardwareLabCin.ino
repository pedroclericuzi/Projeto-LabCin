#include <SPI.h>
#include <MFRC522.h>
#include <Ethernet.h>
#include <LiquidCrystal_I2C.h>

/* ETHERNET VARIABLES */
//byte ip[] = {169,255,5,7};
byte ip[] = {169,254,191,189};
byte mac[] = { 0xDE, 0xAD, 0xBE, 0xEF, 0xFE, 0xED };
EthernetServer server = EthernetServer(9023);

// PIN Numbers : RESET + SDAs
#define RST_PIN         9
#define SS_1_PIN        A0
#define SS_2_PIN        A1

#define NR_OF_READERS   2

String RFID_EXTERNAL = "";
String RFID_1_INTERNAL = "";
String RFID_2_INTERNAL = "";
String RFID_3_INTERNAL = "";

byte ssPins[] = {SS_1_PIN, SS_2_PIN};

MFRC522 mfrc522[NR_OF_READERS];

// MAGNETIC SENSOR
int lastState; //0 = fechada; 1 = aberta
#define pinMagneticSensor 2
bool canChangeStateToOpen;
bool alreadStartedLock;

//Liquid Crystal (LCD)
LiquidCrystal_I2C lcd(0x27,20,4);

//Rele module
const int RelePin = 4;

//Buzzer
const int buzzer = 6;
float seno;
float frequencia;

//SYSTEM VARS
const String BAIA = "4";
bool server_connected;

//ANÁLISE DE TEMPO
unsigned long inicioRequisicao;
unsigned long fimRequisicao;

void setup() 
{
  Serial.begin(9600);
  SPI.begin();
  pinMode(pinMagneticSensor, INPUT);
  setupSettingValues();
  setupEthernet();
  setupLCD();
  setupBuzzer();
  setupRele();
  Serial.println("Approximate your card to the reader...");
  Serial.println();
}

void setupSettingValues() {
  lastState = 1; //0 = fechada; 1 = aberta
  canChangeStateToOpen = false;
  alreadStartedLock = false;
  server_connected = false;
  RFID_EXTERNAL = "";
  RFID_1_INTERNAL = "";
  RFID_2_INTERNAL = "";
  RFID_3_INTERNAL = "";
}

void setupEthernet(){
  Ethernet.begin(mac,ip); // init EthernetShield
  delay(1000);
  server.begin();
  if(server.available()){
    Serial.println("Client available");
  } else {
    Serial.println("Client not avaliable");
  }
}

void setupLCD() {
  lcd.init();
  lcd.backlight();
  lcd.print("Aprox. seu cartao");
}

void setupBuzzer() {
  pinMode(buzzer, OUTPUT);
}

void setupRele() {
  pinMode(RelePin, OUTPUT);  
}

void loop() 
{
  if (canChangeStateToOpen && digitalRead(pinMagneticSensor) == HIGH){
    lastState = 1;
  }

  /*Serial.println("-------------------------------");
  Serial.println(digitalRead(pinMagneticSensor));
  Serial.println(lastState);
  Serial.println(!alreadStartedLock);
  Serial.println(server_connected);
  Serial.println("-------------------------------");*/
  
  read_rfids();
  char joinStringsToSend[50];
  if(digitalRead(pinMagneticSensor) == LOW && RFID_EXTERNAL != "") {
    inicioRequisicao = millis(); //TESTE
    strcpy(joinStringsToSend,"1#");
    strcat(joinStringsToSend,BAIA.c_str());
    strcat(joinStringsToSend,"#");
    strcat(joinStringsToSend,RFID_EXTERNAL.c_str());
    Serial.println(joinStringsToSend);
    char tBuffer[50];
    snprintf(tBuffer, sizeof(tBuffer), "%s", joinStringsToSend);
    server.write(tBuffer);
    RFID_EXTERNAL = "";
    lcd_message("Aguarde um instante");
    delay(1000);
  } else if(digitalRead(pinMagneticSensor) == LOW && lastState==1 && 
              !alreadStartedLock && server_connected){
    inicioRequisicao = millis(); //TESTE
    char joinStringsToSend[50];
    String equipamentos = check_equipament();    
    strcpy(joinStringsToSend,"2#");
    strcat(joinStringsToSend,BAIA.c_str());
    strcat(joinStringsToSend,"#");
    strcat(joinStringsToSend,equipamentos.c_str());
    char tBuffer[50];
    snprintf(tBuffer, sizeof(tBuffer), "%s", joinStringsToSend);
    server.write(tBuffer);
    alreadStartedLock = true;
    lcd_message("Fechando baia...");
  }
  EthernetClient client = server.available();
  if(client){
    receiveResponseFromServer(client);
  }

  RFID_1_INTERNAL = "";
  RFID_2_INTERNAL = "";
  RFID_3_INTERNAL = "";
}

String check_equipament() {
  String equipament = "";
  if (RFID_1_INTERNAL != "") {
    equipament.concat(concatenate_rfid(RFID_1_INTERNAL));
  }
  if (RFID_2_INTERNAL != "") {
    equipament.concat(concatenate_rfid(RFID_2_INTERNAL));
  } 
  if (RFID_3_INTERNAL != "") {
    equipament.concat(RFID_3_INTERNAL);
  }
  return equipament;
}

String concatenate_rfid(String INTERNAL_RFID) {
  String equipamentInCorrectFormat = "";
  const String semicolon = ";";
  equipamentInCorrectFormat.concat(INTERNAL_RFID);
  equipamentInCorrectFormat.concat(semicolon);
  return equipamentInCorrectFormat;
}

void receiveResponseFromServer(EthernetClient client){
  String message = client.readStringUntil('\n');

  /*TESTES*/
  Serial.print("Resposta -> ");
  Serial.println(message);
  Serial.print("Tempo em milisegundos -> ");
  fimRequisicao = millis();
  long calculoTempo = fimRequisicao - inicioRequisicao;
  Serial.println(calculoTempo);
  inicioRequisicao = 0;
  fimRequisicao = 0;
  Serial.println("-------------------------------------");
  if (message == "no_network"){
    lcd_instant_message("Baia fechada.");
    lcd_message("Sem rede.");
  } else if(message == "allowed") {
    canChangeStateToOpen = true;
    turnOnBuzzer(0);
    lcd_instant_message_with_rele("Acesso concedido.");
    noTone(buzzer);
    //Serial.println(message);
  } else if(message == "danied") {
    //Serial.println(message);
    turnOnBuzzer(1);
    lcd_instant_message("Acesso negado.");
    noTone(buzzer);
    lcd.print("Aprox. seu cartao");
  } else if (message == "connected") {
    server_connected = true;
    server.write("conexão estabelecida");
    //Serial.println("está conectado");
  } else if (message == "locked") {
    lastState = 0;
    alreadStartedLock = false;
    lcd_instant_message("Baia fechada.");
    //Serial.println(message);
    lcd.print("Aprox. seu cartao");
  } else if (message == "blocked") {
    lastState = 0;
    alreadStartedLock = false;
    lcd_message("Baia bloqueada.");
  } else if(message == "notfound") {
    //Serial.println(message);
    turnOnBuzzer(1);
    lcd_instant_message("Nao cadastrado.");
    noTone(buzzer);
    lcd.print("Aprox. seu cartao");
  }
}

void lcd_instant_message_with_rele(String message) {
  lcd_message(message);
  digitalWrite(RelePin, HIGH);
  delay(1000);
  digitalWrite(RelePin, LOW);
  delay(2000);
  lcd.clear();
  lcd.backlight();
}

void lcd_instant_message(String message) {
  lcd_message(message);
  delay(3000);
  lcd.clear();
  lcd.backlight();
}

void lcd_message(String message) {
  lcd.setCursor(0,0);
  lcd.print(message);
  //Serial.println("Chegou aqui");
}

void read_rfid_external_only(){
  mfrc522[0].PCD_Init(ssPins[0], RST_PIN);
  Serial.println("Approximate your card to the reader...");
  Serial.println();
  if (mfrc522[0].PICC_IsNewCardPresent() && mfrc522[0].PICC_ReadCardSerial()) {
    // Show some details of the PICC (that is: the tag/card)
    dump_byte_array(mfrc522[0].uid.uidByte, mfrc522[0].uid.size);
    Serial.println();
    // Halt PICC
    mfrc522[0].PICC_HaltA();
  }
}

void read_rfids(){
  String uidTag = "";
  for (uint8_t reader = 0; reader < NR_OF_READERS; reader++) {
    mfrc522[reader].PCD_Init(ssPins[reader], RST_PIN);
    if (mfrc522[reader].PICC_IsNewCardPresent() && mfrc522[reader].PICC_ReadCardSerial()) {
      uidTag = dump_byte_array(mfrc522[reader].uid.uidByte, mfrc522[reader].uid.size);
      if (reader == 0){
        RFID_EXTERNAL = uidTag;  
      } else if (reader == 1) {
        RFID_1_INTERNAL = uidTag;
      } else if (reader == 2) {
        RFID_2_INTERNAL = uidTag;
      } else {
        RFID_3_INTERNAL = uidTag;
      }
      mfrc522[reader].PICC_HaltA();
    }
  }
}

String dump_byte_array(byte * buffer, byte bufferSize) {
  String content= "";
  for (byte i = 0; i < bufferSize; i++) 
  {
     content.concat(String(buffer[i] < 0x10 ? "0" : ""));
     content.concat(String(buffer[i], HEX));
  }
  content.toUpperCase();
  return content;
}

void turnOnBuzzer(int mode) {
 for(int x=0;x<90;x++){
  //converte graus para radiando e depois obtém o valor do seno
  seno=(sin(x*3.1416/180));
  if(mode == 0){
    //gera uma frequência a partir do valor do seno
    frequencia = 500+(int(seno*1000));
  } else {
    frequencia = 1000;
  }
  tone(buzzer,frequencia);
  delay(2);
 }
}

void turnOffBuzzer() {
 noTone(buzzer);
 delay(2);
}
