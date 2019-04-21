<?php

namespace App\Http\Controllers;

use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Validator;
use Laravel\Lumen\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\UserValidation;
use App\User;

class UserController extends BaseController
{
    private $model;

    public function __construct(User $user)
    {
        $this->model = $user;
    }

    public function create(Request $request)
    {
        $data = $request->only('name', 'email');

        $validator = Validator::make($data, UserValidation::RULES, UserValidation::MESSAGES);

        if ($validator->fails()) {
            return response()->json($validator->errors()->first(), Response::HTTP_BAD_REQUEST);
        }

        try {
            $user = $this->model->create($data);
            return response()->json($user, Response::HTTP_CREATED);
        } catch (QueryException $exception) {
            return response()->json(['Error' => $exception . $data], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

    }

    public function retrieve($id)
    {
        $user = $this->model->find($id);

        if (!$user) {
            return response()->json('User not found', Response::HTTP_BAD_REQUEST);
        }

        return response()->json($user, Response::HTTP_OK);

    }

    public function retrieveAll()
    {
        $users = $this->model->all();
        if (!$users) {
            return response()->json([], Response::HTTP_OK);
        }
        return response()->json($users, Response::HTTP_OK);
    }

    public function update(Request $request, $id)
    {
        $data = $request->only('name', 'email');
        $data['id'] = $id;
        $user = $this->model->find($id);

        if (!$user) {
            return response()->json('User not found', Response::HTTP_BAD_REQUEST);
        }

        $user->update($data);
        return response()->json($user, Response::HTTP_OK);

    }

    public function remove($id)
    {
        $this->model->find($id)->delete();
        return response()->json('Removido com sucesso!', Response::HTTP_OK);
    }
}
