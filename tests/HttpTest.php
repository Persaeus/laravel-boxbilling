<?php

use Illuminate\Http\Client\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;
use Nihilsen\FOSSBilling\Facades\FOSSBilling;

it('can query guest api endpoint', function () {
    // Response intentionally truncated for simplicity.
    Http::fake([
        'fossbilling.invalid/api/guest/system/countries_eunion' => Http::response('{"result":{"AT":"Austria","BE":"Belgium","BG":"Bulgaria"},"error":null}'),
    ]);

    $countries = FOSSBilling::guest()->system_countries_eunion();

    expect($countries)
        ->toBeArray()
        ->toBe([
            'AT' => 'Austria',
            'BE' => 'Belgium',
            'BG' => 'Bulgaria',
        ]);
});

it('can query authenticated api endpoint', function () {
    Http::fake(function (Request $request) {
        $isValidAuthenticatedRequest = (
            $request->url() == 'https://fossbilling.invalid/api/admin/system/messages' &&
            $request->hasHeader(
                'Authorization',
                'Basic '.base64_encode('admin:token')
            )
        );

        if ($isValidAuthenticatedRequest) {
            return Http::response('{"result":"test passed","error":null}');
        }
    });

    $messages = FOSSBilling::admin()->system_messages();

    expect($messages)->toBe('test passed');
});

it('attempts to log in via the admin API when querying the client API for a given client id', function () {
    Http::fake(function (Request $request) {
        $cookie = 'auth-cookie=auth-cookie-value';

        if ($request->url() == 'https://fossbilling.invalid/api/admin/client/login') {
            return Http::response(headers: ['Set-Cookie' => $cookie]);
        }

        if (
            $request->url() == 'https://fossbilling.invalid/api/client/profile/get' &&
            in_array($cookie, $request->header('Cookie'))
        ) {
            return Http::response('{"result":"logged in","error":null}');
        }
    });

    expect(FOSSBilling::client(id: 1)->profile_get())->toBe('logged in');
});
