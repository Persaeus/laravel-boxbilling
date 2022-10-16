<?php

use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Nihilsen\BoxBilling\Collection;
use Nihilsen\BoxBilling\Facades\BoxBilling;

it('can query guest api endpoint', function () {
    // Response intentionally truncated for simplicity.
    Http::fake([
        'boxbilling.invalid/api/guest/system/countries_eunion' => Http::response('{"result":{"AT":"Austria","BE":"Belgium","BG":"Bulgaria"},"error":null}'),
    ]);

    $countries = BoxBilling::guest()->system_countries_eunion();

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
        $isValidAuthenticatedRequest = ($request->url() == 'https://boxbilling.invalid/api/admin/system/messages' &&
            $request->hasHeader(
                'Authorization',
                'Basic '.base64_encode('admin:token')
            )
        );

        if ($isValidAuthenticatedRequest) {
            return Http::response('{"result":"test passed","error":null}');
        }
    });

    $messages = BoxBilling::admin()->system_messages();

    expect($messages)->toBe('test passed');
});

it('attempts to log in via the admin API when querying the client API for a given client id', function () {
    Http::fake(function (Request $request) {
        $cookie = 'auth-cookie=auth-cookie-value';

        if ($request->url() == 'https://boxbilling.invalid/api/admin/client/login') {
            return Http::response(headers: ['Set-Cookie' => $cookie]);
        }

        if (
            $request->url() == 'https://boxbilling.invalid/api/client/profile/get' &&
            in_array($cookie, $request->header('Cookie'))
        ) {
            return Http::response('{"result":"logged in","error":null}');
        }
    });

    expect(BoxBilling::client(id: 1)->profile_get())->toBe('logged in');
});

it('handles paginated results', function () {
    Http::fake(function (Request $request) {
        if (! str_contains($request->url(), 'boxbilling.invalid/api/admin/client/get_list')) {
            dd($request->url());

            return;
        }

        parse_str(parse_url($request->url(), PHP_URL_QUERY), $result);

        /** @var int|null $page */
        extract($result);

        if (empty($page) || $page == 1) {
            return Http::response('{
                "result": {
                    "pages": 2,
                    "page": 1,
                    "per_page": 2,
                    "total": "3",
                    "list": [
                        {
                            "id": "1"
                        },
                        {
                            "id": "2"
                        }
                    ]
                },
                "error": null
            }');
        }

        if ($page == 2) {
            return Http::response('{
                "result": {
                    "pages": 2,
                    "page": 2,
                    "per_page": 2,
                    "total": "3",
                    "list": [
                        {
                            "id": "3"
                        }
                    ]
                },
                "error": null
            }');
        }
    });

    expect(BoxBilling::admin()->client_get_list())
        ->toBeInstanceOf(Collection::class)
        ->toHaveCount(3);
});
