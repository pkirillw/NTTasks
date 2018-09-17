<?php
/**
 * Created by PhpStorm.
 * User: Kirill
 * Date: 05.09.2018
 * Time: 2:09
 */

namespace App\Http\Controllers\Api\V1;


use App\Http\Controllers\Controller;
use App\Traits\ApiResponse;
use Dotzero\LaravelAmoCrm\Facades\AmoCrm;

/**
 * Class AmoCRMController
 * @package App\Http\Controllers\Api\V1
 */
class AmoCRMController extends Controller
{
    use ApiResponse;

    /**
     * @param int $amoId
     * @return \Illuminate\Http\JsonResponse
     */
    public function getLeadInfo($amoId = 0)
    {
        if ($amoId == 0) {
            return $this->prepareReturn('_empty_amo_id', 'error');
        }
        $email = '';
        $telphone = '';
        $tz = '';
        $client = AmoCrm::getClient();
        $leadData = $client->lead->apiList([
            'id' => $amoId,
        ]);
        if (empty($leadData[0])) {
            return $this->prepareReturn('_empty_lead_data', 'error');
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
        if ($telphone != '') {
            $phone = preg_replace("/[^0-9]/", '', $telphone);
            $timezoneData = json_decode(file_get_contents('https://timezone.pkirillw.ru/public/getTimezonePhone/' . $phone), true);
            $tz = '('.$timezoneData['data'].' МСК)';
        }
        $response = [
            'nameCompany' => $contactData[0]['company_name'],
            'nameContact' => $contactData[0]['name'],
            'telphone' => $telphone,
            'email' => $email,
            'tz' => $tz
        ];
        return $this->prepareReturn($response);
    }

    /**
     * @param string $text
     * @return \Illuminate\Http\JsonResponse
     */
    public function getLeads($text = '')
    {
        $client = AmoCrm::getClient();
        return $this->prepareReturn($client->lead->apiList(['query' => $text,]));
    }
}