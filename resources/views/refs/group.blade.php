@extends('layouts.main')

@section('tile_widget')

@endsection

@section('content')
    <!-- START BREADCRUMB -->
    <ul class="breadcrumb">
        <li><a href="{{ route('main') }}">Рабочий стол</a></li>
        <li><a href="{{ route('firms') }}">Контрагенты</a></li>
        <li class="active">Группы</li>
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
        @if($groups)
            <a href="{{route('groupAdd')}}">
                <button type="button" class="btn btn-primary btn-rounded">Новая группа</button>
            </a>
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th>Родительская группа</th>
                    <th>Наименование</th>
                    <th>Действия</th>
                </tr>
                </thead>
                <tbody>

                @foreach($groups as $k => $group)

                    <tr>
                        @if(empty(\App\Models\Group::find($group->parent_id)->name))
                            <td> </td>
                        @else
                            <td>{{ \App\Models\Group::find($group->parent_id)->name }}</td>
                        @endif
                        <th>{!! Html::link(route('groupView',['id'=>$group->id]),$group->name,['alt'=>$group->name]) !!}</th>
                        <td style="width:110px;">
                            {!! Form::open(['url'=>route('groupEdit',['id'=>$group->id]), 'class'=>'form-horizontal','method' => 'POST', 'onsubmit' => 'return confirmDelete()']) !!}
                            {{ method_field('DELETE') }}
                            <div class="form-group" role="group">
                                <a href="{{route('groupEdit',['id'=>$group->id])}}"><button class="btn btn-success btn-sm" type="button"><i class="fa fa-edit fa-lg>" aria-hidden="true"></i></button></a>
                                {!! Form::button('<i class="fa fa-trash-o fa-lg>" aria-hidden="true"></i>',['class'=>'btn btn-danger','type'=>'submit']) !!}
                            </div>
                            {!! Form::close() !!}
                        </td>
                    </tr>

                @endforeach
                </tbody>
            </table>
            {{ $groups->links() }}
        @endif
    </div>
    </div>
    <!-- /page content -->
@endsection

@section('user_script')
    @include('confirm')
@endsection
