<?php

namespace Modules\User\Providers;

use Cache;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Support\Str;
use Modules\User\Repositories\UserRepository;
use Modules\User\Repositories\UserTokenRepository;

class TokenToUserProvider implements UserProvider
{
    /** @var UserTokenRepository  */
    private $token;

    /** @var UserRepository  */
    private $user;

    /**
     * TokenToUserProvider constructor.
     * @param UserRepository $userRepository
     * @param UserTokenRepository $tokenRepository
     */
    public function __construct(UserRepository $userRepository, UserTokenRepository $tokenRepository)
    {
        $this->user = $userRepository;
        $this->token = $tokenRepository;
    }

    public function retrieveById($identifier)
    {
        return $this->user->find($identifier);
    }

    public function retrieveByToken($identifier, $token)
    {
        $key = 'user_by_'.$identifier.'_'.$token;
        if (Cache::has($key)) {
            return Cache::get($key);
        }
        $user = $this->user->newQueryBuilder()
            ->whereHas('tokens', function ($q) use ($identifier, $token) {
                $q->where($identifier, $token);
            })
            ->first();
        Cache::put($key, $user, now()->addMinutes(60));
        return $user;
    }

    public function updateRememberToken(Authenticatable $user, $token)
    {
        // update via remember token not necessary
    }

    public function retrieveByCredentials(array $credentials)
    {
        // implementation upto user.
        // how he wants to implement -
        // let's try to assume that the credentials ['username', 'password'] given
        $user = $this->user->newQueryBuilder();
        foreach ($credentials as $credentialKey => $credentialValue) {
            if (!Str::contains($credentialKey, 'password')) {
                $user->where($credentialKey, $credentialValue);
            }
        }
        return $user->first();
    }

    public function validateCredentials(Authenticatable $user, array $credentials)
    {
        $plain = $credentials['password'];
        return app('hash')->check($plain, $user->getAuthPassword());
    }
}
