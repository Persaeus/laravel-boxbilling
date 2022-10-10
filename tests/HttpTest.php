<?php

use Illuminate\Http\Client\Request;
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
            return Http::response('{"result":["test passed"],"error":null}');
        }
    });

    $messages = FOSSBilling::admin()->system_messages();

    expect($messages)
        ->toBeArray()
        ->toBe(['test passed']);
});
