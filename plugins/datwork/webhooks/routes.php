<?php

Route::group(
    [
        'prefix' => 'git/',
        'namespace' => 'Datwork\Webhooks\Http\Controllers'
    ],
    function () {
        Route::post(
            'webhooks',
            'WebhooksController@webhooks'
        )->name('git.webhooks');
        Route::get(
            'webhooks',
            'WebhooksController@checkSignature'
        )->name('git.checkSignature');

    }
);
