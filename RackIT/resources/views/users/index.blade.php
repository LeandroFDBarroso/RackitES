
@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Gerir Utilizadores</h2>
            </div>
            <a href="{{ route('users.create') }}" type="button" class="mt-4 mb-4 btn btn-primary">Criar Utilizador</a>

        </div>
    </div>
    @if($message=Session::get('sucess'))
        <div class="alert alert-sucess">
            <p>{{message}}</p>
        </div>
    @endif
    <table id="users" class="table table-bordered">
        <tr>
            <th>Nº</th>
            <th>Nome</th>
            <th>Email</th>
            <th>Papel</th>
            <th></th>
            </tr>
        @foreach ($data as $key=>$user)
        <tr>
            <td>{{++$i}}</td>
            <td>{{$user->name}}</td>
            <td>{{$user->email}}</td>
            {{-- <td>
                
            @if(!@empty($user->getRoleNames()))
                    @foreach ($user->getRoleNames() as $r)
                    <label class="badge badge-sucess">{{$r}}</label>
                    @endforeach 
                @endif
            </td>  --}}
            
        
            <td>
                @if(!empty($user->getRoleNames()))
                @foreach ($user->getRoleNames() as $r)
                    @if ($r == "User") 
                    <label class="badge badge-primary">{{$r}}​​​​​​​</label>
                    @elseif ($r == "Admin") 
                    <label class="badge badge-danger">{{$r}}​​​​​​​</label>
                    @elseif ($r == "Premium") 
                    <label class="badge badge-success">{{$r}}​​​​​​​</label>
                    @else
                    <label class="badge badge-light">{{$r}}​​​​​​​</label> 
                    @endif
                @endforeach
                @endif
            </td>
        
            
            <td>
                <a class='btn btn-info' href="{{route('users.show',$user->id)}}">Ver</a>
                <a class='btn btn-primary' href="{{route('users.edit',$user->id)}}">Editar</a>
                {!!Form::open(['method'=>'DELETE','route'=>['users.destroy',$user->id],'style'=>'display:inline'])!!}
                {!!Form::submit('Remover',['class'=>'btn btn-danger'])!!}
            </td>
        </tr>
        @endforeach
    </table>
    {!! $data->render()!!}
        {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/js/bootstrap.min.js"></script>
            <script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
        <script type="text/javascript">
        $(document).ready(function () {
        $('#users').DataTable();
        $('.dataTables_length').addClass('bs-select');
        });

    </script> --}}

@endsection