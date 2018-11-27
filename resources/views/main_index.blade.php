@extends('layouts.main')

@section('content')
    <!-- page content -->

        <div class="row">
            {!! $content !!}
        </div>
    </div>
    <!-- /page content -->
@endsection

@section('user_script')
    <script src="/js/raphael.min.js"></script>
    <script src="/js/morris.min.js"></script>

    <script>
        // Use Morris.Bar
        Morris.Bar({
            element: '1',
            data: [
                {x: '2011 Q1', y: 3, z: 2},
                {x: '2011 Q2', y: 2, z: 1},
                {x: '2011 Q3', y: 1, z: 2},
                {x: '2011 Q4', y: 2, z: 4}
            ],
            xkey: 'x',
            ykeys: ['y', 'z'],
            labels: ['Приход', 'Расход']
        });
    </script>

@endsection
