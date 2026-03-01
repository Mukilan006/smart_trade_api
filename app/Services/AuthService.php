<?php

namespace App\Services;

use App\Models\UserMaster;
use App\ResponseModel\LoginResponseModel;
use App\Traits\CommonTraits;
use Carbon\Carbon;
use Exception;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Hash;


class AuthService
{
    use CommonTraits;

    /**
     * @param  $request
     * @param \App\ResponseModel\LoginResponseModel
     * @throws Exception 
     */
    public function login($request): LoginResponseModel
    {
        try {
            $email  = $request->get('email');
            $password  = $request->get('password');
            $loginIp  = $request->get('login_ip');
            $user = $this->getUser(
                email: $email,
                password: $password,
                loginIp: $loginIp
            );
            $accessToken = $this->generateAccessToken(
                userId: $user->id,
                name: $user->name,
                role: 'User'
            );
            $refreshToken = $this->generateRefreshToken(
                userId: $user->id,
                name: $user->name,
                role: 'User'
            );
            $response = new LoginResponseModel(
                accessToken: $accessToken,
                refreshToken: $refreshToken,
                userDetails: [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email
                ]
            );
            return $response;
        } catch (QueryException $e) {
            throw new Exception("Login failed: " . $e->errorInfo[2] ?? $e->getMessage());
        } catch (Exception $e) {
            throw new Exception("Login failed: " . $e->getMessage());
        }
    }
    /**
     * @param string $loginIp
     * @param string $email
     * @param string $password
     * @return App\Models\UserMaster
     * @throws Exception
     */
    public function getUser(
        string $email,
        string $password,
        string $loginIp
    ): UserMaster {
        try {
            $user = UserMaster::where('email', $email)->first();
            if (!$user) {
                throw new Exception('User account not found');
            }
            if (!Hash::check($password, $user->password)) {
                throw new Exception('email or password is incorrect');
            }
            $ips = $user->login_ip ?? [];
            // Normalize IP
            $loginIp = trim($loginIp);
            // Prevent duplicate IP
            if (!in_array($loginIp, $ips, true)) {
                // Allow only 2 unique IPs
                if (count($ips) >= 2) {
                    throw new Exception('Login limit exceeded. Only 2 devices allowed');
                }
                $ips[] = $loginIp;
            }
            $user->update([
                'last_login' => Carbon::now()->format('Y-m-d H:i:s'),
                'login_ip' => $ips
            ]);
            return $user;
        } catch (QueryException $e) {
            throw new Exception($e->errorInfo[2] ?? $e->getMessage());
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }
    /**
     * @param string $email
     * @param string $loginIp
     * @return App\Models\UserMaster
     * @throws Exception
     */
    public function logout(string $email, string $loginIp): bool
    {
        try {
            $user = UserMaster::where('email', $email)->first();

            if (!$user || empty($user->login_ip)) {
                throw new Exception('something went wrong...!');
            }
            $loginIp = trim($loginIp);
            $ips = $user->login_ip;
            if (!in_array($loginIp, $ips, true)) {
                throw new Exception('Session not found for this device');
            }

            $ips = array_values(
                array_filter($user->login_ip, fn($ip) => $ip !== $loginIp)
            );

            $user->update([
                'login_ip' => empty($ips) ? null : $ips
            ]);
            return true;
        } catch (QueryException $e) {
            throw new Exception("Logout failed: " . $e->errorInfo[2] ?? $e->getMessage());
        } catch (Exception $e) {
            throw new Exception("Logout failed: " . $e->getMessage());
        }
    }
    /**
     * @param string $refreshToken
     * @return LoginResponseModel
     * @throws Exception
     */
    public function refreshToken($refreshToken): LoginResponseModel
    {
        try {
            $secretkey = config('AppConfig.jwt_key');
            $decoded = JWT::decode($refreshToken, new Key($secretkey, 'HS256'));
            if ($decoded->type != 'refresh') {
                throw new Exception('Unauthorized token');
            }
            $user = UserMaster::where('id', $decoded->user_id)
                ->where('is_delete', 0)
                ->first();
            if (!$user) {
                throw new Exception('User account not found');
            }
            $accessToken = $this->generateAccessToken(
                userId: $user->id,
                name: $user->first_name,
                role: $user->role
            );
            $refreshToken = $this->generateRefreshToken(
                userId: $user->id,
                name: $user->first_name,
                role: $user->role
            );
            $loginResponse = new LoginResponseModel(
                accessToken: $accessToken,
                refreshToken: $refreshToken,
                userDetails: [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email
                ]
            );
            return $loginResponse;
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }
}
