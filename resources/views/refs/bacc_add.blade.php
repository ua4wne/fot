@extends('layouts.main')

@section('tile_widget')

@endsection

@section('content')
    <!-- START BREADCRUMB -->
    <ul class="breadcrumb">
        <li><a href="{{ route('main') }}">Рабочий стол</a></li>
        <li><a href="{{ route('organizations') }}">Организации</a></li>
        <li><a href="{{ route('bacc') }}">Банковские счета</a></li>
        <li class="active">Новая запись</li>
    </ul>
    <!-- END BREADCRUMB -->
    <!-- page content -->

    <div class="row">
        <h2 class="text-center">Новый банковский счет</h2>
        {!! Form::open(['url' => route('baccAdd'),'class'=>'form-horizontal','method'=>'POST']) !!}

        <div class="form-group">
            {!! Form::label('org_id', 'Организация:',['class'=>'col-xs-2 control-label']) !!}
            <div class="col-xs-8">
                {!! Form::select('org_id',$orgsel, old('org_id'), ['class' => 'form-control']); !!}
                {!! $errors->first('org_id', '<p class="text-danger">:message</p>') !!}
            </div>
        </div>

        <div class="form-group">
            {!! Form::label('bank_id', 'Банк:',['class'=>'col-xs-2 control-label']) !!}
            <div class="col-xs-8">
                {!! Form::select('bank_id',$banksel, old('bank_id'), ['class' => 'form-control']); !!}
                {!! $errors->first('bank_id', '<p class="text-danger">:message</p>') !!}
            </div>
        </div>

        <div class="form-group">
            {!! Form::label('account','Счет:',['class' => 'col-xs-2 control-label'])   !!}
            <div class="col-xs-8">
                {!! Form::text('account',old('account'),['class' => 'form-control','placeholder'=>'Введите банковский счет'])!!}
                {!! $errors->first('account', '<p class="text-danger">:message</p>') !!}
            </div>
        </div>

        <div class="form-group">
            {!! Form::label('currency','Валюта:',['class' => 'col-xs-2 control-label'])   !!}
            <div class="col-xs-8">
                {!! Form::text('currency',old('currency'),['class' => 'form-control','placeholder'=>'Укажите валюту счета'])!!}
                {!! $errors->first('currency', '<p class="text-danger">:message</p>') !!}
            </div>
        </div>

        <div class="form-group">
            {!! Form::label('is_main', 'Основной счет:',['class'=>'col-xs-2 control-label']) !!}
            <div class="col-xs-8">
                {!! Form::select('is_main', array('1' => 'Да', '0' => 'Нет'), '1',['class' => 'form-control']); !!}
            </div>
        </div>

        <div class="form-group">
            <label class="col-xs-2 control-label">Дата открытия</label>
            <div class="col-xs-8">
                {{ Form::date('date_open', \Carbon\Carbon::parse()->format('d-m-Y'),['class' => 'form-control']) }}
            </div>
        </div>

        <div class="form-group">
            <label class="col-xs-2 control-label">Дата закрытия</label>
            <div class="col-xs-8">
                {{ Form::date('date_close', \Carbon\Carbon::parse()->format('d-m-Y'),['class' => 'form-control']) }}
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
