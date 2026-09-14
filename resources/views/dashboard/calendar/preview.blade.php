@extends('adminlte::page')
@section('title', 'Calendar')
@section('content')
    <x-page-header title="Calendar" description="See upcoming appointments and manage your photography schedule in one place."
        :breadcrumbs="[['label' => 'Scheduling']]" :action-url="route('photographer.appointments.create')"
        action-label="Book appointment" action-icon="far fa-calendar-plus" />
    <div id="app">
        <div class="ibox-content calendar">
            <calendar gallery-id="3" current-folder-id="4"></calendar>                
        </div>
    </div>
@stop

@section('js')
    <script src="{{ mix('js/app.js') }}"></script>
@stop

