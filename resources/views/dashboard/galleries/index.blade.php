@extends('adminlte::page')
@section('title', 'Galleries')

@section('content')

    <x-page-header title="Galleries" description="Create, organize, and deliver polished photo collections to your clients."
        :breadcrumbs="[['label' => 'Galleries']]" :action-url="route('dashboard.galleries.create')"
        action-label="Create gallery" action-icon="fas fa-plus" />
    <div class="ibox-content">
        <table class="table">
            <thead>
                <tr>
                    <th>Actions</th>
                    <th>#</th>
                    <th>Gallery Name</th>                
                    <th>Session</th>
                    <th>Is public</th>                
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
            {data:  'name'},
            {data:  'session_name'},
            {data:  'is_public'},
          ]
        })
      })
    </script>
@stop
