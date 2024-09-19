<?php

namespace App\Services;

use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use stdClass;

abstract class Service
{

    protected const STATUS_ERROR = 'ERROR';
    protected const STATUS_OK = 'OK';

    protected const LIST_CACHE_KEY = '';
    protected const CACHE_EXPIRATION_TIME = 3600;

    protected array $_data = [];
    protected string $_error = '';
    protected object $_models;
    protected $_user;

    public function __construct()
    {

        $this->_models = new stdClass();

    }

    public function setData($data): void
    {

        $this->_data = $data;

    }

    public function getData(): array
    {

        return $this->_data;

    }

    protected function getModel($model)
    {

        if (is_object($model)) {
            $modelNameWithPath = get_class($model);
            $nameArray = explode('\\', $modelNameWithPath);
            $modelName = end($nameArray);
        } else {
            $modelNameWithPath = 'App\\Models\\' . $model;
            $modelName = $model;
        }

        if (property_exists($this->_models, $modelName)) {
            return $this->_models->$modelName;
        }

        if (class_exists($modelNameWithPath)) {
            if (is_object($model)) {
                $this->_models->$modelName = $model;

                return $this->_models->$modelName;
            }

            $this->_models->$modelName = new $modelNameWithPath();

            return $this->_models->$modelName;
        }

        $this->setError('Запрашиваемая модель не существует.');

        return false;

    }

    protected function getUser()
    {

        if (!empty($this->_user)) {
            return $this->_user;
        }

        $this->_user = Auth::user();

        return $this->_user;

    }

    protected function setError(string $error): void
    {

        $this->_error = $error;

    }

    public function getError(): string
    {

        return $this->_error;

    }

    public function getApiResponse($data = array()): array
    {

        if (empty($data)) {
            return ['status' => static::STATUS_OK];
        }

        return [
            'status' => static::STATUS_OK,
            'data' => $data,
        ];

    }

    public function getApiError(): array
    {

        $error = $this->getError();

        return [
            'status' => static::STATUS_ERROR,
            'message' => empty($error) ? 'Сервис временно недоступен.' : $error,
        ];

    }

}
