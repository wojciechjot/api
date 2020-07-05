<?php


namespace App\Repositories\User;

use App\User;

interface UserRepositoryInterface
{
    public function create(array $data): User;

    public function setPasswordByToken(array $data): User;

    public function findOneByConfirmationToken(string $token): User;

    public function findOneByEmail(string $email): User;
}
