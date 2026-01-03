@extends('adminlte::page')

@section('content')

<h4 class="page_header">Create Appointment</h4>

<form method="POST" action="{{ route('photographer.appointments.store') }}" enctype="multipart/form-data">

    <div class="ibox-content">
        @include('dashboard.calendar._form')
        <button class="btn btn-primary mt-3">Submit</button>
    </div>

</form>

@stop