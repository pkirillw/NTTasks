<?php
/**
 * Created by PhpStorm.
 * User: Kirill
 * Date: 02.10.2018
 * Time: 16:14
 */

namespace App\Http\Controllers;


use Illuminate\Http\Request;

class WebHookController extends Controller
{

    public function listen(Request $request)
    {
        $filename = 'webhook.txt';
        file_put_contents($filename, json_encode($request->all()) . PHP_EOL, FILE_APPEND);
    }

    public function status_lead(Request $request)
    {
        $leadId = $request['leads']['status'];
    }

    public function add_lead(Request $request)
    {
//        $commentData = [
//            'task_id' => $request->task_id,
//            'name' => 'test',
//            'text' => $request->comment
//        ];
//        $commentRequest = new Request();
//        $commentRequest->setMethod('GET');
//        $commentRequest->query->add($commentData);
//        $apiCommentController = new CommentsController();
//        $apiCommentController->add($commentRequest);
    }
}