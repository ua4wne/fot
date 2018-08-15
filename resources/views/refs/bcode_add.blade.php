@extends('layouts.main')

@section('tile_widget')

@endsection

@section('content')
<!-- START BREADCRUMB -->
<ul class="breadcrumb">
    <li><a href="{{ route('main') }}">Рабочий стол</a></li>
    <li><a href="{{ route('codes') }}">План счетов</a></li>
    <li class="active">Новая запись</li>
</ul>
<!-- END BREADCRUMB -->
<!-- page content -->

<div class="row">
    <h2 class="text-center">Новый счет учета</h2>
    {!! Form::open(['url' => route('codeAdd'),'class'=>'form-horizontal','method'=>'POST']) !!}

    <div class="form-group">
        {!! Form::label('code','Код счета:',['class' => 'col-xs-2 control-label'])   !!}
        <div class="col-xs-8">
            {!! Form::text('code',old('code'),['class' => 'form-control','placeholder'=>'Введите код счета'])!!}
            {!! $errors->first('code', '<p class="text-danger">:message</p>') !!}
        </div>
    </div>

    <div class="form-group">
        {!! Form::label('text','Наименование счета:',['class' => 'col-xs-2 control-label'])   !!}
        <div class="col-xs-8">
            {!! Form::text('text',old('text'),['class' => 'form-control','placeholder'=>'Введите наименование счета'])!!}
            {!! $errors->first('text', '<p class="text-danger">:message</p>') !!}
        </div>
    </div>

    <div class="form-group">
        {!! Form::label('show', 'Видимость:',['class'=>'col-xs-2 control-label']) !!}
        <div class="col-xs-8">
            {!! Form::select('show', array('1' => 'Да', '0' => 'Нет'), '1',['class' => 'form-control']); !!}
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
