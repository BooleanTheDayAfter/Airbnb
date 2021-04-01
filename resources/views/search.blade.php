@extends('layouts.boolbnb')

@section('title')
  Advanced Search
@endsection

@section('content')

<div class="row">



          <div id="app">

                <my-main :searchedcity= "{{ $data }}" :services= "{{$services}}" :guests={{$guests}} :searchcoo={{$positionSearched}}></my-main>

          </div>



</div>















@endsection
