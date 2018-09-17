<?php
/**
 * Created by PhpStorm.
 * User: Kirill
 * Date: 10.04.2018
 * Time: 22:44
 */

namespace App\Http\Controllers;


use App\Pipelines;
use App\Tasks;
use App\Types;
use Dotzero\LaravelAmoCrm\Facades\AmoCrm;
use Illuminate\Http\Request;
use MongoDB\BSON\Type;

class TasksController
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

    public function changePipeline(Request $request)
    {
        if (empty($request)) {
            return 'error';
        }
        $task = Tasks::where([['id', '=', $request->taskId]])->first();
        $task->pipeline_id = $request->newPipelineId;
        $task->save();
        return response()->json($task);
    }

    public function getTaskAPI($id = 0)
    {
        $response = [];
        $task = Tasks::where([['id', '=', $id]])->first();
        if (!empty($task)) {

            $temp = $task->toArray();

            unset($temp['type_id']);
            unset($temp['pipeline_id']);
            $typeTask = Types::where([['id', '=', $task->type_id]])->first();
            $temp['type'] = $typeTask->toArray();
            $pipelineTask = Pipelines::where([['id', '=', $task->pipeline_id]])->first();
            $temp['name'] = [
                'type_name' => config('tasktypes.taskTypes')[$temp['task_name_type']],
                'name' => $temp['number_request']
            ];
            $temp['pipeline'] = $pipelineTask->toArray();
            $temp['created_task_format'] = date('d.m.Y H:i', $temp['created_task']);
            $temp['complite_till_format'] = date('d.m.Y H:i', $temp['complite_till']);
            if ($temp['complite_till'] < time()) {
                $temp['flag_expired'] = true;
            } else {
                $temp['flag_expired'] = false;
            }
            $response = $temp;
        }
        return response()->json($response);

    }

    public function addTaskAPI(Request $request)
    {
        $newTask = new Tasks();
        $newTask->pipeline_id = $request->pipeline_id;
        $newTask->type_id = $request->type_id;
        $newTask->user_id = $request->user_id;
        $newTask->amo_id = $request->amo_id;
        $newTask->number_request = $request->number_request;
        $newTask->task_name_type = $request->type_name_task;
        $newTask->url1 = empty($request->url1) ? '' : $request->url1;
        $newTask->url2 = empty($request->url2) ? '' : $request->url2;
        $newTask->position = $request->position;
        $newTask->comment = $request->comment;
        $newTask->created_task = time();
        $newTask->complite_till = \DateTime::createFromFormat('d.m.Y H:i', $request->complite_till)->format('U');
        $newTask->status = 0;
        $newTask->save();
        return response()->json($newTask->toArray());
    }

    public function editTaskAPI(Request $request)
    {
        $task = Tasks::where([['id', '=', $request->task_id]])->first();
        $task->pipeline_id = $request->pipeline_id;
        $task->type_id = $request->type_id;
        $task->user_id = $request->user_id;
        $task->amo_id = $request->amo_id;
        $task->number_request = $request->number_request;
        $task->task_name_type = $request->task_name_type;
        $task->url1 = empty($request->url1) ? '' : $request->url1;
        $task->url2 = empty($request->url2) ? '' : $request->url2;
        $task->position = $request->position;
        $task->comment = $request->comment;
        $task->created_task = time();
        $task->complite_till = \DateTime::createFromFormat('d.m.Y H:i', $request->complite_till)->format('U');
        $task->status = 0;
        $task->save();
        return response()->json($task->toArray());
    }

    public function endTask($taskId)
    {
        if (empty($taskId)) {
            return 'error';
        }
        $task = Tasks::where([['id', '=', $taskId]])->first();
        $task->status = 1;
        $task->save();
        return response()->json([$taskId]);
    }

    public function changeStatus(Request $request)
    {
        if (empty($request->task_id)) {
            return 'error';
        }
        $task = Tasks::where([['id', '=', $request->task_id]])->first();
        $task->type_id = $request->type_id;
        $task->save();
        return redirect()->action(
            'BoardController@Board', ['id' => $request->user_id]
        );
    }

    public function changeTimer(Request $request)
    {
        if (empty($request->task_id)) {
            return 'error';
        }
        $task = Tasks::where([['id', '=', $request->task_id]])->first();
        $task->complite_till = \DateTime::createFromFormat('d.m.Y H:i', $request->complite_till)->format('U');
        $task->save();
        return redirect()->action(
            'BoardController@Board', ['id' => $request->user_id]
        );
    }

    public function changeText(Request $request)
    {
        if (empty($request)) {
            return 'error';
        }
        $task = Tasks::where([['id', '=', $request->taskId]])->first();
        $task->comment = $request->text;
        $task->save();

    }

    public function add(Request $request)
    {
        $newTask = new Tasks();
        $newTask->pipeline_id = $request->pipeline_id;
        $newTask->type_id = $request->type_id;
        $newTask->user_id = $request->user_id;
        $newTask->amo_id = $request->lead_id;
        $newTask->number_request = $request->number_request1;
        $newTask->url1 = empty($request->url_1) ? '' : $request->url_1;
        $newTask->url2 = empty($request->url_2) ? '' : $request->url_2;
        $newTask->task_name_type = $request->number_request;
        $newTask->position = 0;
        $newTask->comment = $request->comment;
        $newTask->created_task = time();
        $newTask->complite_till = \DateTime::createFromFormat('d.m.Y H:i', $request->complite_till)->format('U');
        $newTask->status = 0;
        $newTask->save();
        //print_r($request->all());
        //dd($newTask->toArray());
        return back();
        return redirect()->action(
            'BoardController@Board', ['id' => $request->user_id]
        );
        dd($request->all());
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function edit(Request $request)
    {

        $task = Tasks::where([['id', '=', $request->task_id]])->first();
        $task->pipeline_id = $request->pipeline_id;
        $task->type_id = $request->type_id;
        $task->user_id = $request->user_id;
        $task->task_name_type = $request->number_request;
        $task->position = 0;
        if ($task->comment != $request->comment) {
            $taskCommentTemp = $task->comment;
            $requestCommentTemp = $request->comment;
            $newComment = str_replace($taskCommentTemp, '', $requestCommentTemp);
            $newComment = '<hr>' . '<span class="commentDate">' . date('d.m') . '</span> ' . $newComment;
            $task->comment .= $newComment;
        }

        $task->url1 = empty($request->url_1) ? '' : $request->url_1;
        $task->url2 = empty($request->url_2) ? '' : $request->url_2;
        $task->created_task = time();
        $task->complite_till = \DateTime::createFromFormat('d.m.Y H:i', $request->complite_till)->format('U');
        $task->status = 0;
        $task->save();
        //print_r($request->all());
        //dd($task->toArray());
        return back();
        return redirect()->action(
            'BoardController@Board', ['id' => $request->user_id]
        );
        dd($request->all());
    }

    public function getLeadTask($leadId = 0)
    {
        $response = [];
        $task = Tasks::where([['amo_id', '=', $leadId], ['status', '=', 0]])->orderBy('complite_till', 'asc')->get();
        if (!empty($task)) {
            foreach ($task as $item) {
                $temp = $item->toArray();

                unset($temp['type_id']);
                unset($temp['pipeline_id']);
                $typeTask = Types::where([['id', '=', $item->type_id]])->first();
                $temp['type'] = $typeTask->toArray();
                $temp['name'] = [
                    'type_name' => config('tasktypes.taskTypes')[$temp['task_name_type']],
                    'name' => $temp['number_request']
                ];
                $pipelineTask = Pipelines::where([['id', '=', $item->pipeline_id]])->first();
                $temp['pipeline'] = $pipelineTask->toArray();
                $temp['created_task_format'] = date('d.m.Y H:i', $temp['created_task']);
                $temp['complite_till_format'] = date('d.m.Y H:i', $temp['complite_till']);
                if ($temp['complite_till'] < time()) {
                    $temp['flag_expired'] = true;
                } else {
                    $temp['flag_expired'] = false;
                }
                $response[] = $temp;
            }

        }
        return response()->json($response);
    }

    public function getLeads($text = '')
    {
        $client = AmoCrm::getClient();
        return response()->json(($client->lead->apiList([
            'query' => $text,
        ])));
    }

    public function getLeadInfo($amoId = '')
    {
        $response = [];
        $email = '';
        $telphone = '';
        $client = AmoCrm::getClient();
        $leadData = $client->lead->apiList([
            'id' => $amoId,
        ]);
        if (empty($leadData[0])) {
            return;
        }
        $contactData = $client->contact->apiList(
            [
                'id' => $leadData[0]['main_contact_id'],
            ]
        );
        if (array_search(1513982, array_column($contactData[0]['custom_fields'], 'id')) !== false) {
            $email = $contactData[0]['custom_fields'][array_search(1513982, array_column($contactData[0]['custom_fields'], 'id'))]['values'][0]['value'];
        }
        if (array_search(1513980, array_column($contactData[0]['custom_fields'], 'id')) !== false) {
            $telphone = $contactData[0]['custom_fields'][array_search(1513980, array_column($contactData[0]['custom_fields'], 'id'))]['values'][0]['value'];
        }
        $response = [
            'nameCompany' => $contactData[0]['company_name'],
            'nameContact' => $contactData[0]['name'],
            'telphone' => $telphone,
            'email' => $email,
        ];
        return response()->json($response);
    }
}