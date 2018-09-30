<html>
<head>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css"
          integrity="sha384-9gVQ4dYFwwWSjIDZnLEWnxCjeSWFphJiwGPXr1jddIhOegiu1FwO5qRGvFXOdJZ4" crossorigin="anonymous">

    <link href="{{url('/')}}/css/newKanban.css" rel="stylesheet">


    <script src="https://code.jquery.com/jquery-2.1.3.min.js"
            crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js"
            integrity="sha384-cs/chFZiN24E4KMATLdqdvsezGxaGsi4hLGOzlXwp5UZB1LY//20VyM2taTB4QvJ"
            crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js"
            integrity="sha384-uefMccjFJAIv6A+rW+L4AHf99KvxDjWSu1z9VI8SKNVmz4sk7buKt/6v9KI65qnm"
            crossorigin="anonymous"></script>
    <script src="{{url('/')}}/js/kanban.js"></script>

    <script src="{{url('/')}}/js/jquery.datetimepicker.full.js"></script>
    <script src="{{url('/')}}/js/twig.js"
            crossorigin="anonymous"></script>
    <link rel="stylesheet" href="{{url('/')}}/css/jquery.datetimepicker.min.css"/>
    <link rel="shortcut icon" href="{{url('/')}}/favicon.ico" type="image/x-icon">
    <title>Дела</title>
    <meta name="csrf-token" content="{{ csrf_token() }}"/>
    <meta name="user_id" content="{{ $user['id'] }}"/>
    <style>
        .icon-task2 {
            background-image: url("{{url('/')}}/images/logo_active.svg");
            height: 35px;
            margin: 5px 17.5px;
            width: 31px;
        }

        .inner-icon-edit {
            background: url({{url('/')}}/images/new/edit-20px.svg);
            background-size: 20px;
        }

        .inner-icon-edit:hover {
            background: url({{url('/')}}/images/new/edit-hover-20px.svg);
            background-size: 20px;
        }

        .inner-icon-done {
            background: url({{url('/')}}/images/new/done-20px.svg);
            background-size: 20px;
        }

        .inner-icon-done:hover {
            background: url({{url('/')}}/images/new/done-hover-20px.svg);
            background-size: 20px;
        }

        .inner-icon-end {
            background: url({{url('/')}}/images/new/contact-20px.svg);
            background-size: 20px;
        }

        .inner-icon-end:hover {
            background: url({{url('/')}}/images/new/contact-hover-20px.svg);
            background-size: 20px;
        }

        .inner-icon-more {
            background: url({{url('/')}}/images/outline-more_horiz-24px_hover.svg);
            background-size: 20px;
        }

        .inner-icon-more:hover {
            background: url({{url('/')}}/images/outline-more_horiz-24px.svg);
            background-size: 20px;
        }

    </style>
</head>
<div id="overlay">
    <div class="overlay-block">
        <span class="overlay-text">У вас нет созданных дел!<br>Создайте дело прямо сейчас?</span><br>
        <a class="btn btn-primary btn-lg btn-add-task" data-toggle="modal" data-target="#createTask"
           onclick="addTaskModal()">
            Добавить дело
        </a>
    </div>
</div>
<body>
<div class="left-menu amocrm-menu">
    <div class="delitemer-left amocrm-menu-delitemer">
        <img src="{{url('/')}}/images/avatar.png">
    </div>
    <ul class="amocrm-menu-list">
        <li class="li-tasks">
            <a href="https://novyetechnologii.amocrm.ru/dashboard/">
                <div class="icon-desktop"></div>
                Рабочий стол</a>
        </li>
        <li class="li-tasks">
            <a href="https://novyetechnologii.amocrm.ru/leads/">
                <div class="icon-lead"></div>
                Сделки</a>
        </li>
        <li class="li-tasks amocrm-menu-icon-active">
            <a href="/">
                <div class="icon-task2"></div>
                Дела</a>
        </li>
        <li class="li-tasks">
            <a href="https://novyetechnologii.amocrm.ru/todo/line/">
                <div class="icon-task"></div>
                Задачи</a>
        </li>
        <li class="li-tasks">
            <a href="https://novyetechnologii.amocrm.ru/contacts/list/">
                <div class="icon-lists"></div>
                Списки</a>
        </li>
        <li class="li-tasks">
            <a href="https://novyetechnologii.amocrm.ru/mail/inbox/">
                <div class="icon-mail"></div>
                Почта</a>
        </li>
        <li class="li-tasks">
            <a href="https://novyetechnologii.amocrm.ru/stats/">
                <div class="icon-analytic"></div>
                Аналитика</a>
        </li>
        <li class="li-tasks">
            <a href="https://novyetechnologii.amocrm.ru/settings/widgets/">
                <div class="icon-setting"></div>
                Настройки</a>
        </li>
    </ul>
