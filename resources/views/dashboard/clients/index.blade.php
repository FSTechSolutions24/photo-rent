@extends('adminlte::page')

@section('content')
{{-- 
<div>
    <h1 class="text-2xl font-bold mb-4">My Clients</h1>


    <a href="{{ route('dashboard.clients.create') }}" class="btn btn-primary mb-3">+ Add Client</a>

    <ul>
        @foreach($clients as $client)
            <li class="mb-2">
                <a href="{{ route('dashboard.clients.show', $client) }}" class="text-blue-600">
                    {{ $client->name }}
                </a>
            </li>
        @endforeach
    </ul>
</div> --}}

<h4 class="page_header">View Clients</h4>
<div class="card">
  <div class="card-body">
    <table class="table table-striped">
      <thead>
        <tr>
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

