@extends('layouts.main')

@section('tile_widget')

@endsection

@section('content')
    <!-- START BREADCRUMB -->
    <ul class="breadcrumb">
        <li><a href="{{ route('main') }}">Рабочий стол</a></li>
        <li><a href="{{ route('groups') }}">Группы контрагентов</a></li>
        <li><a href="{{ route('firms') }}">{{ $title }}</a></li>
        <li class="active">Список</li>
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
            @if(\App\Models\Role::granted('export_refs'))
                <div class="btn-group">
                    <a class="btn btn-success btn-sm" href="#"><i class="fa fa-upload fa-fw"></i> Экспорт</a>
                    <a class="btn btn-success dropdown-toggle btn-sm" data-toggle="dropdown" href="#">
                        <span class="fa fa-caret-down" title="Toggle dropdown menu"></span>
                    </a>
                    <ul class="dropdown-menu">
                        <li><a href="{{ route('exportFirm',['type'=>'xls']) }}"><i class="fa fa-file-excel-o fa-fw"></i> В файл XLS</a></li>
                        <li><a href="{{ route('exportFirm',['type'=>'xlsx']) }}"><i class="fa fa-file-excel-o fa-fw"></i> В файл XLSX</a></li>
                        <li><a href="{{ route('exportFirm',['type'=>'csv']) }}"><i class="fa fa-file-excel-o fa-fw"></i> В файл CSV</a></li>
                    </ul>
                </div>
            @endif
            <div class="btn-group">
                <a href="{{route('firmAdd')}}">
                    <button type="button" class="btn btn-primary btn-sm">Новый контрагент</button>
                </a>
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
                @foreach($docs as $k => $doc)

                    <tr id="{{ $doc->id }}" class="{{ $firm->type }}">
                        <th>{{ $doc->name }}</th>
                        <td>{{ $doc->full_name }}</td>
                        @if(empty($doc->group_id))
                            <td></td>
                        @else
                            <td>{{ \App\Models\Group::find($firm->group_id)->name }}</td>
                        @endif
                        <td>{{ $doc->inn }}</td>
                        <td>{{ $doc->kpp }}</td>
                        @if(empty($doc->acc_id))
                            <td></td>
                        @else
                            <td>{{ \App\Models\BankAccount::find($doc->acc_id)->name }}</td>
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
