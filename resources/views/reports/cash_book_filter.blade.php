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
        <h2 class="text-center">Выбор периода</h2>
        {!! Form::open(['url' => route('cash_book'),'class'=>'form-horizontal','method'=>'POST']) !!}

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
            {!! Form::label('org_id', 'Организация:',['class'=>'col-xs-2 control-label']) !!}
            <div class="col-xs-8">
                {!! Form::select('org_id', $orgsel, old('org_id'),['class' => 'form-control','id'=>'organ_id']); !!}
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
        $("#organ_id :contains('Стандарт Юнион ООО')").attr("selected", "selected");
    </script>
@endsection