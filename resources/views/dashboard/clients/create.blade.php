@extends('adminlte::page')

@section('title', 'Create Client')

@section('content')

<x-page-header title="Create Client"
    description="Add a new client and keep their contact details ready for future sessions and galleries."
    :breadcrumbs="[['label' => 'Clients', 'url' => route('dashboard.clients.index')], ['label' => 'Create Client']]"
    :action-url="route('dashboard.clients.index')" action-label="View clients" action-icon="fas fa-users" />

<form method="POST" action="{{ route('dashboard.clients.store') }}" enctype="multipart/form-data">

    <div class="ibox-content">
        @include('dashboard.clients._form')
        <button class="btn btn-primary mt-3">Submit</button>
    </div>

</form>

@stop
