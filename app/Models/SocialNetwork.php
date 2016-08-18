<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Laravel\Socialite\Contracts\User as ProviderUser;
class SocialNetwork extends Model
{
    protected $fillable = [
        'user_id',
        'provider_user_id',
        'provider',
        'avatar',
    ];
    public function createOrGetUser(ProviderUser $providerUser, $typeAccount)
    {
        $account = $this->whereProvider($typeAccount)
            ->whereProviderUserId($providerUser->getId())
            ->first();
        if ($account) {
            return $account->user;
        } else {
            $account = new $this([
                'provider_user_id' => $providerUser->getId(),
                'provider' => $typeAccount,
                'avatar' => $providerUser->getAvatar()
            ]);
            $user = User::whereEmail($providerUser->getEmail())->first();
            if (!$user) {
                $user = User::create([
                    'email' => $providerUser->getEmail(),
                    'name' => $providerUser->getName(),
                    'avatar' => $providerUser->getAvatar()
                ]);
            }
            $account->user()->associate($user);
            $account->save();
            return $user;
        }
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}