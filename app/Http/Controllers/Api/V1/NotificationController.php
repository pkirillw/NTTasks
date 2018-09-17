<?php
/**
 * Created by PhpStorm.
 * User: Kirill
 * Date: 10.09.2018
 * Time: 0:37
 */

namespace App\Http\Controllers\Api\V1;


use App\Http\Controllers\Controller;
use App\Notifications;
use App\Traits\ApiResponse;

/**
 * Class NotificationController
 * @package App\Http\Controllers\Api\V1
 */
class NotificationController extends Controller
{

    use ApiResponse;

    /**
     * @param $userId
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUserNotifications($userId)
    {
        $response = [];
        if ($userId == 0) {
            return $this->prepareReturn('_empty_user_id', 'error');
        }
        $notifications = Notifications::where([['user_id', '=', $userId], ['status', '=', '0']])->get();
        if (empty($notifications->toArray())) {
            return $this->prepareReturn('_empty_notifications', 'error');
        }
        foreach ($notifications->toArray() as $item) {
            if (time() < $item['calltime']) {
                continue;
            }
            $response[] = $item;
        }
        return $this->prepareReturn($response);
    }

    /**
     * @param $userId
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAllUserNotifications($userId)
    {
        if ($userId == 0) {
            return $this->prepareReturn('_empty_user_id', 'error');
        }
        $notifications = Notifications::where([['user_id', '=', $userId]])->get();
        $response = $notifications->toArray();
        if (empty($response)) {
            return $this->prepareReturn('_empty_notifications', 'error');
        }
        return $this->prepareReturn($response);
    }

    /**
     * @param int $notificationId
     * @return \Illuminate\Http\JsonResponse
     */
    public function getNotification($notificationId = 0)
    {
        if ($notificationId == 0) {
            return $this->prepareReturn('_empty_notification_id', 'error');
        }
        $notification = Notifications::where([['id', '=', $notificationId]])->first();
        if (empty($notification->toArray())) {
            return $this->prepareReturn('_empty_notifications', 'error');
        }
        return $this->prepareReturn($notification);
    }

    /**
     * @param int $taskId
     * @return \Illuminate\Http\JsonResponse
     */
    public function getTaskNotifications($taskId = 0)
    {
        if ($taskId == 0) {
            return $this->prepareReturn('_empty_task_id', 'error');
        }
        $notifications = Notifications::where([['task_id', '=', $taskId], ['status', '=', '0']])->get();
        if (empty($notifications->toArray())) {
            return $this->prepareReturn('_empty_notifications', 'error');
        }
        return $this->prepareReturn($notifications);
    }

    /**
     * @param int $notificationId
     * @return \Illuminate\Http\JsonResponse
     */
    public function readNotification($notificationId = 0)
    {
        if ($notificationId == 0) {
            return $this->prepareReturn('_empty_notification_id', 'error');
        }
        $notification = Notifications::where([['id', '=', $notificationId]])->first();
        if (empty($notification->toArray())) {
            return $this->prepareReturn('_empty_notifications', 'error');
        }
        $notification->status = 1;
        $notification->save();
        return $this->prepareReturn($notification);

    }
}