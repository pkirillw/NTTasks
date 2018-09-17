<?php
/**
 * Created by PhpStorm.
 * User: Kirill
 * Date: 06.09.2018
 * Time: 1:55
 */

namespace App\Http\Controllers\Api\V1;


use App\Comments;
use App\Http\Controllers\Controller;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

/**
 * Class CommentsController
 * @package App\Http\Controllers\Api\V1
 */
class CommentsController extends Controller
{
    use ApiResponse;

    /**
     * @param int $taskId
     * @return \Illuminate\Http\JsonResponse
     */
    public function getTaskComments($taskId = 0)
    {
        $response = [];
        if ($taskId == 0) {
            return $this->prepareReturn('_empty_task_id', 'error');
        }
        $comments = Comments::where([['task_id', '=', $taskId]])->orderBy('id', 'desc')->get();
        if (empty($comments->toArray())) {
            return $this->prepareReturn('_empty_comments_task', 'error');
        }

        foreach ($comments->toArray() as $comment) {
            $tempComment = $comment;
            $tempComment['created_at'] = \DateTime::createFromFormat('Y-m-d H:i:s', $comment['created_at'])->format('d.m.Y H:i');
            $response[] = $tempComment;
        }
        return $this->prepareReturn($response);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function add(Request $request)
    {
        if (empty($request->all())) {
            return $this->prepareReturn('_empty_request', 'error');
        }
        $newComment = new Comments();
        $newComment->task_id = $request->task_id;
        $newComment->name = $request->name;
        $newComment->text = $request->text;
        $newComment->save();
        return $this->prepareReturn($newComment->toArray());
    }
}