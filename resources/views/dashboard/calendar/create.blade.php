@extends('adminlte::page')
@section('title', 'Create Appointment')

@section('content')

<x-page-header title="Create Appointment" description="Add a meeting or booking to your photography schedule."
    :breadcrumbs="[['label' => 'Scheduling', 'url' => route('photographer.appointments.index')], ['label' => 'Create Appointment']]"
    :action-url="route('photographer.appointments.index')" action-label="Open calendar" action-icon="far fa-calendar-alt" />

<form method="POST" action="{{ route('photographer.appointments.store') }}" enctype="multipart/form-data">

    <div class="ibox-content">
        @include('dashboard.calendar._form')
        <button class="btn btn-primary mt-3">Submit</button>
    </div>

</form>

@stop
