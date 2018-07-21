@extends('layouts.main')

@section('tile_widget')

@endsection

@section('content')
    <!-- START BREADCRUMB -->
    <ul class="breadcrumb">
        <li><a href="{{ route('main') }}">Рабочий стол</a></li>
        <li><a href="{{ route('firms') }}">Контрагенты</a></li>
        <li><a href="{{ route('groups') }}">Группы</a></li>
        <li class="active">Новая запись</li>
    </ul>
    <!-- END BREADCRUMB -->
    <!-- page content -->

    <div class="row">
        <h2 class="text-center">Новая группа</h2>
        {!! Form::open(['url' => route('groupAdd'),'class'=>'form-horizontal','method'=>'POST']) !!}

        <div class="form-group">
            {!! Form::label('name','Название:',['class' => 'col-xs-2 control-label'])   !!}
            <div class="col-xs-8">
                {!! Form::text('name',old('name'),['class' => 'form-control','placeholder'=>'Введите название группы'])!!}
                {!! $errors->first('name', '<p class="text-danger">:message</p>') !!}
            </div>
        </div>
        @if($grpsel)
        <div class="form-group">
            {!! Form::label('parent_id', 'Родительская группа:',['class'=>'col-xs-2 control-label']) !!}
            <div class="col-xs-8">
                {!! Form::select('parent_id',$grpsel, old('parent_id'), ['class' => 'form-control','id'=>'parent_id']); !!}
            </div>
        </div>
        @endif

        <div class="form-group">
            <div class="col-xs-offset-2 col-xs-10">
                {!! Form::button('Сохранить', ['class' => 'btn btn-primary','type'=>'submit']) !!}
            </div>
        </div>

        {!! Form::close() !!}

    </div>
    </div>
@endsection

@section('user_script')
    <script>
        $("#parent_id").prepend( $('<option value="0">Выберите родительскую группу</option>'));
        $("#parent_id :first").attr("selected", "selected");
        $("#parent_id :first").attr("disabled", "disabled");
    </script>
@endsection
