@extends('layouts.public')
@section('title', 'Privacy policy')
@section('description', 'How VUE collects, uses, stores, and protects personal information.')
@section('content')
    <div class="eyebrow">Privacy policy</div><h1 class="page-title">Your privacy matters.</h1>
    <p class="lead">This policy explains how {{ config('business.name') }} handles personal information when photographers and their clients use our website, accounts, portfolios, and galleries.</p>
    <div class="updated">Last updated: October 1, 2026</div>
    <div class="content">
        <section><h2>Information we collect</h2><p>We collect account and contact details such as names, email addresses, phone numbers, login credentials, studio information, and subscription records. We also process content uploaded to the service, including photographs, gallery information, client details, and session details.</p><p>When you use VUE, we may receive technical information such as IP address, browser and device information, access times, and activity needed to secure and operate the service.</p></section>
        <section><h2>Payments</h2><p>Payments are processed by our payment provider. VUE receives payment status, order references, amount, and limited transaction details needed to activate subscriptions and maintain records. We do not store complete card numbers or card security codes.</p></section>
        <section><h2>How we use information</h2><ul><li>Provide accounts, subscriptions, galleries, portfolios, downloads, and customer support.</li><li>Process transactions and send service communications.</li><li>Protect accounts, prevent abuse, troubleshoot issues, and improve reliability.</li><li>Comply with applicable legal, accounting, and regulatory requirements.</li></ul></section>
        <section><h2>Photographs and face features</h2><p>Photographers control the content they upload and are responsible for having the necessary permissions. If a photographer enables face-recognition features, image-derived face data is processed only to group and filter photos within that gallery. It is not sold or used for advertising.</p></section>
        <section><h2>Sharing and retention</h2><p>We share information only with service providers needed to run VUE, such as hosting, storage, email, messaging, and payment providers, or when required by law. We keep information only as long as needed for the service, legitimate business records, security, and legal obligations.</p></section>
        <section><h2>Your choices</h2><p>You may request access to, correction of, or deletion of your personal information, subject to legal retention requirements. Photographers can manage or remove their uploaded content through their accounts. Send privacy requests to <a href="mailto:{{ config('business.email') }}">{{ config('business.email') }}</a>.</p></section>
        <section><h2>Security and contact</h2><p>We use reasonable technical and organizational measures to protect information, but no online service can guarantee absolute security. Questions about this policy can be sent to <a href="mailto:{{ config('business.email') }}">{{ config('business.email') }}</a> or directed to {{ config('business.address') }}.</p></section>
    </div>
@endsection
