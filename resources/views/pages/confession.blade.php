@extends('layouts.app')
@section('site-title')
@section('body-attr', 'id=confession-body')
@section('site-content')
  <div class="row">
    <div class="col-12 col-md-2 offset-md-5">
      <img src="/img/cross.svg" alt="kszysz kufa" class="mb-3 ">
    </div>
  </div>
  <div class="row text-center">

    <h1 class="w-100">
      @if(!isset($total_amount))
        Spowiedź online
      @elseif($total_amount / 100 == 5)
        Podstawowy pakiet rozgrzeszający
      @elseif($total_amount / 100 == 15)
        Rozszerzony pakiet rozgrzeszający
      @elseif($total_amount / 100 == 50)
        Pakiet oczyszczenia
      @endif
    </h1>
    @if(!isset($total_amount))
    <p>Już teraz możesz dokonać spowiedzi online, bez wychodzenia z domu! Dzięki nowoczesnej technologii i ultra szybkim łączeniu się z niebem nie potrzebujesz nawet mówić o swoich grzechach, ponieważ wyczytujemy je z myśli. Musisz jedynie odbyć pokutę - cykliczne datki na kościół. Wiemy, że nie każdy dysponuje takimi samymi środkami, toteż dajemy Wam wybór pakietu rozgrzeszenia poniżej.</p>
    <p class="font-weight-bold text-danger font-italic"><b>Uwaga! Anulowanie subskrypcji szybciej niż po upływie roku od jej rozpoczęcia skutkować będzie anulowaniem rozgrzeszenia i wpisania wszystkich grzechów (ale nie pieniędzy) z powrotem na konto spowiednika!</b></p>
    @else
    <p>Wybrany pakiet rozgrzeszający zostanie aktywowany na okres nieokreślony. W każdej chwili możliwa jest jednak dezaktywacja, jednak należy pamiętać, że uczynienie tego przed upływem roku skutkuje unieważnieniem rozgrzeszenia.</p>
    @endif
  </div>
  <div class="row">
    @if(!isset($total_amount))
    <div class="col-12 col-md-4">
      <div class="card text-center">
        <div class="card-header ">
          <h5>5 zł / miesiąc</h5>
        </div>
        <div class="card-body">
          <p>Podstawow pakiet rozgrzeszający. Dobrze zmywa grzechy lekkie.</p>
        </div>
        <div class="card-footer">
          <a id="low-payment" name="stake" class="btn btn-primary confession-payment-a" href="/confession/fine/1">Zapłać teraz!</a>
        </div>
      </div>
    </div>
    <div class="col-12 col-md-4">
      <div class="card text-center">
        <div class="card-header">
          <h5>15 zł / miesiąc</h5>
        </div>
        <div class="card-body">
          <p>Pakiet rozgrzeszający rozszerzony. Zmywa grzechy lekkie oraz grzechy ciężkie.</p>
        </div>
        <div class="card-footer">
          <a id="med-payment" name="stake" class="btn btn-primary confession-payment-a" href="/confession/fine/2">Zapłać teraz!</a>
        </div>
      </div>
    </div>
    <div class="col-12 col-md-4">
      <div class="card text-center">
        <div class="card-header">
          <h5>50 zł / miesiąc</h5>
        </div>
        <div class="card-body">
          <p>Pakiet oczyszczenia. Jedyny pakiet, który może zmyć grzechy przeciwko kościołowi i jego sługom.</p>
        </div>
        <div class="card-footer">
          <a id="high-payment" name="stake" class="btn btn-primary confession-payment-a" href="/confession/fine/3">Zapłać teraz!</a>
        </div>
      </div>
    </div>
    @else
    <div class="col-12 col-md-4 offset-md-4">
      <div class="card">
        <div class="card-header text-center">
          <h3>Cena: {{$total_amount / 100}} zł / miesiąc</h3>
        </div>
        <div class="card-body text-center p-5">

          <form class="" action="http://payu.test/process-payment/{{$packet_id}}" method="post">
            {{ csrf_field() }}
            <button type="button" name="button" id="payment-button" class="btn w-100 btn-primary confession-payment-a font-weight-bold pt-3"><h5>Zapłać</h5></button>
          </form>
          <script
              src="https://secure.snd.payu.com/front/widget/js/payu-bootstrap.js"
              pay-button="#payment-button"
              merchant-pos-id="{{$merchant_pos_id}}"
              shop-name="{{$shop_name}}"
              total-amount="{{$total_amount}}"
              currency-code="{{$currency_code}}"
              customer-language="{{$customer_language}}"
              store-card="{{$store_card}}"
              recurring-payment="{{$recurring_payment}}"
              sig="{{$sig_value}}">
          </script>

        </div>
        <div class="card-footer">
          <p class="font-italic small text-muted text-center">Płatność możliwa jest tylko poprzez kartę</p>
        </div>
      </div>
    </div>
    <div class="col-12 text-center mt-2">
      <a href="/confession">Wróć do wyboru pakietu</a>
    </div>
    @endif
  </div>
@stop
