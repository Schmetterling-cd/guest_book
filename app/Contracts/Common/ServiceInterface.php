<?php

namespace App\Contracts\Common;

interface ServiceInterface
{

	public function setData($data): void;
	public function getData(): array;
	public function getError(): string;
	public function getApiResponse(): array;
	public function getApiError(): array;

}