</div>
<nav class="navbar navbar-expand-lg navbar-light header-navbar">
    <span class="navbar-brand mb-0 h1 header-navbar-title">ДЕЛА</span>
    <div class="navbar-collapse" id="navbarNavAltMarkup" style="    display: -ms-flexbox!important;
    display: flex!important;
    -ms-flex-preferred-size: auto;
    flex-basis: auto;">
        <div class="navbar-nav" style="
    height: 100%;
    padding:  13px 5px;
    border-right: 1px solid #e8eaeb;
">
            <div class="dropdown" style="
    min-width: 50px;
    padding: 5px 23px;
">
                <a class="dropdown-toggle" id="dropdownMenuButton" data-toggle="dropdown">
                    <img src="{{url('/')}}/images/outline-center_focus_strong-24px.svg" style="
    width: 28px;
">
                </a>
                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    <li class="dropdown-item" onclick="changeMode('all')">Все <span
                                class="sr-only">(current)</span></li>
                    <li class="dropdown-item" onclick="changeMode('expired')">Просроченные</li>
                    <li class="dropdown-item" onclick="changeMode('today')">Сегодня</li>
                    <li class="dropdown-divider"></li>
                    <li class="dropdown-item" onclick="setIntervalMenuModal()">Выбрать дату</li>
                    <li class="dropdown-submenu"><a class="dropdown-item dropdown-toggle" href="#">Выбрать приоритет</a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" onclick="changeMode('type|1')">Высокий</a></li>
                            <li><a class="dropdown-item" onclick="changeMode('type|3')">Средний</a></li>
                            <li><a class="dropdown-item" onclick="changeMode('type|2')">Низкий</a></li>
                        </ul>
                    </li>
                    <li class="dropdown-divider"></li>
                    <li class="dropdown-item" onclick="changeMode('end')">Завершенные</li>
                    <li class="dropdown-divider"></li>
                    <li class="dropdown-item" onclick="showNotificationModal()">Уведомления</li>
                </ul>
            </div>
        </div>
        <form class="form-inline my-2 my-lg-0" style="
    padding: 0 10px;
    width: 100%;
    border-right: 1px solid #e8eaeb;
    height: 100%;

    ">
            <div class="input-group mb-3" style="
    width: -webkit-fill-available;
    margin: inherit !important;
">
                <input type="text" class="form-control" id="searchText" placeholder="Поиск" aria-label="Поиск"
                       aria-describedby="basic-addon2">
                <div class="input-group-append">
                    <button class="btn btn-primary" type="button"
                            style="color: #fff; -webkit-border-radius: 0px;-moz-border-radius: 0px;border-radius: 0px;"
                            onclick="searchit();">Поиск
                    </button>
                </div>
            </div>
        </form>

        <a class="btn btn-primary"
           style="color: #f5f5f5;margin: 10px;text-transform: uppercase;font-family: 'Arial';font-weight: 600;font-size: 15px;border-radius: 0px;"
           data-toggle="modal" data-target="#createTask" onclick="addTaskModal()">+ Добавить задачу</a>
    </div>

