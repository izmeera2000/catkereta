@extends('layouts.app')

@section('title', 'Dashboard')
@section('page_title', 'Dashboard')
@section('page_description', 'Welcome to your dashboard.')

@section('breadcrumbs')
  <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
@endsection

@section('content')
  <div class="nk-block">
    <div class="card card-bordered">
      <div class="card-inner">
        <h5 class="card-title">Welcome</h5>
        <p class="card-text">Your page content goes here.</p>
      </div>
    </div>
  </div>
@endsection
