<?php

namespace App\Services;

use App\Exceptions\ApiException;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\PersonalAccessToken;

class AuthService
{

    public function getUserByEmail($email): Model|User|null {
        return User::query()->where('email', $email)->first();
    }

    /**
     * @throws ApiException
     */
    public function login(string $email, string $password): User {
        $user = $this->getUserByEmail($email);

        if (is_null($user) || !Hash::check($password, $user->getPassword())) {
            throw new ApiException("INVALID_CREDENTIALS", 422);
        }

        $token = $this->createToken($user);

        $user->setToken($token);

        return $user;
    }

    /**
     * @throws ApiException
     */
    public function register(string $name, string $email, string $password): User|Model {
        $user = $this->getUserByEmail($email);
        if (!is_null($user)) {
            throw new ApiException("USER_ALREADY_EXISTS", 422);
        }

        $user = User::query()->create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($password),
        ]);
        $user->setToken($this->createToken($user));
        return $user;
    }

    private function createToken(User|Model $user): string {
        return $user->createToken('api-token')->plainTextToken;
    }

    /**
     * @throws ApiException
     */
    public function sendResetPasswordEmail(string $email): bool
    {
        $user = $this->getUserByEmail($email);
        if (is_null($user)) {
            throw new ApiException("USER_NOT_FOUND", 404);
        }
        return true;
    }

    /**
     * @throws ApiException
     */
    public function retrieveToken(int $userId, string $plainTextToken): Model|User
    {
        $token = PersonalAccessToken::query()->where('token', hash('sha256', last(explode('|', $plainTextToken))))->first();
        if (is_null($token)) {
            throw new ApiException("INVALID_TOKEN", 404);
        }
        $user = User::query()->find($userId);
        $user->token = $plainTextToken;
        return $user;
    }

}
