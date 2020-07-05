<?php

namespace App\Repositories\User;

use App\User;

class UserRepository implements UserRepositoryInterface
{
    public function create(array $data): User
    {
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);

        $user->setConfirmationToken();
        $user->update();

        return $user;
    }

    public function setPasswordByToken(array $data): User
    {
        $user = $this->findOneByConfirmationToken($data['token']);

        $user->update(['password' => bcrypt($data['password'])]);

        return $user;
    }

    public function findOneByConfirmationToken(string $token): User
    {
        return User::where('confirmation_token', $token)
            ->firstOrFail()
        ;
    }

    public function findOneByEmail(string $email): User
    {
        return User::where('email', $email)
            ->firstOrFail()
        ;
    }
}
