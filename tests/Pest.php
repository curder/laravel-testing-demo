<?php

use Illuminate\Foundation\Testing\TestCase;
use Tests\CreatesApplication;

uses(TestCase::class, CreatesApplication::class)->in('Unit', 'Feature');
