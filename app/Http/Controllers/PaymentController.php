<?php

namespace App\Http\Controllers;

use App\User;
use App\Http\Controllers\Controller;

class PaymentController extends Controller
{
    public function scheduleThisMothafucka($paymentAmount, $orderID, $value, $type, $packetName, $token_type, $access_token){

      //Schedulowe pobieranie opłaty
      $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL, "https://secure.snd.payu.com/api/v2_1/orders/");
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
      curl_setopt($ch, CURLOPT_HEADER, FALSE);

      curl_setopt($ch, CURLOPT_POST, TRUE);
      curl_setopt($ch, CURLOPT_POSTFIELDS, "{
        \"notifyUrl\": \"http://payu.test/notify\",
        \"customerIp\": \"127.0.0.1\",
        \"merchantPosId\": \"332235\",
        \"description\": \"Testowy sklep\",
        \"currencyCode\": \"PLN\",
        \"totalAmount\": \"$paymentAmount\",
        \"extOrderId\": \"$orderID\",
        \"recurring\": \"STANDARD\",
        \"products\": [
          {
            \"name\": \"$packetName\",
            \"unitPrice\": \"$paymentAmount\",
            \"quantity\": \"1\"
          }
        ],
        \"buyer\": {
          \"email\": \"john.doe@example.com\",
          \"firstName\": \"Jan\",
          \"lastName\": \"Kowalski\",
          \"language\": \"pl\"
        },
        \"payMethods\": {
          \"payMethod\":{
            \"value\": \"$value\",
            \"type\": \"$type\"
          }
        }
      }");
      curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        "Content-Type: application/json",
        "Authorization: $token_type $access_token"
      ));

      $response = curl_exec($ch);
      curl_close($ch);
      $response = json_decode($response, true);
      echo "<pre>";
      var_dump($response);
      echo "</pre>";
    }

    public function processPaymentRequest($packet_id){
      //Sprawdzanie wybranego pakietu i dostosowywanie zmiennych
        switch($packet_id){
          case 1:
            $paymentAmount = 500;
            $packetName = "Basic fine";
            break;
          case 2:
            $paymentAmount = 1500;
            $packetName = "Extended fine";
            break;
          case 3:
            $paymentAmount = 5000;
            $packetName = "Enlightement fine";
            break;
          default:
            return view('pages.confession');
            break;
        }

        //Uwierzytelnianie OAuth  http://developers.payu.com/pl/restapi.html#references_api_signature
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://secure.snd.payu.com/pl/standard/user/oauth/authorize");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);

        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, "grant_type=client_credentials&client_id=332235&client_secret=c90df3f3f178bc0ea7421317376daa98");

        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
          "Content-Type: application/x-www-form-urlencoded"
        ));

        $OAuthToken = curl_exec($ch);
        curl_close($ch);
        $OAuthToken = json_decode($OAuthToken, true);
        extract($OAuthToken);
        extract($_POST);

        //Tworzenie i realizacja zamówienia http://developers.payu.com/pl/recurring.html#payu_express_retrieve
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://secure.snd.payu.com/api/v2_1/orders/");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);

        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, "{
          \"notifyUrl\": \"http://payu.test/notify\",
          \"customerIp\": \"127.0.0.1\",
          \"merchantPosId\": \"332235\",
          \"description\": \"Testowy sklep\",
          \"currencyCode\": \"PLN\",
          \"totalAmount\": \"$paymentAmount\",
          \"products\": [
            {
              \"name\": \"$packetName\",
              \"unitPrice\": \"$paymentAmount\",
              \"quantity\": \"1\"
            }
          ],
          \"buyer\": {
            \"email\": \"john.doe@example.com\",
            \"firstName\": \"Jan\",
            \"lastName\": \"Kowalski\",
            \"language\": \"pl\"
          },
          \"payMethods\": {
            \"payMethod\":{
              \"value\": \"$value\",
              \"type\": \"$type\"
            }
          }
        }");
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
          "Content-Type: application/json",
          "Authorization: $token_type $access_token"
        ));

        $response = curl_exec($ch);
        curl_close($ch);
        $response = json_decode($response, true);

        echo "<pre>";
        var_dump($response);
        echo "</pre>";
        echo "\n";
        echo $response['orderId']."\n";
        echo $response['payMethods']['payMethod']['value']."\n";
        echo $response['payMethods']['payMethod']['type']."\n";
        echo "\n\n";
        sleep(15);
        $this->scheduleThisMothafucka($paymentAmount, $response['orderId'], $response['payMethods']['payMethod']['value'], $response['payMethods']['payMethod']['type'], $packetName, $token_type, $access_token);


    }



    public function displayPaymentWindow($id = null){
        if(!isset($id))
          return view('pages.confession');

        switch($id){
          case 1:
            $paymentAmount = 500;
            break;
          case 2:
            $paymentAmount = 1500;
            break;
          case 3:
            $paymentAmount = 5000;
            break;
          default:
            return view('pages.confession');
            break;
        }

        //Data needed to generate SIG, as described in http://developers.payu.com/en/recurring.html#payu_express_widget
        $widgetScriptData = [
          'currency_code' => 'PLN',
          'customer_language' => 'pl',
          'merchant_pos_id' => 332235,
          'recurring_payment' => 'true',
          'shop_name' => 'Testowanie',
          'store_card' => 'true',
          'total_amount' => $paymentAmount
        ];

        //Tworzenie i dodawanie klucza SIG
        ksort($widgetScriptData);
        $secondaryKey = '24179aee3323e3f8464e0678a913e341';
        $sigValue = hash('sha256', implode($widgetScriptData).$secondaryKey);
        $widgetScriptData['sig_value'] = $sigValue;
        $widgetScriptData['packet_id'] = $id;

        return view('pages.confession', $widgetScriptData);
    }
}
