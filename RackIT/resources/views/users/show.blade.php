@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>
                    Detalhes do Utilizador
                </h2>
            </div>
            <div class="pull-right">
                <a class="btn btn-primary" href="{{route('users.index')}}">Voltar à lista</a>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12 clo-sm-12 col-md-12">
            <div class="form-group">
                <b>Nome</b>
                {{$user->name}}
            </div>
        </div>
        <div class="col-xs-12 clo-sm-12 col-md-12">
            <div class="form-group">
                <b>E-mail</b>
                {{$user->email}}
            </div>
        </div>
    </div>
@endsection