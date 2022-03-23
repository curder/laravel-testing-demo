<?php

use function Pest\Laravel\get;

it('can render index page', function () {
    get('/')->assertOk();
});
