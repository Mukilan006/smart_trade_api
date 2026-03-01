<?php

namespace App\Http\Controllers;

use App\Helper\ResponseHelper;
use App\Services\AuthService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Throwable;

class AuthController extends Controller
{
    protected AuthService $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    /**
     * @param Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        try {
            $Validator = Validator::make($request->all(), [
                'email' => 'required|strict_string',
                'password' => 'required|strict_string',
                'login_ip' => 'required|strict_string',
            ]);
            if ($Validator->fails()) {
                return ResponseHelper::failureResponse(message: $Validator->errors()->first(), code: 400);
            }
            $response = $this->authService->login(request: $request)->toArray();
            return ResponseHelper::successResponse(data: $response, message: "Data Fetched", code: 200);
        } catch (Throwable $e) {
            return ResponseHelper::failureResponse(message: $e->getMessage(), code: 400);
        }
    }
    /**
     * @param Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh(Request $request)
    {
        try {
            $Validator = Validator::make($request->all(), [
                'refresh_token' => 'required|strict_string'
            ]);
            if ($Validator->fails()) {
                return ResponseHelper::failureResponse(message: $Validator->errors()->first(), code: 400);
            }
            $refresh = $request->get('refresh_token');
            $response = $this->authService->refreshToken(refreshToken: $refresh)->toArray();
            return ResponseHelper::successResponse(data: $response, message: "Data Fetched", code: 200);
        } catch (Throwable $e) {
            return ResponseHelper::failureResponse(message: $e->getMessage(), code: 400);
        }
    }
    /**
     * @param Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
    {
        try {
            $Validator = Validator::make($request->all(), [
                'email' => 'required|strict_string',
                'login_ip' => 'required|strict_string',
            ]);
            if ($Validator->fails()) {
                return ResponseHelper::failureResponse(message: $Validator->errors()->first(), code: 400);
            }
            $response = $this->authService->logout(
                email: $request->get('email'),
                loginIp: $request->get('login_ip')
            );
            return ResponseHelper::successResponse(data: [], message: "user logout successfully...!", code: 200);
        } catch (Throwable $e) {
            return ResponseHelper::failureResponse(message: $e->getMessage(), code: 400);
        }
    }
}
