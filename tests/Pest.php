<?php

use Tests\CreatesApplication;
use Illuminate\Foundation\Testing\TestCase;

uses(TestCase::class, CreatesApplication::class)->in('Unit', 'Feature');
