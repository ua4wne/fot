@extends('layouts.main')

@section('tile_widget')

@endsection

@section('content')
    <!-- START BREADCRUMB -->
    <ul class="breadcrumb">
        <li><a href="{{ route('main') }}">Рабочий стол</a></li>
        <li><a href="{{ route('statements') }}">Банковские выписки</a></li>
        <li class="active">{{ $title }}</li>
    </ul>
    <!-- END BREADCRUMB -->
    <!-- page content -->

    <div class="row">
        @if($direction == 'coming')
            <h2 class="text-center">Поступление на расчетный счет</h2>
        @else
            <h2 class="text-center">Списание с расчетного счета</h2>
        @endif
        {!! Form::open(['url' => route('statementAdd',['direction'=>$direction]),'class'=>'form-horizontal','method'=>'POST']) !!}

        <div class="form-group">
            {!! Form::label('type', 'Документ №:',['class'=>'col-xs-2 control-label']) !!}
            <div class="col-xs-8">
                {!! Form::text('doc_num', $doc_num,['class' => 'form-control','placeholder'=>'Введите номер документа'])!!}
                {!! $errors->first('doc_num', '<p class="text-danger">:message</p>') !!}
            </div>
        </div>

        <div class="form-group">
            {!! Form::label('created_at', 'Дата:',['class'=>'col-xs-2 control-label']) !!}
            <div class="col-xs-8">
                {{ Form::date('created_at', \Carbon\Carbon::createFromFormat('Y-m-d', date('Y-m-d')),['class' => 'form-control']) }}
            </div>
        </div>

        <div class="form-group">
            {!! Form::label('operation_id', 'Вид операции:',['class'=>'col-xs-2 control-label']) !!}
            <div class="col-xs-8">
                {!! Form::select('operation_id', $opersel, old('operation_id'),['class' => 'form-control','id'=>'operation_id']); !!}
            </div>
        </div>

        <div class="form-group">
            {!! Form::label('buhcode_id', 'Счет учета:',['class'=>'col-xs-2 control-label']) !!}
            <div class="col-xs-8">
                {!! Form::select('buhcode_id', $codesel, old('buhcode_id'),['class' => 'form-control', 'id'=>'buhcode_id']); !!}
            </div>
        </div>

        <div class="form-group">
            {!! Form::label('amount','Сумма, руб.:',['class' => 'col-xs-2 control-label'])   !!}
            <div class="col-xs-8">
                {!! Form::text('amount',old('amount'),['class' => 'form-control','placeholder'=>'Введите сумму в рублях'])!!}
                {!! $errors->first('amount', '<p class="text-danger">:message</p>') !!}
            </div>
        </div>

        <div class="form-group">
            {!! Form::label('org_id', 'Организация:',['class'=>'col-xs-2 control-label']) !!}
            <div class="col-xs-8">
                {!! Form::select('org_id', $orgsel, old('org_id'),['class' => 'form-control','id'=>'org_id']); !!}
            </div>
        </div>

        <div class="form-group">
            {!! Form::label('bacc_id', 'Банковский счет:',['class'=>'col-xs-2 control-label']) !!}
            <div class="col-xs-8">
                {!! Form::select('bacc_id', $bacc , old('bacc_id'),['class' => 'form-control', 'id'=>'bacc_id']); !!}
            </div>
        </div>

        <div class="form-group">
            {!! Form::label('firm_id','Получатель:',['class' => 'col-xs-2 control-label','id'=>'control-label'])   !!}
            <div class="col-xs-8">
                {!! Form::text('firm_id',old('firm_id'),['class' => 'form-control','placeholder'=>'Введите полное наименование организации','id'=>'search_firm'])!!}
                {!! $errors->first('firm_id', '<p class="text-danger">:message</p>') !!}
            </div>
        </div>

        <div class="form-group">
            {!! Form::label('contract', 'Договор:',['class'=>'col-xs-2 control-label']) !!}
            <div class="col-xs-8">
                {!! Form::select('contract', $bacc , old('contract'),['class' => 'form-control', 'id'=>'contract']); !!}
            </div>
        </div>

        <div class="form-group">
            {!! Form::label('purpose','Назначение платежа:',['class' => 'col-xs-2 control-label'])   !!}
            <div class="col-xs-8">
                {!! Form::textarea('purpose',old('purpose'),['class' => 'form-control','placeholder'=>'Назначение платежа','rows' => 2, 'cols' => 40, 'id' => 'purpose'])!!}
                {!! $errors->first('purpose', '<p class="text-danger">:message</p>') !!}
            </div>
        </div>

        <div class="form-group">
            {!! Form::label('comment','Комментарий:',['class' => 'col-xs-2 control-label'])   !!}
            <div class="col-xs-8">
                {!! Form::text('comment',old('comment'),['class' => 'form-control','placeholder'=>'Комментарий'])!!}
                {!! $errors->first('comment', '<p class="text-danger">:message</p>') !!}
            </div>
        </div>

        <div class="form-group">
            <div class="col-xs-offset-2 col-xs-10">
                {!! Form::button('Сохранить', ['class' => 'btn btn-primary','type'=>'submit']) !!}
            </div>
        </div>

        {!! Form::close() !!}

    </div>
    </div>
@endsection

@section('user_script')
    <script src="/js/typeahead.min.js"></script>
    <script>
        var url = "{{ route('getOrg') }}";
        $('#search_firm').typeahead({
            source: function (query, process) {
                return $.get(url, {query: query}, function (data) {
                    return process(data);
                });
            }
        });

        $("#org_id").prepend($('<option value="0">Выберите организацию</option>'));
        $("#org_id :first").attr("selected", "selected");
        $("#org_id :first").attr("disabled", "disabled");
        @if($direction == 'coming')
            $("#buhcode_id :contains('76.06')").attr("selected", "selected");
        @else
            $("#buhcode_id :contains('76.05')").attr("selected", "selected");
        @endif

        if ($('.text-center').text() == 'Списание с расчетного счета') {
            $("#operation_id :contains('Оплата поставщику')").attr("selected", "selected");
            $('#control-label').text('Получатель:');
        }
        else {
            $("#operation_id :contains('Оплата от покупателя')").attr("selected", "selected");
            $('#control-label').text('Плательщик:');
        }

        $('#org_id').on('change', function () {
            $("#bacc_id").empty(); //очищаем от старых значений
            var id = $("#org_id option:selected").val();
            $.ajax({
                type: 'POST',
                url: '{{ route('findBacc') }}',
                data: {'id': id},
                headers: {
                    'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (res) {
                    //alert(res);
                    $("#bacc_id").prepend($(res));
                }
            });
        });

        $("#search_firm").blur(function () {
            $("#contract").empty(); //очищаем от старых значений
            var firm = $("#search_firm").val();
            var org_id = $("#org_id option:selected").val();
            $.ajax({
                type: 'POST',
                url: '{{ route('findContract') }}',
                data: {'firm': firm, 'org_id': org_id},
                headers: {
                    'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (res) {
                    //alert(res);
                    $("#contract").prepend($(res));
                }
            });
        });

        $( "#contract" ).change(function() {
            var txt = $('#contract option:selected').text();
            $('#purpose').val(txt);
        });

    </script>
@endsection
