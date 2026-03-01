<?php

namespace App\Http\Controllers\User;

use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Helper\ResponseHelper;
use App\Http\Controllers\Controller;
use Throwable;

class UserController extends Controller
{
    protected UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * @param Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        try {
            $Validator = Validator::make($request->all(), [
                'name' => 'required|strict_string',
                'email' => 'required|strict_string',
                'mobile' => 'required|strict_string',
                'password' => 'required|strict_string'
            ]);
            if ($Validator->fails()) {
                return ResponseHelper::failureResponse(message: $Validator->errors()->first(), code: 400);
            }
            $response = $this->userService->userRegister(request: $request);
            return ResponseHelper::successResponse(data: [], message: "User Registered Successfully...!", code: 200);
        } catch (Throwable $e) {
            return ResponseHelper::failureResponse(message: $e->getMessage(), code: 400);
        }
    }
}
