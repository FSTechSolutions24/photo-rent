@extends('layouts.public')
@section('title', 'About us')
@section('description', 'Learn about VUE, the photography business and gallery delivery platform.')
@section('content')
    <div class="eyebrow">About us</div>
    <h1 class="page-title">Built for photographers who want their work to stay in focus.</h1>
    <p class="lead">VUE is an online photography business platform based in {{ config('business.address') }}. We help photographers manage clients, plan sessions, publish portfolios, and securely deliver photo galleries from one workspace.</p>
    <div class="content">
        <section><h2>What we provide</h2><p>Our subscription service includes client and session management, cloud gallery publishing, password-protected delivery, portfolio tools, storage, and plan-dependent features such as face-recognition filters.</p></section>
        <section><h2>Why VUE exists</h2><p>Photographers should spend more time creating and less time switching between disconnected administration and delivery tools. VUE keeps the practical work together while presenting every gallery in a polished, client-friendly experience.</p></section>
        <section><h2>Who we serve</h2><p>VUE serves independent photographers and photography studios in Egypt. Customers can review current plans and exact prices on our <a href="{{ route('home') }}#pricing">pricing section</a> before creating an account.</p></section>
    </div>
@endsection
