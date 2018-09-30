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
    <div class="modal fade" id="setPeriod" tabindex="-1" role="dialog" aria-labelledby="setPeriod" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i class="fa fa-times-circle fa-lg" aria-hidden="true"></i>
                    </button>
                    <h4 class="modal-title">Выбор периода</h4>
                </div>
                {!! Form::open(['url' => route('cash_docs_period'),'id'=>'set_period','class'=>'form-horizontal','method'=>'POST']) !!}
                <div class="modal-body">

                    <div class="form-group">
                        <label class="col-xs-3 control-label">Начало</label>
                        <div class="col-xs-8">
                            @if(empty($from))
                                {{ Form::date('from', \Carbon\Carbon::parse()->format('d-m-Y'),['class' => 'form-control']) }}
                            @else
                                {{ Form::date('from', \Carbon\Carbon::createFromFormat('Y-m-d', $from),['class' => 'form-control']) }}
                            @endif
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-xs-3 control-label">Конец</label>
                        <div class="col-xs-8">
                            @if(empty($to))
                                {{ Form::date('to', \Carbon\Carbon::parse()->format('d-m-Y'),['class' => 'form-control']) }}
                            @else
                                {{ Form::date('to', \Carbon\Carbon::createFromFormat('Y-m-d', $to),['class' => 'form-control']) }}
                            @endif
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Отмена</button>
                    {!! Form::submit('Установить',['class'=>'btn btn-primary']) !!}
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
    <div class="modal fade" id="importDocs" tabindex="-1" role="dialog" aria-labelledby="importDocs" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i class="fa fa-times-circle fa-lg" aria-hidden="true"></i>
                    </button>
                    <h4 class="modal-title">Загрузка данных</h4>
                </div>
                {!! Form::open(array('route' => 'importCashDoc','method'=>'POST','files'=>'true')) !!}
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
    @if($docs)
        <div class="x_content">
            @if(\App\Models\Role::granted('import_refs'))
                <div class="btn-group">
                    <a href="#">
                        <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#importDocs"><i class="fa fa-upload fa-fw"></i> Импорт</button>
                    </a>
                </div>
            @endif
            <div class="btn-group">
                <a href="{{route('cashDocAdd',['direction'=>'coming'])}}">
                    <button type="button" class="btn btn-default btn-sm"><i class="fa fa-plus green" aria-hidden="true"></i> Приход</button>
                </a>
                <a href="{{route('cashDocAdd',['direction'=>'expense'])}}">
                    <button type="button" class="btn btn-default btn-sm"><i class="fa fa-minus red" aria-hidden="true"></i> Расход</button>
                </a>
                <a href="#">
                    <button type="button" class="btn btn-default btn-sm" data-toggle="modal" data-target="#setPeriod"><i class="fa fa-search" aria-hidden="true"></i> Период</button>
                </a>
            </div>
        </div>

        <div class="modal fade" id="editDoc" tabindex="-1" role="dialog" aria-labelledby="editDoc" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <i class="fa fa-times-circle fa-lg" aria-hidden="true"></i>
                        </button>
                        <h4 class="modal-title">Редактирование записи</h4>
                    </div>
                    <div class="modal-body">
                        {!! Form::open(['url' => route('editCashDoc'),'id'=>'edit_doc','class'=>'form-horizontal','method'=>'POST']) !!}

                        <div class="form-group">
                            {!! Form::label('doc_num', 'Документ №:',['class'=>'col-xs-2 control-label']) !!}
                            <div class="col-xs-8">
                                {!! Form::text('doc_num', old('doc_num'), ['class' => 'form-control','required'=>'required','id'=>'doc_num'])!!}
                            </div>
                        </div>

                        <div class="form-group">
                            {!! Form::label('operation_id', 'Вид операции:',['class'=>'col-xs-2 control-label']) !!}
                            <div class="col-xs-8">
                                {!! Form::select('operation_id', $opersel, old('operation_id'),['class' => 'form-control','required'=>'required','id'=>'operation_id']); !!}
                            </div>
                        </div>

                        <div class="form-group">
                            {!! Form::label('buhcode_id', 'Счет учета:',['class'=>'col-xs-2 control-label']) !!}
                            <div class="col-xs-8">
                                {!! Form::select('buhcode_id', $codesel, old('buhcode_id'),['class' => 'form-control','required'=>'required','id'=>'buhcode_id']); !!}
                            </div>
                        </div>

                        <div class="form-group">
                            {!! Form::label('amount','Сумма, руб.:',['class' => 'col-xs-2 control-label'])   !!}
                            <div class="col-xs-8">
                                {!! Form::text('amount',old('amount'),['class' => 'form-control','placeholder'=>'Введите сумму в рублях','required'=>'required','id'=>'amount'])!!}
                                {!! $errors->first('amount', '<p class="text-danger">:message</p>') !!}
                            </div>
                        </div>

                        <div class="form-group">
                            {!! Form::label('firm_id','Получатель\ Плательщик:',['class' => 'col-xs-2 control-label'])   !!}
                            <div class="col-xs-8">
                                {!! Form::text('firm_id',old('firm_id'),['class' => 'form-control','placeholder'=>'Введите полное наименование организации','required'=>'required','id'=>'search_firm'])!!}
                                {!! $errors->first('firm_id', '<p class="text-danger">:message</p>') !!}
                            </div>
                        </div>

                        <div class="form-group">
                            {!! Form::label('org_id', 'Организация:',['class'=>'col-xs-2 control-label']) !!}
                            <div class="col-xs-8">
                                {!! Form::select('org_id', $orgsel, old('org_id'),['class' => 'form-control','id'=>'organ_id','required'=>'required']); !!}
                            </div>
                        </div>

                        <div class="form-group">
                            {!! Form::label('contract','Договор:',['class' => 'col-xs-2 control-label'])   !!}
                            <div class="col-xs-8">
                                {!! Form::text('contract',old('contract'),['class' => 'form-control','placeholder'=>'Укажите договор','id'=>'contract'])!!}
                                {!! $errors->first('contract', '<p class="text-danger">:message</p>') !!}
                            </div>
                        </div>

                        <div class="form-group">
                            {!! Form::label('comment','Комментарий:',['class' => 'col-xs-2 control-label'])   !!}
                            <div class="col-xs-8">
                                {!! Form::text('comment',old('comment'),['class' => 'form-control','placeholder'=>'Комментарий','id'=>'comment'])!!}
                                {!! $errors->first('comment', '<p class="text-danger">:message</p>') !!}
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-xs-8">
                                {!! Form::hidden('id_doc','',['class' => 'form-control','id'=>'id_doc','required'=>'required']) !!}
                            </div>
                        </div>

                        {!! Form::close() !!}
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Отмена</button>
                        <button type="button" class="btn btn-primary" id="edit_btn">Сохранить</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="x_panel">
            <table id="datatable" class="table table-striped table-bordered">
                <thead>
                <tr>
                    <th>Дата</th>
                    <th>Номер</th>
                    <th>Приход</th>
                    <th>Расход</th>
                    <th>Валюта</th>
                    <th>Получатель\Плательщик</th>
                    <th>Вид операции</th>
                    <th>Организация</th>
                    <th>Ответственный</th>
                    <th>Комментарий</th>
                    <th>Действия</th>
                </tr>
                </thead>
                <tbody>
                @foreach($docs as $k => $doc)

                    <tr id="{{ $doc->id }}">
                        <td>{{ $doc->created_at }}</td>
                        <th class="edit">{{ $doc->doc_num }}</th>
                        @if($doc->direction == 'coming')
                            <td>{{ $doc->amount }}</td>
                            <td></td>
                        @else
                            <td></td>
                            <td>{{ $doc->amount }}</td>
                        @endif
                        <td>руб.</td>
                        <td>{{ \App\Models\Firm::find($doc->firm_id)->name }}</td>
                        <td>{{ \App\Models\Operation::find($doc->operation_id)->name }}</td>
                        <td>{{ \App\Models\Organisation::find($doc->org_id)->name }}</td>
                        <td>{{ \App\User::find($doc->user_id)->name }}</td>
                        <td>{{ $doc->comment }}</td>
                        <td style="width:110px;">
                            <div class="form-group" role="group">
                                <button class="btn btn-success btn-sm doc_edit" type="button" data-toggle="modal" data-target="#editDoc" title="Редактировать документ"><i class="fa fa-edit fa-lg" aria-hidden="true"></i></button>
                                <button class="btn btn-danger btn-sm doc_delete" type="button" title="Удалить документ"><i class="fa fa-trash fa-lg" aria-hidden="true"></i></button>
                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>

            @endif
        </div>
        <!-- /page content -->
