@extends('layouts.main')

@section('tile_widget')

@endsection

@section('content')
    <!-- START BREADCRUMB -->
    <ul class="breadcrumb">
        <li><a href="{{ route('main') }}">Рабочий стол</a></li>
        <li><a href="{{ route('currency') }}">Валюты</a></li>
        <li class="active">Новая запись</li>
    </ul>
    <!-- END BREADCRUMB -->
    <!-- page content -->

    <div class="row">
        <h2 class="text-center">Новая валюта</h2>
        {!! Form::open(['url' => route('currencyAdd'),'class'=>'form-horizontal','method'=>'POST']) !!}

        <div class="form-group">
            {!! Form::label('name','Название валюты:',['class' => 'col-xs-2 control-label'])   !!}
            <div class="col-xs-8">
                {!! Form::text('name',old('name'),['class' => 'form-control','placeholder'=>'Введите название'])!!}
                {!! $errors->first('name', '<p class="text-danger">:message</p>') !!}
            </div>
        </div>

        <div class="form-group">
            {!! Form::label('dcode','Цифр. код:',['class' => 'col-xs-2 control-label'])   !!}
            <div class="col-xs-8">
                {!! Form::text('dcode',old('dcode'),['class' => 'form-control','placeholder'=>'Введите цифровой код'])!!}
                {!! $errors->first('dcode', '<p class="text-danger">:message</p>') !!}
            </div>
        </div>

        <div class="form-group">
            {!! Form::label('scode','Симв. код:',['class' => 'col-xs-2 control-label'])   !!}
            <div class="col-xs-8">
                {!! Form::text('scode',old('scode'),['class' => 'form-control','placeholder'=>'Введите символьный код'])!!}
                {!! $errors->first('scode', '<p class="text-danger">:message</p>') !!}
            </div>
        </div>

        <div class="form-group">
            {!! Form::label('cource','Курс:',['class' => 'col-xs-2 control-label'])   !!}
            <div class="col-xs-8">
                {!! Form::text('cource',old('cource'),['class' => 'form-control','placeholder'=>'Введите текущий курс'])!!}
                {!! $errors->first('cource', '<p class="text-danger">:message</p>') !!}
            </div>
        </div>

        <div class="form-group">
            {!! Form::label('multi','Кратность:',['class' => 'col-xs-2 control-label'])   !!}
            <div class="col-xs-8">
                {!! Form::text('multi',old('multi'),['class' => 'form-control','placeholder'=>'Введите множитель'])!!}
                {!! $errors->first('multi', '<p class="text-danger">:message</p>') !!}
            </div>
        </div>

        <div class="form-group">
            <div class="col-xs-offset-2 col-xs-10">
                {!! Form::button('Сохранить', ['class' => 'btn btn-primary','type'=>'submit']) !!}
            </div>
        </div>

        {!! Form::close() !!}

    </div>
    </div>
@endsection
