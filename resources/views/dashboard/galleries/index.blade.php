@extends('adminlte::page')
@section('title', 'Galleries')

@section('content_header')
    <h1>Galleries</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Actions</th>
                        <th>#</th>
                        <th>Client</th>
                        <th>Gallery Name</th>                
                    </tr>
                </thead>
                <tbody>            
                </tbody>
            </table>
        </div>
    </div>
@stop

@section('css')
    {{-- Add here extra stylesheets --}}
    {{-- <link rel="stylesheet" href="/css/admin_custom.css"> --}}
@stop

@section('js')
    <script> 
      $(document).ready(function(){            
        window.view_reports =  $('.table').DataTable({
          processing: true,
          serverSide: true,
          ajax: "{{ route('dashboard.galleries.data') }}",
          columns: [
            {data:  'actions'},
            {data:  'id'},
            {data:  'client_id'},
            { data: 'name'},
          ]
        })
      })
    </script>
@stop