@endsection

@section('user_script')
    <script src="/js/jquery.dataTables.min.js"></script>
    <script src="/js/typeahead.min.js"></script>
    <!--<script src="/js/moment.min.js"></script>
    <script src="/js/bootstrap-datetimepicker.min.js"></script>
    <script src="/js/ru.js"></script> -->
    <script>
        $(document).ready(function(){
            var options = {
                'backdrop' : 'true',
                'keyboard' : 'true'
            }
            $('#basicModal').modal(options);
        });
        var url = "{{ route('getOrg') }}";
        $('#search_firm').typeahead({
            source:  function (query, process) {
                return $.get(url, { query: query }, function (data) {
                    return process(data);
                });
            }
        });

        /*$('#from').datetimepicker({
            format: 'YYYY-MM-DD',
            locale: 'ru'
        });
        $('#to').datetimepicker({
            format: 'YYYY-MM-DD',
            locale: 'ru'
        }); */

        $('.doc_edit').click(function(){
            var id = $(this).parent().parent().parent().attr("id");
            var comment  = $(this).parent().parent().prev().text();
            //var cdate = $(this).parent().parent().prevAll().eq(1).text();
            var org = $(this).parent().parent().prevAll().eq(2).text();
            var operation = $(this).parent().parent().prevAll().eq(3).text();
            var firm = $(this).parent().parent().prevAll().eq(4).text();
            var doc_num = $(this).parent().parent().prevAll().eq(9).text();
            var amount = $(this).parent().parent().prevAll().eq(6).text();
            if(amount.length == 0)
                amount = $(this).parent().parent().prevAll().eq(7).text();
            var doc_num = $(this).parent().parent().prevAll().eq(8).text();

            $("#operation_id :contains("+operation+")").attr("selected", "selected");
            $('#comment').val(comment);
            $('#search_firm').val(firm);
            $('#doc_num').val(doc_num);
            $("#organ_id :contains("+org+")").attr("selected", "selected");
            $('#id_doc').val(id);
            $('#amount').val(amount);
        });

        $('#edit_btn').click(function(e){
            e.preventDefault();
            var error=0;
            $("#edit_doc").find(":input").each(function() {// проверяем каждое поле ввода в форме
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
                    url: '{{ route('editCashDoc') }}',
                    data: $('#edit_doc').serialize(),
                    success: function(res){
                        //alert(res);
                        if(res=='OK')
                            window.location.replace('/cash_docs');
                        if(res=='NO')
                            alert('Выполнение операции запрещено!');
                    }
                });
            }
        });

        $('.doc_delete').click(function(){
            var id = $(this).parent().parent().parent().attr("id");
            var x = confirm("Выбранная запись будет удалена. Продолжить (Да/Нет)?");
            if (x) {
                $.ajax({
                    type: 'POST',
                    url: '{{ route('delCashDoc') }}',
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
                    }
                });
            }
            else {
                return false;
            }
        });

    </script>
@endsection
