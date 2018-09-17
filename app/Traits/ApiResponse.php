<?php
/**
 * Created by PhpStorm.
 * User: Kirill
 * Date: 04.09.2018
 * Time: 16:27
 */

namespace App\Traits;

/**
 * Trait ApiResponse
 * @package App\Traits
 */
trait ApiResponse
{
    /**
     * @var array
     */
    public $response = [
        'status' => '',
        'data' => '',
    ];

    /**
     * @param array $data
     * @param string $status
     * @return \Illuminate\Http\JsonResponse
     */
    public function prepareReturn($data = [], $status = 'success')
    {
        $this->response['status'] = $status;
        if ($status == 'error') {
            $this->response['error_data'] = config('apierror.' . $data);
            $data = [];
        }
        $this->response['data'] = $data;
        return response()->json($this->response);
    }
}