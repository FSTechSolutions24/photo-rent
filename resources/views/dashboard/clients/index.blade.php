@extends('adminlte::page')
@section('title', 'View Clients')
@section('content')

  <x-page-header title="Clients" description="Manage client details and keep every photography relationship organized."
    :breadcrumbs="[['label' => 'Clients']]" :action-url="route('dashboard.clients.create')"
    action-label="Add client" action-icon="fas fa-user-plus" />

  <div class="ibox-content">
    <table class="table">
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
        window.view_reports = $('.table').DataTable({
          processing: true,
          serverSide: true,
          scrollX: true,            // ➜ enables horizontal scroll
          autoWidth: false,         // ➜ prevents auto-expanding beyond parent
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

