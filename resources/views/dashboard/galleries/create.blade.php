@extends('adminlte::page')
@section('title', 'Create Gallery')


@section('content')
    <x-page-header title="Create Gallery" description="Build a new gallery and prepare a beautiful collection for your client."
        :breadcrumbs="[['label' => 'Galleries', 'url' => route('dashboard.galleries.index')], ['label' => 'Create Gallery']]"
        :action-url="route('dashboard.galleries.index')" action-label="View galleries" action-icon="fas fa-images" />
    <form method="POST" action="{{ route('dashboard.galleries.store') }}" enctype="multipart/form-data">
        <div class="ibox-content">
            @include('dashboard.galleries._form')
            <button class="btn btn-primary mt-3">Submit</button>
        </div>
    </form>
@stop
