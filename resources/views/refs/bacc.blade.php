@extends('layouts.main')

@section('tile_widget')

@endsection

@section('content')
    <!-- START BREADCRUMB -->
    <ul class="breadcrumb">
        <li><a href="{{ route('main') }}">Рабочий стол</a></li>
        <li><a href="{{ route('organizations') }}">Организации</a></li>
        <li class="active">Банковские счета</li>
    </ul>
    <!-- END BREADCRUMB -->
    <!-- page content -->
    @if (session('status'))
        <div class="row">
            <div class="alert alert-success panel-remove">
                <a href="#" class="close" data-dismiss="alert">&times;</a>
                {{ session('status') }}
            </div>
        </div>
    @endif
    <div class="row">
        <h2 class="text-center">{{ $head }}</h2>
        @if($bacc)
            <a href="{{route('baccAdd')}}">
                <button type="button" class="btn btn-primary btn-rounded">Новая запись</button>
            </a>
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th>Организация</th>
                    <th>Банк</th>
                    <th>Счет</th>
                    <th>Валюта</th>
                    <th>Открыт</th>
                    <th>Закрыт</th>
                    <th>Основной</th>
                    <th>Действия</th>
                </tr>
                </thead>
                <tbody>

                @foreach($bacc as $k => $acc)

                    <tr>
                        <td>{{ $acc->org_id }}</td>
                        <td>{{ $acc->bank_id }}</td>
                        <th>{!! Html::link(route('baccEdit',['id'=>$acc->id]),$acc->account,['alt'=>$acc->account]) !!}</th>
                        <td>{{ $acc->currency }}</td>
                        <td>{{ $acc->date_open }}</td>
                        <td>{{ $acc->date_close }}</td>
                        <td>{{ $acc->is_main }}</td>

                        <td style="width:110px;">
                            {!! Form::open(['url'=>route('baccEdit',['id'=>$acc->id]), 'class'=>'form-horizontal','method' => 'POST', 'onsubmit' => 'return confirmDelete()']) !!}
                            {{ method_field('DELETE') }}
                            <div class="form-group" role="group">
                                <a href="{{route('baccEdit',['id'=>$acc->id])}}"><button class="btn btn-success btn-sm" type="button"><i class="fa fa-edit fa-lg>" aria-hidden="true"></i></button></a>
                                {!! Form::button('<i class="fa fa-trash-o fa-lg>" aria-hidden="true"></i>',['class'=>'btn btn-danger','type'=>'submit']) !!}
                            </div>
                            {!! Form::close() !!}
                        </td>
                    </tr>

                @endforeach
                </tbody>
            </table>
            {{ $bacc->links() }}
        @endif
    </div>
    </div>
    <!-- /page content -->
@endsection

@section('user_script')
    @include('confirm')
@endsection
