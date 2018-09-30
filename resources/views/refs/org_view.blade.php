@extends('layouts.main')

@section('tile_widget')

@endsection

@section('content')
    <!-- START BREADCRUMB -->
    <ul class="breadcrumb">
        <li><a href="{{ route('main') }}">Рабочий стол</a></li>
        <li><a href="{{ route('organizations') }}">Организации</a></li>
        <li class="active">{{ $org['name'] }}</li>
    </ul>
    <!-- END BREADCRUMB -->
    <!-- page content -->

    <div class="row">
        <h2 class="text-center">{{ $org['name'] }}</h2>
                <div class="x_content">
                    <div class="col-xs-2">
                        <!-- required for floating -->
                        <!-- Nav tabs -->
                        <ul class="nav nav-tabs tabs-left">
                            <li class="active"><a href="#division" data-toggle="tab">Подразделения</a>
                            </li>
                            <li><a href="#account" data-toggle="tab">Банковские счета</a>
                            </li>
                        </ul>
                    </div>

                    <div class="col-xs-10">
                        <!-- Tab panes -->
                        <div class="tab-content">
                            <div class="tab-pane active" id="division">
                                @if($divs)
                                    <a href="#">
                                        <button type="button" class="btn btn-link" data-toggle="modal" data-target="#newDivision">Новое подразделение</button>
                                    </a>
                                    <div class="modal fade" id="newDivision" tabindex="-1" role="dialog" aria-labelledby="newDivision" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <i class="fa fa-times-circle fa-lg" aria-hidden="true"></i>
                                                    </button>
                                                    <h4 class="modal-title">Новое подразделение</h4>
                                                </div>
                                                <div class="modal-body">
                                                    {!! Form::open(['url' => route('divisionAdd'),'id'=>'new_division','class'=>'form-horizontal','method'=>'POST']) !!}

                                                    <div class="form-group">
                                                        {!! Form::label('name','Название:',['class' => 'col-xs-2 control-label'])   !!}
                                                        <div class="col-xs-8">
                                                            {!! Form::text('name',old('name'),['class' => 'form-control','required','placeholder'=>'Введите название подразделения'])!!}
                                                        </div>
                                                    </div>

                                                    <div class="form-group">
                                                        <div class="col-xs-8">
                                                            {!! Form::hidden('org_id',$org->id,['class' => 'form-control','required']) !!}
                                                        </div>
                                                    </div>

                                                    {!! Form::close() !!}
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-default" data-dismiss="modal">Отмена</button>
                                                    <button type="button" class="btn btn-primary" id="add_division">Сохранить</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="modal fade" id="editDivision" tabindex="-1" role="dialog" aria-labelledby="editDivision" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <i class="fa fa-times-circle fa-lg" aria-hidden="true"></i>
                                                    </button>
                                                    <h4 class="modal-title">Редактирование записи</h4>
                                                </div>
                                                <div class="modal-body">
                                                    {!! Form::open(['url' => route('editDivision'),'id'=>'edit_division','class'=>'form-horizontal','method'=>'POST']) !!}

                                                    <div class="form-group">
                                                        {!! Form::label('name','Название:',['class' => 'col-xs-2 control-label'])   !!}
                                                        <div class="col-xs-8">
                                                            {!! Form::text('name','',['class' => 'form-control','id'=>'dvs_name','required'=>'required','placeholder'=>'Введите новое название подразделения'])!!}
                                                        </div>
                                                    </div>

                                                    <div class="form-group">
                                                        <div class="col-xs-8">
                                                            {!! Form::hidden('id_dvsn','',['class' => 'form-control','id'=>'id_dvsn','required'=>'required']) !!}
                                                        </div>
                                                    </div>

                                                    {!! Form::close() !!}
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-default" data-dismiss="modal">Отмена</button>
                                                    <button type="button" class="btn btn-primary" id="edit_dvs">Сохранить</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <table class="table table-bordered">
                                        <thead>
                                        <tr>
                                            <th>Название</th>
                                            <th>Действия</th>
                                        </tr>
                                        </thead>
                                        <tbody>

                                        @foreach($divs as $k => $item)

                                            <tr id="{{ $item->id }}">
                                                <td>{{ $item->name }}</td>
                                                <td style="width:110px;">
                                                    <div class="form-group" role="group">
                                                        <button class="btn btn-success btn-sm div_edit" type="button" data-toggle="modal" data-target="#editDivision" title="Редактировать запись"><i class="fa fa-edit fa-lg>" aria-hidden="true"></i></button>
                                                        <button class="btn btn-danger btn-sm div_delete" type="button" title="Удалить запись"><i class="fa fa-trash fa-lg>" aria-hidden="true"></i></button>
                                                    </div>
                                                </td>
                                            </tr>

                                        @endforeach
                                        </tbody>
                                    </table>
                                    {{--{{ $divs->links() }}--}}
                                @endif
                            </div>
                            <div class="tab-pane" id="account">
                                @if($divs)
                                    <a href="#">
                                        <button type="button" class="btn btn-link" data-toggle="modal" data-target="#newAccount">Новый счет</button>
                                    </a>
                                    <div class="modal fade" id="newAccount" tabindex="-1" role="dialog" aria-labelledby="newAccount" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <i class="fa fa-times-circle fa-lg" aria-hidden="true"></i>
                                                    </button>
                                                    <h4 class="modal-title">Новый счет</h4>
                                                </div>
                                                <div class="modal-body">
                                                    {!! Form::open(['url' => route('baccAdd'),'id'=>'new_account','class'=>'form-horizontal','method'=>'POST']) !!}

                                                    <div class="form-group">
                                                        {!! Form::label('bank_id', 'Банк:',['class'=>'col-xs-2 control-label']) !!}
                                                        <div class="col-xs-8">
                                                            {!! Form::select('bank_id',$banksel, old('bank_id'), ['class' => 'form-control','required'=>'required']); !!}
                                                        </div>
                                                    </div>

                                                    <div class="form-group">
                                                        {!! Form::label('account','Счет:',['class' => 'col-xs-2 control-label'])   !!}
                                                        <div class="col-xs-8">
                                                            {!! Form::text('account',old('account'),['class' => 'form-control','placeholder'=>'Введите банковский счет','required'=>'required'])!!}
                                                        </div>
                                                    </div>

                                                    <div class="form-group">
                                                        {!! Form::label('currency','Валюта:',['class' => 'col-xs-2 control-label'])   !!}
                                                        <div class="col-xs-8">
                                                            {!! Form::text('currency',old('currency'),['class' => 'form-control','placeholder'=>'Укажите валюту счета','required'=>'required'])!!}
                                                        </div>
                                                    </div>

                                                    <div class="form-group">
                                                        {!! Form::label('is_main', 'Основной счет:',['class'=>'col-xs-2 control-label']) !!}
                                                        <div class="col-xs-8">
                                                            {!! Form::select('is_main', array('1' => 'Да', '0' => 'Нет'), '0',['class' => 'form-control','required'=>'required']); !!}
                                                        </div>
                                                    </div>

                                                    <div class="form-group">
                                                        <label class="col-xs-2 control-label">Дата открытия</label>
                                                        <div class="col-xs-8">
                                                            {{ Form::date('date_open', \Carbon\Carbon::parse()->format('d-m-Y'),['class' => 'form-control']) }}
                                                        </div>
                                                    </div>

                                                    <div class="form-group">
                                                        <label class="col-xs-2 control-label">Дата закрытия</label>
                                                        <div class="col-xs-8">
                                                            {{ Form::date('date_close', \Carbon\Carbon::parse()->format('d-m-Y'),['class' => 'form-control']) }}
                                                        </div>
                                                    </div>

                                                    <div class="form-group">
                                                        <div class="col-xs-8">
                                                            {!! Form::hidden('org_id',$org->id,['class' => 'form-control','required']) !!}
                                                        </div>
                                                    </div>

                                                    {!! Form::close() !!}
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-default" data-dismiss="modal">Отмена</button>
                                                    <button type="button" class="btn btn-primary" id="add_acc">Сохранить</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="modal fade" id="editAccount" tabindex="-1" role="dialog" aria-labelledby="editAccount" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <i class="fa fa-times-circle fa-lg" aria-hidden="true"></i>
                                                    </button>
                                                    <h4 class="modal-title">Редактирование записи</h4>
                                                </div>
                                                <div class="modal-body">
                                                    {!! Form::open(['url' => route('editAccount'),'id'=>'edit_account','class'=>'form-horizontal','method'=>'POST']) !!}

                                                    <div class="form-group">
                                                        {!! Form::label('bank_id', 'Банк:',['class'=>'col-xs-2 control-label']) !!}
                                                        <div class="col-xs-8">
                                                            {!! Form::select('bank_id',$banksel, old('bank_id'), ['class' => 'form-control','required'=>'required','id'=>'idbank']); !!}
                                                        </div>
                                                    </div>

                                                    <div class="form-group">
                                                        {!! Form::label('account','Счет:',['class' => 'col-xs-2 control-label'])   !!}
                                                        <div class="col-xs-8">
                                                            {!! Form::text('account',old('account'),['class' => 'form-control','placeholder'=>'Введите банковский счет','required'=>'required','id'=>'acc_name'])!!}
                                                        </div>
                                                    </div>

                                                    <div class="form-group">
                                                        {!! Form::label('currency','Валюта:',['class' => 'col-xs-2 control-label'])   !!}
                                                        <div class="col-xs-8">
                                                            {!! Form::text('currency',old('currency'),['class' => 'form-control','placeholder'=>'Укажите валюту счета','required'=>'required','id'=>'curr'])!!}
                                                        </div>
                                                    </div>

                                                    <div class="form-group">
                                                        {!! Form::label('is_main', 'Основной счет:',['class'=>'col-xs-2 control-label']) !!}
                                                        <div class="col-xs-8">
                                                            {!! Form::select('is_main', array('1' => 'Да', '0' => 'Нет'), '0',['class' => 'form-control','required'=>'required','id'=>'ismain']); !!}
                                                        </div>
                                                    </div>

                                                    <div class="form-group">
                                                        <label class="col-xs-2 control-label">Дата открытия</label>
                                                        <div class="col-xs-8">
                                                            {{ Form::date('date_open', \Carbon\Carbon::parse()->format('d-m-Y'),['class' => 'form-control','id'=>'odate']) }}
                                                        </div>
                                                    </div>

                                                    <div class="form-group">
                                                        <label class="col-xs-2 control-label">Дата закрытия</label>
                                                        <div class="col-xs-8">
                                                            {{ Form::date('date_close', \Carbon\Carbon::parse()->format('d-m-Y'),['class' => 'form-control','id'=>'cdate']) }}
                                                        </div>
                                                    </div>

                                                    <div class="form-group">
                                                        <div class="col-xs-8">
                                                            {!! Form::hidden('id_acc','',['class' => 'form-control','id'=>'id_acc','required'=>'required']) !!}
                                                        </div>
                                                    </div>

                                                    {!! Form::close() !!}
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-default" data-dismiss="modal">Отмена</button>
                                                    <button type="button" class="btn btn-primary" id="edit_acc">Сохранить</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <table class="table table-bordered">
                                        <thead>
                                        <tr>
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

                                        @foreach($baccs as $k => $bacc)

                                            <tr id="{{ $bacc->id }}">
                                                <td>{{ \App\Models\Bank::find($bacc->bank_id)->name }}</td>
                                                <td>{{ $bacc->account }}</td>
                                                <td>{{ $bacc->currency }}</td>
                                                <td>{{ $bacc->date_open }}</td>
                                                <td>{{ $bacc->date_close }}</td>
                                                @if($bacc->is_main)
                                                    <td>Да</td>
                                                @else
                                                    <td>Нет</td>
                                                @endif
                                                <td style="width:110px;">
                                                    <div class="form-group" role="group">
                                                        <button class="btn btn-success btn-sm acc_edit" type="button" data-toggle="modal" data-target="#editAccount" title="Редактировать запись"><i class="fa fa-edit fa-lg>" aria-hidden="true"></i></button>
                                                        <button class="btn btn-danger btn-sm acc_delete" type="button" title="Удалить запись"><i class="fa fa-trash fa-lg>" aria-hidden="true"></i></button>
                                                    </div>
                                                </td>
                                            </tr>

                                        @endforeach
                                        </tbody>
                                    </table>
                                    {{--{{ $divs->links() }}--}}
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="clearfix"></div>

                </div>
            </div>
    </div>
