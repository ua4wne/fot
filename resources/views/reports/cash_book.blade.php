@extends('layouts.main')

@section('tile_widget')

@endsection

@section('content')
    <!-- START BREADCRUMB -->
    <ul class="breadcrumb">
        <li><a href="{{ route('main') }}">Рабочий стол</a></li>
        <li><a href="{{ route('cash_docs') }}">Кассовые документы</a></li>
        <li><a href="{{ route('cashBookFilter') }}">Выбор периода</a></li>
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