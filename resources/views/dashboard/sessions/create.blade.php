@extends('adminlte::page')
@section('title', 'Create Session')


@section('content')
    <x-page-header title="Create Session" description="Schedule a photography session and connect it with the right client."
        :breadcrumbs="[['label' => 'Sessions', 'url' => route('dashboard.sessions.index')], ['label' => 'Create Session']]"
        :action-url="route('dashboard.sessions.index')" action-label="View sessions" action-icon="fas fa-camera" />
    <form method="POST" action="{{ route('dashboard.sessions.store') }}" enctype="multipart/form-data">
        <div class="ibox-content">
            @include('dashboard.sessions._form')
            <button class="btn btn-primary mt-3">Submit</button>
        </div>
    </form>
@stop
