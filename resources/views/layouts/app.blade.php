@extends('layouts.metadata')
@section('interface')

  @include('inc.navbar')
  <main class="container pt-0" role="main">
    @yield('site-content')
  </main>
  @include('inc.footer')

@stop
