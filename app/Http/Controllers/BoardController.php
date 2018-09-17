<?php
/**
 * Created by PhpStorm.
 * User: Kirill
 * Date: 10.04.2018
 * Time: 23:02
 */

namespace App\Http\Controllers;


use App\Pipelines;
use App\Tasks;
use App\Types;

class BoardController extends Controller
{
    private $_taskNameTypes = [
        1 => 'Связаться с клиентом',
        13 => 'Cвязаться с поставщиком', // 2
        3 => 'Жду информацию от клиента',
        4 => 'Отправлено ТКП',
        5 => 'Отправлен счет на оплату',
        6 => 'Отправлен запрос поставщику',
        7 => 'Жду инфрмацию от поставщика',
        8 => 'Жду оплату от клиента',
        9 => 'Жду оплату поставщику',
        10 => 'Разместить в производство',
        11 => 'Срочная отгрузка',
        12 => 'Отгрузка',
        14 => 'Связаться по возможным заявкам', //13
        15 => 'Проблемная отгрузка', //14
        16 => 'Тендер - запрос специалисту', //15
        17 => 'Тендер - ТКП', //16
        18 => 'Тендер - Жду результат', //17
        19 => 'Подготовить ТКП',
        20 => 'Подготовить Счет и договор',
        21 => 'Отправлен проект договора'
    ];


    public function board($id = 0)
    {
        if ($id == 0) {
            return 'Error';
        }
        $templateData = [];
        $pipelines = Pipelines::all();
        $typesTasks = Types::all()->toArray();
        $userTasks = Tasks::where([['user_id', '=', $id], ['status', '=', 0]])->orderBy('complite_till', 'asc')->get();
        foreach ($pipelines as $pipeline) {
            $templateData['board'][$pipeline->id] = [
                'id' => $pipeline->id,
                'name' => $pipeline->name,
                'position' => $pipeline->position,
                'style' => $pipeline->style,
                'tasks' => []
            ];
        }
        foreach ($userTasks as $userTask) {
            $task = $userTask->toArray();
            $keyTypeTask = array_search($userTask->type_id, array_column($typesTasks, 'id'));
            if ($keyTypeTask !== false) {
                $task['type'] = $typesTasks[$keyTypeTask]['name'];
            }
            $task['name'] = [
                'type_name' => $this->_taskNameTypes[$task['task_name_type']],
                'name' => $task['number_request']
            ];
            if ($task['complite_till'] < time()) {
                $task['flag_expired'] = true;
            } else {
                $task['flag_expired'] = false;
            }
            $templateData['board'][$userTask->pipeline_id]['tasks'][] = $task;

        }
        $templateData['user']['id'] = $id;
        echo view('board2', $templateData);
    }
    public function oldBoard($id = 0)
    {
        if ($id == 0) {
            return 'Error';
        }
        $templateData = [];
        $pipelines = Pipelines::all();
        $typesTasks = Types::all()->toArray();
        $userTasks = Tasks::where([['user_id', '=', $id], ['status', '=', 0]])->orderBy('complite_till', 'asc')->get();
        foreach ($pipelines as $pipeline) {
            $templateData['board'][$pipeline->id] = [
                'id' => $pipeline->id,
                'name' => $pipeline->name,
                'position' => $pipeline->position,
                'style' => $pipeline->style,
                'tasks' => []
            ];
        }
        foreach ($userTasks as $userTask) {
            $task = $userTask->toArray();
            $keyTypeTask = array_search($userTask->type_id, array_column($typesTasks, 'id'));
            if ($keyTypeTask !== false) {
                $task['type'] = $typesTasks[$keyTypeTask]['name'];
            }
            $task['name'] = [
                'type_name' => $this->_taskNameTypes[$task['task_name_type']],
                'name' => $task['number_request']
            ];
            if ($task['complite_till'] < time()) {
                $task['flag_expired'] = true;
            } else {
                $task['flag_expired'] = false;
            }
            $templateData['board'][$userTask->pipeline_id]['tasks'][] = $task;

        }
        $templateData['user']['id'] = $id;
        echo view('board3', $templateData);
    }

    public function getCounterTasks($id = 0)
    {
        $counter = 0;
        if ($id == 0) {
            return 'Error';
        }
        $userTasks = Tasks::where([['user_id', '=', $id], ['status', '=', 0]])->orderBy('complite_till', 'asc')->get();

        foreach ($userTasks as $userTask) {
            $task = $userTask->toArray();
            if ($task['complite_till'] < time()) {
                $counter++;
            }
        }
        return response()->json(['counter' => $counter]);
    }

