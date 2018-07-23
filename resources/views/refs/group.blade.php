@extends('layouts.main')

@section('tile_widget')

@endsection

@section('content')
    <!-- START BREADCRUMB -->
    <ul class="breadcrumb">
        <li><a href="{{ route('main') }}">Рабочий стол</a></li>
        <li><a href="{{ route('firms') }}">Контрагенты</a></li>
        <li class="active">Группы</li>
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
        @if($groups)
            <a href="{{route('groupAdd')}}">
                <button type="button" class="btn btn-primary btn-rounded">Новая группа</button>
            </a>

            <div class="x_content">
                <a href="#">
                    <button type="button" class="btn btn-link" data-toggle="modal" data-target="#newGroup">Новая подгруппа</button>
                </a>
                <div class="modal fade" id="newGroup" tabindex="-1" role="dialog" aria-labelledby="newGroup" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <i class="fa fa-times-circle fa-lg" aria-hidden="true"></i>
                                </button>
                                <h4 class="modal-title">Новая подгруппа</h4>
                            </div>
                            <div class="modal-body">
                                {!! Form::open(['url' => route('newGroup'),'id'=>'new_group','class'=>'form-horizontal','method'=>'POST']) !!}

                                <div class="form-group">
                                    {!! Form::label('name','Название:',['class' => 'col-xs-2 control-label'])   !!}
                                    <div class="col-xs-8">
                                        {!! Form::text('name',old('name'),['class' => 'form-control','required','placeholder'=>'Введите название подгруппы'])!!}
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="col-xs-8">
                                        {!! Form::hidden('parent_id','',['class' => 'form-control','required','id'=>'parent_id']) !!}
                                    </div>
                                </div>

                                {!! Form::close() !!}
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Отмена</button>
                                <button type="button" class="btn btn-primary" id="add_group">Сохранить</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal fade" id="editGroup" tabindex="-1" role="dialog" aria-labelledby="editGroup" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <i class="fa fa-times-circle fa-lg" aria-hidden="true"></i>
                                </button>
                                <h4 class="modal-title">Редактирование</h4>
                            </div>
                            <div class="modal-body">
                                {!! Form::open(['url' => route('editGroup'),'id'=>'edit_group','class'=>'form-horizontal','method'=>'POST']) !!}

                                <div class="form-group">
                                    {!! Form::label('name','Название:',['class' => 'col-xs-2 control-label'])   !!}
                                    <div class="col-xs-8">
                                        {!! Form::text('name',old('name'),['class' => 'form-control','required'=>'required','id'=>'gname'])!!}
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="col-xs-8">
                                        {!! Form::hidden('id','',['class' => 'form-control','required'=>'required','id'=>'child_id']) !!}
                                    </div>
                                </div>

                                {!! Form::close() !!}
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Отмена</button>
                                <button type="button" class="btn btn-primary" id="grp_edit">Сохранить</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xs-2">
                    <!-- required for floating -->
                    <!-- Nav tabs -->
                    <ul class="nav nav-tabs tabs-left">
                        @foreach($groups as $k => $group)
                            @if($k==0 && !$group->parent_id)
                                <li class="active"><a href="#group{{ $group->id }}" data-toggle="tab">{{ $group->name }}</a></li>
                            @elseif($k>0 && !$group->parent_id)
                                <li><a href="#group{{ $group->id }}" data-toggle="tab">{{ $group->name }}</a></li>
                            @endif
                        @endforeach
                    </ul>
                </div>
                <div class="col-xs-10">
                    <!-- Tab panes -->
                    <div class="tab-content">
                        @foreach($groups as $k => $group)
                            @if($k==0)
                                <div class="tab-pane active" id="group{{ $group->id }}">
                                @if($childs)
                                        <table class="table table-striped table-bordered">
                                            <thead>
                                            <tr>
                                                <th>Наименование</th>
                                                <th>Действия</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                    @foreach($childs as $child)
                                        @if($child->parent_id == $group->id)
                                        <tr id="{{ $child->id }}">
                                            <th>{!! Html::link(route('groupView',['id'=>$child->id]),$child->name,['alt'=>$child->name]) !!}</th>
                                            <td style="width:110px;">
                                                <div class="form-group" role="group">
                                                    <button class="btn btn-success btn-sm group_edit" type="button" data-toggle="modal" data-target="#editGroup"><i class="fa fa-edit fa-lg>" aria-hidden="true"></i></button>
                                                    <button class="btn btn-danger btn-sm group_delete" type="button"><i class="fa fa-trash fa-lg>" aria-hidden="true"></i></button>
                                                </div>
                                            </td>
                                        </tr>
                                        @endif
                                    @endforeach
                                            </tbody>
                                        </table>
                                @endif
                                </div>
                            @elseif($k>0)
                                <div class="tab-pane" id="group{{ $group->id }}">
                                    @if($childs)
                                        <table class="table table-striped table-bordered">
                                            <thead>
                                            <tr>
                                                <th>Наименование</th>
                                                <th>Действия</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($childs as $child)
                                                @if($child->parent_id == $group->id)
                                                    <tr id="{{ $child->id }}">
                                                        <th>{!! Html::link(route('groupView',['id'=>$child->id]),$child->name,['alt'=>$child->name]) !!}</th>
                                                        <td style="width:110px;">
                                                            <div class="form-group" role="group">
                                                                <button class="btn btn-success btn-sm group_edit" type="button" data-toggle="modal" data-target="#editGroup"><i class="fa fa-edit fa-lg>" aria-hidden="true"></i></button>
                                                                <button class="btn btn-danger btn-sm group_delete" type="button"><i class="fa fa-trash fa-lg>" aria-hidden="true"></i></button>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endif
                                            @endforeach
                                            </tbody>
                                        </table>
                                    @endif
                                </div>
                            @endif
                        @endforeach
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
    </div>
    <!-- /page content -->
@endsection

@section('user_script')
    {{--<script src="/js/jquery.dataTables.min.js"></script>--}}
    <script>
        $(document).ready(function(){
            var options = {
            'backdrop' : 'true',
            'keyboard' : 'true'
            }
            $('#basicModal').modal(options);
        });

        $('#grp_edit').click(function(e){
            e.preventDefault();
            var error=0;
            $("#edit_group").find(":input").each(function() {// проверяем каждое поле ввода в форме
                if($(this).attr("required")=='required'){ //обязательное для заполнения поле формы?
                    if(!$(this).val()){// если поле пустое
                        $(this).css('border', '1px solid red');// устанавливаем рамку красного цвета
                        error=1;// определяем индекс ошибки
                    }
                    else{
                        $(this).css('border', '1px solid green');// устанавливаем рамку зеленого цвета
                    }

                }
            })
            if(error){
                alert("Необходимо заполнять все доступные поля!");
                return false;
            }
            else{
                $.ajax({
                    type: 'POST',
                    url: '{{ route('editGroup') }}',
                    data: $('#edit_group').serialize(),
                    success: function(res){
                        //alert(res);
                        if(res=='ERR')
                            alert('Ошибка записи данных.');
                        else{
                            var obj = jQuery.parseJSON(res);
                            var id = obj.id;
                            var name = obj.name;
                            $("#"+id).children('th').text(name);
                        }
                    }
                });
            }
        });

        $('.group_edit').click(function(){
            var id = $(this).parent().parent().parent().attr("id");
            var old = $(this).parent().parent().prev().text();
            $('#gname').val(old);
            $('#child_id').val(id);
        });



    </script>
    @include('confirm')
@endsection
