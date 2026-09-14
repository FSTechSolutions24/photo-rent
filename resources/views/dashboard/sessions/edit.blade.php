@extends('adminlte::page')
@section('title', 'Update Session')

@section('content')
    <x-page-header title="Update Session" description="Keep this session’s schedule, pricing, and client information up to date."
        :breadcrumbs="[['label' => 'Sessions', 'url' => route('dashboard.sessions.index')], ['label' => 'Update Session']]"
        :action-url="route('dashboard.sessions.index')" action-label="View sessions" action-icon="fas fa-camera" />
    <form method="POST" action="{{ route('dashboard.sessions.update', $session->id) }}" enctype="multipart/form-data">
        @method('PUT')
        <div class="ibox-content">
            @include('dashboard.sessions._form')
            <button class="btn btn-primary mt-3">Update</button>
        </div>
    </form>
@stop
