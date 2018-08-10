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
        <h1 class="text-center">Что-то пошло не так!</h1>
        <h2 class="text-center">Такой страницы не существует. Попробуйте ввести правильную ссылку.</h2>
        <img src="/images/404.jpg" class="img-responsive center-block">
    </div>
    </div>

@endsection

@section('user_script')

@endsection