<?php

namespace App\Contracts\Administration\Role;

use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Resources\Json\JsonResource;

interface RoleInterface
{

    public function get(): JsonResource|bool;
    public function add(): int|bool;
    public function update(): int|bool;
    public function delete(): bool;
    public function getList(): AnonymousResourceCollection|bool;
    public function setRoleToUser(): bool;

}
