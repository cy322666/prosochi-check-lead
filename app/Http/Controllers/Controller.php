<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;

class Controller extends BaseController
{
    public $amoClient;

    public function __construct()
    {
        $this->amoClient = \Ufee\Amo\Oauthapi::setInstance([
            'domain' => env('AMO_DOMAIN'),
            'client_id' => env('AMO_CLIENT_ID'),
            'client_secret' => env('AMO_CLIENT_SECRET'),
            'redirect_uri' => env('AMO_REDIRECT_URI'),
        ]);

        $this->amoClient = \Ufee\Amo\Oauthapi::getInstance(env('AMO_CLIENT_ID'));

        $this->amoClient->queries->cachePath(storage_path('cache/amocrm'));
        $this->amoClient->queries->logs(storage_path('logs/amocrm'));

        $this->amoClient->queries->setDelay(0.5);
    }
}
