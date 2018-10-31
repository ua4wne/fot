@extends('layouts.main')

@section('tile_widget')

@endsection

@section('content')
<!-- START BREADCRUMB -->
<ul class="breadcrumb">
    <li><a href="{{ route('main') }}">Рабочий стол</a></li>
    <li><a href="{{ route('advances') }}">Авансовые отчеты</a></li>
    <li class="active">{{ $title }}</li>
</ul>
<!-- END BREADCRUMB -->
<!-- page content -->

<div class="row" id="doc_header">
    <h2 class="text-center">{{ $header }}</h2>
    {!! Form::open(['url' => route('cloneAdvance'),'class'=>'form-horizontal','method'=>'POST', 'id'=>'new_doc']) !!}

    <div class="form-group">
        {!! Form::label('doc_num', 'Документ №:',['class'=>'col-xs-2 control-label']) !!}
        <div class="col-xs-8">
            {!! Form::text('doc_num', $model->doc_num, ['class' => 'form-control','placeholder'=>'Введите номер документа','required' => 'required', 'id' => 'doc_num'])!!}
            {!! $errors->first('doc_num', '<p class="text-danger">:message</p>') !!}
        </div>
    </div>

    <div class="form-group">
        {!! Form::label('created_at', 'Дата:',['class'=>'col-xs-2 control-label']) !!}
        <div class="col-xs-8">
            {{ Form::date('created_at', \Carbon\Carbon::createFromFormat('Y-m-d', $created_at),['class' => 'form-control','required'=>'required','id'=>'created_at']) }}
        </div>
    </div>

    <div class="form-group">
        {!! Form::label('person_id', 'Подотчетное лицо:',['class'=>'col-xs-2 control-label']) !!}
        <div class="col-xs-8">
            {!! Form::select('person_id', $persel, $model->person_id, ['class' => 'form-control','required' => 'required', 'id' => 'person_id']); !!}
        </div>
    </div>

    <div class="form-group">
        {!! Form::label('currency_id', 'Валюта:',['class'=>'col-xs-2 control-label']) !!}
        <div class="col-xs-8">
            {!! Form::select('currency_id', $currsel, $model->currency_id, ['class' => 'form-control','required' => 'required', 'id' => 'currency_id']); !!}
        </div>
    </div>

    <div class="form-group">
        {!! Form::label('org_id', 'Организация:',['class'=>'col-xs-2 control-label']) !!}
        <div class="col-xs-8">
            {!! Form::select('org_id', $orgsel, $model->org_id, ['class' => 'form-control','required' => 'required', 'id' => 'org_id']); !!}
        </div>
    </div>

    <div class="form-group">
        {!! Form::label('comment', 'Комментарий:',['class'=>'col-xs-2 control-label']) !!}
        <div class="col-xs-8">
            {!! Form::text('comment', $model->comment, ['class' => 'form-control','placeholder'=>'Введите комментарий', 'id' => 'comment'])!!}
            {!! $errors->first('comment', '<p class="text-danger">:message</p>') !!}
        </div>
    </div>

    <div class="form-group">
        <div class="col-xs-8">
            {!! Form::hidden('id',$model->id,['class' => 'form-control', 'required'=>'required']) !!}
        </div>
    </div>

    <div class="form-group">
        <div class="col-xs-offset-2 col-xs-10">
            {!! Form::button('Сохранить', ['class' => 'btn btn-primary','type'=>'submit', 'id'=>'addBtn']) !!}
        </div>
    </div>

    {!! Form::close() !!}

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
            <tbody>
            @if($positions)
            @foreach($positions as $position)
            <tr id="{{ $position->id }}" class="advance_pos">
                <td>{{ $position->text }}</td>
                <td>{{ $position->firm->name }}</td>
                <td>{{ $position->contract->name }}</td>
                <td>{{ $position->comment }}</td>
                <td>{{ $position->amount }}</td>
                <td>{{ $position->buhcode->code }}</td>
                <td style="width:70px;">
                    <div class="form-group" role="group">
                        <button class="btn btn-danger btn-sm pos_delete" type="button" title="Удалить позицию" disabled="disabled"><i class="fa fa-trash fa-lg" aria-hidden="true"></i></button>
                    </div>
                </td>
            </tr>
            @endforeach
            @endif
            </tbody>
        </table>
    </div>
</div>

</div>
@endsection

@section('user_script')
<script src="/js/typeahead.min.js"></script>
<script>
    var url = "{{ route('getOrg') }}";
    $('#search_firm').typeahead({
        source:  function (query, process) {
            return $.get(url, { query: query }, function (data) {
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
                url: '{{ route('cloneAdvance') }}',
                data: $('#new_doc').serialize(),
                success: function (res) {
                    //alert(res);
                    if (res) {
                        //$('#addBtn').prop('disabled', true);
                        //$('#addBtn').hide();
                        window.location.replace('/advances/edit/'+res);
                    }
                }
            });
        }
    });

</script>
@endsection
