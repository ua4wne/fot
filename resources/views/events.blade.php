@extends('layouts.main')

@section('tile_widget')

@endsection

@section('content')
    <!-- START BREADCRUMB -->
    <ul class="breadcrumb">
        <li><a href="{{ route('main') }}">Рабочий стол</a></li>
        <li class="active"><a href="{{ route('eventlog') }}">{{ $title }}</a></li>
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
                    {!! Form::open(['url' => route('eventlog'),'class'=>'form-horizontal','method'=>'POST']) !!}
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

        <h2 class="text-center">{{ $head }}</h2>
        @if($count)
        <div class="x_content">
            @if(\App\Models\Role::granted('admin'))
                {!! Form::open(['url'=>route('eventEdit',['id'=>'all']), 'class'=>'form-horizontal','method' => 'POST', 'onsubmit' => 'return confirmDelete()']) !!}
                {{ method_field('DELETE') }}
                <div class="form-group" role="group">
                    {!! Form::button('Очистить журнал',['class'=>'btn btn-danger btn-sm','type'=>'submit']) !!}
                </div>
                {!! Form::close() !!}
            @endif
        </div>
        @endif
    </div>

    <div class="x_panel">
        @if($events)
        <table id="datatable" class="table table-striped table-bordered">
            <thead>
            <tr>
                <th>Дата</th>
                <th>Событие</th>
                <th>Инициатор</th>
                <th>IP-адрес</th>
                <th>Тип события</th>
                <th>Действия</th>
            </tr>
            </thead>
            <tbody>
            @foreach($events as $k => $event)

                <tr id="{{ $event->id }}" class="eventlog">
                    <td>{{ $event->created_at }}</td>
                    <td>{{ $event->text }}</td>
                    <td>{{ $event->users->name }}</td>
                    <td>{{ $event->ip }}</td>
                    <td>{{ $event->type }}</td>
                    <td style="width:70px;">
                        {!! Form::open(['url'=>route('eventEdit',['id'=>$event->id]), 'class'=>'form-horizontal','method' => 'POST', 'onsubmit' => 'return confirmDelete()']) !!}
                        {{ method_field('DELETE') }}
                        <div class="form-group" role="group">
                            {!! Form::button('<i class="fa fa-trash-o fa-lg>" aria-hidden="true"></i>',['class'=>'btn btn-danger','type'=>'submit','title'=>'Удалить запись']) !!}
                        </div>
                        {!! Form::close() !!}
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

        $(".form-horizontal").submit(function (event) {
            var x = confirm("Все записи будут удалены, журнал событий будет очищен! Продолжить (Да/Нет)?");
            if (x) {
                return true;
            }
            else {

                event.preventDefault();
                return false;
            }

        });
    </script>

@endsection
