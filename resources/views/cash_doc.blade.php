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

    <h2 class="text-center">{{ $head }}</h2>
    @if($docs)
        <div class="x_content">
            @if(\App\Models\Role::granted('import_refs'))
                <div class="btn-group">
                    <a href="#">
                        <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#importFirm"><i class="fa fa-upload fa-fw"></i> Импорт</button>
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
                </tr>
                </thead>
                <tbody>
                @foreach($docs as $k => $doc)

                    <tr id="{{ $doc->id }}">
                        <th>{{ $doc->created_at }}</th>
                        <th>{{ $doc->doc_num }}</th>
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
                        <td>{{ \App\Models\Firm::find($doc->firm_id)->name }}</td>
                        <td>{{ \App\User::find($doc->user_id)->name }}</td>
                        <td>{{ $doc->comment }}</td>
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


    </script>
@endsection
