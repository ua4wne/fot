@extends('layouts.main')

@section('tile_widget')

@endsection

@section('content')
    <!-- START BREADCRUMB -->
    <ul class="breadcrumb">
        <li><a href="{{ route('main') }}">Рабочий стол</a></li>
        <li><a href="{{ route('persons') }}">Физические лица</a></li>
        <li class="active">Редактирование записи</li>
    </ul>
    <!-- END BREADCRUMB -->
    <!-- page content -->

    <div class="row">
        <h2 class="text-center">{{ $data['fio'] }}</h2>
        {!! Form::open(['url' => route('personEdit',['id'=>$data['id']]),'class'=>'form-horizontal','method'=>'POST']) !!}

        <div class="form-group">
            {!! Form::label('fio','ФИО:',['class' => 'col-xs-2 control-label'])   !!}
            <div class="col-xs-8">
                {!! Form::text('fio',$data['fio'],['class' => 'form-control','placeholder'=>'Введите ФИО'])!!}
                {!! $errors->first('fio', '<p class="text-danger">:message</p>') !!}
            </div>
        </div>

        <div class="form-group">
            {!! Form::label('inn','ИНН:',['class' => 'col-xs-2 control-label'])   !!}
            <div class="col-xs-8">
                {!! Form::text('inn',$data['inn'],['class' => 'form-control','placeholder'=>'Введите ИНН'])!!}
                {!! $errors->first('inn', '<p class="text-danger">:message</p>') !!}
            </div>
        </div>

        <div class="form-group">
            {!! Form::label('snils','СНИЛС:',['class' => 'col-xs-2 control-label'])   !!}
            <div class="col-xs-8">
                {!! Form::text('snils',$data['snils'],['class' => 'form-control','placeholder'=>'Введите СНИЛС'])!!}
                {!! $errors->first('snils', '<p class="text-danger">:message</p>') !!}
            </div>
        </div>

        <div class="form-group">
            {!! Form::label('gender', 'Выберите пол:',['class'=>'col-xs-2 control-label']) !!}
            <div class="col-xs-8">
                {!! Form::select('gender', array('male' => 'Мужской', 'female' => 'Женский'), $data['gender'],['class' => 'form-control']); !!}
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
