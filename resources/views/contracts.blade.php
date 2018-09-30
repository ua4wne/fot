@extends('layouts.main')

@section('tile_widget')

@endsection

@section('content')
    <!-- START BREADCRUMB -->
    <ul class="breadcrumb">
        <li><a href="{{ route('main') }}">Рабочий стол</a></li>
        <li><a href="{{ route('groups') }}">Группы контрагентов</a></li>
        <li><a href="{{ route('firms') }}">Контрагенты</a></li>
        <li><a href="{{ route('contracts') }}">{{ $title }}</a></li>
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
                        <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#importContract"><i class="fa fa-upload fa-fw"></i> Импорт</button>
                    </a>
                </div>
                @endif
                @if(\App\Models\Role::granted('export_sale_doc'))
                <div class="btn-group">
                    <a class="btn btn-success btn-sm" href="#"><i class="fa fa-upload fa-fw"></i> Экспорт</a>
                    <a class="btn btn-success dropdown-toggle btn-sm" data-toggle="dropdown" href="#">
                        <span class="fa fa-caret-down" title="Toggle dropdown menu"></span>
                    </a>
                    <ul class="dropdown-menu">
                        <li><a href="{{ route('exportContract',['type'=>'xls']) }}"><i class="fa fa-file-excel-o fa-fw"></i> В файл XLS</a></li>
                        <li><a href="{{ route('exportContract',['type'=>'xlsx']) }}"><i class="fa fa-file-excel-o fa-fw"></i> В файл XLSX</a></li>
                        <li><a href="{{ route('exportContract',['type'=>'csv']) }}"><i class="fa fa-file-excel-o fa-fw"></i> В файл CSV</a></li>
                    </ul>
                </div>
                @endif
                <div class="btn-group">
                    <a href="{{route('contractAdd')}}">
                        <button type="button" class="btn btn-primary btn-sm">Новый договор</button>
                    </a>
                </div>
            </div>
    </div>

    <div class="x_panel">
        <table id="datatable" class="table table-striped table-bordered">
            <thead>
            <tr>
                <th>№ договора</th>
                <th>Вид договора</th>
                <th>Наименование</th>
                <th>Организация</th>
                <th>Контрагент</th>
                <th>Комментарии</th>
                <th>Дата договора</th>
                <th>Срок действия</th>
                <th>Вид расчета</th>
            </tr>
            </thead>
            <tbody>
            @foreach($contracts as $k => $contract)

                <tr id="{{ $contract->id }}" class="contract">
                    <td>{{ $contract->num_doc }}</td>
                    @if(empty($contract->tdoc_id))
                        <td></td>
                    @else
                        <td>{{ \App\Models\Typedoc::find($contract->tdoc_id)->name }}</td>
                    @endif
                    <th>{!! Html::link(route('contractEdit',['id'=>$contract->id]),$contract->name,['alt'=>$contract->name]) !!}</th>
                    @if(empty($contract->org_id))
                        <td></td>
                    @else
                        <td>{{ \App\Models\Organisation::find($contract->org_id)->name }}</td>
                    @endif
                    @if(empty($contract->firm_id))
                        <td></td>
                    @else
                        <td>{{ \App\Models\Firm::find($contract->firm_id)->name }}</td>
                    @endif
                    <td>{{ $contract->text }}</td>
                    <td>{{ $contract->start }}</td>
                    <td>{{ $contract->stop }}</td>
                    @if(empty($contract->settlement_id))
                        <td></td>
                    @else
                        <td>{{ \App\Models\Settlement::find($contract->settlement_id)->name }}</td>
                    @endif
                    <td style="width:110px;">
                        <div class="form-group" role="group">
                            <button class="btn btn-success btn-sm contract_edit" type="button" data-toggle="modal" data-target="#editContract" title="Редактировать запись"><i class="fa fa-edit fa-lg" aria-hidden="true"></i></button>
                            <button class="btn btn-danger btn-sm contract_delete" type="button" title="Удалить запись"><i class="fa fa-trash fa-lg" aria-hidden="true"></i></button>
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

        $('#frm_edit').click(function(e){
            e.preventDefault();
            var error=0;
            $("#edit_firm").find(":input").each(function() {// проверяем каждое поле ввода в форме
                if($(this).attr("required")=='required'){ //обязательное для заполнения поле формы?
                    if(!$(this).val()){// если поле пустое
                        $(this).css('border', '1px solid red');// устанавливаем рамку красного цвета
                        error=1;// определяем индекс ошибки
                    }
                    else{
                        $(this).css('border', '1px solid green');// устанавливаем рамку зеленого цвета
                    }

                }
            })
            if(error){
                alert("Необходимо заполнять все доступные поля!");
                return false;
            }
            else{
                $.ajax({
                    type: 'POST',
                    url: '{{ route('editFirm') }}',
                    data: $('#edit_firm').serialize(),
                    success: function(res){
                        //alert(res);
                        if(res=='ERR')
                            alert('Ошибка записи данных.');
                        else{
                            var obj = jQuery.parseJSON(res);
                            var id = obj.id;
                            var name = obj.name;
                            var fname = obj.fname;
                            var group = obj.group;
                            var inn = obj.inn;
                            var kpp = obj.kpp;
                            $("#"+id).children('th').text(name);
                            $("#"+id).children('td').first().text(fname);
                            $("#"+id).children('td').first().next().text(group);
                            $("#"+id).children('td').first().next().next().text(inn);
                            $("#"+id).children('td').first().next().next().next().text(kpp);
                        }
                    }
                });
            }
        });

        $('.firm_edit').click(function(){
            var id = $(this).parent().parent().parent().attr("id");
            var vid  = $(this).parent().parent().parent().attr("class");
            var kpp = $(this).parent().parent().prevAll().eq(1).text();
            var inn = $(this).parent().parent().prevAll().eq(2).text();
            var group = $(this).parent().parent().prevAll().eq(3).text();
            var fname = $(this).parent().parent().prevAll().eq(4).text();
            var name = $(this).parent().parent().prevAll().eq(5).text();
            $("#group_id :contains("+group+")").attr("selected", "selected");
            $('#kpp').val(kpp);
            $('#inn').val(inn);
            if(vid.indexOf('physical') > -1)
                $("#type :contains('Физическое лицо')").attr("selected", "selected");
            else
                $("#type :contains('Юридическое лицо')").attr("selected", "selected");
            $('#fname').val(fname);
            $('#name').val(name);
            $('#firm_id').val(id);
        });

        $('.firm_delete').click(function(){
            var id = $(this).parent().parent().parent().attr("id");
            var x = confirm("Выбранная запись будет удалена. Продолжить (Да/Нет)?");
            if (x) {
                $.ajax({
                    type: 'POST',
                    url: '{{ route('deleteFirm') }}',
                    data: {'id':id},
                    headers: {
                        'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(res){
                        //alert(res);
                        if(res=='OK')
                            $('#'+id).hide();
                        else
                            alert('Ошибка удаления данных.');
                    }
                });
            }
            else {
                return false;
            }
        });

    </script>
@endsection
