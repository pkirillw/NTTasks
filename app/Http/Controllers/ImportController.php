<?php
/**
 * Created by PhpStorm.
 * User: Kirill
 * Date: 11.04.2018
 * Time: 14:29
 */

namespace App\Http\Controllers;

use App\AmoUsers;
use Dotzero\LaravelAmoCrm\Facades\AmoCrm;

/**
 * Class ImportController
 * @package App\Http\Controllers
 */
class ImportController
{
    /**
     * @return string
     */
    public function loadUsers()
    {
        $client = AmoCrm::getClient();
        $account = $client->account;

        // Вывод информации об аккаунте check
        $accountData = $account->apiCurrent();
        if (empty($accountData['users'])) {
            return 'Empty AccountData[\'users\']';
        }
        foreach ($accountData['users'] as $user) {
            $amoUser = new AmoUsers();
            $amoUser->amo_id = $user['id'];
            $amoUser->name = $user['name'];
            $amoUser->email = $user['login'];
            $amoUser->password = '';
            $amoUser->save();
        }
    }
}