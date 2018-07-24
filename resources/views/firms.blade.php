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
    <div class="row">
        <h2 class="text-center">{{ $head }}</h2>
        @if($firms)
            <a href="{{route('firmAdd')}}">
                <button type="button" class="btn btn-primary btn-rounded">Новый контрагент</button>
            </a>
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
                @foreach($firms as $k => $firm)

                    <tr>
                        <th>{!! Html::link(route('firmEdit',['id'=>$firm->id]),$firm->name,['alt'=>$firm->name]) !!}</th>
                        <td>{{ $firm->full_name }}</td>
                        <td>{{ \App\Models\Group::find('id',$firm->group_id)->name }}</td>
                        <td>{{ $firm->inn }}</td>
                        <td>{{ $firm->kpp }}</td>
                        <td>{{ \App\Models\BankAccount::find('id',$firm->acc_id)->name }}</td>
                        <td style="width:110px;">
                            {!! Form::open(['url'=>route('orgEdit',['org'=>$org->id]), 'class'=>'form-horizontal','method' => 'POST', 'onsubmit' => 'return confirmDelete()']) !!}
                            {{ method_field('DELETE') }}
                            <div class="form-group" role="group">
                                <a href="{{route('orgEdit',['org'=>$org->id])}}"><button class="btn btn-success btn-sm" type="button"><i class="fa fa-edit fa-lg>" aria-hidden="true"></i></button></a>
                                {!! Form::button('<i class="fa fa-trash-o fa-lg>" aria-hidden="true"></i>',['class'=>'btn btn-danger','type'=>'submit']) !!}
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
    <!-- /page content -->
@endsection

@section('user_script')
    <script src="/js/dataTables.min.js"></script>
    @include('confirm')
@endsection
