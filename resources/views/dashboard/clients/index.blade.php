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



<table class="table">
  <thead>
    <tr>
      <th scope="col">#</th>
      <th scope="col">First</th>
      <th scope="col">Last</th>
      <th scope="col">Handle</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td >1</td>
      <td>Mark</td>
      <td>Otto</td>
      <td>@mdo</td>
    </tr>
    <tr>
      <th >2</th>
      <td>Jacob</td>
      <td>Thornton</td>
      <td>@fat</td>
    </tr>

  </tbody>
</table>

@stop

@section('js')
    <script> 
        $(document).ready(function(){            
            window.view_reports =   $('.table').DataTable({


            })
        })
    </script>
@stop

