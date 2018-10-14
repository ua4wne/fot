@extends('layouts.main')

@section('tile_widget')

@endsection

@section('content')
<!-- START BREADCRUMB -->
<ul class="breadcrumb">
    <li><a href="{{ route('main') }}">Рабочий стол</a></li>
    <li><a href="{{ route('persons') }}">Физические лица</a></li>
    <li class="active">{{ $title }}</li>
</ul>
<!-- END BREADCRUMB -->
<!-- page content -->

<div class="row">
    <h2 class="text-center">Новое физическое лицо</h2>
    {!! Form::open(['url' => route('personAdd'),'class'=>'form-horizontal','method'=>'POST']) !!}

    <div class="form-group">
        {!! Form::label('fio','ФИО:',['class' => 'col-xs-2 control-label'])   !!}
        <div class="col-xs-8">
            {!! Form::text('fio',old('fio'),['class' => 'form-control','placeholder'=>'Введите ФИО'])!!}
            {!! $errors->first('fio', '<p class="text-danger">:message</p>') !!}
        </div>
    </div>

    <div class="form-group">
        {!! Form::label('inn','ИНН:',['class' => 'col-xs-2 control-label'])   !!}
        <div class="col-xs-8">
            {!! Form::text('inn',old('inn'),['class' => 'form-control','placeholder'=>'Введите ИНН'])!!}
            {!! $errors->first('inn', '<p class="text-danger">:message</p>') !!}
        </div>
    </div>

    <div class="form-group">
        {!! Form::label('snils','СНИЛС:',['class' => 'col-xs-2 control-label'])   !!}
        <div class="col-xs-8">
            {!! Form::text('snils',old('snils'),['class' => 'form-control','placeholder'=>'Введите СНИЛС'])!!}
            {!! $errors->first('snils', '<p class="text-danger">:message</p>') !!}
        </div>
    </div>

    <div class="form-group">
        {!! Form::label('gender', 'Выберите пол:',['class'=>'col-xs-2 control-label']) !!}
        <div class="col-xs-8">
            {!! Form::select('gender', array('male' => 'Мужской', 'female' => 'Женский'), 'male',['class' => 'form-control']); !!}
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
