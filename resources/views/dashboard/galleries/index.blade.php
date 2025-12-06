@extends('adminlte::page')
@section('title', 'Galleries')

@section('content')

    <h4 class="page_header">Gallery List</h4>
    <div class="ibox-content">
        <table class="table">
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
          scrollX: true,            // ➜ enables horizontal scroll
          autoWidth: false,         // ➜ prevents auto-expanding beyond parent
          ajax: "{{ route('dashboard.galleries.data') }}",
          columns: [
            {data:  'actions'},
            {data:  'id'},
            {data:  'client_name'},
            {data:  'name'},
          ]
        })
      })
    </script>
@stop
