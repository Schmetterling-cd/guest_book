<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'login',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    protected function getDefaultGuardName(): string
    {

        return 'sanctum';

    }

	public function modelHasPermission(): HasMany
	{

		return $this->hasMany(ModelHasPermission::class, 'model_id', 'id');

	}

	public function modelHasRoles(): HasMany
	{

		return $this->hasMany(ModelHasPermission::class, 'model_id', 'id');

	}

	protected static function boot()
	{

		parent::boot();

		static::deleting(function($user) {
			$user->modelHasPermission()->delete();
			$user->modelHasRoles()->delete();
		});

	}

}
