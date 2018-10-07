@extends('layouts.main')

@section('tile_widget')

@endsection

@section('content')
    <!-- START BREADCRUMB -->
    <ul class="breadcrumb">
        <li><a href="{{ route('main') }}">Рабочий стол</a></li>
        <li><a href="{{ route('groups') }}">Группы контрагентов</a></li>
        <li><a href="{{ route('firms') }}">Контрагенты</a></li>
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
    <div class="row">
        <div class="modal fade" id="importContract" tabindex="-1" role="dialog" aria-labelledby="importContract" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <i class="fa fa-times-circle fa-lg" aria-hidden="true"></i>
                        </button>
                        <h4 class="modal-title">Загрузка данных</h4>
                    </div>
                    {!! Form::open(array('route' => 'importContract','method'=>'POST','files'=>'true')) !!}
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
        @if($contracts)
            <div class="x_content">
                @if(\App\Models\Role::granted('import_sale_doc'))
                <div class="btn-group">
                    <a href="#">
                        <button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#importContract"><i class="fa fa-upload fa-fw"></i> Импорт</button>
                    </a>
                </div>
                @endif
                <div class="btn-group">
                    <a href="{{route('contractAdd')}}">
                        <button type="button" class="btn btn-primary btn-sm">Новый договор</button>
                    </a>
                </div>

                <div class="col-md-5 col-sm-5 col-xs-12 form-group pull-right top_search">
                    {!! Form::open(['url' => route('contractView'),'method'=>'POST']) !!}
                       <div class="input-group">
                           {!! Form::text('org_id',$name,['class' => 'form-control','placeholder'=>'Введите название организации'])!!}
                           <span class="input-group-btn">
                               {!! Form::button('<i class="fa fa-search" aria-hidden="true"></i>', ['class' => 'btn btn-default','type'=>'submit']) !!}
                            </span>
                       </div>
                    {!! Form::close() !!}
                </div>
            </div>
    </div>

    <div class="x_panel">
        <table id="datatable" class="table table-striped table-bordered">
            <thead>
            <tr>
                <th>Наименование</th>
                <th>Контрагент</th>
                <th>Вид договора</th>
                <th>Срок действия</th>
                <th>Валюта</th>
                <th>Организация</th>
                <th>Действия</th>
            </tr>
            </thead>
            <tbody>
            @foreach($contracts as $k => $contract)

                <tr id="{{ $contract->id }}" class="contract">
                    <th>{!! Html::link(route('contractEdit',['id'=>$contract->id]),$contract->name,['alt'=>$contract->name]) !!}</th>
                    @if(empty($contract->firm_id))
                        <td></td>
                    @else
                        <td>{{ \App\Models\Firm::find($contract->firm_id)->name }}</td>
                    @endif
                    @if(empty($contract->tdoc_id))
                        <td></td>
                    @else
                        <td>{{ \App\Models\Typedoc::find($contract->tdoc_id)->name }}</td>
                    @endif
                    <td>{{ $contract->stop }}</td>
                    @if(empty($contract->currency_id))
                        <td></td>
                    @else
                        <td>{{ \App\Models\Currency::find($contract->currency_id)->name }}</td>
                    @endif
                    @if(empty($contract->org_id))
                        <td></td>
                    @else
                        <td>{{ \App\Models\Organisation::find($contract->org_id)->name }}</td>
                    @endif
                    <td style="width:110px;">
                        {!! Form::open(['url'=>route('contractEdit',['id'=>$contract->id]), 'class'=>'form-horizontal','method' => 'POST', 'onsubmit' => 'return confirmDelete()']) !!}
                        {{ method_field('DELETE') }}
                        <div class="form-group" role="group">
                            <a href="{{route('contractEdit',['id'=>$contract->id])}}"><button class="btn btn-success btn-sm" type="button" title="Редактировать запись"><i class="fa fa-edit fa-lg>" aria-hidden="true"></i></button></a>
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
    @include('confirm')
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
