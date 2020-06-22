<?php

use DateTime;

class AJPES
{
    private $cert; //Path to certificate in pem format
    private $pw; //Certificate password
    private $password; //AjpesController password
    private $username; //AjpesController username
    private $xml; //Data to post
    private $wsdl; //WSDL service
    private $body;
    private $format = 1;
    private $response;

    public function __construct()
    {
        $this->cert = "/path/to/your/cert.pem";
        $this->pw = "certpassword";
        $this->username = "ajpes_username";
        $this->password = "ajpes_password";
        $this->wsdl = "https://www.ajpes.si/wsrno/eTurizem/wsETurizemPorocanje.asmx?WSDL";
        $this->getXMLdata();
    }

    public function setTestMode(){
        $this->cert = "AjpesTest.pem";
        $this->pw = "ajpestest";
        $this->username = "apiTest";
        $this->password = "Test123!";
        $this->wsdl = "https://wwwt.ajpes.si/rno/rnoApi/eTurizem/wsETurizemPorocanje.asmx";
        $this->getXMLdata();
    }


    private function createSoapBody(){
        $this->body =
            "<soapenv:Envelope xmlns:soapenv=\"http://schemas.xmlsoap.org/soap/envelope/\" xmlns:etur=\"http://www.ajpes.si/eturizem/\">
                <soapenv:Header/>
                   <soapenv:Body>
                         <etur:oddajPorocilo>
                                  <etur:uName>".$this->username."</etur:uName>
                                  <etur:pwd>".$this->password."</etur:pwd>
                                  <etur:data>
                                  ".$this->xml."
                                  </etur:data>
                                  <etur:format>
                                  ".$this->format."
                                  </etur:format>
                                        </etur:oddajPorocilo>
                                           </soapenv:Body>
                                  </soapenv:Envelope>";
    }
    private function createHeaders(){
        return [
            "Content-Type: text/xml;charset=UTF-8,",
            "SOAPAction: \"http://www.ajpes.si/eturizem/oddajPorocilo\"",
            "Host: wwwt.ajpes.si"
        ];
    }

    public function report(){
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->wsdl,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "gzip,deflate",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => $this->body,
            CURLOPT_HTTPHEADER => $this->createHeaders(),
            CURLOPT_SSL_VERIFYHOST => 2,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSLCERT => $this->cert,
            CURLOPT_SSLCERTPASSWD => $this->pw,
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return $response;
    }

