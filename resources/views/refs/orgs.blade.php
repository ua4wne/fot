@extends('layouts.main')

@section('tile_widget')

@endsection

@section('content')
    <!-- START BREADCRUMB -->
    <ul class="breadcrumb">
        <li><a href="{{ route('main') }}">Рабочий стол</a></li>
        <li class="active">Организации</li>
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
        @if($orgs)
            <a href="{{route('orgAdd')}}">
                <button type="button" class="btn btn-primary btn-rounded">Новая запись</button>
            </a>
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th>Название</th>
                    <th>Полное наименование</th>
                    <th>ИНН</th>
                    <th>КПП</th>
                    <th>Действия</th>
                </tr>
                </thead>
                <tbody>

                @foreach($orgs as $k => $org)

                    <tr>
                        <th>{!! Html::link(route('orgEdit',['org'=>$org->id]),$org->name,['alt'=>$org->name]) !!}</th>
                        <td>{{ $org->full_name }}</td>
                        <td>{{ $org->inn }}</td>
                        <td>{{ $org->kpp }}</td>
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
            {{ $orgs->links() }}
        @endif
    </div>
    </div>
    <!-- /page content -->
@endsection

@section('user_script')
    @include('confirm')
@endsection
