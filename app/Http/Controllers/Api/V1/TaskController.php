<?php
/**
 * Created by PhpStorm.
 * User: Kirill
 * Date: 04.09.2018
 * Time: 16:26
 */

namespace App\Http\Controllers\Api\V1;


use App\Comments;
use App\Http\Controllers\Controller;
use App\Notifications;
use App\Pipelines;
use App\Tasks;
use App\Traits\ApiResponse;
use App\Types;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

/**
 * Class TaskController
 * @package App\Http\Controllers\Api\V1
 */
class TaskController extends Controller
{
    use ApiResponse;


    /**
     * @param int $taskId
     * @return \Illuminate\Http\JsonResponse
     */
    public function get($taskId = 0)
    {

        if ($taskId == 0) {
            return $this->prepareReturn('_empty_task_id', 'error');
        }
        $task = Tasks::where([['id', '=', $taskId]])->first();
        if (empty($task)) {
            return $this->prepareReturn('_empty_task', 'error');
        }

        $response = $task->toArray();

        unset($response['type_id']);
        unset($response['pipeline_id']);
        $typeTask = Types::where([['id', '=', $task->type_id]])->first();
        $response['type'] = $typeTask->toArray();
        $pipelineTask = Pipelines::where([['id', '=', $task->pipeline_id]])->first();
        $response['name'] = [
            'type_name' => config('tasktypes.taskTypes')[$response['task_name_type']],
            'name' => $response['number_request']
        ];
        $response['pipeline'] = $pipelineTask->toArray();
        $response['created_task_format'] = date('d.m.Y H:i', $response['created_task']);
        $response['complite_till_format'] = date('d.m.Y H:i', $response['complite_till']);
        if ($response['complite_till'] < time()) {
            $response['flag_expired'] = true;
        } else {
            $response['flag_expired'] = false;
        }
        return $this->prepareReturn($response);

    }