</nav>
<div class="container-fluid">
    <div class="row">
        @foreach($board as $pipeline)
            <div class="col-sm" style="padding: 0">
                <div class="pipeline ">
                    <div class="pipeline-header font-weight-bold">
                        {{$pipeline['name']}}<br>
                        <span style="
    color: #666;
    font-weight: 400;
    line-height: 20px;
"><span id="pipeline{{$pipeline['id']}}_count_task">0</span> задач</span>
                    </div>
                    <div class="pipeline-body" id="pipeline{{$pipeline['id']}}">

                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
</body>


<div class="modal fade" id="createTask" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
     aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content" style="
    border-radius: 0px;
">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Создание задачи</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form role="form" action="{{url('/')}}/tasks/add" method="post">

                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="user_id" id="addTask_user_id" value="{{$user['id']}}">
                    <div class="form-group">
                        <label for="exampleInputEmail1">Наименование сделки</label>
                        <input type="text" class="form-control" id="name_lead"
                               placeholder="ХХ-0000-00">
                        <div class="progress" id="loaderLeads" style="height: 5px; margin-top: 5px; display: none">
                            <div class="progress-bar" id="innerLoaderLeads" role="progressbar" style="width: 0%;"
                                 aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        <select style="margin-top: 5px; display: none" class="form-control" name="lead_id"
                                id="lead_select">
                            <option disabled="disabled">----</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1">Воронка</label>
                        <select name="pipeline_id" id="addTask_pipeline_id" class="form-control">
                            <option value="1">Новая заявка</option>
                            <option value="2">Подбор поставщика</option>
                            <option value="3">На контроль</option>
                            <option value="4">Выполнен</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1">Приоритет задачи</label>
                        <select name="type_id" id="addTask_type_id" class="form-control">
                            <option style="background: #ff0000; color: #000;" value="1">Высокий</option>
                            <option style="background: #92d050; color: #000;" value="3">Средний</option>
                            <option style="background: #9e9e9e; color: #FFF;" value="2">Низкий</option>

                        </select>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1">Наименование задачи</label>
                        <select name="number_request" id="addTask_number_request" class="form-control">
                            <option value="1">Связаться с клиентом</option>
                            <option value="13">Cвязаться с поставщиком</option>
                            <option value="3">Жду информацию от клиента</option>
                            <option value="4">Отправлено ТКП</option>
                            <option value="5">Отправлен счет на оплату</option>
                            <option value="6">Отправлен запрос поставщику</option>
                            <option value="7">Жду информацию от поставщика</option>
                            <option value="8">Жду оплату от клиента</option>
                            <option value="9">Жду оплату поставщику</option>
                            <option value="10">Разместить в производство</option>
                            <option value="11">Срочная отгрузка</option>
                            <option value="12">Отгрузка</option>
                            <option value="14">Связаться по возможным заявкам</option>
                            <option value="15">Проблемная отгрузка</option>
                            <option value="16">Тендер - запрос специалисту</option>
                            <option value="17">Тендер - ТКП</option>
                            <option value="18">Тендер - Жду результат</option>
                            <option value="19">Подготовить ТКП</option>
                            <option value="20">Подготовить Счет и договор</option>
                            <option value="21">Отправлен проект договора</option>
                        </select>
                    </div>
                    <input type="hidden" name="number_request1" id="addTask_number_request1" class="form-control">
                    <div class="form-group">
                        <label for="exampleInputEmail1">URL #1</label>
                        <input type="text" name="url_1" id="addTask_url1" class="form-control"
                               placeholder="http://example.com/index.php">
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1">URL #2</label>
                        <input type="text" name="url_2" id="addTask_url2" class="form-control"
                               placeholder="http://example.com/index.php">
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1">Комментарий</label>
                        <textarea name="comment" id="addTask_comment" class="form-control" rows="3"></textarea>
                    </div>

                    <div class="form-group">
                        <label for="exampleInputEmail1">Время окончания задачи</label>
                        <div class="input-group mb-3">
                            <input name="complite_till" type='text' onchange="checkTime('datetimepicker1')"
                                   id='datetimepicker1' class="form-control"
                                   aria-describedby="basic-addon2" value="{{date('d.m.Y H:i')}}">
                            <div class="input-group-append" onclick="$('#datetimepicker1').datetimepicker('show');">
                                <span class="input-group-text" id="basic-addon2"><img width="24px"
                                                                                      src="{{url('/')}}/images/new/calendar-20px.svg"></span>
                            </div>
                        </div>
                        <span id="datetimepicker1_text" style="display: none"></span>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="" id="addTask_notification">
                        <label class="form-check-label" for="addTask_notification">
                            Уведомить?
                        </label>
                    </div>

                </form>

            </div>
            <div class="modal-footer" style="
    display: block;
