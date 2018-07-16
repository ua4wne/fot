@extends('layouts.main')

@section('tile_widget')

@endsection

@section('content')
    <!-- START BREADCRUMB -->
    <ul class="breadcrumb">
        <li><a href="{{ route('main') }}">Рабочий стол</a></li>
        <li><a href="{{ route('organizations') }}">Организации</a></li>
        <li class="active">Редактирование записи</li>
    </ul>
    <!-- END BREADCRUMB -->
    <!-- page content -->

    <div class="row">
        <h1 class="text-center">{{ $data['name'] }}</h1>
        {!! Form::open(['url' => route('orgEdit',['org'=>$data['id']]),'class'=>'form-horizontal','method'=>'POST']) !!}

        <div class="form-group">
            {!! Form::label('name','Название:',['class' => 'col-xs-2 control-label'])   !!}
            <div class="col-xs-8">
                {!! Form::hidden('name',$data['name'],['class' => 'form-control','placeholder'=>'Введите название организации'])!!}
            </div>
        </div>

        <div class="form-group">
            {!! Form::label('full_name','Полное наименование:',['class' => 'col-xs-2 control-label'])   !!}
            <div class="col-xs-8">
                {!! Form::text('full_name',$data['full_name'],['class' => 'form-control','placeholder'=>'Введите полное наименование организации','disabled'=>'disabled'])!!}
                {!! $errors->first('full_name', '<p class="text-danger">:message</p>') !!}
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
            {!! Form::label('kpp','КПП:',['class' => 'col-xs-2 control-label'])   !!}
            <div class="col-xs-8">
                {!! Form::text('kpp',$data['kpp'],['class' => 'form-control','placeholder'=>'Введите КПП'])!!}
                {!! $errors->first('kpp', '<p class="text-danger">:message</p>') !!}
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
