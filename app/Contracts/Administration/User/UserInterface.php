<?php

namespace App\Contracts\Administration\User;

use App\Http\Resources\IdResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Resources\Json\JsonResource;

interface UserInterface
{

    public function get(): bool|JsonResource;
    public function add(): bool|IdResource;
    public function update(): bool|IdResource;
    public function delete(): bool;
	public function getList(): bool|AnonymousResourceCollection;

}
