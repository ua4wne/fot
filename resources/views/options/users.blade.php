@extends('layouts.main')

@section('tile_widget')

@endsection

@section('content')
    <!-- START BREADCRUMB -->
    <ul class="breadcrumb">
        <li><a href="{{ route('main') }}">Рабочий стол</a></li>
        <li class="active">Пользователи</li>
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
        @if($users)
            <a href="{{route('userAdd')}}">
                <button type="button" class="btn btn-primary btn-rounded">Новая запись</button>
            </a>
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th>Аватар</th>
                    <th>ФИО</th>
                    <th>E-mail</th>
                    <th>Логин</th>
                    <th>Статус</th>
                    <th>Действия</th>
                </tr>
                </thead>
                <tbody>

                @foreach($users as $k => $user)

                    <tr>
                        <td>{{ $user->image }}</td>
                        <th>{!! Html::link(route('userEdit',['id'=>$user->id]),$user->name,['alt'=>$user->name]) !!}</th>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->login }}</td>
                        @if($user->active)
                            <td><i class="fa fa-check-circle-o success fa-lg" aria-hidden="true"></i></td>
                        @else
                            <td><i class="fa fa-ban fa-lg danger" aria-hidden="true"></i></td>
                        @endif
                        <td style="width:110px;">
                            {!! Form::open(['url'=>route('userEdit',['id'=>$user->id]), 'class'=>'form-horizontal','method' => 'POST', 'onsubmit' => 'return confirmDelete()']) !!}
                            {{ method_field('DELETE') }}
                            <div class="form-group" role="group">
                                <a href="{{route('userEdit',['id'=>$user->id])}}"><button class="btn btn-success btn-sm" type="button"><i class="fa fa-edit fa-lg>" aria-hidden="true"></i></button></a>
                                {!! Form::button('<i class="fa fa-trash-o fa-lg>" aria-hidden="true"></i>',['class'=>'btn btn-danger','type'=>'submit']) !!}
                            </div>
                            {!! Form::close() !!}
                        </td>
                    </tr>

                @endforeach
                </tbody>
            </table>
            {{ $users->links() }}
        @endif
    </div>
    </div>
    <!-- /page content -->
@endsection

@section('user_script')
    @include('confirm')
@endsection