@endsection

@section('user_script')
    <script>
        $(document).ready(function(){
            var options = {
                'backdrop' : 'true',
                'keyboard' : 'true'
            }
            $('#basicModal').modal(options);

            $('#add_division').click(function(e){
                e.preventDefault();
                var error=0;
                $("#new_division").find(":input").each(function() {// проверяем каждое поле ввода в форме
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
                        url: '{{ route('newDivision') }}',
                        data: $('#new_division').serialize(),
                        success: function(res){
                            //alert(res);
                            if(res=='OK')
                                window.location.replace('/organization/view/{{ $org->id }}');
                            if(res=='NO')
                                alert('Выполнение операции запрещено!');
                            else
                                alert('Ошибка записи данных.');
                        }
                    });
                }
            });

            $('#edit_dvs').click(function(e){
                e.preventDefault();
                var error=0;
                $("#edit_division").find(":input").each(function() {// проверяем каждое поле ввода в форме
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
                        url: '{{ route('editDivision') }}',
                        data: $('#edit_division').serialize(),
                        success: function(res){
                            //alert(res);
                            if(res=='OK')
                                window.location.replace('/organization/view/{{ $org->id }}');
                            if(res=='NO')
                                alert('Выполнение операции запрещено!');
                            else
                                alert('Ошибка записи данных.');
                        }
                    });
                }
            });

            $('.div_edit').click(function(){
                var id = $(this).parent().parent().parent().attr("id");
                var old = $(this).parent().parent().prev().text();
                $('#dvs_name').val(old);
                $('#id_dvsn').val(id);
            });

            $('.div_delete').click(function(){
                var id = $(this).parent().parent().parent().attr("id");
                var x = confirm("Выбранная запись будет удалена. Продолжить (Да/Нет)?");
                if (x) {
                    $.ajax({
                        type: 'POST',
                        url: '{{ route('deleteDivision') }}',
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
                            else
                                alert('Ошибка удаления данных.');
                        }
                    });
                }
                else {
                    return false;
                }
            });

            $('#add_acc').click(function(e){
                e.preventDefault();
                var error=0;
                $("#new_account").find(":input").each(function() {// проверяем каждое поле ввода в форме
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
                        url: '{{ route('newAccount') }}',
                        data: $('#new_account').serialize(),
                        success: function(res){
                            //alert(res);
                            if(res=='OK')
                                window.location.replace('/organization/view/{{ $org->id }}');
                            if(res=='NO')
                                alert('Выполнение операции запрещено!');
                            else
                                alert('Ошибка записи данных.');
                        }
                    });
                }
            });

            $('#edit_acc').click(function(e){
                e.preventDefault();
                var error=0;
                $("#edit_account").find(":input").each(function() {// проверяем каждое поле ввода в форме
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
                        url: '{{ route('editAccount') }}',
                        data: $('#edit_account').serialize(),
                        success: function(res){
                            //alert(res);
                            if(res=='OK')
                                window.location.replace('/organization/view/{{ $org->id }}');
                            if(res=='NO')
                                alert('Выполнение операции запрещено!');
                            else
                                alert('Ошибка записи данных.');
                        }
                    });
                }
            });

            $('.acc_edit').click(function(){
                var id = $(this).parent().parent().parent().attr("id");
                var ismain  = $(this).parent().parent().prev().text();
                var cdate = $(this).parent().parent().prevAll().eq(1).text();
                var odate = $(this).parent().parent().prevAll().eq(2).text();
                var curr = $(this).parent().parent().prevAll().eq(3).text();
                var acc = $(this).parent().parent().prevAll().eq(4).text();
                var bank = $(this).parent().parent().prevAll().eq(5).text();
                $("#idbank :contains("+bank+")").attr("selected", "selected");
                $('#acc_name').val(acc);
                $('#curr').val(curr);
                $("#ismain :contains("+ismain+")").attr("selected", "selected");
                $('#odate').val(odate);
                $('#cdate').val(cdate);
                $('#id_acc').val(id);
            });

            $('.acc_delete').click(function(){
                var id = $(this).parent().parent().parent().attr("id");
                var x = confirm("Выбранная запись будет удалена. Продолжить (Да/Нет)?");
                if (x) {
                    $.ajax({
                        type: 'POST',
                        url: '{{ route('deleteAccount') }}',
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
                            else
                                alert('Ошибка удаления данных.');
                        }
                    });
                }
                else {
                    return false;
                }
            });

        });
    </script>
@endsection