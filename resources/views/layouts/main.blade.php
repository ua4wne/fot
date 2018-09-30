<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="images/favicon.ico" type="image/ico" />

    <title>{{ $title ?? '' }}</title>

    <!-- Bootstrap -->
    <link href="/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="/css/font-awesome.min.css" rel="stylesheet">
    <!-- NProgress -->
    <link href="/css/nprogress.css" rel="stylesheet">
    <!-- iCheck -->
    <link href="/css/green.css" rel="stylesheet">

    <!-- bootstrap-progressbar -->
    <link href="/css/bootstrap-progressbar-3.3.4.min.css" rel="stylesheet">
    <!-- JQVMap -->
    <!--<link href="../vendors/jqvmap/dist/jqvmap.min.css" rel="stylesheet"/>
    <!-- bootstrap-datetimepicker -->
    <link href="/css/bootstrap-datetimepicker.css" rel="stylesheet">

    <!-- Custom Theme Style -->
    <link href="/css/custom.min.css" rel="stylesheet">
</head>

<body class="nav-md">
<div class="container body">
    <div class="main_container">
        @section('left_menu')
            <div class="col-md-3 left_col">
                <div class="left_col scroll-view">
                    <div class="navbar nav_title" style="border: 0;">
                        <a href="{{ route('main') }}" class="site_title"><i class="fa fa-paw"></i> <span>Финплан</span></a>
                    </div>

                    <div class="clearfix"></div>

                    <!-- menu profile quick info -->
                    <div class="profile clearfix">
                        <div class="profile_pic">
                            @if(Auth::user()->image)
                                <img src="{{ Auth::user()->image }}" alt="..." class="img-circle profile_img">
                            @else
                                <img src="/images/male.png" alt="..." class="img-circle profile_img">
                            @endif
                        </div>
                        <div class="profile_info">
                            <span>Здравствуйте,</span>
                            <h2>{{ Auth::user()->name }}</h2>
                        </div>
                    </div>
                    <!-- /menu profile quick info -->

                    <br />

                    <!-- sidebar menu -->
                    <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
                        <div class="menu_section">
                            <ul class="nav side-menu">
                                <li><a href="{{ route('main') }}"><i class="fa fa-home"></i> Рабочий стол </a></li>
                                <li><a><i class="fa fa-university"></i> Банк и касса <span class="fa fa-chevron-down"></span></a>
                                    <ul class="nav child_menu">
                                        <li><a href="{{ route('bstate') }}">Банковские выписки</a></li>
                                        <li><a href="{{ route('cash_docs') }}">Кассовые документы</a></li>
                                        <li><a href="form_validation.html">Авансовые отчеты</a></li>
                                    </ul>
                                </li>
                                <li><a><i class="fa fa-credit-card"></i> Покупки и продажи <span class="fa fa-chevron-down"></span></a>
                                    <ul class="nav child_menu">
                                        <li><a href="general_elements.html">Реализация (продажи)</a></li>
                                        <li><a href="media_gallery.html">Поступление (покупки)</a></li>
                                    </ul>
                                </li>
                                <li><a><i class="fa fa-users"></i> Сотрудники и зарплата <span class="fa fa-chevron-down"></span></a>
                                    <ul class="nav child_menu">
                                        <li><a href="tables.html">Сотрудники</a></li>
                                        <li><a href="tables_dynamic.html">Физические лица</a></li>
                                    </ul>
                                </li>
                                <li><a><i class="fa fa-sitemap"></i> Наши юр. лица <span class="fa fa-chevron-down"></span></a>
                                    <ul class="nav child_menu">
                                        <li><a href="{{ route('organizations') }}">Организации</a></li>
                                        <li><a href="{{ route('divisions') }}">Подразделения</a></li>
                                        <li><a href="{{ route('bacc') }}">Банковские счета</a></li>
                                    </ul>
                                </li>
                                <li><a><i class="fa fa-address-card"></i>Контрагенты <span class="fa fa-chevron-down"></span></a>
                                    <ul class="nav child_menu">
                                        <li><a href="{{ route('groups') }}">Группы контрагентов</a></li>
                                        <li><a href="{{ route('contracts') }}">Договоры</a></li>
                                        <li><a href="{{ route('physical') }}">Физлица</a></li>
                                        <li><a href="{{ route('legal_entity') }}">Юрлица</a></li>
                                    </ul>
                                </li>
                                <li><a><i class="fa fa-bar-chart-o"></i> Отчеты <span class="fa fa-chevron-down"></span></a>
                                    <ul class="nav child_menu">
                                        <li><a href="chartjs.html">Chart JS</a></li>
                                        <li><a href="chartjs2.html">Chart JS2</a></li>
                                        <li><a href="morisjs.html">Moris JS</a></li>
                                        <li><a href="echarts.html">ECharts</a></li>
                                        <li><a href="other_charts.html">Other Charts</a></li>
                                    </ul>
                                </li>
                            </ul>
                        </div>
                        <div class="menu_section">
                            <ul class="nav side-menu">
                                <li><a><i class="fa fa-address-book"></i> Справочники <span class="fa fa-chevron-down"></span></a>
                                    <ul class="nav child_menu">
                                        <li><a href="{{ route('currency') }}">Валюты</a></li>
                                        <li><a href="{{ route('banks') }}">Банки</a></li>
                                        <li><a href="{{ route('operations') }}">Виды операции</a></li>
                                        <li><a href="{{ route('typedocs') }}">Виды договоров</a></li>
                                        <li><a href="{{ route('settlements') }}">Виды расчетов</a></li>
                                        <li><a href="{{ route('codes') }}">План счетов бухучета</a></li>
                                        <li><a href="profile.html">Счета расчетов с контрагентами</a></li>
                                    </ul>
                                </li>
                                @if(\App\User::hasRole('admin'))
                                <li><a><i class="fa fa-cog"></i> Настройки <span class="fa fa-chevron-down"></span></a>
                                    <ul class="nav child_menu">
                                        <li><a href="{{ route('users') }}">Пользователи</a></li>
                                        <li><a href="{{ route('roles') }}">Роли</a></li>
                                        <li><a href="{{ route('actions') }}">Разрешения</a></li>
                                        </li>
                                    </ul>
                                </li>
                                @endif
                            </ul>
                        </div>

                    </div>
                    <!-- /sidebar menu -->

                    <!-- /menu footer buttons -->
                    <div class="sidebar-footer hidden-small">
                        <a data-toggle="tooltip" data-placement="top" title="Settings">
                            <span class="glyphicon glyphicon-cog" aria-hidden="true"></span>
                        </a>
                        <a data-toggle="tooltip" data-placement="top" title="FullScreen">
                            <span class="glyphicon glyphicon-fullscreen" aria-hidden="true"></span>
                        </a>
                        <a data-toggle="tooltip" data-placement="top" title="Lock">
                            <span class="glyphicon glyphicon-eye-close" aria-hidden="true"></span>
                        </a>
                        <a data-toggle="tooltip" data-placement="top" title="Logout" href="{{ route('logout') }}">
                            <span class="glyphicon glyphicon-off" aria-hidden="true"></span>
                        </a>
                    </div>
                    <!-- /menu footer buttons -->
                </div>
            </div>
    @show

    @section('top_nav')
        <!-- top navigation -->
            <div class="top_nav">
                <div class="nav_menu">
                    <nav>
                        <div class="nav toggle">
                            <a id="menu_toggle"><i class="fa fa-bars"></i></a>
                        </div>

                        <ul class="nav navbar-nav navbar-right">
                            <li class="">
                                <a href="javascript:;" class="user-profile dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                    @if(Auth::user()->image)
                                        <img src="{{ Auth::user()->image }}" alt="...">
                                    @else
                                        <img src="/images/male.png" alt="...">
                                    @endif
                                    {{ Auth::user()->login }}
                                    <span class=" fa fa-angle-down"></span>
                                </a>
                                <ul class="dropdown-menu dropdown-usermenu pull-right">
                                    <li><a href="javascript:;"> Профиль</a></li>
                                    <li><a href="{{ route('logout') }}"><i class="fa fa-sign-out pull-right"></i> Log Out</a></li>
                                </ul>
                            </li>


                        </ul>
                    </nav>
                </div>
            </div>
            <!-- /top navigation -->
    @show
            <div class="right_col" role="main">
            @section('tile_widget')
                <!-- top tiles -->
                    <div class="row top_tiles">
                        <div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
                            <div class="tile-stats">
                                <div class="icon"><i class="fa fa-rub"></i></div>
                                <div class="count">Касса</div>
                                <h3>Остаток - {{ empty($kassa) ? '' : $kassa }} (руб.)</h3>
                                <p>подробнее</p>
                            </div>
                        </div>
                        <div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
                            <div class="tile-stats">
                                <div class="icon"><i class="fa fa-comments-o"></i></div>
                                <div class="count">179</div>
                                <h3>New Sign ups</h3>
                                <p>Lorem ipsum psdea itgum rixt.</p>
                            </div>
                        </div>
                        <div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
                            <div class="tile-stats">
                                <div class="icon"><i class="fa fa-sort-amount-desc"></i></div>
                                <div class="count">179</div>
                                <h3>New Sign ups</h3>
                                <p>Lorem ipsum psdea itgum rixt.</p>
                            </div>
                        </div>
                        <div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
                            <div class="tile-stats">
                                <div class="icon"><i class="fa fa-check-square-o"></i></div>
                                <div class="count">179</div>
                                <h3>New Sign ups</h3>
                                <p>Lorem ipsum psdea itgum rixt.</p>
                            </div>
                        </div>
                    </div>
                    <!-- /top tiles -->
            @endsection
            @yield('tile_widget')

    @yield('content')

        @section('footer')
        <!-- footer content -->
        <footer>
            <div class="pull-right">
                Gentelella - Bootstrap Admin Template by <a href="https://colorlib.com">Colorlib</a>
            </div>
            <div class="clearfix"></div>
        </footer>
        <!-- /footer content -->
    </div>
</div>

<!-- jQuery -->
<script src="/js/jquery.min.js"></script>
<!-- Bootstrap -->
<script src="/js/bootstrap.min.js"></script>
<!-- FastClick -->
<script src="/js/fastclick.js"></script>
<!-- NProgress -->
<script src="/js/nprogress.js"></script>
<!-- Chart.js -->
<!--<script src="../vendors/Chart.js/dist/Chart.min.js"></script>
<!-- gauge.js -->
<!--<script src="../vendors/gauge.js/dist/gauge.min.js"></script>
<!-- bootstrap-progressbar
<script src="/js/bootstrap-progressbar.min.js"></script> -->
<!-- iCheck -->
<script src="/js/icheck.min.js"></script>


<!-- Custom Theme Scripts -->
<script src="/js/custom.min.js"></script>

@show

@section('user_script')
@show

</body>
</html>
