@extends('layouts.main')

@section('tile_widget')

@endsection

@section('content')
    <!-- START BREADCRUMB -->
    <ul class="breadcrumb">
        <li><a href="{{ route('main') }}">Рабочий стол</a></li>
        <li>Отчеты</li>
        <li><a href="{{ route('acctFilter') }}">Настройки</a></li>
        <li class="active">{{ $title }}</li>
    </ul>
    <!-- END BREADCRUMB -->
    <!-- page content -->
    <div class="row">
        <div class="col-xs-offset-1 col-xs-10">
            {!! $content !!}
        </div>
    </div>
    </div>

@endsection