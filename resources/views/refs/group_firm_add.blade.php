@extends('layouts.main')

@section('tile_widget')

@endsection

@section('content')
    <!-- START BREADCRUMB -->
    <ul class="breadcrumb">
        <li><a href="{{ route('main') }}">Рабочий стол</a></li>
        <li><a href="{{ route('groups') }}">Группы контрагентов</a></li>
        <li><a href="{{ route('groupView',['id'=>$id]) }}">{{ $link }}</a></li>
        <li class="active">{{ $title }}</li>
    </ul>
    <!-- END BREADCRUMB -->
    <!-- page content -->

    <div class="row">
        <h2 class="text-center">Новый контрагент</h2>
        {!! Form::open(['url' => route('groupFirmAdd',['id'=>$id]),'class'=>'form-horizontal','method'=>'POST']) !!}

        <div class="form-group">
            {!! Form::label('type', 'Вид контрагента:',['class'=>'col-xs-2 control-label']) !!}
            <div class="col-xs-8">
                {!! Form::select('type', array('physical' => 'Физическое лицо', 'legal_entity' => 'Юридическое лицо'), 'legal_entity',['class' => 'form-control']); !!}
            </div>
        </div>

        <div class="form-group">
            {!! Form::label('name','Наименование:',['class' => 'col-xs-2 control-label'])   !!}
            <div class="col-xs-8">
                {!! Form::text('name',old('name'),['class' => 'form-control','placeholder'=>'Введите наименование организации'])!!}
                {!! $errors->first('name', '<p class="text-danger">:message</p>') !!}
            </div>
        </div>

        <div class="form-group">
            {!! Form::label('full_name','Полное наименование:',['class' => 'col-xs-2 control-label'])   !!}
            <div class="col-xs-8">
                {!! Form::text('full_name',old('full_name'),['class' => 'form-control','placeholder'=>'Введите полное наименование организации'])!!}
                {!! $errors->first('full_name', '<p class="text-danger">:message</p>') !!}
            </div>
        </div>

        <div class="form-group">
            {!! Form::label('group_id', 'Входит в группу:',['class'=>'col-xs-2 control-label']) !!}
            <div class="col-xs-8">
                {!! Form::select('group_id', $grpsel, old('group_id'),['class' => 'form-control','id'=>'group_id']); !!}
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
            {!! Form::label('kpp','КПП:',['class' => 'col-xs-2 control-label'])   !!}
            <div class="col-xs-8">
                {!! Form::text('kpp',old('kpp'),['class' => 'form-control','placeholder'=>'Введите КПП'])!!}
                {!! $errors->first('kpp', '<p class="text-danger">:message</p>') !!}
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

{{--@section('user_script')
    <script>
        $("#group_id").prepend( $('<option value="0">Выберите группу</option>'));
        $("#group_id :first").attr("selected", "selected");
        $("#group_id :first").attr("disabled", "disabled");
    </script>
@endsection--}}
