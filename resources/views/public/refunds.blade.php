@extends('layouts.public')
@section('title', 'Refund and cancellation policy')
@section('description', 'VUE subscription cancellation and refund terms.')
@section('content')
    <div class="eyebrow">Refund &amp; cancellation policy</div><h1 class="page-title">Clear subscription terms.</h1>
    <p class="lead">This policy applies to paid VUE software subscriptions. Your plan price and billing period are shown before payment and in the website pricing section.</p>
    <div class="updated">Last updated: October 1, 2026</div>
    <div class="content">
        <section><h2>Cancelling a subscription</h2><p>You may request cancellation at any time by contacting <a href="mailto:{{ config('business.email') }}">{{ config('business.email') }}</a> from the email address registered to your account. Cancellation stops future renewal charges. Unless a refund is approved, access continues until the end of the paid billing period.</p></section>
        <section><h2>Refund requests</h2><p>Refund requests must be submitted within 14 calendar days of the payment. Refunds may be approved for duplicate or incorrect charges, a paid subscription that was not activated, or a material technical failure that prevented use of the paid service and could not be resolved.</p><p>Change-of-mind requests and partially used billing periods are not normally refundable, except where required by applicable law.</p></section>
        <section><h2>How to request a refund</h2><p>Email <a href="mailto:{{ config('business.email') }}">{{ config('business.email') }}</a> with your account email, transaction reference, payment date, and reason for the request. Do not send your full card number or card security code.</p></section>
        <section><h2>Review and payment timing</h2><p>We aim to review complete requests within 5 business days. Approved refunds are returned through the original payment method. Your bank or payment provider may take an additional 5 to 14 business days to display the credit.</p></section>
        <section><h2>Contact</h2><p>For cancellation or payment help, contact us at <a href="mailto:{{ config('business.email') }}">{{ config('business.email') }}</a>, call <a href="tel:{{ preg_replace('/[^+0-9]/', '', config('business.phone')) }}">{{ config('business.phone') }}</a>, or write to {{ config('business.address') }}.</p></section>
    </div>
@endsection
