<?php

namespace App\Http\Controllers;

use App\User;
use App\Http\Controllers\Controller;

class PaymentController extends Controller
{
    public function processPaymentRequest(){
        
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
        ksort($widgetScriptData);
        $secondaryKey = '24179aee3323e3f8464e0678a913e341';
        $sigValue = hash('sha256', implode($widgetScriptData).$secondaryKey);
        $widgetScriptData['sig_value'] = $sigValue;
        return view('pages.confession', $widgetScriptData);
    }
}
