<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class IdResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

		$modelName = strtolower(
			substr(
				get_class($this->resource),
				strripos(
					get_class($this->resource),
					'\\'
				) + 1
			)
		);

        return [
			$modelName . 'Id' => $this->id,
		];
    }
}
