<?php

namespace Tests;

use App\Models\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Auth;
use Mockery;
use Mockery\MockInterface;

abstract class TestCase extends BaseTestCase
{

    public object $service;
    public object $controller;

    public function setUp(): void
    {
        parent::setUp();

        $this->actingAs(User::find(1));
        $this->app->make(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();

    }

}
