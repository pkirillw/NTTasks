<link href="//netdna.bootstrapcdn.com/bootstrap/3.1.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<link href="https://tasks.pkirillw.ru/public/css/kanban.css" rel="stylesheet" id="bootstrap-css">
<script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
<script src="//netdna.bootstrapcdn.com/bootstrap/3.1.0/js/bootstrap.min.js"></script>
<meta name="csrf-token" content="{{ csrf_token() }}"/>
<meta name="user_id" content="{{ $user['id'] }}"/>
<script src="https://tasks.pkirillw.ru/public/js/kanban.js"></script>
<!------ Include the above in your HEAD tag ---------->
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.1/moment.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.1/locale/ru.js"></script>
<link rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css"/>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js"></script>
<div class="container-fluid ">
    <div class="board">
        <div id="sortableKanbanBoards" class="">

            <!--sütun başlangıç-->
            @foreach($board as $pipeline)
                <div class="panel panel-primary kanban-col">
                    <div class="panel-heading" style="{{$pipeline['style']}}">
                        {{$pipeline['name']}}
                    </div>
                    <div class="add-card">
                        <a href="#" data-toggle="modal" data-target="#createTask">Добавить задачу</a>
                    </div>
                    <div class="panel-body">
                        <div id="pipeline{{$pipeline['id']}}" class="kanban-centered">
                            @foreach($pipeline['tasks'] as $task)
                                <article class="kanban-entry grab border-{{$task['type']}}" id="task{{$task['id']}}"
                                         draggable="true">
                                    <div class="kanban-entry-inner">
                                        <div class="kanban-label">
                                            <h2><a href="#">{{$task['number_request']}}</a>
                                                <span class="pull-right"
                                                      @if($task['flag_expired'] == true)
                                                      style="color: red;"
                                                        @endif
                                                >
                                                    @if($task['flag_expired'] == true)
                                                        <span class="glyphicon glyphicon-flash"
                                                              aria-hidden="true"></span>
                                                    @endif

                                                    ({{date('d.m.Y H:i:s', $task['complite_till'])}})
                                                </span>
                                            </h2>
                                            <textarea id="comment{{$task['id']}}" style="
    border: 1px solid #ccc;
    min-height:  150px;
    margin: 5px 0;
    padding:  5px;
    font-size:  14px;
    max-width: 100%;
    min-width: 100%;
" onblur="saveText({{$task['id']}});">{{$task['comment']}}</textarea>
                                            <p></p>
                                            <div class="control-button row">
                                                <div class="col-sm-6">
                                                    <input class="form-control" data-toggle="modal"
                                                           data-target="#changeStatus" type="button"
                                                           onclick="changeStatusTaskId({{$task['id']}})"
                                                           value="Сменить статус">
                                                </div>
                                                <div class="col-sm-6">
                                                    <input class="form-control" data-toggle="modal"
                                                           data-target="#changeTimer" type="button"
                                                           onclick="changeTimerTaskId({{$task['id']}})"
                                                           value="Сменить таймер">
                                                </div>

                                            </div>
                                            <div class="row " style="    margin-top: 5px;">
                                                <div class="col-sm-12 text-center">
                                                    <a class="btn btn-primary" onclick="endTask({{$task['id']}})">
                                                        Завершить задачу
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </article>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>


</div>


<!-- Static Modal -->
<div class="modal modal-static fade" id="processing-modal" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <div class="text-center">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/7/7d/Pedro_luis_romani_ruiz.gif"
                         width="80px">
                    <h4>Перенос...</h4>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="createTask" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">Создание задачи</h4>
            </div>
            <div class="modal-body">
                <form role="form" action="/tasks/add" method="post">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="user_id" value="{{$user['id']}}">
                    <div class="form-group">
                        <label for="exampleInputEmail1">Наименование сделки</label>
                        <input type="text"  class="form-control" id="name_lead"
                               placeholder="ID Сделки">
                        <select class="form-control" name="lead_id" id="lead_select">
                            <option disabled="disabled">----</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1">Воронка</label>
                        <select name="pipeline_id" class="form-control">
                            <option value="1">Качество запроса</option>
                            <option value="2">Назначен поставщик</option>
                            <option value="3">На контроль</option>
                            <option value="4">Выполнен</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1">Цвет задачи</label>
                        <select name="type_id" class="form-control">
                            <option style="background: #ff0000; color: #000;" value="1">Красный</option>
                            <option style="background: #ffc000; color: #000;" value="2">Желтый</option>
                            <option style="background: #92d050; color: #000;" value="3">Зеленый</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1">Наименование задачи</label>
                        <input type="text" name="number_request" class="form-control" id="exampleInputEmail1"
                               placeholder="№123456">
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1">Позиция</label>
                        <input type="number" name="position" class="form-control" id="exampleInputEmail1"
                               placeholder="0">
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1">Комментарий</label>
                        <textarea name="comment" class="form-control" rows="3"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1">Время окончания задачи</label>
                        <div class='input-group date' id='datetimepicker1'>
                            <input name="complite_till" type='text' class="form-control"/>
                            <span class="input-group-addon">
                                <span class="glyphicon glyphicon-calendar"></span>
                            </span>
                        </div>
                    </div>
                    <div class="text-center">
                        <button type="submit" class="btn btn-primary">Отправить</button>
                    </div>

                </form>
            </div>

        </div>
    </div>
</div>

<div class="modal fade" id="changeTimer" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">Изменение таймера</h4>
            </div>
            <div class="modal-body">
                <form role="form" action="/tasks/changeTimer" method="post">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="user_id" value="{{$user['id']}}">
                    <input type="hidden" name="task_id" id="task_id_timer" value="">
                    <div class="form-group">
                        <label for="exampleInputEmail1">Время окончания задачи</label>
                        <div class='input-group date' id='datetimepicker2'>
                            <input name="complite_till" type='text' class="form-control"/>
                            <span class="input-group-addon">
                                <span class="glyphicon glyphicon-calendar"></span>
                            </span>
                        </div>
                    </div>
                    <div class="text-center">
                        <button type="submit" class="btn btn-primary">Отправить</button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="changeStatus" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">Смена статуса</h4>
            </div>
            <div class="modal-body">
                <form role="form" action="/tasks/changeStatus" method="post">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="user_id" value="{{$user['id']}}">
                    <input type="hidden" name="task_id" id="task_id_status" value="">
                    <div class="form-group">
                        <label for="exampleInputEmail1">Цвет задачи</label>
                        <select name="type_id" class="form-control">
                            <option style="background: #ff0000; color: #000;" value="1">Красный</option>
                            <option style="background: #ffc000; color: #000;" value="2">Желтый</option>
                            <option style="background: #92d050; color: #000;" value="3">Зеленый</option>
                        </select>
                    </div>
                    <div class="text-center">
                        <button type="submit" class="btn btn-primary">Отправить</button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>