    public function ends($id = 0)
    {
        if ($id == 0) {
            return 'Error';
        }
        $templateData = [];
        $pipelines = Pipelines::all();
        $typesTasks = Types::all()->toArray();
        $userTasks = Tasks::where([['user_id', '=', $id], ['status', '=', 1]])->orderBy('complite_till', 'asc')->get();
        foreach ($pipelines as $pipeline) {
            $templateData['board'][$pipeline->id] = [
                'id' => $pipeline->id,
                'name' => $pipeline->name,
                'position' => $pipeline->position,
                'style' => $pipeline->style,
                'tasks' => []
            ];
        }
        foreach ($userTasks as $userTask) {
            $task = $userTask->toArray();
            $keyTypeTask = array_search($userTask->type_id, array_column($typesTasks, 'id'));
            if ($keyTypeTask !== false) {
                $task['type'] = $typesTasks[$keyTypeTask]['name'];
            }
            $task['name'] = [
                'type_name' => $this->_taskNameTypes[$task['task_name_type']],
                'name' => $task['number_request']
            ];
            if ($task['complite_till'] < time()) {
                $task['flag_expired'] = true;
            } else {
                $task['flag_expired'] = false;
            }
            $templateData['board'][$userTask->pipeline_id]['tasks'][] = $task;

        }
        $templateData['user']['id'] = $id;
        echo view('board2', $templateData);
    }

    public function openBoard($id = 0)
    {
        dd(config('apierror._empty_task'));
        print_r(public_path());
        print_r(config('app.timezone'));
        dd(time());
        return redirect()->action(
            'BoardController@Board', ['id' => $id]
        );
    }

    public function expired($id = 0)
    {
        if ($id == 0) {
            return 'Error';
        }
        $templateData = [];
        $pipelines = Pipelines::all();
        $typesTasks = Types::all()->toArray();
        $userTasks = Tasks::where([['user_id', '=', $id], ['status', '=', 0]])->orderBy('complite_till', 'asc')->get();
        foreach ($pipelines as $pipeline) {
            $templateData['board'][$pipeline->id] = [
                'id' => $pipeline->id,
                'name' => $pipeline->name,
                'position' => $pipeline->position,
                'style' => $pipeline->style,
                'tasks' => []
            ];
        }
        foreach ($userTasks as $userTask) {
            $task = $userTask->toArray();
            $keyTypeTask = array_search($userTask->type_id, array_column($typesTasks, 'id'));
            if ($keyTypeTask !== false) {
                $task['type'] = $typesTasks[$keyTypeTask]['name'];
            }
            $task['name'] = [
                'type_name' => $this->_taskNameTypes[$task['task_name_type']],
                'name' => $task['number_request']
            ];
            if ($task['complite_till'] < time()) {
                $task['flag_expired'] = true;
                $templateData['board'][$userTask->pipeline_id]['tasks'][] = $task;
            } else {
                $task['flag_expired'] = false;
            }
        }
        $templateData['user']['id'] = $id;
        echo view('board2', $templateData);
    }

    public function today($id = 0)
    {
        if ($id == 0) {
            return 'Error';
        }
        $templateData = [];
        $pipelines = Pipelines::all();
        $typesTasks = Types::all()->toArray();
        $userTasks = Tasks::where([['user_id', '=', $id], ['status', '=', 0]])->orderBy('complite_till', 'asc')->get();
        foreach ($pipelines as $pipeline) {
            $templateData['board'][$pipeline->id] = [
                'id' => $pipeline->id,
                'name' => $pipeline->name,
                'position' => $pipeline->position,
                'style' => $pipeline->style,
                'tasks' => []
            ];
        }
        foreach ($userTasks as $userTask) {
            $task = $userTask->toArray();
            $keyTypeTask = array_search($userTask->type_id, array_column($typesTasks, 'id'));
            if ($keyTypeTask !== false) {
                $task['type'] = $typesTasks[$keyTypeTask]['name'];
            }
            $task['name'] = [
                'type_name' => $this->_taskNameTypes[$task['task_name_type']],
                'name' => $task['number_request']
            ];
            if ($task['complite_till'] < time()) {
                $task['flag_expired'] = true;
            } else {
                $task['flag_expired'] = false;
            }
            if ($task['complite_till'] < mktime(23, 59, 59, date('m'), date('d'), date('Y'))) {
                $templateData['board'][$userTask->pipeline_id]['tasks'][] = $task;
            }

        }
        $templateData['user']['id'] = $id;
        echo view('board2', $templateData);
    }

