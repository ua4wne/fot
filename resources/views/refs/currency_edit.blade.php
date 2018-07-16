@extends('layouts.main')

@section('tile_widget')

@endsection

@section('content')
    <!-- START BREADCRUMB -->
    <ul class="breadcrumb">
        <li><a href="{{ route('main') }}">Рабочий стол</a></li>
        <li><a href="{{ route('currency') }}">Валюты</a></li>
        <li class="active">Редактирование записи</li>
    </ul>
    <!-- END BREADCRUMB -->
    <!-- page content -->

    <div class="row">
        <h1 class="text-center">{{ $data['name'] }}</h1>
        {!! Form::open(['url' => route('currencyEdit',['currency'=>$data['id']]),'class'=>'form-horizontal','method'=>'POST']) !!}

        {!! Form::hidden('name',$data['name'],['class' => 'form-control','placeholder'=>'Введите название'])!!}

        <div class="form-group">
            {!! Form::label('dcode','Цифр. код:',['class' => 'col-xs-2 control-label'])   !!}
            <div class="col-xs-8">
                {!! Form::text('dcode',$data['dcode'],['class' => 'form-control','placeholder'=>'Введите цифровой код', 'disabled'=>'disabled'])!!}
                {!! $errors->first('dcode', '<p class="text-danger">:message</p>') !!}
            </div>
        </div>

        <div class="form-group">
            {!! Form::label('scode','Симв. код:',['class' => 'col-xs-2 control-label'])   !!}
            <div class="col-xs-8">
                {!! Form::text('scode',$data['scode'],['class' => 'form-control','placeholder'=>'Введите символьный код', 'disabled'=>'disabled'])!!}
                {!! $errors->first('scode', '<p class="text-danger">:message</p>') !!}
            </div>
        </div>

        <div class="form-group">
            {!! Form::label('cource','Курс:',['class' => 'col-xs-2 control-label'])   !!}
            <div class="col-xs-8">
                {!! Form::text('cource',$data['cource'],['class' => 'form-control','placeholder'=>'Введите текущий курс'])!!}
                {!! $errors->first('cource', '<p class="text-danger">:message</p>') !!}
            </div>
        </div>

        <div class="form-group">
            {!! Form::label('multi','Кратность:',['class' => 'col-xs-2 control-label'])   !!}
            <div class="col-xs-8">
                {!! Form::text('multi',$data['multi'],['class' => 'form-control','placeholder'=>'Введите множитель'])!!}
                {!! $errors->first('multi', '<p class="text-danger">:message</p>') !!}
            </div>
        </div>

        <div class="form-group">
            <div class="col-xs-offset-2 col-xs-10">
                {!! Form::button('Обновить', ['class' => 'btn btn-primary','type'=>'submit']) !!}
            </div>
        </div>

        {!! Form::close() !!}

    </div>
    </div>
@endsection
