@extends('layouts.main')

@section('tile_widget')

@endsection

@section('content')
    <!-- START BREADCRUMB -->
    <ul class="breadcrumb">
        <li><a href="{{ route('main') }}">Рабочий стол</a></li>
        <li><a href="{{ route('advances') }}">Авансовые отчеты</a></li>
        <li class="active">Новая запись</li>
    </ul>
    <!-- END BREADCRUMB -->
    <!-- page content -->
    <div class="modal fade" id="editDoc" tabindex="-1" role="dialog" aria-labelledby="editDoc" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i class="fa fa-times-circle fa-lg" aria-hidden="true"></i>
                    </button>
                    <h4 class="modal-title">Новая позиция</h4>
                </div>
                <div class="modal-body">
                    {!! Form::open(['url' => route('addAdvancePos'),'id'=>'add_pos','class'=>'form-horizontal','method'=>'POST']) !!}

                    <div class="form-group">
                        {!! Form::label('text', 'Документ расхода:',['class'=>'col-xs-2 control-label']) !!}
                        <div class="col-xs-8">
                            {!! Form::text('text', old('text'), ['class' => 'form-control','required'=>'required','id'=>'text'])!!}
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::label('firm_id','Контрагент:',['class' => 'col-xs-2 control-label'])   !!}
                        <div class="col-xs-8">
                            {!! Form::text('firm_id',old('firm_id'),['class' => 'form-control','placeholder'=>'Введите полное наименование организации','id'=>'search_firm'])!!}
                            {!! $errors->first('firm_id', '<p class="text-danger">:message</p>') !!}
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::label('contract_id', 'Договор:',['class'=>'col-xs-2 control-label']) !!}
                        <div class="col-xs-8">
                            {!! Form::select('contract_id', array(), old('contract_id'),['class' => 'form-control','id'=>'contract','required'=>'required']); !!}
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::label('comment','Содержание:',['class' => 'col-xs-2 control-label'])   !!}
                        <div class="col-xs-8">
                            {!! Form::text('comment',old('comment'),['class' => 'form-control','placeholder'=>'Комментарий','id'=>'comment'])!!}
                            {!! $errors->first('comment', '<p class="text-danger">:message</p>') !!}
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
                        {!! Form::label('buhcode_id', 'Счет учета:',['class'=>'col-xs-2 control-label']) !!}
                        <div class="col-xs-8">
                            {!! Form::select('buhcode_id', $codesel, old('buhcode_id'),['class' => 'form-control']); !!}
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
                    <button type="button" class="btn btn-primary" id="new_btn">Сохранить</button>
                </div>
            </div>
        </div>
    </div>

    <div class="row" id="doc_header">
        <h2 class="text-center">Новый документ</h2>
        {!! Form::open(['url' => route('addAdvance'),'class'=>'form-horizontal','method'=>'POST', 'id'=>'new_doc']) !!}

        <div class="form-group">
            {!! Form::label('doc_num', 'Документ №:',['class'=>'col-xs-2 control-label']) !!}
            <div class="col-xs-8">
                {!! Form::text('doc_num', $doc_num, ['class' => 'form-control','placeholder'=>'Введите номер документа','required' => 'required', 'id' => 'doc_num'])!!}
                {!! $errors->first('doc_num', '<p class="text-danger">:message</p>') !!}
            </div>
        </div>

        <div class="form-group">
            {!! Form::label('created_at', 'Дата:',['class'=>'col-xs-2 control-label']) !!}
            <div class="col-xs-8">
                {{ Form::date('created_at', \Carbon\Carbon::createFromFormat('Y-m-d', date('Y-m-d')),['class' => 'form-control','required'=>'required','id'=>'created_at']) }}
            </div>
        </div>

        <div class="form-group">
            {!! Form::label('person_id', 'Подотчетное лицо:',['class'=>'col-xs-2 control-label']) !!}
            <div class="col-xs-8">
                {!! Form::select('person_id', $persel, old('person_id'),['class' => 'form-control','required' => 'required', 'id' => 'person_id']); !!}
            </div>
        </div>

        <div class="form-group">
            {!! Form::label('currency_id', 'Валюта:',['class'=>'col-xs-2 control-label']) !!}
            <div class="col-xs-8">
                {!! Form::select('currency_id', $currsel, old('currency_id'),['class' => 'form-control','required' => 'required', 'id' => 'currency_id']); !!}
            </div>
        </div>

        <div class="form-group">
            {!! Form::label('org_id', 'Организация:',['class'=>'col-xs-2 control-label']) !!}
            <div class="col-xs-8">
                {!! Form::select('org_id', $orgsel, old('org_id'),['class' => 'form-control','required' => 'required', 'id' => 'org_id']); !!}
            </div>
        </div>

        <div class="form-group">
            {!! Form::label('comment', 'Комментарий:',['class'=>'col-xs-2 control-label']) !!}
            <div class="col-xs-8">
                {!! Form::text('comment', old('comment'),['class' => 'form-control','placeholder'=>'Введите комментарий', 'id' => 'comment'])!!}
                {!! $errors->first('comment', '<p class="text-danger">:message</p>') !!}
            </div>
        </div>

        <div class="form-group">
            <div class="col-xs-offset-2 col-xs-10">
                {!! Form::button('Сохранить', ['class' => 'btn btn-primary','type'=>'submit', 'id'=>'addBtn']) !!}
            </div>
        </div>

        {!! Form::close() !!}

        <div class="x_content">
            <div class="btn-group container">
                <a href="#">
                    <button type="button" class="btn btn-success btn-sm" id="new_pos" data-toggle="modal" data-target="#editDoc"><i class="fa fa-plus-circle success" aria-hidden="true"></i> Добавить</button>
                </a>
                <a href="{{route('advances')}}">
                    <button type="button" class="btn btn-default btn-sm"><i class="fa fa-save" aria-hidden="true"></i> Сохранить</button>
                </a>
            </div>
        </div>
            <div class="x_panel">
        <table id="doc_table" class="table table-striped table-bordered">
            <thead>
            <tr>
                <th>Документ расхода</th>
                <th>Контрагент</th>
                <th>Договор</th>
                <th>Содержание</th>
                <th>Сумма</th>
                <th>Счета расчетов</th>
                <th>Действия</th>
            </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
    </div>

    </div>
