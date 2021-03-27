@extends('layouts.boolbnb')

@section('title')
  User Dashboard
@endsection

@section('content')

          <div id="dashboard" class="container">

              <my-dashboard class="row" :user="{{$user}}"> </my-dashboard>

          </div>

@endsection
