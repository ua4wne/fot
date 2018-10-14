@extends('layouts.main')

@section('tile_widget')

@endsection

@section('content')
    <!-- START BREADCRUMB -->
    <ul class="breadcrumb">
        <li><a href="{{ route('main') }}">Рабочий стол</a></li>
        <li class="active">{{ $head }}</li>
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
        @if($persons)
            <a href="{{route('personAdd')}}">
                <button type="button" class="btn btn-primary btn-rounded">Новая запись</button>
            </a>
            <table id="datatable" class="table table-striped table-bordered">
                <thead>
                <tr>
                    <th>ФИО</th>
                    <th>ИНН</th>
                    <th>СНИЛС</th>
                    <th>Действия</th>
                </tr>
                </thead>
                <tbody>

                @foreach($persons as $k => $person)

                    <tr>
                        <th>{!! Html::link(route('personEdit',['id'=>$person->id]),$person->fio,['alt'=>$person->fio]) !!}</th>
                        <td>{{ $person->inn }}</td>
                        <td>{{ $person->snils }}</td>

                        <td style="width:110px;">
                            {!! Form::open(['url'=>route('personEdit',['id'=>$person->id]), 'class'=>'form-horizontal','method' => 'POST', 'onsubmit' => 'return confirmDelete()']) !!}
                            {{ method_field('DELETE') }}
                            <div class="form-group" role="group">
                                <a href="{{route('personEdit',['id'=>$person->id])}}"><button class="btn btn-success btn-sm" type="button" title="Редактировать запись"><i class="fa fa-edit fa-lg>" aria-hidden="true"></i></button></a>
                                {!! Form::button('<i class="fa fa-trash-o fa-lg>" aria-hidden="true"></i>',['class'=>'btn btn-danger','type'=>'submit','title'=>'Удалить запись']) !!}
                            </div>
                            {!! Form::close() !!}
                        </td>
                    </tr>

                @endforeach
                </tbody>
            </table>
            {{ $persons->links() }}
        @endif
    </div>
    </div>
    <!-- /page content -->
@endsection

@section('user_script')
    <script src="/js/jquery.dataTables.min.js"></script>
    @include('confirm')
@endsection
