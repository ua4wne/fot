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
            <table id="datatable" class="table table-striped table-bordered">
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
                        @if($user->image)
                            <td><div class="profile_pic user-profile"><img src="{{ $user->image }}" alt="..." class="img-responsive center-block"></div></td>
                        @else
                            <td style="width:70px;"><img src="/images/male.png" alt="..." class="img-responsive center-block"></td>
                        @endif
                        <th>{!! Html::link(route('userEdit',['id'=>$user->id]),$user->name,['alt'=>$user->name]) !!}</th>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->login }}</td>
                        @if($user->active)
                            <td><i class="fa fa-check-circle-o text-success fa-3x" aria-hidden="true"></i></td>
                        @else
                            <td><i class="fa fa-ban fa-3x text-danger" aria-hidden="true"></i></td>
                        @endif
                        <td style="width:110px;">
                            {!! Form::open(['url'=>route('userEdit',['id'=>$user->id]), 'class'=>'form-horizontal','method' => 'POST', 'onsubmit' => 'return confirmDelete()']) !!}
                            {{ method_field('DELETE') }}
                            <div class="form-group" role="group">
                                <a href="{{route('userEdit',['id'=>$user->id])}}"><button class="btn btn-success btn-sm" type="button"><i class="fa fa-edit fa-3x>" aria-hidden="true"></i></button></a>
                                {!! Form::button('<i class="fa fa-trash-o fa-3x>" aria-hidden="true"></i>',['class'=>'btn btn-danger','type'=>'submit']) !!}
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
    <script src="/js/jquery.dataTables.min.js"></script>
    @include('confirm')
@endsection
