@extends('layouts.main')

@section('tile_widget')

@endsection

@section('content')
    <!-- START BREADCRUMB -->
    <ul class="breadcrumb">
        <li><a href="{{ route('main') }}">Рабочий стол</a></li>
        <li class="active">Валюты</li>
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
            @if($money)
                <a href="{{route('currencyAdd')}}">
                    <button type="button" class="btn btn-primary btn-rounded">Новая запись</button>
                </a>
                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th>Наименование валюты</th>
                        <th>Цифр. код</th>
                        <th>Симв. код</th>
                        <th>Курс</th>
                        <th>Кратность</th>
                        <th>Действия</th>
                    </tr>
                    </thead>
                    <tbody>

                    @foreach($money as $k => $curr)

                        <tr>

                            <th>{!! Html::link(route('currencyEdit',['currency'=>$curr->id]),$curr->name,['alt'=>$curr->name]) !!}</th>
                            <td>{{ $curr->dcode }}</td>
                            <td>{{ $curr->scode }}</td>
                            <td>{{ $curr->cource }}</td>
                            <td>{{ $curr->multi }}</td>

                            <td style="width:110px;">
                                {!! Form::open(['url'=>route('currencyEdit',['currency'=>$curr->id]), 'class'=>'form-horizontal','method' => 'POST','onsubmit' => 'return confirmDelete()']) !!}
                                {{ method_field('DELETE') }}
                                <div class="form-group" role="group">
                                    <a href="{{route('currencyEdit',['currency'=>$curr->id])}}"><button class="btn btn-success btn-sm" type="button"><i class="fa fa-edit fa-lg>" aria-hidden="true"></i></button></a>
                                    {!! Form::button('<i class="fa fa-trash-o fa-lg>" aria-hidden="true"></i>',['class'=>'btn btn-danger btn-sm','type'=>'submit']) !!}
                                </div>
                                {!! Form::close() !!}
                            </td>
                        </tr>

                    @endforeach
                    </tbody>
                </table>
                {{ $money->links() }}
            @endif
        </div>
    </div>
    <!-- /page content -->
@endsection

@section('user_script')
    @include('confirm')
@endsection
