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
        <div class="modal fade" id="editCode" tabindex="-1" role="dialog" aria-labelledby="editCode" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <i class="fa fa-times-circle fa-lg" aria-hidden="true"></i>
                        </button>
                        <h4 class="modal-title">Редактирование</h4>
                    </div>
                    <div class="modal-body">
                        {!! Form::open(['url' => route('editCode'),'id'=>'edit_code','class'=>'form-horizontal','method'=>'POST']) !!}

                        <div class="form-group">
                            <div class="col-xs-8">
                                {!! Form::hidden('id','',['class' => 'form-control','required'=>'required','id'=>'code_id']) !!}
                            </div>
                        </div>

                        <div class="form-group">
                            {!! Form::label('code','Код счета:',['class' => 'col-xs-3 control-label'])   !!}
                            <div class="col-xs-8">
                                {!! Form::text('code',old('code'),['class' => 'form-control','placeholder'=>'Введите код счета','id'=>'code','disabled'=>'disabled'])!!}
                            </div>
                        </div>

                        <div class="form-group">
                            {!! Form::label('text','Наименование счета:',['class' => 'col-xs-3 control-label'])   !!}
                            <div class="col-xs-8">
                                {!! Form::text('text',old('text'),['class' => 'form-control','placeholder'=>'Введите наименование счета','id'=>'text','required'=>'required'])!!}
                            </div>
                        </div>

                        <div class="form-group">
                            {!! Form::label('show', 'Видимость:',['class'=>'col-xs-3 control-label']) !!}
                            <div class="col-xs-8">
                                {!! Form::select('show', array('1' => 'Да', '0' => 'Нет'), old('show'),['class' => 'form-control','id'=>'show','required'=>'required']); !!}
                            </div>
                        </div>

                        {!! Form::close() !!}
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Отмена</button>
                        <button type="button" class="btn btn-primary" id="code_edit">Сохранить</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="importCode" tabindex="-1" role="dialog" aria-labelledby="importCode" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <i class="fa fa-times-circle fa-lg" aria-hidden="true"></i>
                        </button>
                        <h4 class="modal-title">Загрузка данных</h4>
                    </div>
                    {!! Form::open(array('route' => 'importCode','method'=>'POST','files'=>'true')) !!}
                    <div class="modal-body">

                        <div class="form-group">
                            {!! Form::label('file', 'Файл:',['class'=>'col-xs-2 control-label']) !!}
                            <div class="col-xs-8">
                                {!! Form::file('file', ['class' => 'filestyle','data-buttonText'=>'Выберите файл','data-buttonName'=>"btn-primary",'data-placeholder'=>"Файл не выбран"]) !!}
                                {!! $errors->first('file', '<p class="alert alert-danger">:message</p>') !!}
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Отмена</button>
                        {!! Form::submit('Загрузить',['class'=>'btn btn-primary']) !!}
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
        <h2 class="text-center">{{ $head }}</h2>
        @if($codes)
            <div class="x_content">
            @if(\App\Models\Role::granted('import_refs'))
                <div class="btn-group">
                    <a href="#">
                        <button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#importCode"><i class="fa fa-upload fa-fw"></i> Импорт</button>
                    </a>
                </div>
            @endif
                <div class="btn-group">
                    <a href="{{route('codeAdd')}}">
                        <button type="button" class="btn btn-primary btn-sm">Новая запись</button>
                    </a>
                </div>
            </div>
            <table id="datatable" class="table table-striped table-bordered">
                <thead>
                <tr>
                    <th>Код счета</th>
                    <th>Наименование счета</th>
                    <th>Видимость</th>
                    <th>Действия</th>
                </tr>
                </thead>
                <tbody>

                @foreach($codes as $code)

                    <tr id="{{ $code->id }}">
                        <td>{{ $code->code }}</td>
                        <td>{{ $code->text }}</td>
                        @if($code->show)
                            <td>Используется</td>
                        @else
                            <td>Не используется</td>
                        @endif
                        <td style="width:110px;">
                            <div class="form-group" role="group">
                                <button class="btn btn-success btn-sm code_edit" type="button" data-toggle="modal" data-target="#editCode"><i class="fa fa-edit fa-lg" aria-hidden="true"></i></button>
                                <button class="btn btn-danger btn-sm code_delete" type="button"><i class="fa fa-trash fa-lg" aria-hidden="true"></i></button>
                            </div>
                        </td>
                    </tr>

                @endforeach
                </tbody>
            </table>
        @endif
    </div>
    </div>
    <!-- /page content -->
@endsection

@section('user_script')
    <script src="/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function(){
            var options = {
                'backdrop' : 'true',
                'keyboard' : 'true'
            }
            $('#basicModal').modal(options);
        });

        $('#code_edit').click(function(e){
            e.preventDefault();
            var error=0;
            $("#edit_code").find(":input").each(function() {// проверяем каждое поле ввода в форме
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
                    url: '{{ route('editCode') }}',
                    data: $('#edit_code').serialize(),
                    success: function(res){
                        //alert(res);
                        if(res=='ERR')
                            alert('Ошибка записи данных.');
                        if(res=='NO')
                            alert('Выполнение операции запрещено!');
                        else{
                            var obj = jQuery.parseJSON(res);
                            var id = obj.id;
                            //var code = obj.code;
                            var text = obj.text;
                            var show = obj.show;
                            //$("#"+id).children('th').text(code);
                            //$("#"+id).children('td').first().text(text);
                            $("#"+id).children('td').first().next().text(text);
                            if(show==1)
                                $("#"+id).children('td').first().next().next().text('Используется');
                            else
                                $("#"+id).children('td').first().next().next().text('Не используется');
                        }
                    }
                });
            }
        });

        $('.code_edit').click(function(){
            var id = $(this).parent().parent().parent().attr("id");
            var code = $(this).parent().parent().prevAll().eq(2).text();
            var text = $(this).parent().parent().prevAll().eq(1).text();
            var show = $(this).parent().parent().prevAll().eq(0).text();
            //alert('show='+show);
            $('#code').val(code);
            $('#text').val(text);
            $('#code_id').val(id);
            if(show.indexOf('Не') > -1)
                $("#show :contains('Нет')").attr("selected", "selected");
            else
                $("#show :contains('Да')").attr("selected", "selected");
        });

        $('.code_delete').click(function(){
            var id = $(this).parent().parent().parent().attr("id");
            var x = confirm("Выбранная запись будет удалена. Продолжить (Да/Нет)?");
            if (x) {
                $.ajax({
                    type: 'POST',
                    url: '{{ route('deleteCode') }}',
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
        });

    </script>

@endsection
