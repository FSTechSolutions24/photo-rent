@extends('adminlte::page')
@section('title', 'Edit Client')

@section('content')

<x-page-header title="Edit Client" description="Update this client’s contact information and account details."
    :breadcrumbs="[['label' => 'Clients', 'url' => route('dashboard.clients.index')], ['label' => 'Edit Client']]"
    :action-url="route('dashboard.clients.index')" action-label="View clients" action-icon="fas fa-users" />

<form method="POST" action="{{ route('dashboard.clients.update', $client->id) }}" enctype="multipart/form-data">

    @method('PUT')
    <div class="ibox-content">
        @include('dashboard.clients._form')
        <button class="btn btn-primary mt-3">Update</button>
    </div>
</form>

@stop