@endsection

@section('user_script')
    <script src="/js/typeahead.min.js"></script>
    <script>
        $('.x_panel').hide();
        $('.x_content').hide();
        $("#person_id").prepend($('<option value="0">Выберите подотчетное лицо</option>'));
        $("#person_id :first").attr("selected", "selected");
        $("#person_id :first").attr("disabled", "disabled");
        $("#org_id").prepend($('<option value="0">Выберите организацию</option>'));
        $("#org_id :first").attr("selected", "selected");
        $("#org_id :first").attr("disabled", "disabled");

        var url_firm = "{{ route('getOrg') }}";
        $('#search_firm').typeahead({
            source:  function (query, process) {
                return $.get(url_firm, { query: query }, function (data) {
                    return process(data);
                });
            }
        });

        $('#new_pos').click(function(){
            $("#buhcode_id :contains('76.09')").attr("selected", "selected");
        });

        $( "#search_firm" ).blur(function() {
            $("#contract").empty(); //очищаем от старых значений
            var firm = $("#search_firm").val();
            var org_id = $("#org_id option:selected").val();
            $.ajax({
                type: 'POST',
                url: '{{ route('findContract') }}',
                data: {'firm': firm,'org_id':org_id},
                headers: {
                    'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (res) {
                    //alert(res);
                    $("#contract").prepend($(res));
                }
            });
        });

        $('#new_btn').click(function(){
            //e.preventDefault();
            var error = 0;
            $("#add_pos").find(":input").each(function () {// проверяем каждое поле ввода в форме
                if ($(this).attr("required") == 'required') { //обязательное для заполнения поле формы?
                    if (!$(this).val()) {// если поле пустое
                        $(this).css('border', '1px solid red');// устанавливаем рамку красного цвета
                        error = 1;// определяем индекс ошибки
                    }
                    else {
                        $(this).css('border', '1px solid green');// устанавливаем рамку зеленого цвета
                    }

                }
            })
            if (error) {
                alert("Необходимо заполнять все доступные поля!");
                return false;
            }
            else {
                $.ajax({
                    type: 'POST',
                    url: '{{ route('addAdvancePos') }}',
                    data: $('#add_pos').serialize(),
                    success: function (res) {
                        //alert(res);
                        if (res) {
                            $("#doc_table").append($(res));
                            $('#amount').val('');
                        }
                    },
                    error: function (xhr, ajaxOptions, thrownError) {
                        alert(xhr.status);
                        alert(thrownError);
                    }
                });
            }
        });

        $('#new_doc').submit(function (e) {
            e.preventDefault();
            var error = 0;
            $("#new_doc").find(":input").each(function () {// проверяем каждое поле ввода в форме
                if ($(this).attr("required") == 'required') { //обязательное для заполнения поле формы?
                    if (!$(this).val()) {// если поле пустое
                        $(this).css('border', '1px solid red');// устанавливаем рамку красного цвета
                        error = 1;// определяем индекс ошибки
                    }
                    else {
                        $(this).css('border', '1px solid green');// устанавливаем рамку зеленого цвета
                    }

                }
            })
            if (error) {
                alert("Необходимо заполнять все доступные поля!");
                return false;
            }
            else {
                $.ajax({
                    type: 'POST',
                    url: '{{ route('addAdvance') }}',
                    data: $('#new_doc').serialize(),
                    success: function (res) {
                        //alert(res);
                        if (res) {
                            $('#addBtn').prop('disabled', true);
                            $('#addBtn').hide();
                            $('.x_panel').show();
                            $('.x_content').show();
                            $('#id_doc').val(res);
                        }
                    }
                });
            }
        });

        $(document).on ({
            click: function() {
                var id = $(this).parent().parent().parent().attr("id");
                var x = confirm("Выбранная запись будет удалена. Продолжить (Да/Нет)?");
                if (x) {
                    $.ajax({
                        type: 'POST',
                        url: '{{ route('delAdvancePos') }}',
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
            }
        }, ".pos_delete" );

    </script>
@endsection
