<?php

namespace App\Http\Interfaces;

interface AuthorizationInterface
{

    public function isValid(): bool;

}
