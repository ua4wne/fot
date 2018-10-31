@extends('layouts.main')

@section('tile_widget')

@endsection

@section('content')
    <!-- START BREADCRUMB -->
    <ul class="breadcrumb">
        <li><a href="{{ route('main') }}">Рабочий стол</a></li>
        <li class="active"><a href="{{ route('advances') }}">{{ $title }}</a></li>
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
        <div class="modal fade" id="setPeriod" tabindex="-1" role="dialog" aria-labelledby="setPeriod" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <i class="fa fa-times-circle fa-lg" aria-hidden="true"></i>
                        </button>
                        <h4 class="modal-title">Выбор периода</h4>
                    </div>
                    {!! Form::open(['url' => route('advances'),'id'=>'set_period','class'=>'form-horizontal','method'=>'POST']) !!}
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
        <div class="modal fade" id="importAdvance" tabindex="-1" role="dialog" aria-labelledby="importAdvance" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <i class="fa fa-times-circle fa-lg" aria-hidden="true"></i>
                        </button>
                        <h4 class="modal-title">Загрузка данных</h4>
                    </div>
                    {!! Form::open(array('route' => 'importAdvances','method'=>'POST','files'=>'true')) !!}
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
                @if(\App\Models\Role::granted('finance'))
                    <div class="btn-group">
                        <a href="#">
                            <button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#importAdvance"><i class="fa fa-upload fa-fw"></i> Импорт</button>
                        </a>
                    </div>
                @endif
                <div class="btn-group">
                    <a href="{{route('advanceAdd')}}">
                        <button type="button" class="btn btn-primary btn-sm">Новый документ</button>
                    </a>
                </div>

                <div class="btn-group">
                    <a href="#">
                        <button type="button" class="btn btn-default btn-sm" data-toggle="modal" data-target="#setPeriod"><i class="fa fa-search" aria-hidden="true"></i> Период</button>
                    </a>
                </div>

            </div>
    </div>

    <div class="x_panel">
        <table id="datatable" class="table table-striped table-bordered">
            <thead>
            <tr>
                <th>Дата</th>
                <th>Номер</th>
                <th>Подотчетное лицо</th>
                <th>Сумма</th>
                <th>Валюта</th>
                <th>Организация</th>
                <th>Ответственный</th>
                <th>Комментарий</th>
                <th>Действия</th>
            </tr>
            </thead>
            <tbody>
            @foreach($docs as $k => $doc)

                <tr id="{{ $doc->id }}" class="advance">
                    <td>{{ $doc->created_at }}</td>
                    <td>{{ $doc->doc_num }}</td>
                    <td>{{ $doc->person->fio }}</td>
                    <td>{{ \App\Models\AdvanceTable::where(['advance_id'=>$doc->id])->sum('amount') }}</td>
                    <td>{{ $doc->currency->name }}</td>
                    <td>{{ $doc->organisation->name }}</td>
                    <td>{{ \App\User::find($doc->user_id)->name }}</td>
                    <td>{{ $doc->comment }}</td>
                    <td style="width:130px;">
                        <div class="form-group" role="group">
                            <a href="{{route('advanceClone',['id'=>$doc->id])}}"><button class="btn btn-info btn-sm" type="button" title="Клонировать документ"><i class="fa fa-clone" aria-hidden="true"></i></button></a>
                            <a href="{{route('advanceEdit',['id'=>$doc->id])}}"><button class="btn btn-success btn-sm" type="button" title="Редактировать документ"><i class="fa fa-edit" aria-hidden="true"></i></button></a>
                            <button class="btn btn-danger btn-sm doc_delete" type="button" title="Удалить документ"><i class="fa fa-trash" aria-hidden="true"></i></button>
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

        $('.doc_delete').click(function(){
            var id = $(this).parent().parent().parent().attr("id");
            var x = confirm("Выбранная запись будет удалена. Продолжить (Да/Нет)?");
            if (x) {
                $.ajax({
                    type: 'POST',
                    url: '{{ route('delAdvance') }}',
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
