@extends('layouts.public')
@section('title', 'Delivery and shipping policy')
@section('description', 'How GALERIVE subscriptions and digital photography services are delivered.')
@section('content')
    <div class="eyebrow">Delivery &amp; shipping policy</div><h1 class="page-title">Digital delivery, with no physical shipping.</h1>
    <p class="lead">GALERIVE sells online software subscriptions and digital services. We do not sell or ship physical products.</p>
    <div class="updated">Last updated: October 1, 2026</div>
    <div class="content">
        <section><h2>Subscription delivery</h2><p>Account access begins after registration and verification. Paid plan features are normally activated immediately after successful payment confirmation from our payment provider. You will see the active subscription in your GALERIVE account.</p></section>
        <section><h2>Gallery and file delivery</h2><p>Photographers upload and publish their own photographs. Published galleries are delivered online through a private or public gallery link. Where enabled, client download files and email delivery may require processing time based on gallery size, internet speed, and service load.</p></section>
        <section><h2>Delays and failed delivery</h2><p>If a successful payment is not reflected in your account within 24 hours, or a digital delivery remains unavailable, contact us at <a href="mailto:{{ config('business.email') }}">{{ config('business.email') }}</a> or <a href="tel:{{ preg_replace('/[^+0-9]/', '', config('business.phone')) }}">{{ config('business.phone') }}</a>. Include the account email and transaction reference; never send full card details.</p></section>
        <section><h2>Service area</h2><p>Because GALERIVE is delivered online, there are no shipping charges or delivery zones. Customers need a supported device and internet connection to use the service.</p></section>
    </div>
@endsection
