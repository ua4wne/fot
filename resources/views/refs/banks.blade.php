@extends('layouts.main')

@section('tile_widget')

@endsection

@section('content')
    <!-- START BREADCRUMB -->
    <ul class="breadcrumb">
        <li><a href="{{ route('main') }}">Рабочий стол</a></li>
        <li class="active">Банки</li>
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
        @if($banks)
            <a href="{{route('bankAdd')}}">
                <button type="button" class="btn btn-primary btn-rounded">Новая запись</button>
            </a>
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th>Наименование</th>
                    <th>БИК</th>
                    <th>SWIFT</th>
                    <th>Корр. счет</th>
                    <th>Город</th>
                    <th>Адрес</th>
                    <th>Телефоны</th>
                    <th>Страна</th>
                    <th>Действия</th>
                </tr>
                </thead>
                <tbody>

                @foreach($banks as $k => $bank)

                    <tr>
                        <th>{!! Html::link(route('bankEdit',['bank'=>$bank->id]),$bank->name,['alt'=>$bank->name]) !!}</th>
                        <td>{{ $bank->bik }}</td>
                        <td>{{ $bank->swift }}</td>
                        <td>{{ $bank->account }}</td>
                        <td>{{ $bank->city }}</td>
                        <td>{{ $bank->address }}</td>
                        <td>{{ $bank->phone }}</td>
                        <td>{{ $bank->country }}</td>

                        <td style="width:110px;">
                            {!! Form::open(['url'=>route('bankEdit',['bank'=>$bank->id]), 'class'=>'form-horizontal','method' => 'POST', 'onsubmit' => 'return confirmDelete()']) !!}
                            {{ method_field('DELETE') }}
                            <div class="form-group" role="group">
                                <a href="{{route('bankEdit',['bank'=>$bank->id])}}"><button class="btn btn-success btn-sm" type="button" title="Редактировать запись"><i class="fa fa-edit fa-lg>" aria-hidden="true"></i></button></a>
                                {!! Form::button('<i class="fa fa-trash-o fa-lg>" aria-hidden="true"></i>',['class'=>'btn btn-danger','type'=>'submit','title'=>'Удалить запись']) !!}
                            </div>
                            {!! Form::close() !!}
                        </td>
                    </tr>

                @endforeach
                </tbody>
            </table>
            {{ $banks->links() }}
        @endif
    </div>
    </div>
    <!-- /page content -->
@endsection

@section('user_script')
    @include('confirm')
@endsection
