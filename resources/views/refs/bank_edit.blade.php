@extends('layouts.main')

@section('tile_widget')

@endsection

@section('content')
    <!-- START BREADCRUMB -->
    <ul class="breadcrumb">
        <li><a href="{{ route('main') }}">Рабочий стол</a></li>
        <li><a href="{{ route('banks') }}">Банки</a></li>
        <li class="active">Редактирование записи</li>
    </ul>
    <!-- END BREADCRUMB -->
    <!-- page content -->

    <div class="row">
        <h1 class="text-center">{{ $data['name'] }}</h1>
        {!! Form::open(['url' => route('bankEdit',['bank'=>$data['id']]),'class'=>'form-horizontal','method'=>'POST']) !!}

       {!! Form::hidden('name',$data['name'],['class' => 'form-control','placeholder'=>'Введите название'])!!}

        <div class="form-group">
            {!! Form::label('dcode','БИК:',['class' => 'col-xs-2 control-label'])   !!}
            <div class="col-xs-8">
                {!! Form::text('bik',$data['bik'],['class' => 'form-control','placeholder'=>'Введите БИК', 'disabled'=>'disabled'])!!}
                {!! $errors->first('bik', '<p class="text-danger">:message</p>') !!}
            </div>
        </div>

        <div class="form-group">
            {!! Form::label('swift','SWIFT:',['class' => 'col-xs-2 control-label'])   !!}
            <div class="col-xs-8">
                {!! Form::text('swift',$data['swift'],['class' => 'form-control','placeholder'=>'Введите SWIFT', 'disabled'=>'disabled'])!!}
                {!! $errors->first('swift', '<p class="text-danger">:message</p>') !!}
            </div>
        </div>

        <div class="form-group">
            {!! Form::label('account','Корр. счет:',['class' => 'col-xs-2 control-label'])   !!}
            <div class="col-xs-8">
                {!! Form::text('account',$data['account'],['class' => 'form-control','placeholder'=>'Введите корр счет', 'disabled'=>'disabled'])!!}
                {!! $errors->first('account', '<p class="text-danger">:message</p>') !!}
            </div>
        </div>

        <div class="form-group">
            {!! Form::label('city','Город:',['class' => 'col-xs-2 control-label'])   !!}
            <div class="col-xs-8">
                {!! Form::text('city',$data['city'],['class' => 'form-control','placeholder'=>'Укажите город'])!!}
                {!! $errors->first('city', '<p class="text-danger">:message</p>') !!}
            </div>
        </div>

        <div class="form-group">
            {!! Form::label('address','Адрес:',['class' => 'col-xs-2 control-label'])   !!}
            <div class="col-xs-8">
                {!! Form::text('address',$data['address'],['class' => 'form-control','placeholder'=>'Укажите адрес'])!!}
                {!! $errors->first('address', '<p class="text-danger">:message</p>') !!}
            </div>
        </div>

        <div class="form-group">
            {!! Form::label('phone','Телефоны:',['class' => 'col-xs-2 control-label'])   !!}
            <div class="col-xs-8">
                {!! Form::text('phone',$data['phone'],['class' => 'form-control','placeholder'=>'Укажите телефон'])!!}
                {!! $errors->first('phone', '<p class="text-danger">:message</p>') !!}
            </div>
        </div>

        <div class="form-group">
            {!! Form::label('country','Страна:',['class' => 'col-xs-2 control-label'])   !!}
            <div class="col-xs-8">
                {!! Form::text('country',$data['country'],['class' => 'form-control','placeholder'=>'Укажите страну'])!!}
                {!! $errors->first('country', '<p class="text-danger">:message</p>') !!}
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
