@extends('adminlte::page')
@section('title', 'Sessions')

@section('content')
  <x-page-header title="Sessions" description="Track photography sessions, client bookings, dates, and financial details."
    :breadcrumbs="[['label' => 'Sessions']]" :action-url="route('dashboard.sessions.create')"
    action-label="Create session" action-icon="fas fa-plus" />
  <div class="ibox-content">
    <table class="table">
      <thead>
        <tr>
          <th>Actions</th>
          <th>#</th>
          <th>Client</th>
          <th>Name</th>                
          <th>Phone</th>                
          <th>Date</th>                
          <th>Total Amount</th>                
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
        window.view_sessions =  $('.table').DataTable({
          processing: true,
          serverSide: true,
          scrollX: true,            // ➜ enables horizontal scroll
          autoWidth: false,         // ➜ prevents auto-expanding beyond parent
          ajax: "{{ route('dashboard.sessions.data') }}",
          columns: [
            {data:  'actions'},
            {data:  'id'},
            {data:  'client_name'},
            {data:  'name'},
            {data:  'phone'},
            {data:  'date'},
            {data:  'total_amount'},
          ]
        })
      })
    </script>
@stop
