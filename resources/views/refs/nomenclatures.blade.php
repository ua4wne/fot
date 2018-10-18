@extends('layouts.main')

@section('tile_widget')

@endsection

@section('content')
    <!-- START BREADCRUMB -->
    <ul class="breadcrumb">
        <li><a href="{{ route('main') }}">Рабочий стол</a></li>
        <li class="active">{{ $title }}</li>
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
            <a href="#">
                <button type="button" class="btn btn-link" data-toggle="modal" data-target="#newGroup">Новая группа</button>
            </a>

            <div class="x_content">
                <div class="modal fade" id="newGroup" tabindex="-1" role="dialog" aria-labelledby="newGroup" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <i class="fa fa-times-circle fa-lg" aria-hidden="true"></i>
                                </button>
                                <h4 class="modal-title">Новая номенклатурная группа</h4>
                            </div>
                            <div class="modal-body">
                                {!! Form::open(['url' => route('groupNomenclatureAdd'),'id'=>'new_group','class'=>'form-horizontal','method'=>'POST']) !!}

                                <div class="form-group">
                                    {!! Form::label('name','Название:',['class' => 'col-xs-2 control-label'])   !!}
                                    <div class="col-xs-8">
                                        {!! Form::text('name',old('name'),['class' => 'form-control','required','placeholder'=>'Введите название группы','id'=>'new_name'])!!}
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

                <div class="modal fade" id="newNomenclature" tabindex="-1" role="dialog" aria-labelledby="newNomenclature" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <i class="fa fa-times-circle fa-lg" aria-hidden="true"></i>
                                </button>
                                <h4 class="modal-title">Новая номенклатура</h4>
                            </div>
                            <div class="modal-body">
                                {!! Form::open(['url' => route('nomenclatureAdd'),'id'=>'new_nomenclature','class'=>'form-horizontal','method'=>'POST']) !!}

                                <div class="form-group">
                                    <div class="col-xs-8">
                                        {!! Form::hidden('group_id','',['class' => 'form-control','required','id'=>'group_id'])!!}
                                    </div>
                                </div>

                                <div class="form-group">
                                    {!! Form::label('name','Название:',['class' => 'col-xs-3 control-label'])   !!}
                                    <div class="col-xs-8">
                                        {!! Form::text('name',old('name'),['class' => 'form-control','required','placeholder'=>'Введите название номенклатуры','id'=>'name'])!!}
                                    </div>
                                </div>

                                <div class="form-group">
                                    {!! Form::label('unit','Ед. измерения:',['class' => 'col-xs-3 control-label'])   !!}
                                    <div class="col-xs-8">
                                        {!! Form::text('unit','шт.',['class' => 'form-control','required','placeholder'=>'Введите ед измерения','id'=>'name'])!!}
                                    </div>
                                </div>

                                {!! Form::close() !!}
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Отмена</button>
                                <button type="button" class="btn btn-primary" id="add_new">Сохранить</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xs-2">
                    <!-- required for floating -->
                    <!-- Nav tabs -->
                    <ul class="nav nav-tabs tabs-left">
                        @foreach($groups as $k => $group)
                            @if($k==0)
                                <li class="active"><a href="#group{{ $group->id }}" data-toggle="tab">{{ $group->name }}</a></li>
                            @else
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
                                    <a href="#">
                                        <button type="button" class="btn btn-link" id="{{ $group->id }}" data-toggle="modal" data-target="#newNomenclature">Новая номенклатура</button>
                                    </a>
                                    @if($childs)
                                        <table class="table table-striped table-bordered" id="table{{ $group->id }}">
                                            <thead>
                                            <tr>
                                                <th>Наименование</th>
                                                <th>Ед. измерения</th>
                                                <th>Действия</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($childs as $child)
                                                @if($child->group_id == $group->id)
                                                    <tr id="{{ $child->id }}">
                                                        <td>{{ $child->name }}</td>
                                                        <td>{{ $child->unit }}</td>
                                                        <td style="width:70px;">
                                                            <div class="form-group" role="group">
                                                                <button class="btn btn-danger btn-sm btn_delete" type="button" title="Удалить запись"><i class="fa fa-trash fa-lg" aria-hidden="true"></i></button>
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
                                    <a href="#">
                                        <button type="button" class="btn btn-link" id="{{ $group->id }}" data-toggle="modal" data-target="#newNomenclature">Новая номенклатура</button>
                                    </a>
                                    @if($childs)
                                        <table class="table table-striped table-bordered" id="table{{ $group->id }}">
                                            <thead>
                                            <tr>
                                                <th>Наименование</th>
                                                <th>Ед. измерения</th>
                                                <th>Действия</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($childs as $child)
                                                @if($child->group_id == $group->id)
                                                    <tr id="{{ $child->id }}">
                                                        <td>{{ $child->name }}</td>
                                                        <td>{{ $child->unit }}</td>
                                                        <td style="width:70px;">
                                                            <div class="form-group" role="group">
                                                                <button class="btn btn-danger btn-sm btn_delete" type="button" title="Удалить запись"><i class="fa fa-trash fa-lg" aria-hidden="true"></i></button>
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

        $('#add_group').click(function(e){
            e.preventDefault();
            var error=0;
            $("#new_group").find(":input").each(function() {// проверяем каждое поле ввода в форме
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
                    url: '{{ route('groupNomenclatureAdd') }}',
                    data: $('#new_group').serialize(),
                    success: function(res){
                        //alert(res);
                        if(res=='ERR')
                            alert('Ошибка записи данных.');
                        if(res=='OK')
                            location.reload();
                        else
                            alert(res);
                    }
                });
            }
        });

        $('#add_new').click(function(e){
            e.preventDefault();
            var error=0;
            $("#new_nomenclature").find(":input").each(function() {// проверяем каждое поле ввода в форме
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
                    url: '{{ route('nomenclatureAdd') }}',
                    data: $('#new_nomenclature').serialize(),
                    success: function(res){
                        //alert(res);
                        if(res=='ERR')
                            alert('Ошибка записи данных.');
                        if(res=='NO')
                            alert('Ошибка валидации данных.');
                        else{
                            var id = $('#group_id').val();
                            $("#table"+id).append(res);
                        }
                    }
                });
            }
        });

        $('.btn-link').click(function(){
            var id = $(this).attr("id");
            $('#group_id').val(id);
        });

        $(document).on ({
            click: function() {
                var id = $(this).parent().parent().parent().attr("id");
                var x = confirm("Выбранная запись будет удалена. Продолжить (Да/Нет)?");
                if (x) {
                    $.ajax({
                        type: 'POST',
                        url: '{{ route('deleteNomenclature') }}',
                        data: {'id':id},
                        headers: {
                            'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(res){
                            //alert(res);
                            if(res=='OK')
                                $('#'+id).hide();
                            if(res=='NO')
                                alert('Выполнение операции запрещено!');
                            if(res!='OK'&&res!='NO')
                                alert('Ошибка удаления данных.');
                        }
                    });
                }
                else {
                    return false;
                }
            }
        }, ".btn_delete" );

    </script>
    @include('confirm')
@endsection