    public function week($id = 0)
    {
        if ($id == 0) {
            return 'Error';
        }
        $templateData = [];
        $pipelines = Pipelines::all();
        $typesTasks = Types::all()->toArray();
        $userTasks = Tasks::where([['user_id', '=', $id], ['status', '=', 0]])->orderBy('complite_till', 'asc')->get();
        foreach ($pipelines as $pipeline) {
            $templateData['board'][$pipeline->id] = [
                'id' => $pipeline->id,
                'name' => $pipeline->name,
                'position' => $pipeline->position,
                'style' => $pipeline->style,
                'tasks' => []
            ];
        }
        foreach ($userTasks as $userTask) {
            $task = $userTask->toArray();
            $keyTypeTask = array_search($userTask->type_id, array_column($typesTasks, 'id'));
            if ($keyTypeTask !== false) {
                $task['type'] = $typesTasks[$keyTypeTask]['name'];
            }
            $task['name'] = [
                'type_name' => $this->_taskNameTypes[$task['task_name_type']],
                'name' => $task['number_request']
            ];
            if ($task['complite_till'] < time()) {
                $task['flag_expired'] = true;
            } else {
                $task['flag_expired'] = false;
            }
            if ($task['complite_till'] < time() + 60 * 60 * 24 * 7) {
                $templateData['board'][$userTask->pipeline_id]['tasks'][] = $task;
            }
        }
        $templateData['user']['id'] = $id;
        echo view('board2', $templateData);
    }

    public function month($id = 0)
    {
        if ($id == 0) {
            return 'Error';
        }
        $templateData = [];
        $pipelines = Pipelines::all();
        $typesTasks = Types::all()->toArray();
        $userTasks = Tasks::where([['user_id', '=', $id], ['status', '=', 0]])->orderBy('complite_till', 'asc')->get();
        foreach ($pipelines as $pipeline) {
            $templateData['board'][$pipeline->id] = [
                'id' => $pipeline->id,
                'name' => $pipeline->name,
                'position' => $pipeline->position,
                'style' => $pipeline->style,
                'tasks' => []
            ];
        }
        foreach ($userTasks as $userTask) {
            $task = $userTask->toArray();
            $keyTypeTask = array_search($userTask->type_id, array_column($typesTasks, 'id'));
            if ($keyTypeTask !== false) {
                $task['type'] = $typesTasks[$keyTypeTask]['name'];
            }
            $task['name'] = [
                'type_name' => $this->_taskNameTypes[$task['task_name_type']],
                'name' => $task['number_request']
            ];
            if ($task['complite_till'] < time()) {
                $task['flag_expired'] = true;
            } else {
                $task['flag_expired'] = false;
            }
            if ($task['complite_till'] < time() + 60 * 60 * 24 * 30) {
                $templateData['board'][$userTask->pipeline_id]['tasks'][] = $task;
            }
        }
        $templateData['user']['id'] = $id;
        echo view('board2', $templateData);
    }

    public function search($id = 0, $text = '')
    {
        if ($id == 0) {
            return 'Error';
        }
        $templateData = [];
        $pipelines = Pipelines::all();
        $typesTasks = Types::all()->toArray();
        $userTasks = Tasks::where([['user_id', '=', $id], ['status', '=', 0]])->orderBy('complite_till', 'asc')->get();
        foreach ($pipelines as $pipeline) {
            $templateData['board'][$pipeline->id] = [
                'id' => $pipeline->id,
                'name' => $pipeline->name,
                'position' => $pipeline->position,
                'style' => $pipeline->style,
                'tasks' => []
            ];
        }
        foreach ($userTasks as $userTask) {
            $task = $userTask->toArray();
            $keyTypeTask = array_search($userTask->type_id, array_column($typesTasks, 'id'));
            if ($keyTypeTask !== false) {
                $task['type'] = $typesTasks[$keyTypeTask]['name'];
            }
            $task['name'] = [
                'type_name' => $this->_taskNameTypes[$task['task_name_type']],
                'name' => $task['number_request']
            ];
            if ($task['complite_till'] < time()) {
                $task['flag_expired'] = true;
            } else {
                $task['flag_expired'] = false;
            }
            if (stripos($task['number_request'], $text) !== false || stripos($task['comment'], $text) !== false) {
                $templateData['board'][$userTask->pipeline_id]['tasks'][] = $task;
            }
        }
        $templateData['user']['id'] = $id;
        echo view('board2', $templateData);
    }


}