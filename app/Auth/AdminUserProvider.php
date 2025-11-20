<?php

namespace App\Auth;

use Illuminate\Auth\EloquentUserProvider;
use Illuminate\Contracts\Auth\Authenticatable;

class AdminUserProvider extends EloquentUserProvider
{
    /**
     * Retrieve a user by their unique identifier.
     */
    public function retrieveById($identifier)
    {
        $user = parent::retrieveById($identifier);
        
        // Only return user if they are admin
        if ($user && $user->isAdmin()) {
            return $user;
        }
        
        return null;
    }

    /**
     * Retrieve a user by their unique identifier and "remember me" token.
     */
    public function retrieveByToken($identifier, $token)
    {
        $user = parent::retrieveByToken($identifier, $token);
        
        // Only return user if they are admin
        if ($user && $user->isAdmin()) {
            return $user;
        }
        
        return null;
    }

    /**
     * Retrieve a user by the given credentials.
     */
    public function retrieveByCredentials(array $credentials)
    {
        $user = parent::retrieveByCredentials($credentials);
        
        // Only return user if they are admin
        if ($user && $user->isAdmin()) {
            return $user;
        }
        
        return null;
    }

    /**
     * Validate a user against the given credentials.
     */
    public function validateCredentials(Authenticatable $user, array $credentials)
    {
        // First validate the password
        $valid = parent::validateCredentials($user, $credentials);
        
        // Then check if user is admin
        return $valid && $user->isAdmin();
    }
}
