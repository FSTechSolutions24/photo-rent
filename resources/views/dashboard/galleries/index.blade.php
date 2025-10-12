@extends('adminlte::page')
@section('title', 'Dashboard')

@section('content_header')
    <h1>Dashboard</h1>
@stop

@section('content')
    <h2 class="text-xl font-semibold mb-3">Galleries for {{ $client->name }}</h2>

    <table class="table table-striped">
        <thead>
            <tr>
                <th>Name</th>
                <th>Number of items</th>
                <th>Created at</th>                
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Gallery1</td>
                <td>20</td>
                <td>12/10/2025</td>
            </tr>
            <tr>
                <td>Gallery1</td>
                <td>20</td>
                <td>12/10/2025</td>
            </tr>
            <tr>
                <td>Gallery1</td>
                <td>20</td>
                <td>12/10/2025</td>
            </tr>
        </tbody>
    </table>
@stop

@section('css')
    {{-- Add here extra stylesheets --}}
    {{-- <link rel="stylesheet" href="/css/admin_custom.css"> --}}
@stop

@section('js')
    <script> console.log("Hi, I'm using the Laravel-AdminLTE package!"); </script>
@stop
