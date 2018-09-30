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
        $supplier = [
            'nameCompany' => '',
            'emailCompany' => '',
            'telphoneCompany' => '',
            'contactCompany' => ''
        ];
        $client = AmoCrm::getClient();
        $leadData = $client->lead->apiList([
            'id' => $amoId,
        ]);
        if (empty($leadData[0])) {
            return $this->prepareReturn('_empty_lead_data', 'error');
        }
        $linksData = $client->links->apiList([
            'from' => 'leads',
            'from_id' => $amoId,
            'to' => 'catalog_elements',
            'to_catalog_id' => 8918
        ]);
        if (!empty($linksData[0])) {

            $catalogElementData = $client->catalog_element->apiList([
                'catalog_id' => 8918,
                'id' => $linksData[0]['to_id']
            ]);
            if (!empty($catalogElementData[0])) {
                if (array_search(1976870, array_column($catalogElementData[0]['custom_fields'], 'id')) !== false) {
                    $supplier['nameCompany'] = $catalogElementData[0]['custom_fields'][array_search(1976870, array_column($catalogElementData[0]['custom_fields'], 'id'))]['values'][0]['value'];
                }
                if (array_search(1976862, array_column($catalogElementData[0]['custom_fields'], 'id')) !== false) {
                    $supplier['emailCompany'] = $catalogElementData[0]['custom_fields'][array_search(1976862, array_column($catalogElementData[0]['custom_fields'], 'id'))]['values'][0]['value'];
                }
                if (array_search(1976864, array_column($catalogElementData[0]['custom_fields'], 'id')) !== false) {
                    $supplier['telphoneCompany'] = $catalogElementData[0]['custom_fields'][array_search(1976864, array_column($catalogElementData[0]['custom_fields'], 'id'))]['values'][0]['value'];
                }
                if (array_search(1976866, array_column($catalogElementData[0]['custom_fields'], 'id')) !== false) {
                    $supplier['contactCompany'] = $catalogElementData[0]['custom_fields'][array_search(1976866, array_column($catalogElementData[0]['custom_fields'], 'id'))]['values'][0]['value'];
                }
            }
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
            $tz = '(МСК' . $timezoneData['data'] . ')';
        }
        $response['idContact'] = $contactData[0]['company_name'];
        $response['nameCompany'] = $contactData[0]['company_name'];
        $response['nameContact'] = $contactData[0]['name'];
        $response['telphone'] = $telphone;
        $response['email'] = $email;
        $response['tz'] = $tz;
        $response['nameSCompany'] = $supplier['nameCompany'];
        $response['emailCompany'] = $supplier['emailCompany'];
        $response['telphoneCompany'] = $supplier['telphoneCompany'];
        $response['contactCompany'] = $supplier['contactCompany'];


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