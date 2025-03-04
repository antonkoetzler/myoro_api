<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

/**
 * @mixin \Illuminate\Database\Eloquent\Model
 *
 * @property string $id
 * @property string $name
 * @property string $username
 * @property string $email
 * @property string $password
 *
 * @method static User create(array<string, mixed> $attributes = [])
 * @method static \Illuminate\Database\Eloquent\Builder<User> where(string $column, mixed $value)
 * @method static \Illuminate\Database\Eloquent\Builder<User> orWhere(string $column, mixed $value)
 * @method static User|null first()
 * @method static User|null find(mixed $id)
 */
class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory;
    use Notifiable;
    use HasApiTokens;

    public const NAME = 'name';
    public const USERNAME = 'username';
    public const EMAIL = 'email';
    public const PASSWORD = 'password';
    public const PASSWORD_CONFIRMATION = 'password_confirmation';
    public const REMEMBER_TOKEN = 'remember_token';
    public const EMAIL_VERIFIED_AT = 'email_verified_at';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        User::NAME,
        User::USERNAME,
        User::EMAIL,
        User::PASSWORD,
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        User::PASSWORD,
        User::REMEMBER_TOKEN,
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            User::EMAIL_VERIFIED_AT => 'datetime',
            User::PASSWORD => 'hashed',
        ];
    }
}
