@extends('adminlte::page')
@section('title', 'View Clients')
@section('content_header')
    <h1>View Clients</h1>
@stop
@section('content')

<div class="ibox-content">
  <table class="table table-striped">
    <thead>
      <tr>
        <th scope="col">Actions</th>
        <th scope="col">#</th>
        <th scope="col">Name</th>
        <th scope="col">Phone</th>
        <th scope="col">Phone2</th>
        <th scope="col">Email</th>
      </tr>
    </thead>
    <tbody>        
    </tbody>
  </table>
</div>

@stop

@section('js')
    <script> 
      $(document).ready(function(){            
        window.view_reports =   $('.table').DataTable({
          processing: true,
          serverSide: true,
          ajax: "{{ route('dashboard.clients.data') }}",
          columns: [
            {data:  'actions'},
            {data:  'id'},
            { data: 'name'},
            { data: 'phone'},
            { data: 'phone2'},
            { data: 'email'},
          ]
        })
      })
    </script>
@stop

