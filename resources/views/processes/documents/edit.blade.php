@extends('layouts.layout')

@section('title')
  {{__('Edit Document')}}
@endsection

@section('mainbar')
@include('layouts.sidebar', ['sidebar'=> Menu::get('sidebar_admin')])
@endsection

@section('content')
@endsection

@section('js')
@endsection