<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model
{

    use HasFactory;

    protected string $_error = '';

    public function __construct(array $attributes = [])
    {

        parent::__construct($attributes);

    }

    protected function setError(string $error): void
    {

        $this->_error = $error;

    }

    public function getError(): string
    {

        return $this->_error;

    }

}
