@extends('adminlte::page')
@section('title', 'Profile Complete')
@section('content')
<x-page-header title="Profile Complete" description="Your photographer profile is ready and your workspace setup is complete."
    :breadcrumbs="[['label' => 'Profile'], ['label' => 'Complete']]" :action-url="route('dashboard')"
    action-label="Go to dashboard" action-icon="fas fa-th-large" />
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <h1>Your profile has been completed</h1>
        </div>
    </div>
</div>
</form>
@stop
