@extends('adminlte::page')

@section('content')

<h4 class="page_header">Edit Appointment</h4>

<form method="POST" action="{{ route('photographer.appointments.update', $appointment->id) }}" enctype="multipart/form-data">

    @method('PUT')
    <div class="ibox-content">
        @include('dashboard.calendar._form')
        <button class="btn btn-primary mt-3">Update</button>
    </div>

</form>

@stop