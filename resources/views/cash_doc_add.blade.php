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

        {!! Form::hidden('doc_num',$doc_num) !!}

        <div class="form-group">
            {!! Form::label('type', 'Вид операции:',['class'=>'col-xs-2 control-label']) !!}
            <div class="col-xs-8">
                {!! Form::select('operation_id', array('physical' => 'Физическое лицо', 'legal_entity' => 'Юридическое лицо'), 'legal_entity',['class' => 'form-control']); !!}
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
            {!! Form::label('org_id','Получатель\Плательщик:',['class' => 'col-xs-2 control-label'])   !!}
            <div class="col-xs-8">
                {!! Form::text('org_id',old('org_id'),['class' => 'form-control','placeholder'=>'Введите полное наименование организации'])!!}
                {!! $errors->first('org_id', '<p class="text-danger">:message</p>') !!}
            </div>
        </div>

        <div class="form-group">
            {!! Form::label('firm_id', 'Организация:',['class'=>'col-xs-2 control-label']) !!}
            <div class="col-xs-8">
                {!! Form::select('firm_id', $orgsel, old('firm_id'),['class' => 'form-control','id'=>'organ_id']); !!}
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
    <script>
        $("#organ_id").prepend( $('<option value="0">Выберите организацию</option>'));
        $("#organ_id :first").attr("selected", "selected");
        $("#organ_id :first").attr("disabled", "disabled");
    </script>
@endsection
