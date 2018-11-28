@extends('layouts.main')

@section('tile_widget')

@endsection

@section('content')
    <!-- START BREADCRUMB -->
    <ul class="breadcrumb">
        <li><a href="{{ route('main') }}">Рабочий стол</a></li>
        <li>Отчеты</li>
        <li class="active">{{ $title }}</li>
    </ul>
    <!-- END BREADCRUMB -->
    <!-- page content -->
    <div class="row">
        <h2 class="text-center">Настройки отчета</h2>
        {!! Form::open(['url' => route('acctReport'),'class'=>'form-horizontal','method'=>'POST']) !!}

        <div class="form-group">
            {!! Form::label('from', 'Начало периода:',['class'=>'col-xs-2 control-label']) !!}
            <div class="col-xs-8">
                {{ Form::date('from', \Carbon\Carbon::createFromFormat('Y-m-d', date('Y-m-d')),['class' => 'form-control','required'=>'required','id'=>'from']) }}
            </div>
        </div>

        <div class="form-group">
            {!! Form::label('to', 'Конец периода:',['class'=>'col-xs-2 control-label']) !!}
            <div class="col-xs-8">
                {{ Form::date('to', \Carbon\Carbon::createFromFormat('Y-m-d', date('Y-m-d')),['class' => 'form-control','required'=>'required','id'=>'to']) }}
            </div>
        </div>

        <div class="form-group">
            {!! Form::label('code_id', 'Счет:',['class'=>'col-xs-2 control-label']) !!}
            <div class="col-xs-8">
                {!! Form::select('code_id', $codsel, old('code_id'),['class' => 'form-control','id'=>'code_id','required'=>'required']); !!}
            </div>
        </div>

        <div class="form-group">
            {!! Form::label('org_id', 'Организация:',['class'=>'col-xs-2 control-label']) !!}
            <div class="col-xs-8">
                {!! Form::select('org_id', $orgsel, old('org_id'),['class' => 'form-control','id'=>'org_id']); !!}
            </div>
        </div>

        <div class="form-group">
            <div class="col-xs-offset-2 col-xs-10">
                {!! Form::button('Сформировать', ['class' => 'btn btn-primary','type'=>'submit']) !!}
            </div>
        </div>

        {!! Form::close() !!}

    </div>
    </div>
@endsection

@section('user_script')
    <script>
        //$("#organ_id :contains('Стандарт Юнион ООО')").attr("selected", "selected");
        $("#code_id").prepend($('<option value="0">Выберите счет</option>'));
        $("#code_id :first").attr("selected", "selected");
        $("#code_id :first").attr("disabled", "disabled");

        $("#org_id").prepend($('<option value="0">Выберите организацию (не обязательно)</option>'));
        $("#org_id :first").attr("selected", "selected");
        $("#org_id :first").attr("disabled", "disabled");
    </script>
@endsection