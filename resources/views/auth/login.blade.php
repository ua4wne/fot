@extends('layouts.app')

@section('content')
    {!! Form::open(['url' => route('login'),'class'=>'form-horizontal','method'=>'POST']) !!}

        <h2 class="text-center">Авторизация</h2>
        <div class="form-group">
            <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-user fa-fw"></i></span>
                {!! Form::text('login',old('login'),['class' => 'form-control','placeholder'=>'Введите логин','required'=>''])!!}
            </div>
            {!! $errors->first('login', '<p class="text-danger">:message</p>') !!}
        </div>

        <div class="form-group">
            <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-key fa-fw"></i></span>
                {{ Form::password('password', array('id' => 'password', "class" => "form-control",'placeholder'=>'Введите пароль','required'=>'')) }}
                {!! $errors->first('password', '<p class="text-danger">:message</p>') !!}
            </div>
        </div>

    <div class="form-group">
        <div >
            {!! Form::button('Войти', ['class' => 'btn btn-primary','type'=>'submit']) !!}
            <a href="{{ route('firms') }}">Забыли пароль?</a>
        </div>
    </div>

    {!! Form::close() !!}

@endsection