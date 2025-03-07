<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'email',
        'username',
        'password',
        'two_factor_enabled',
        'two_factor_secret',
        'two_factor_recovery_codes',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_secret',
        'two_factor_recovery_codes',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'two_factor_enabled' => 'boolean',
    ];

    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    public function assignRole($role)
    {
        return $this->roles()->save(
            Role::whereName($role)->firstOrFail()
        );
    }

    public function hasUserRole()
    {
        return $this->roles()->wherePivot('role_id', 2)->exists();
    }

    public function hasAuthorizedRole()
    {
        return $this->roles()->whereIn('id', [1, 3, 4])->exists();
    }

    public function hasAdminRole()
    {
        return $this->roles()->wherePivot('role_id', 1)->exists();
    }

    public function hasSuperAdminRole()
    {
        return $this->roles()->wherePivot('role_id', 3)->exists();
    }

    public function hasAttendantRole()
    {
        return $this->roles()->wherePivot('role_id', 4)->exists();
    }

    public function hasAnyRole($roles)
    {
        return $this->roles()->whereIn('name', $roles)->exists();
    }

    public function orders(){
        return $this->hasMany(Order::class);
    }
    public function cart()
    {
        return $this->hasOne(Cart::class, 'user_id');
    }

    public function enableTwoFactorAuth($secret)
    {
        $this->two_factor_secret = $secret;
        $this->two_factor_enabled = true;
        $this->two_factor_recovery_codes = json_encode($this->generateRecoveryCodes());
        $this->save();
    }

    public function disableTwoFactorAuth()
    {
        $this->two_factor_secret = null;
        $this->two_factor_enabled = false;
        $this->two_factor_recovery_codes = null;
        $this->save();
    }

    public function generateRecoveryCodes()
    {
        $codes = [];
        for ($i = 0; $i < 8; $i++) {
            $codes[] = Str::random(10);
        }
        return $codes;
    }

    public function getRecoveryCodes()
    {
        return $this->two_factor_recovery_codes ? json_decode($this->two_factor_recovery_codes) : [];
    }

    public function validateTwoFactorCode($code)
    {
        $google2fa = app('pragmarx.google2fa');
        return $google2fa->verifyKey($this->two_factor_secret, $code);
    }
}
