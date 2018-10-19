@extends('layouts.main')

@section('tile_widget')

@endsection

@section('content')
    <!-- START BREADCRUMB -->
    <ul class="breadcrumb">
        <li><a href="{{ route('main') }}">Рабочий стол</a></li>
        <li><a href="{{ route('purchases') }}">Поступления</a></li>
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
                    {!! Form::open(['url' => route('addPurchasePos'),'id'=>'add_pos','class'=>'form-horizontal','method'=>'POST']) !!}

                    <div class="form-group">
                        {!! Form::label('nomenclature_id', 'Номенклатура:',['class'=>'col-xs-3 control-label']) !!}
                        <div class="col-xs-8">
                            {!! Form::select('nomenclature_id', $nomsel, old('nomenclature_id'), ['class' => 'form-control','id'=>'nomenclature'])!!}
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::label('qty','Количество:',['class' => 'col-xs-3 control-label'])   !!}
                        <div class="col-xs-8">
                            {!! Form::text('qty',old('qty'),['class' => 'form-control','placeholder'=>'Введите количество','required'=>'required','id'=>'qty'])!!}
                            {!! $errors->first('qty', '<p class="text-danger">:message</p>') !!}
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::label('price','Цена:',['class' => 'col-xs-3 control-label'])   !!}
                        <div class="col-xs-8">
                            {!! Form::text('price',old('price'),['class' => 'form-control','placeholder'=>'Укажите цену','required'=>'required','id'=>'price'])!!}
                            {!! $errors->first('price', '<p class="text-danger">:message</p>') !!}
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::label('buhcode_id', 'Счет учета:',['class'=>'col-xs-3 control-label']) !!}
                        <div class="col-xs-8">
                            {!! Form::select('buhcode_id', $codesel, old('buhcode_id'),['class' => 'form-control','required'=>'required','id'=>'buhcode_id']); !!}
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
        {!! Form::open(['url' => route('addPurchase'),'class'=>'form-horizontal','method'=>'POST', 'id'=>'new_doc']) !!}

        <div class="form-group">
            {!! Form::label('doc_num', 'Документ №:',['class'=>'col-xs-2 control-label']) !!}
            <div class="col-xs-8">
                {!! Form::text('doc_num', $doc_num, ['class' => 'form-control','placeholder'=>'Введите номер документа','required' => 'required', 'id' => 'doc_num'])!!}
                {!! $errors->first('doc_num', '<p class="text-danger">:message</p>') !!}
            </div>
        </div>

        <div class="form-group">
            {!! Form::label('org_id', 'Организация:',['class'=>'col-xs-2 control-label']) !!}
            <div class="col-xs-8">
                {!! Form::select('org_id', $orgsel, old('org_id'),['class' => 'form-control','required' => 'required', 'id' => 'org_id']); !!}
            </div>
        </div>

        <div class="form-group">
            {!! Form::label('buhcode', 'Расчеты:',['class'=>'col-xs-2 control-label']) !!}
            <div class="col-xs-8">
                {!! Form::select('buhcode', $codesel, old('buhcode'),['class' => 'form-control','required'=>'required','id'=>'buhcode']); !!}
            </div>
        </div>

        <div class="form-group">
            {!! Form::label('currency_id', 'Валюта:',['class'=>'col-xs-2 control-label']) !!}
            <div class="col-xs-8">
                {!! Form::select('currency_id', $currsel, old('currsel'),['class' => 'form-control','required'=>'required','id'=>'currency_id']); !!}
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
            {!! Form::label('contract','Договор:',['class' => 'col-xs-2 control-label'])   !!}
            <div class="col-xs-8">
                {!! Form::select('contract',array(),old('contract'),['class' => 'form-control','required'=>'required','id'=>'contract'])!!}
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
                <a href="{{route('purchases')}}">
                    <button type="button" class="btn btn-default btn-sm"><i class="fa fa-save" aria-hidden="true"></i> Сохранить</button>
                </a>
            </div>
        </div>
        <div class="x_panel">
            <table id="doc_table" class="table table-striped table-bordered">
                <thead>
                <tr>
                    <th>Номенклатура</th>
                    <th>Количество</th>
                    <th>Цена</th>
                    <th>Сумма</th>
                    <th>Счета учета</th>
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
        $("#buhcode :contains('76.05')").attr("selected", "selected");
        $("#org_id").prepend($('<option value="0">Выберите организацию</option>'));
        $("#org_id :first").attr("selected", "selected");
        $("#org_id :first").attr("disabled", "disabled");
        $("#qty").val('1');

        var url_firm = "{{ route('getOrg') }}";
        $('#search_firm').typeahead({
            source:  function (query, process) {
                return $.get(url_firm, { query: query }, function (data) {
                    return process(data);
                });
            }
        });

        $('#new_pos').click(function(){
            $("#buhcode_id :contains('44.02')").attr("selected", "selected");
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
                    url: '{{ route('addPurchasePos') }}',
                    data: $('#add_pos').serialize(),
                    success: function (res) {
                        //alert(res);
                        if (res) {
                            $("#doc_table").append($(res));
                            $('#price').val('');
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
                    url: '{{ route('addPurchase') }}',
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
                    },
                    error: function (xhr, ajaxOptions, thrownError) {
                        alert(xhr.status);
                        alert(thrownError);
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
                        url: '{{ route('delPurchasePos') }}',
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
