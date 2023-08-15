<?php

test(description: 'can render index page')
    ->get('/')
    ->assertOk()
    ->assertViewIs('welcome');
