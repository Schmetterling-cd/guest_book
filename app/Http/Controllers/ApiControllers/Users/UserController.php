<?php

namespace App\Http\Controllers\ApiControllers\Users;

use App\Contracts\Administration\User\UserInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\ListRequest;
use App\Rules\LoginUpdateRule;
use Illuminate\Http\Request;

class UserController extends Controller
{

	public function getUser(Request $request, UserInterface $service)
	{

		$service->setData(
			$request->validate([
				'userId' => ['required', 'string', 'exists:users,id'],
			])
		);

		$user = $service->get();
		if (empty($user)) {
			return $service->getApiError();
		}

		return $service->getApiResponse($user);

	}

	public function addUser(Request $request, UserInterface $service)
	{

		$service->setData(
			$request->validate([
				'userLogin' => ['required', 'string', 'lowercase', 'max:255', 'unique:users,login'],
				'userPassword' => ['required', Rules\Password::defaults()],
			])
		);

		$userId = $service->add();
		if (empty($userId)) {
			return $service->getApiError();
		}

		return $service->getApiresponse($userId);

	}

	public function updateUser(Request $request, UserInterface $service)
	{

		$service->setData(
			$request->validate([
				'userId' => ['required', 'string', 'exists:users,id'],
				'userLogin' => ['required', 'string', 'lowercase', 'max:255', new LoginUpdateRule],
				'userPassword' => ['required', Rules\Password::defaults()],
			])
		);

		$userId = $service->update();
		if (empty($userId)) {
			return $service->getApiError();
		}

		return $service->getApiResponse($userId);

	}

	public function deleteUser(Request $request, UserInterface $service)
	{

		$service->setData(
			$request->validate([
				'userId' => ['required', 'string', 'exists:users,id'],
			])
		);

		if ($service->delete()) {
			return $service->getApiResponse();
		}

		return $service->getApiError();

	}

	public function getUserList(ListRequest $request, UserInterface $service)
	{

		$service->setData($request->validated());

		$list = $service->getList();
		if ($list === false) {
			return $service->getApiError();
		}

		return $service->getApiResponse($list);

	}

	public function getMe(Request $request, UserInterface $service)
	{

		$user = $request->user();
		$service->setData([
			'userId' => $user->id
		]);

		$user = $service->get();
		if (empty($user)) {
			return $service->getApiError();
		}

		return $service->getApiResponse($user);

	}

}
