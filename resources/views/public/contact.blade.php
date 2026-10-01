@extends('layouts.public')
@section('title', 'Contact us')
@section('description', 'Contact VUE by email or phone, or find our business location.')
@section('content')
    <div class="eyebrow">Contact us</div>
    <h1 class="page-title">We are here to help.</h1>
    <p class="lead">For account, subscription, payment, gallery delivery, privacy, or refund questions, contact our team using the details below.</p>
    <div class="contact-grid">
        <div class="contact-card"><small>Email</small><a href="mailto:{{ config('business.email') }}">{{ config('business.email') }}</a></div>
        <div class="contact-card"><small>Phone</small><a dir="ltr" href="tel:{{ preg_replace('/[^+0-9]/', '', config('business.phone')) }}">{{ config('business.phone') }}</a></div>
        <div class="contact-card"><small>Address</small><address>{{ config('business.address') }}</address></div>
    </div>
@endsection
