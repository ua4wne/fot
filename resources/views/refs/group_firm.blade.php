@extends('layouts.main')

@section('tile_widget')

@endsection

@section('content')
    <!-- START BREADCRUMB -->
    <ul class="breadcrumb">
        <li><a href="{{ route('main') }}">Рабочий стол</a></li>
        <li><a href="{{ route('groups') }}">Группы контрагентов</a></li>
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
        <div class="modal fade" id="editFirm" tabindex="-1" role="dialog" aria-labelledby="editFirm" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <i class="fa fa-times-circle fa-lg" aria-hidden="true"></i>
                        </button>
                        <h4 class="modal-title">Редактирование</h4>
                    </div>
                    <div class="modal-body">
                        {!! Form::open(['url' => route('editFirm'),'id'=>'edit_firm','class'=>'form-horizontal','method'=>'POST']) !!}

                        <div class="form-group">
                            <div class="col-xs-8">
                                {!! Form::hidden('id','',['class' => 'form-control','required'=>'required','id'=>'firm_id']) !!}
                            </div>
                        </div>

                        <div class="form-group">
                            {!! Form::label('type', 'Вид контрагента:',['class'=>'col-xs-3 control-label']) !!}
                            <div class="col-xs-8">
                                {!! Form::select('type', array('physical' => 'Физическое лицо', 'legal_entity' => 'Юридическое лицо'), 'legal_entity',['class' => 'form-control','id'=>'type']); !!}
                            </div>
                        </div>

                        <div class="form-group">
                            {!! Form::label('name','Название:',['class' => 'col-xs-3 control-label'])   !!}
                            <div class="col-xs-8">
                                {!! Form::text('name',old('name'),['class' => 'form-control','placeholder'=>'Введите наименование организации','required','id'=>'name'])!!}
                            </div>
                        </div>

                        <div class="form-group">
                            {!! Form::label('full_name','Наименование:',['class' => 'col-xs-3 control-label'])   !!}
                            <div class="col-xs-8">
                                {!! Form::text('full_name',old('full_name'),['class' => 'form-control','placeholder'=>'Введите полное наименование организации','id'=>'fname'])!!}
                            </div>
                        </div>

                        <div class="form-group">
                            {!! Form::label('group_id', 'Входит в группу:',['class'=>'col-xs-3 control-label']) !!}
                            <div class="col-xs-8">
                                {!! Form::select('group_id', $grpsel, old('group_id'),['class' => 'form-control','id'=>'group_id']); !!}
                            </div>
                        </div>

                        <div class="form-group">
                            {!! Form::label('inn','ИНН:',['class' => 'col-xs-3 control-label'])   !!}
                            <div class="col-xs-8">
                                {!! Form::text('inn',old('inn'),['class' => 'form-control','placeholder'=>'Введите ИНН','id'=>'inn'])!!}
                            </div>
                        </div>

                        <div class="form-group">
                            {!! Form::label('kpp','КПП:',['class' => 'col-xs-3 control-label'])   !!}
                            <div class="col-xs-8">
                                {!! Form::text('kpp',old('kpp'),['class' => 'form-control','placeholder'=>'Введите КПП','id'=>'kpp'])!!}
                            </div>
                        </div>

                        {!! Form::close() !!}
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Отмена</button>
                        <button type="button" class="btn btn-primary" id="frm_edit">Сохранить</button>
                    </div>
                </div>
            </div>
        </div>
        <h2 class="text-center">{{ $head }}</h2>
        @if($firms)
            <div class="x_content">
                <div class="btn-group">
                    <a href="{{route('groupFirmAdd',['id'=>$id])}}">
                        <button type="button" class="btn btn-primary btn-sm">Новый контрагент</button>
                    </a>
                </div>
            </div>
    </div>

    <div class="x_panel">
        <table id="datatable" class="table table-striped table-bordered">
            <thead>
            <tr>
                <th>Название</th>
                <th>Полное наименование</th>
                <th>Входит в группу</th>
                <th>ИНН</th>
                <th>КПП</th>
                <th>Основной счет</th>
                <th>Действия</th>
            </tr>
            </thead>
            <tbody>
            @foreach($firms as $k => $firm)

                <tr id="{{ $firm->id }}" class="{{ $firm->type }}">
                    <th>{{ $firm->name}}</th>
                    <td>{{ $firm->full_name }}</td>
                    @if(empty($firm->group_id))
                        <td></td>
                    @else
                        <td>{{ \App\Models\Group::find($firm->group_id)->name }}</td>
                    @endif
                    <td>{{ $firm->inn }}</td>
                    <td>{{ $firm->kpp }}</td>
                    @if(empty($firm->acc_id))
                        <td></td>
                    @else
                        <td>{{ \App\Models\BankAccount::find($firm->acc_id)->name }}</td>
                    @endif
                    <td style="width:110px;">
                        <div class="form-group" role="group">
                            <button class="btn btn-success btn-sm firm_edit" type="button" data-toggle="modal" data-target="#editFirm"><i class="fa fa-edit fa-lg" aria-hidden="true"></i></button>
                            <button class="btn btn-danger btn-sm firm_delete" type="button"><i class="fa fa-trash fa-lg" aria-hidden="true"></i></button>
                        </div>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>

        @endif
    </div>
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

        $('#frm_edit').click(function(e){
            e.preventDefault();
            var error=0;
            $("#edit_firm").find(":input").each(function() {// проверяем каждое поле ввода в форме
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
                    url: '{{ route('editFirm') }}',
                    data: $('#edit_firm').serialize(),
                    success: function(res){
                        //alert(res);
                        if(res=='ERR')
                            alert('Ошибка записи данных.');
                        if(res=='NO')
                            alert('Выполнение операции запрещено!');
                        else{
                            var obj = jQuery.parseJSON(res);
                            var id = obj.id;
                            var name = obj.name;
                            var fname = obj.fname;
                            var group = obj.group;
                            var inn = obj.inn;
                            var kpp = obj.kpp;
                            $("#"+id).children('th').text(name);
                            $("#"+id).children('td').first().text(fname);
                            $("#"+id).children('td').first().next().text(group);
                            $("#"+id).children('td').first().next().next().text(inn);
                            $("#"+id).children('td').first().next().next().next().text(kpp);
                        }
                    }
                });
            }
        });

        $('.firm_edit').click(function(){
            var id = $(this).parent().parent().parent().attr("id");
            var vid  = $(this).parent().parent().parent().attr("class");
            var kpp = $(this).parent().parent().prevAll().eq(1).text();
            var inn = $(this).parent().parent().prevAll().eq(2).text();
            var group = $(this).parent().parent().prevAll().eq(3).text();
            var fname = $(this).parent().parent().prevAll().eq(4).text();
            var name = $(this).parent().parent().prevAll().eq(5).text();
            $("#group_id :contains("+group+")").attr("selected", "selected");
            $('#kpp').val(kpp);
            $('#inn').val(inn);
            if(vid.indexOf('physical') > -1)
                $("#type :contains('Физическое лицо')").attr("selected", "selected");
            else
                $("#type :contains('Юридическое лицо')").attr("selected", "selected");
            $('#fname').val(fname);
            $('#name').val(name);
            $('#firm_id').val(id);
        });

        $('.firm_delete').click(function(){
            var id = $(this).parent().parent().parent().attr("id");
            var x = confirm("Выбранная запись будет удалена. Продолжить (Да/Нет)?");
            if (x) {
                $.ajax({
                    type: 'POST',
                    url: '{{ route('deleteFirm') }}',
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
