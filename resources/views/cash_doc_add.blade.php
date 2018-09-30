@extends('layouts.main')

@section('tile_widget')

@endsection

@section('content')
    <!-- START BREADCRUMB -->
    <ul class="breadcrumb">
        <li><a href="{{ route('main') }}">Рабочий стол</a></li>
        <li><a href="{{ route('cash_docs') }}">Кассовые документы</a></li>
        <li class="active">{{ $title }}</li>
    </ul>
    <!-- END BREADCRUMB -->
    <!-- page content -->

    <div class="row">
        @if($direction == 'coming')
            <h2 class="text-center">Поступление наличных</h2>
        @else
            <h2 class="text-center">Выдача наличных</h2>
        @endif
        {!! Form::open(['url' => route('cashDocAdd',['direction'=>$direction]),'class'=>'form-horizontal','method'=>'POST']) !!}

        <div class="form-group">
            {!! Form::label('type', 'Документ №:',['class'=>'col-xs-2 control-label']) !!}
            <div class="col-xs-8">
                {!! Form::text('doc_num', $doc_num,['class' => 'form-control','placeholder'=>'Введите номер документа'])!!}
                {!! $errors->first('doc_num', '<p class="text-danger">:message</p>') !!}
            </div>
        </div>

        <div class="form-group">
            {!! Form::label('type', 'Вид операции:',['class'=>'col-xs-2 control-label']) !!}
            <div class="col-xs-8">
                {!! Form::select('operation_id', $opersel, old('operation_id'),['class' => 'form-control']); !!}
            </div>
        </div>

        <div class="form-group">
            {!! Form::label('type', 'Счет учета:',['class'=>'col-xs-2 control-label']) !!}
            <div class="col-xs-8">
                {!! Form::select('buhcode_id', $codesel, old('buhcode_id'),['class' => 'form-control']); !!}
            </div>
        </div>

        <div class="form-group">
            {!! Form::label('name','Сумма, руб.:',['class' => 'col-xs-2 control-label'])   !!}
            <div class="col-xs-8">
                {!! Form::text('amount',old('amount'),['class' => 'form-control','placeholder'=>'Введите сумму в рублях'])!!}
                {!! $errors->first('amount', '<p class="text-danger">:message</p>') !!}
            </div>
        </div>

        <div class="form-group">
            {!! Form::label('firm_id','Получатель\Плательщик:',['class' => 'col-xs-2 control-label'])   !!}
            <div class="col-xs-8">
                {!! Form::text('firm_id',old('firm_id'),['class' => 'form-control','placeholder'=>'Введите полное наименование организации','id'=>'search_firm'])!!}
                {!! $errors->first('firm_id', '<p class="text-danger">:message</p>') !!}
            </div>
        </div>

        <div class="form-group">
            {!! Form::label('org_id', 'Организация:',['class'=>'col-xs-2 control-label']) !!}
            <div class="col-xs-8">
                {!! Form::select('org_id', $orgsel, old('org_id'),['class' => 'form-control','id'=>'organ_id']); !!}
            </div>
        </div>

        <div class="form-group">
            {!! Form::label('contract','Договор:',['class' => 'col-xs-2 control-label'])   !!}
            <div class="col-xs-8">
                {!! Form::text('contract',old('contract'),['class' => 'form-control','placeholder'=>'Укажите договор'])!!}
                {!! $errors->first('contract', '<p class="text-danger">:message</p>') !!}
            </div>
        </div>

        <div class="form-group">
            {!! Form::label('comment','Комментарий:',['class' => 'col-xs-2 control-label'])   !!}
            <div class="col-xs-8">
                {!! Form::text('comment',old('comment'),['class' => 'form-control','placeholder'=>'Комментарий'])!!}
                {!! $errors->first('comment', '<p class="text-danger">:message</p>') !!}
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
    </script>
@endsection
