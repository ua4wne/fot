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
        <div class="modal fade" id="editUser" tabindex="-1" role="dialog" aria-labelledby="editUser" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <i class="fa fa-times-circle fa-lg" aria-hidden="true"></i>
                        </button>
                        <h4 class="modal-title">Редактирование</h4>
                    </div>
                    <div class="modal-body">
                        {!! Form::open(['url' => '#','id'=>'edit_login','class'=>'form-horizontal','method'=>'POST']) !!}

                        <div class="form-group">
                            <div class="col-xs-8">
                                {!! Form::hidden('id','',['class' => 'form-control','required'=>'required','id'=>'login_id']) !!}
                            </div>
                        </div>

                        <div class="form-group">
                            {!! Form::label('name','ФИО:',['class' => 'col-xs-3 control-label'])   !!}
                            <div class="col-xs-8">
                                {!! Form::text('name',old('name'),['class' => 'form-control','placeholder'=>'Введите ФИО','required','id'=>'name'])!!}
                            </div>
                        </div>

                        <div class="form-group">
                            {!! Form::label('email','E-mail:',['class' => 'col-xs-3 control-label'])   !!}
                            <div class="col-xs-8">
                                {!! Form::email('email',old('email'),['class' => 'form-control','placeholder'=>'Введите E-mail','id'=>'email'])!!}
                            </div>
                        </div>

                        <div class="form-group">
                            {!! Form::label('login','Логин:',['class' => 'col-xs-3 control-label'])   !!}
                            <div class="col-xs-8">
                                {!! Form::email('login',old('login'),['class' => 'form-control','placeholder'=>'Введите логин','id'=>'login'])!!}
                            </div>
                        </div>

                        <div class="form-group">
                            {!! Form::label('active', 'Статус:',['class'=>'col-xs-3 control-label']) !!}
                            <div class="col-xs-8">
                                {!! Form::select('active', array('0'=>'Не активен','1'=>'Активен'), old('active'),['class' => 'form-control','id'=>'active']); !!}
                            </div>
                        </div>

                        {!! Form::close() !!}
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Отмена</button>
                        <button type="button" class="btn btn-primary" id="save">Сохранить</button>
                    </div>
                </div>
            </div>
        </div>
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
                        <th>{{ $user->name }}</th>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->login }}</td>
                        @if($user->active)
                            <td><span role="button" class="label label-success" id="{{ $user->id }}">Активен</span></td>
                        @else
                            <td><span role="button" class="label label-danger" id="{{ $user->id }}">Не активен</span></td>
                        @endif
                        @if($user->id==1)
                            <td style="width:110px;">
                                <div class="form-group" role="group">
                                    <button class="btn btn-success btn-sm login_edit" type="button" data-toggle="modal" data-target="#editUser"><i class="fa fa-edit fa-lg" aria-hidden="true"></i></button>
                                </div>
                            </td>
                        @else
                            <td style="width:110px;">
                                <div class="form-group" role="group">
                                    <button class="btn btn-success btn-sm login_edit" type="button" data-toggle="modal" data-target="#editUser"><i class="fa fa-edit fa-lg" aria-hidden="true"></i></button>
                                    <button class="btn btn-danger btn-sm login_delete" type="button"><i class="fa fa-trash fa-lg" aria-hidden="true"></i></button>
                                </div>
                            </td>
                        @endif
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
    <script>
        $('.label-success').click(function(){
            var id = $(this).attr("id");
            $.ajax({
                type: 'POST',
                url: '{{ route('switchLogin') }}',
                data: {'id':id,'active':0},
                headers: {
                    'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(res){
                    //alert(res);
                    if(res=='OK'){
                        $('#'+id).removeClass('label-success').addClass('label-danger');
                        $('#'+id).text('Не активен');
                    }
                    else
                        alert('Ошибка операции.');
                }
            });
        });

        $('.label-danger').click(function(){
            var id = $(this).attr("id");
            $.ajax({
                type: 'POST',
                url: '{{ route('switchLogin') }}',
                data: {'id':id,'active':1},
                headers: {
                    'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(res){
                    //alert(res);
                    if(res=='OK'){
                        $('#'+id).removeClass('label-danger').addClass('label-success');
                        $('#'+id).text('Активен');
                    }
                    else
                        alert('Ошибка операции.');
                }
            });
        });

        $('.login_edit').click(function(){
            var id = $(this).attr("id");
            var status = $(this).parent().parent().prevAll().eq(0).text();
            var login = $(this).parent().parent().prevAll().eq(1).text();
            var email = $(this).parent().parent().prevAll().eq(2).text();
            var name = $(this).parent().parent().prevAll().eq(3).text();
            $("#active :contains("+status+")").attr("selected", "selected");
            $('#email').val(email);
            $('#login').val(login);
            $('#name').val(name);
            $('#login_id').val(id);
        });

    </script>
@endsection
