@extends('layouts.main')

@section('tile_widget')

@endsection

@section('content')
    <!-- START BREADCRUMB -->
    <ul class="breadcrumb">
        <li><a href="{{ route('main') }}">Рабочий стол</a></li>
        <li><a href="{{ route('codes') }}">План счетов</a></li>
        <li class="active">Редактирование записи</li>
    </ul>
    <!-- END BREADCRUMB -->
    <!-- page content -->

    <div class="row">
        <h2 class="text-center">Редактирование счета {{ $data['code'] }}</h2>
        {!! Form::open(['url' => route('codeEdit',['id'=>$data['id']]),'class'=>'form-horizontal','method'=>'POST']) !!}

        <div class="form-group">
            {!! Form::label('code','Код счета:',['class' => 'col-xs-2 control-label'])   !!}
            <div class="col-xs-8">
                {!! Form::text('code',$data['code'],['class' => 'form-control','placeholder'=>'Введите счет бухучета','disabled'=>'disabled'])!!}
            </div>
        </div>

        <div class="form-group">
            {!! Form::label('text','Наименование счета:',['class' => 'col-xs-2 control-label'])   !!}
            <div class="col-xs-8">
                {!! Form::text('text',$data['text'],['class' => 'form-control','placeholder'=>'Укажите наименование счета'])!!}
                {!! $errors->first('text', '<p class="text-danger">:message</p>') !!}
            </div>
        </div>

        <div class="form-group">
            {!! Form::label('show', 'Видимость:',['class'=>'col-xs-2 control-label']) !!}
            <div class="col-xs-8">
                {!! Form::select('show', array('1' => 'Да', '0' => 'Нет'), $data['show'],['class' => 'form-control']); !!}
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