    public function createXMLdata( ){
        $this->xml = "<knjigaGostov>
            <row status=\"1\" ttVisina=\"0.246\" ttObracun=\"12\" casOdhoda=\"2017-09-13T23:26:00\" casPrihoda=\"2017-05-09T14:50:00\" idStDok=\"132F20F6-73E\" vrstaDok=\"H\" drzava=\"SE\" dtRoj=\"1985-11-26\" sp=\"F\" pri=\"Fraser\" ime=\"Grace\" zst=\"14\" idNO=\"0\"/>
            <row status=\"1\" ttVisina=\"1.374\" ttObracun=\"2\" casOdhoda=\"2017-05-15T08:09:41\" casPrihoda=\"2017-05-11T20:13:41\" idStDok=\"9F207CC9-604\" vrstaDok=\"V\" drzava=\"BA\" dtRoj=\"2000-06-12\" sp=\"M\" pri=\"Janković\" ime=\"Đorđe\" zst=\"11\" idNO=\"0\"/>
            <row status=\"1\" ttVisina=\"1.750\" ttObracun=\"7\" casPrihoda=\"2017-05-09T14:50:00\" idStDok=\"688603F6-566\" vrstaDok=\"I\" drzava=\"SI\" dtRoj=\"1991-05-29\" sp=\"M\" pri=\"Žalič\" ime=\"Miško\" zst=\"7\" idNO=\"0\"/>
            <row status=\"1\" ttVisina=\"0.430\" ttObracun=\"6\" casOdhoda=\"2017-05-13T21:46:51\" casPrihoda=\"2017-05-10T09:50:51\" idStDok=\"6759488E-605\" vrstaDok=\"V\" drzava=\"SI\" dtRoj=\"1960-03-04\" sp=\"M\" pri=\"Čargaž\" ime=\"Šime\" zst=\"7\" idNO=\"20037\"/>
            <row status=\"1\" ttVisina=\"1.920\" ttObracun=\"1\" casOdhoda=\"2017-01-05T14:13:28\" casPrihoda=\"2017-01-02T02:17:28\" idStDok=\"499275B4-AD9\" vrstaDok=\"U\" drzava=\"PH\" dtRoj=\"1978-10-15\" sp=\"M\" pri=\"Rees\" ime=\"Richard\" zst=\"77\" idNO=\"20052\"/>
            <row status=\"1\" ttVisina=\"0.166\" ttObracun=\"4\" casOdhoda=\"2017-02-23T07:14:09\" casPrihoda=\"2017-02-19T19:18:09\" idStDok=\"12ADC671-F6F\" vrstaDok=\"O\" drzava=\"GQ\" dtRoj=\"1940-09-03\" sp=\"M\" pri=\"Peake\" ime=\"Gavin\" zst=\"24\" idNO=\"0\"/>
            <row status=\"1\" ttVisina=\"0.908\" ttObracun=\"13\" casOdhoda=\"2017-03-11T13:58:05\" casPrihoda=\"2017-03-08T02:02:05\" idStDok=\"2711BE4C-D79\" vrstaDok=\"P\" drzava=\"IL\" dtRoj=\"1999-02-25\" sp=\"M\" pri=\"Brown\" ime=\"Michael\" zst=\"64\" idNO=\"20037\"/>
            <row status=\"1\" ttVisina=\"1.640\" ttObracun=\"3\" casPrihoda=\"2017-05-09T14:50:00\" idStDok=\"F9BC4F2E-7F5\" vrstaDok=\"V\" drzava=\"CU\" dtRoj=\"1966-09-26\" sp=\"F\" pri=\"Dickens\" ime=\"Abigail\" zst=\"150\" idNO=\"20040\"/>
            <row status=\"1\" ttVisina=\"0.674\" ttObracun=\"2\" casOdhoda=\"2017-09-13T23:26:00\" casPrihoda=\"2017-05-09T14:50:00\" idStDok=\"F0340017-391\" vrstaDok=\"H\" drzava=\"HK\" dtRoj=\"2000-06-16\" sp=\"F\" pri=\"King\" ime=\"Abigail\" zst=\"43\" idNO=\"20045\"/>
            <row status=\"1\" ttVisina=\"1.830\" ttObracun=\"4\" casPrihoda=\"2017-05-03T02:37:20\" idStDok=\"72F7A07C-927\" vrstaDok=\"H\" drzava=\"KY\" dtRoj=\"1979-04-12\" sp=\"M\" pri=\"Randall\" ime=\"Adam\" zst=\"100\" idNO=\"20048\"/>
            <row status=\"1\" ttVisina=\"0.428\" ttObracun=\"1\" casOdhoda=\"2017-09-14T20:40:42\" casPrihoda=\"2017-05-09T14:50:00\" idStDok=\"ADBB7F3C-5E0\" vrstaDok=\"O\" drzava=\"CA\" dtRoj=\"1977-02-11\" sp=\"M\" pri=\"Mackenzie\" ime=\"Adrian\" zst=\"112\" idNO=\"20037\"/>
            <row status=\"1\" ttVisina=\"1.832\" ttObracun=\"4\" casPrihoda=\"2017-03-25T12:50:18\" idStDok=\"4EB81C1D-F9E\" vrstaDok=\"F\" drzava=\"SL\" dtRoj=\"1946-02-16\" sp=\"M\" pri=\"Clark\" ime=\"Alan\" zst=\"40\" idNO=\"20048\"/>
            <row status=\"1\" ttVisina=\"1.875\" ttObracun=\"11\" casOdhoda=\"2017-09-13T23:26:00\" casPrihoda=\"2017-05-09T14:50:00\" idStDok=\"634A3BA0-E81\" vrstaDok=\"U\" drzava=\"PR\" dtRoj=\"1952-09-08\" sp=\"F\" pri=\"Rees\" ime=\"Diana\" zst=\"91\" idNO=\"20043\"/>
            </knjigaGostov>";

        // SOME TEST DATA
        $this->createSoapBody();
        return $this->xml;
    }
}