">
                <button type="button" class="btn btn-primary pull-left" onclick="addTask()">Добавить</button>
                <button type="button" class="btn btn-secondary pull-right" data-dismiss="modal" style="
    float: right;
">Отменить
                </button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="changeStatus" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
     aria-hidden="true">
    <div class="modal-dialog  modal-lg" role="document">
        <div class="modal-content" style="
    border-radius: 0px;
">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Редактирование задачи <span id="edit_nameTask_header">TEST</span>
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form role="form" action="{{url('/')}}/tasks/edit" method="post">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="user_id" id="edit_userId" value="{{$user['id']}}">
                    <input type="hidden" name="task_id" value="" id="edit_taskId">
                    <div class="form-group">
                        <label for="exampleInputEmail1">Время окончания задачи</label>
                        <div class="input-group mb-3">
                            <input name="complite_till" type='text' onchange="checkTime('datetimepicker2')"
                                   id='datetimepicker2' class="form-control"
                                   aria-describedby="basic-addon2">
                            <div class="input-group-append" onclick="$('#datetimepicker2').datetimepicker('show');">
                                <span class="input-group-text" id="basic-addon2"><img width="24px"
                                                                                      src="{{url('/')}}/images/new/calendar-20px.svg"></span>
                            </div>
                        </div>
                    </div>
                    <span id="datetimepicker2_text" style="display: none"></span>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="" id="edit_notification">
                        <label class="form-check-label" for="edit_notification">
                            Уведомить? <span id="edit_notification_text"></span>
                        </label>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1">Воронка</label>
                        <select name="pipeline_id" id="edit_pipelineId" class="form-control">
                            <option value="1">Новая заявка</option>
                            <option value="2">Подбор поставщика</option>
                            <option value="3">На контроль</option>
                            <option value="4">Выполнен</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1">Приоритет задачи</label>
                        <select name="type_id" id="edit_typeId" class="form-control">
                            <option style="background: #ff0000; color: #000;" value="1">Высокий</option>
                            <option style="background: #92d050; color: #000;" value="3">Средний</option>
                            <option style="background: #9e9e9e; color: #FFF;" value="2">Низкий</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1">Наименование задачи</label>
                        <select name="number_request" class="form-control" id="edit_numberRequest">
                            <option value="1">Связаться с клиентом</option>
                            <option value="13">Cвязаться с поставщиком</option>
                            <option value="3">Жду информацию от клиента</option>
                            <option value="4">Отправлено ТКП</option>
                            <option value="5">Отправлен счет на оплату</option>
                            <option value="6">Отправлен запрос поставщику</option>
                            <option value="7">Жду информацию от поставщика</option>
                            <option value="8">Жду оплату от клиента</option>
                            <option value="9">Жду оплату поставщику</option>
                            <option value="10">Разместить в производство</option>
                            <option value="11">Срочная отгрузка</option>
                            <option value="12">Отгрузка</option>
                            <option value="14">Связаться по возможным заявкам</option>
                            <option value="15">Проблемная отгрузка</option>
                            <option value="16">Тендер - запрос специалисту</option>
                            <option value="17">Тендер - ТКП</option>
                            <option value="18">Тендер - Жду результат</option>
                            <option value="19">Подготовить ТКП</option>
                            <option value="20">Подготовить Счет и договор</option>
                            <option value="21">Отправлен проект договора</option>
                        </select>

                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1">URL #1</label>
                        <input type="text" name="url_1" class="form-control" id="edit_url1"
                               placeholder="http://example.com/index.php">
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1">URL #2</label>
                        <input type="text" name="url_2" class="form-control" id="edit_url2"
                               placeholder="http://example.com/index.php">
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1">Комментарий</label>
                        <textarea name="comment" id="edit_comment" class="form-control" rows="3"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer" style="
    display: block;
