<?php

namespace App\Contracts\Administration\Permission;

use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Resources\Json\JsonResource;

interface PermissionInterface
{

    public function get(): JsonResource|bool;
    public function add(): int|bool;
    public function update(): int|bool;
    public function delete(): bool;
    public function getList(): AnonymousResourceCollection|bool;

}
