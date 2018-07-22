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

            <div class="x_content">
                <div class="col-xs-2">
                    <!-- required for floating -->
                    <!-- Nav tabs -->
                    <ul class="nav nav-tabs tabs-left">
                        @foreach($groups as $k => $group)
                            @if($k==0 && !$group->parent_id)
                                <li class="active"><a href="#group{{ $group->id }}" data-toggle="tab">{{ $group->name }}</a></li>
                            @elseif($k>0 && !$group->parent_id)
                                <li><a href="#group{{ $group->id }}" data-toggle="tab">{{ $group->name }}</a></li>
                            @endif
                        @endforeach
                    </ul>
                </div>
                <div class="col-xs-10">
                    <!-- Tab panes -->
                    <div class="tab-content">
                        @foreach($groups as $k => $group)
                            @if($k==0)
                                <div class="tab-pane active" id="group{{ $group->id }}">
                                @if($childs)
                                        <table class="table table-striped table-bordered">
                                            <thead>
                                            <tr>
                                                <th>Наименование</th>
                                                <th>Действия</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                    @foreach($childs as $child)
                                        @if($child->parent_id == $group->id)
                                        <tr>
                                            <th>{!! Html::link(route('groupView',['id'=>$child->id]),$child->name,['alt'=>$child->name]) !!}</th>
                                            <td style="width:110px;">
                                                {!! Form::open(['url'=>route('groupEdit',['id'=>$child->id]), 'class'=>'form-horizontal','method' => 'POST', 'onsubmit' => 'return confirmDelete()']) !!}
                                                {{ method_field('DELETE') }}
                                                <div class="form-group" role="group">
                                                    <a href="{{route('groupEdit',['id'=>$child->id])}}"><button class="btn btn-success btn-sm" type="button"><i class="fa fa-edit fa-lg>" aria-hidden="true"></i></button></a>
                                                    {!! Form::button('<i class="fa fa-trash-o fa-lg>" aria-hidden="true"></i>',['class'=>'btn btn-danger','type'=>'submit']) !!}
                                                </div>
                                                {!! Form::close() !!}
                                            </td>
                                        </tr>
                                        @endif
                                    @endforeach
                                            </tbody>
                                        </table>
                                @endif
                                </div>
                            @elseif($k>0)
                                <div class="tab-pane" id="group{{ $group->id }}">
                                    @if($childs)
                                        <table class="table table-striped table-bordered">
                                            <thead>
                                            <tr>
                                                <th>Наименование</th>
                                                <th>Действия</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($childs as $child)
                                                @if($child->parent_id == $group->id)
                                                    <tr>
                                                        <th>{!! Html::link(route('groupView',['id'=>$child->id]),$child->name,['alt'=>$child->name]) !!}</th>
                                                        <td style="width:110px;">
                                                            {!! Form::open(['url'=>route('groupEdit',['id'=>$child->id]), 'class'=>'form-horizontal','method' => 'POST', 'onsubmit' => 'return confirmDelete()']) !!}
                                                            {{ method_field('DELETE') }}
                                                            <div class="form-group" role="group">
                                                                <a href="{{route('groupEdit',['id'=>$child->id])}}"><button class="btn btn-success btn-sm" type="button"><i class="fa fa-edit fa-lg>" aria-hidden="true"></i></button></a>
                                                                {!! Form::button('<i class="fa fa-trash-o fa-lg>" aria-hidden="true"></i>',['class'=>'btn btn-danger','type'=>'submit']) !!}
                                                            </div>
                                                            {!! Form::close() !!}
                                                        </td>
                                                    </tr>
                                                @endif
                                            @endforeach
                                            </tbody>
                                        </table>
                                    @endif
                                </div>
                            @endif
                        @endforeach
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
    </div>
    <!-- /page content -->
@endsection

@section('user_script')
    {{--<script src="/js/jquery.dataTables.min.js"></script>--}}
    @include('confirm')
@endsection
