@extends('layouts.main')

@section('tile_widget')

@endsection

@section('content')
    <!-- START BREADCRUMB -->
    <ul class="breadcrumb">
        <li><a href="{{ route('main') }}">Рабочий стол</a></li>
        <li class="active">Доступ запрещен</li>
    </ul>
    <!-- END BREADCRUMB -->
    <!-- page content -->
    <div class="row">
        <h1 class="text-center">У Вас нет прав на просмотр страницы!</h1>
        <img src="/images/ops.jpg" class="img-responsive center-block">
    </div>
    </div>

@endsection

@section('user_script')

@endsection