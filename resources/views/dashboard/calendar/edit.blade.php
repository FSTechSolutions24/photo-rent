@extends('adminlte::page')
@section('title', 'Edit Appointment')

@section('content')

<x-page-header title="Edit Appointment" description="Update the date, time, type, or details for this appointment."
    :breadcrumbs="[['label' => 'Scheduling', 'url' => route('photographer.appointments.index')], ['label' => 'Edit Appointment']]"
    :action-url="route('photographer.appointments.index')" action-label="Open calendar" action-icon="far fa-calendar-alt" />

<form method="POST" action="{{ route('photographer.appointments.update', $appointment->id) }}" enctype="multipart/form-data">

    @method('PUT')
    <div class="ibox-content">
        @include('dashboard.calendar._form')
        <button class="btn btn-primary mt-3">Update</button>
    </div>

</form>

@stop
