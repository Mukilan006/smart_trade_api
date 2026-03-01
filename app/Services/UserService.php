<?php

namespace App\Services;

use App\Models\UserMaster;
use Exception;
use Illuminate\Database\QueryException;

class UserService
{
    /**
     * @param $request
     * @return UserMaster
     * @throws Exception
     */
    public function userRegister($request): UserMaster
    {
        try {
            $name = $request->get('name');
            $email = $request->get('email');
            $mobile = $request->get('mobile');
            $password = $request->get('password');
            $user = UserMaster::create([
                'name' => $name,
                'email' => $email,
                'mobile' => $mobile,
                'password' => password_hash($password, PASSWORD_BCRYPT)
            ]);
            return $user;
        } catch (QueryException $e) {
            throw new Exception("User Register failed: " . $e->errorInfo[2] ?? $e->getMessage());
        } catch (Exception $e) {
            throw new Exception("User Register failed: " . $e->getMessage());
        }
    }
}
