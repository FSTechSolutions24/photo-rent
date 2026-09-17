@extends('adminlte::page')
@section('title', 'View Plans')
@section('content')

  <x-page-header title="Subscription Plans" description="Manage pricing, storage allowances, and features available to photographers."
    :breadcrumbs="[['label' => 'Administration'], ['label' => 'Plans']]" :action-url="route('superadmin.plans.create')"
    action-label="Create plan" action-icon="fas fa-plus" />

  <div class="ibox-content">
    <table class="table">
      <thead>
        <tr>
          <th scope="col">Actions</th>
          <th scope="col">#</th>
          <th scope="col">Name</th>          
          <th scope="col">Price</th>
          <th scope="col">Storage (GB)</th>
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
          scrollX: true,            // ➜ enables horizontal scroll
          autoWidth: false,         // ➜ prevents auto-expanding beyond parent
          ajax: "{{ route('superadmin.plans.data') }}",
          columns: [
            {data:  'actions'},
            {data:  'id'},
            { data: 'name'},
            { data: 'price'},
            { data: 'storage_gb'},
          ]
        })
      })
    </script>
@stop

