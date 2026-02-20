<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Log;

class UserService
{
    public function getAllUsers(): \Illuminate\Database\Eloquent\Collection
    {
        return User::all();
    }

    /**
     * @throws \Exception
     */
    public function createUser(array $data)
    {
        try {
            return User::create($data);
        } catch (\Exception $e) {
            Log::error('Failed to create user: '.$e->getMessage());
            throw $e;
        }
    }

    /**
     * @throws \Exception
     */
    public function getUserById($id): ?User
    {
        try {
            return User::findOrFail($id);
        } catch (\Exception $e) {
            Log::error('User Not Found: '.$e->getMessage());
            throw $e;
        }
    }

    /**
     * @throws \Exception
     */
    public function updateUser(User $user, array $data): User
    {
        try {
            $user->update($data);

            return $user->fresh();
        } catch (\Exception $e) {
            Log::error('Failed to update user: '.$e->getMessage());
            throw $e;
        }
    }

    /**
     * @throws \Exception
     */
    public function deleteUser(User $user): bool
    {
        try {
            $user->delete();

            return true;
        } catch (\Exception $e) {
            Log::error('Failed to delete user: '.$e->getMessage());
            throw $e;
        }
    }
}
