@extends('layouts.main')

@section('tile_widget')

@endsection

@section('content')
    <!-- START BREADCRUMB -->
    <ul class="breadcrumb">
        <li><a href="{{ route('main') }}">Рабочий стол</a></li>
        <li><a href="{{ route('groups') }}">Группы контрагентов</a></li>
        <li><a href="{{ route('firms') }}">Контрагенты</a></li>
        <li><a href="{{ route('contracts') }}">Договоры</a></li>
        <li class="active">{{ $title }}</li>
    </ul>
    <!-- END BREADCRUMB -->
    <!-- page content -->

    <div class="row">
        <h2 class="text-center">{{ $title }}</h2>

        {!! Form::open(['url' => route('contractAdd'),'class'=>'form-horizontal','method'=>'POST']) !!}

        <div class="form-group">
            {!! Form::label('num_doc', 'Документ №:',['class'=>'col-xs-2 control-label']) !!}
            <div class="col-xs-8">
                {!! Form::text('num_doc', $doc_num,['class' => 'form-control','placeholder'=>'Введите номер договора'])!!}
                {!! $errors->first('num_doc', '<p class="text-danger">:message</p>') !!}
            </div>
        </div>

        <div class="form-group">
            {!! Form::label('name', 'Наименование:',['class'=>'col-xs-2 control-label']) !!}
            <div class="col-xs-8">
                {!! Form::text('name', old('name'),['class' => 'form-control','placeholder'=>'Введите наименование договора'])!!}
                {!! $errors->first('name', '<p class="text-danger">:message</p>') !!}
            </div>
        </div>

        <div class="form-group">
            {!! Form::label('tdoc_id', 'Вид договора:',['class'=>'col-xs-2 control-label']) !!}
            <div class="col-xs-8">
                {!! Form::select('tdoc_id', $tdocsel, old('tdoc_id'),['class' => 'form-control']); !!}
            </div>
        </div>

        <div class="form-group">
            {!! Form::label('org_id', 'Организация:',['class'=>'col-xs-2 control-label']) !!}
            <div class="col-xs-8">
                {!! Form::select('org_id', $orgsel, old('org_id'),['class' => 'form-control','id'=>'organ_id']); !!}
            </div>
        </div>

        <div class="form-group">
            {!! Form::label('firm_id','Контрагент:',['class' => 'col-xs-2 control-label','id'=>'control-label'])   !!}
            <div class="col-xs-8">
                {!! Form::text('firm_id',old('firm_id'),['class' => 'form-control','placeholder'=>'Введите полное наименование контрагента','id'=>'search_firm'])!!}
                {!! $errors->first('firm_id', '<p class="text-danger">:message</p>') !!}
            </div>
        </div>

        <div class="form-group">
            {!! Form::label('currency_id', 'Валюта:',['class'=>'col-xs-2 control-label']) !!}
            <div class="col-xs-8">
                {!! Form::select('currency_id', $currsel, old('currency_id'),['class' => 'form-control','id'=>'currency_id']); !!}
            </div>
        </div>

        <div class="form-group">
            <label class="col-xs-2 control-label">Срок действия</label>
            <div class="col-xs-8">
               {{ Form::date('stop', \Carbon\Carbon::parse()->format('d-m-Y'),['class' => 'form-control']) }}
            </div>
        </div>

        <div class="form-group">
            {!! Form::label('text','Комментарий:',['class' => 'col-xs-2 control-label'])   !!}
            <div class="col-xs-8">
                {!! Form::text('text',old('text'),['class' => 'form-control','placeholder'=>'Комментарий'])!!}
                {!! $errors->first('text', '<p class="text-danger">:message</p>') !!}
            </div>
        </div>

        <div class="form-group">
            {!! Form::label('settlement_id', 'Вид расчетов:',['class'=>'col-xs-2 control-label']) !!}
            <div class="col-xs-8">
                {!! Form::select('settlement_id', $settlsel, old('settlement_id'),['class' => 'form-control','id'=>'settlement_id']); !!}
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

@section('user_script')
    <script src="/js/typeahead.min.js"></script>
    <script>
        var url = "{{ route('getOrg') }}";
        $('#search_firm').typeahead({
            source:  function (query, process) {
                return $.get(url, { query: query }, function (data) {
                    return process(data);
                });
            }
        });

        $("#organ_id").prepend( $('<option value="0">Выберите организацию</option>'));
        $("#organ_id :first").attr("selected", "selected");
        $("#organ_id :first").attr("disabled", "disabled");
        $("#settlement_id").prepend( $('<option value="0">Выберите вид расчета</option>'));
        $("#settlement_id :first").attr("selected", "selected");
        $("#settlement_id :first").attr("disabled", "disabled");
    </script>
@endsection