    /**
     * @param int $userId
     * @param string $mode
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUserTasks($userId = 0, $mode = 'all')
    {
        if ($userId == 0) {
            return $this->prepareReturn('_empty_user_id', 'error');
        }
        $response = [];
        $typesTasks = Types::all()->toArray();
        if ($mode == 'end') {
            $userTasks = Tasks::where([['user_id', '=', $userId], ['status', '=', 1]])->orderBy('complite_till', 'asc')->get();
        } else {
            $userTasks = Tasks::where([['user_id', '=', $userId], ['status', '=', 0]])->orderBy('complite_till', 'asc')->get();
        }
        if (empty($userTasks->toArray())) {
            return $this->prepareReturn('_empty_user_tasks', 'error');
        }
        foreach ($userTasks as $userTask) {
            $task = $userTask->toArray();
            $keyTypeTask = array_search($userTask->type_id, array_column($typesTasks, 'id'));
            if ($keyTypeTask !== false) {
                $task['type'] = $typesTasks[$keyTypeTask]['name'];
            }
            $task['name'] = [
                'type_name' => config('tasktypes.taskTypes')[$task['task_name_type']],
                'name' => $task['number_request']
            ];
            if (stripos($mode, 'search') !== false) {
                $match = explode('|', $mode);
                if (stripos($task['number_request'], $match[1]) === false) {
                    continue;
                }
            }
            if (stripos($mode, 'type') !== false) {
                $match = explode('|', $mode);
                if ($task['type_id'] != $match[1]) {
                    continue;
                }
            }
            if (stripos($mode, 'interval') !== false) {
                $match = explode('|', $mode);

                $from = \DateTime::createFromFormat('d.m.Y H:i', $match[1])->format('U');
                $to = \DateTime::createFromFormat('d.m.Y H:i', $match[2])->format('U');
                if (((int)$task['complite_till'] < (int)$from) || ((int)$task['complite_till'] > (int)$to)) {
                    continue;
                }
            }
            if (($task['complite_till'] > mktime(23, 59, 59, date('m'), date('d'), date('Y'))) && ($mode == 'today')) {
                continue;
            }
            if (($task['complite_till'] > date("U", strtotime("Sunday"))) && ($mode == 'week')) {
                continue;
            }
            if ($mode == 'month') {
                $day = new \DateTime(date('Y-m-d'));
                $endmonth = new \DateTime(date('Y-m-' . $day->format('t')));
                if ($task['complite_till'] > ($endmonth->format('U') + 24 * 60 * 60)) {
                    continue;
                }
            }
            if ($task['complite_till'] < time()) {
                $task['flag_expired'] = true;
            } else {
                $task['flag_expired'] = false;
                if ($mode == 'expired') {
                    continue;
                }
            }
            $response['tasks'][] = $task;

        }
        $response['user']['id'] = $userId;

        return $this->prepareReturn($response);
    }


    /**
     * @param int $leadId
     * @return \Illuminate\Http\JsonResponse
     */
    public function getLeadTasks($leadId = 0)
    {
        if ($leadId == 0) {
            return $this->prepareReturn('_empty_lead_id', 'error');
        }
        $response = [];

        $tasks = Tasks::where([['amo_id', '=', $leadId], ['status', '=', 0]])->orderBy('complite_till', 'asc')->get();


        if (empty($tasks->toArray())) {
            return $this->prepareReturn('_empty_lead_tasks', 'error');
        }
        if (!empty($tasks)) {
            foreach ($tasks as $item) {
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
        return $this->prepareReturn($response);
    }

    /**
     * @param int $userId
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCountExpiredTasks($userId = 0)
    {
        $counter = 0;
        if ($userId == 0) {
            return $this->prepareReturn('_empty_user_id', 'error');
        }
        $userTasks = Tasks::where([['user_id', '=', $userId], ['status', '=', 0]])->orderBy('complite_till', 'asc')->get();

        foreach ($userTasks as $userTask) {
            $task = $userTask->toArray();
            if ($task['complite_till'] < time()) {
                $counter++;
            }
        }
        return $this->prepareReturn(['counter' => $counter]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updatePipeline(Request $request)
    {
        if (empty($request)) {
            return $this->prepareReturn('_empty_request', 'error');
        }
        $task = Tasks::where([['id', '=', $request->taskId]])->first();
        $task->pipeline_id = $request->newPipelineId;
        $task->save();
        return $this->prepareReturn($task->toArray());
    }


    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function add(Request $request)
    {
        if (empty($request)) {
            return $this->prepareReturn('_empty_request', 'error');
        }
        $newTask = new Tasks();
        $newTask->pipeline_id = $request->pipeline_id;
        $newTask->type_id = $request->type_id;
        $newTask->user_id = $request->user_id;
        $newTask->amo_id = $request->amo_id;
        $newTask->number_request = $request->number_request;
        $newTask->task_name_type = $request->task_name_type;
        $newTask->url1 = empty($request->url1) ? '' : $request->url1;
        $newTask->url2 = empty($request->url2) ? '' : $request->url2;
        $newTask->position = $request->position;
        $newTask->created_task = time();
        $newTask->complite_till = \DateTime::createFromFormat('d.m.Y H:i', $request->complite_till)->format('U');
        $newTask->status = 0;
        $newTask->save();
        if (!empty($request->comment)) {
            $newComment = new Comments();
            $newComment->task_id = $newTask->id;
            $newComment->name = 'test';
            $newComment->text = $request->comment;
            $newComment->save();
        }
        if ($request->notification == 'true') {
            $newNotification = new Notifications();
            $newNotification->user_id = $request->user_id;
            $newNotification->task_id = $newTask->id;
            $newNotification->status = 0;
            $newNotification->calltime = ($newTask->complite_till - 2 * 60);
            $newNotification->save();
        } else {
            $apiNotificationController = new NotificationController();
            $apiNotificationController->removeTaskNotification($newTask->id);
        }
        return $this->prepareReturn($newTask->toArray());
    }


    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request)
    {
        if (empty($request)) {
            return $this->prepareReturn('_empty_request', 'error');
        }
        $task = Tasks::where([['id', '=', $request->task_id]])->first();
        $task->pipeline_id = $request->pipeline_id;
        $task->type_id = $request->type_id;
        $task->user_id = $request->user_id;
        $task->task_name_type = $request->task_name_type;
        $task->position = 0;
        $task->url1 = empty($request->url1) ? '' : $request->url1;
        $task->url2 = empty($request->url2) ? '' : $request->url2;
        $task->created_task = time();
        $task->complite_till = \DateTime::createFromFormat('d.m.Y H:i', $request->complite_till)->format('U');
        $task->status = 0;
        $task->save();
        if (!empty($request->comment)) {
            $newComment = new Comments();
            $newComment->task_id = $task->id;
            $newComment->name = 'test';
            $newComment->text = $request->comment;
            $newComment->save();
        }
        if ($request->notification == 'true') {
            $newNotification = new Notifications();
            $newNotification->user_id = $request->user_id;
            $newNotification->task_id = $task->id;
            $newNotification->status = 0;
            $newNotification->calltime = ($task->complite_till - 2 * 60);
            $newNotification->save();
        } else {
            $apiNotificationController = new NotificationController();
            $apiNotificationController->removeTaskNotification($task->id);
        }
        return $this->prepareReturn($task->toArray());
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkTime(Request $request)
    {
        if (empty($request)) {
            return $this->prepareReturn('_empty_request', 'error');
        }
        $time = \DateTime::createFromFormat('d.m.Y H:i', $request->time)->format('U');
        $nextTime = $time;
        $oldTime = $time;
        $task = Tasks::where([['user_id', '=', $request->user_id], ['complite_till', '=', $time]])->first();
        $tempTask = $task;
        while (!empty($task)) {
            $nextTime = $nextTime + 60 * 15;
            $task = Tasks::where([['user_id', '=', $request->user_id], ['complite_till', '=', $nextTime]])->first();
        }

        while (!empty($tempTask)) {
            $oldTime = $oldTime - 60 * 15;
            $tempTask = Tasks::where([['user_id', '=', $request->user_id], ['complite_till', '=', $oldTime]])->first();
        }
        if ($nextTime != $oldTime) {
            return $this->prepareReturn([
                'nextTime' => date('d.m.Y H:i', $nextTime),
                'oldTime' => date('d.m.Y H:i', $oldTime)
            ]);
        } else {
            return $this->prepareReturn(['time' => date('d.m.Y H:i', $time)]);
        }
    }

    /**
     * @param int $taskId
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete($taskId = 0)
    {
        if ($taskId == 0) {
            return $this->prepareReturn('_empty_task_id', 'error');
        }
        $task = Tasks::where([['id', '=', $taskId]])->first();
        if (empty($task)) {
            return $this->prepareReturn('_empty_task', 'error');
        }
        $task->status = 1;
        $task->save();
        return $this->prepareReturn($task->toArray());
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function miniEdit(Request $request)
    {
        if (empty($request)) {
            return $this->prepareReturn('_empty_request', 'error');
        }
        $task = Tasks::where([['id', '=', $request->task_id]])->first();
        $task->complite_till = \DateTime::createFromFormat('d.m.Y H:i', $request->complite_till)->format('U');
        $task->save();
        if ($request->notification == 'true') {
            $newNotification = new Notifications();
            $newNotification->user_id = $task->user_id;
            $newNotification->task_id = $task->id;
            $newNotification->status = 0;
            $newNotification->calltime = ($task->complite_till - 2 * 60);
            $newNotification->save();
        } else {
            $apiNotificationController = new NotificationController();
            $apiNotificationController->removeTaskNotification($task->id);
        }
        if ($request->comment != '') {
            $commentData = [
                'task_id' => $request->task_id,
                'name' => 'test',
                'text' => $request->comment
            ];
            $commentRequest = new Request();
            $commentRequest->setMethod('GET');
            $commentRequest->query->add($commentData);
            $apiCommentController = new CommentsController();
            $apiCommentController->add($commentRequest);
        }
        return $this->prepareReturn($task->toArray());

    }

}