">
                <button type="button" class="btn btn-primary pull-left" onclick="editTask()">Отредактировать</button>
                <button type="button" class="btn btn-secondary " data-dismiss="modal" style="
    float: right;
">Отменить
                </button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="additionalInfo" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Дополнительная информация</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th colspan="2">Покупатель</th>
                    </tr>
                    </thead>

                    <tr>
                        <td>Наименование компании</td>
                        <td id="info_nameCompany">Имя</td>
                    </tr>
                    <tr>
                        <td>Контактое лицо</td>
                        <td id="info_nameContact">Имя</td>
                    </tr>
                    <tr>
                        <td>Телефон</td>
                        <td id="info_telphone">Имя</td>
                    </tr>
                    <tr>
                        <td>E-Mail</td>
                        <td id="info_email">Имя</td>
                    </tr>
                    <thead>
                    <tr>
                        <th colspan="2">Поставщик</th>
                    </tr>
                    </thead>
                    <tr>
                        <td>Наименование компании</td>
                        <td id="info_supplier_nameCompany">Имя</td>
                    </tr>
                    <tr>
                        <td>Контактое лицо</td>
                        <td id="info_supplier_nameContact">Имя</td>
                    </tr>
                    <tr>
                        <td>Телефон</td>
                        <td id="info_supplier_telphone">Имя</td>
                    </tr>
                    <tr>
                        <td>E-Mail</td>
                        <td id="info_supplier_email">Имя</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="endTask" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Завершить дело </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="text-center" style="
    font-size: 18px;
">Вы уверены что хотите завершить дело? <br><span id="endTask_task_name" style="
    font-weight: bold;
"></span><br><span
                            id="endTask_task_subname" style="
    font-weight: bold;
"></span></div>
            </div>
            <div class="modal-footer" style="
    display: block;
">
                <button type="button" class="btn btn-danger" id="endTask_end_button">
                    Завершить
                </button>
                <button type="button" class="btn btn-primary" data-dismiss="modal" aria-label="Close" style="
    float: right;
">
                    Отмена
                </button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="addComment" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
     aria-hidden="true">
    <div class="modal-dialog  modal-lg" role="document">
        <div class="modal-content" style="
    border-radius: 0px;
">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Добавление комментария для <span
                            id="addComment_nameTask_header">TEST</span>
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="task_id" value="" id="addComment_taskId">
                <div class="form-group">
                    <label for="exampleInputEmail1">Комментарий</label>
                    <textarea name="comment" id="addComment_comment" class="form-control" rows="3"></textarea>
                </div>

            </div>
            <div class="modal-footer" style="
    display: block;
">
                <button type="button" class="btn btn-primary pull-left" onclick="addComment()">Добавить</button>
                <button type="button" class="btn btn-secondary " data-dismiss="modal" style="
    float: right;
">Отменить
                </button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="miniEdit" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
     aria-hidden="true">
    <div class="modal-dialog  modal-lg" role="document">
        <div class="modal-content" style="
    border-radius: 0px;
">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Быстрое редактирование задачи <span
                            id="miniEdit_nameTask_header">TEST</span>
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="task_id" value="" id="miniEdit_taskId">
                <div class="form-group" style="width: 308px;">
                    <label for="exampleInputEmail1">Время окончания задачи</label>
                    <div class="input-group mb-3">
                        <input name="complite_till" type='text' onchange="checkTime('datetimepicker3')"
                               id='datetimepicker3' class="form-control"
                               aria-describedby="basic-addon2">
                        <div class="input-group-append" onclick="$('#datetimepicker3').datetimepicker('show');">
                                <span class="input-group-text" id="basic-addon2"><img width="24px"
                                                                                      src="{{url('/')}}/images/new/calendar-20px.svg"></span>
                        </div>
                    </div>
                </div>
                <span id="datetimepicker3_text" style="display: none"></span>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="" id="miniEdit_notification">
                    <label class="form-check-label" for="miniEdit_notification">
                        Уведомить? <span id="miniEdit_notification_text"></span>
                    </label>
                </div>
                <div class="form-group">
                    <label for="exampleInputEmail1">Комментарий</label>
                    <textarea name="comment" id="miniEdit_comment" class="form-control" rows="3"></textarea>
                </div>

            </div>
            <div class="modal-footer" style="
    display: block;
