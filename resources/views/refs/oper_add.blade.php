@extends('layouts.main')

@section('tile_widget')

@endsection

@section('content')
<!-- START BREADCRUMB -->
<ul class="breadcrumb">
    <li><a href="{{ route('main') }}">Рабочий стол</a></li>
    <li><a href="{{ route('operations') }}">Виды операций</a></li>
    <li class="active">Новая запись</li>
</ul>
<!-- END BREADCRUMB -->
<!-- page content -->

<div class="row">
    <h2 class="text-center">Новая операция</h2>
    {!! Form::open(['url' => route('operationAdd'),'class'=>'form-horizontal','method'=>'POST']) !!}

    <div class="form-group">
        {!! Form::label('name','Наименование:',['class' => 'col-xs-2 control-label'])   !!}
        <div class="col-xs-8">
            {!! Form::text('name',old('name'),['class' => 'form-control','placeholder'=>'Введите наименование операции'])!!}
            {!! $errors->first('name', '<p class="text-danger">:message</p>') !!}
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
