<?php

namespace Modules\User\Entities;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Modules\Media\Traits\MediaRelation;
use Modules\User\Notifications\VerifyAccount;
use Modules\User\Notifications\WelcomeEmail;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements MustVerifyEmail
{
    use Notifiable, HasRoles, MediaRelation;

    protected $table = 'users';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'email_verified_at', 'activated', 'last_login', 'phone', 'gender'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function getPictureAttribute()
    {
        return $this->filesByZone('picture')->first();
    }

    public function tokens()
    {
        return $this->hasMany(UserToken::class, 'user_id', 'id');
    }

    public function getFirstToken()
    {
        return $this->tokens()->first();
    }

    public function avatar()
    {
        if ($this->picture) {
            return $this->picture->path->getUrl();
        }
        return $this->gravatar();
    }

    public function gravatar($s = 80, $d = 'mp', $r = 'g', $img = false, $atts = array())
    {
        $url = 'https://www.gravatar.com/avatar/';
        $url .= md5(strtolower(trim($this->email)));
        $url .= "?s=$s&d=$d&r=$r";
        if ($img) {
            $url = '<img src="' . $url . '"';
            foreach ($atts as $key => $val) {
                $url .= ' ' . $key . '="' . $val . '"';
            }
            $url .= ' />';
        }
        return $url;
    }

    public function getEditUrl()
    {
        return route('admin.user.edit', ['id' => $this->id]);
    }

    public function getDeleteUrl()
    {
        return route('api.user.delete', ['id' => $this->id]);
    }

    public function markEmailAsVerified()
    {
        return $this->forceFill([
            'email_verified_at' => $this->freshTimestamp(),
            'activated' => 1,
        ])->save();
    }

    public function sendEmailWelcome()
    {
        $this->notify(new WelcomeEmail());
    }

    /**
     * Route notifications for the Slack channel.
     *
     * @param  \Illuminate\Notifications\Notification  $notification
     * @return string
     */
    public function routeNotificationForSlack($notification)
    {
        return env('SLACK_WEBHOOK_URL', '');
    }

    public function sendEmailVerificationNotification()
    {
        if (config('user.account.verify')) {
            $this->notify(new VerifyAccount);
        }
    }
}