">
                <button type="button" class="btn btn-primary pull-left" onclick="miniEdit()">Отредактировать</button>
                <button type="button" class="btn btn-secondary " data-dismiss="modal" style="
    float: right;
">Отменить
                </button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="setIntervalMenuModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
     aria-hidden="true">
    <div class="modal-dialog  modal-lg" role="document">
        <div class="modal-content" style="
    border-radius: 0px;
">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Выбор интервала
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <div class="form-group">
                    <label for="exampleInputEmail1">Время окончания задачи ОТ</label>
                    <div class="input-group mb-3">
                        <input name="complite_till" type='text' id='datetimepicker4' class="form-control"
                               aria-describedby="basic-addon2">
                        <div class="input-group-append" onclick="$('#datetimepicker4').datetimepicker('show');">
                                <span class="input-group-text" id="basic-addon2"><img width="24px"
                                                                                      src="{{url('/')}}/images/icon_calendar.svg"></span>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="exampleInputEmail1">Время окончания задачи ДО</label>
                    <div class="input-group mb-3">
                        <input name="complite_till" type='text' id='datetimepicker5' class="form-control"
                               aria-describedby="basic-addon2">
                        <div class="input-group-append" onclick="$('#datetimepicker5').datetimepicker('show');">
                                <span class="input-group-text" id="basic-addon2"><img width="24px"
                                                                                      src="{{url('/')}}/images/icon_calendar.svg"></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer" style="
    display: block;
">
                <button type="button" class="btn btn-primary pull-left" onclick="setIntervalMenu()">Применить фильтр
                </button>

                <button type="button" class="btn btn-secondary " data-dismiss="modal" style="
    float: right;
">Отменить
                </button>
                <button type="button" class="btn btn-secondary " data-dismiss="modal" onclick="clearIntervalMenu()"
                        style="
    float: right;
">Отменить фильтр
                </button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="notificationModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
     aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Уведомления</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <ul class="nav nav-fill nav-pills" id="pills-tab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="pills-home-tab" data-toggle="pill" href="#pills-home" role="tab"
                           aria-controls="pills-home" aria-selected="true">Активные</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="pills-profile-tab" data-toggle="pill" href="#pills-profile" role="tab"
                           aria-controls="pills-profile" aria-selected="false">Завершенные</a>
                    </li>
                </ul>
                <div class="tab-content" id="pills-tabContent">
                    <div class="tab-pane fade show active" id="pills-home" role="tabpanel"
                         aria-labelledby="pills-home-tab">
                        <div class="inner-pills">
                            <table class="table table-sm" id="notification_modal_active">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Задача</th>
                                    <th>Статус</th>
                                    <th>Время уведомления</th>
                                </tr>
                                </thead>
                                <tbody id="notification_modal_active_body">

                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">
                        <div class="inner-pills">
                            <table class="table table-sm" id="notification_modal_expired">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Задача</th>
                                    <th>Статус</th>
                                    <th>Время уведомления</th>
                                </tr>
                                </thead>
                                <tbody id="notification_modal_expired_body">

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="alert alert-success" role="alert" id="alert-success" style="width: 20%;
    float: right;
    bottom: 20px;
    right: 25px;
        margin: 0;
display: none">

</div>
<div class="alert alert-danger" role="alert" id="alert-danger" style="width: 20%;
    float: right;
    bottom: 20px;
    right: 25px;
        margin: 0;
display: none">

</div>
</html>