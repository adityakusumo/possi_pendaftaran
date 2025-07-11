<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles; // Make sure this is present if you're using Spatie roles
use Carbon\Carbon; // Import Carbon for date handling

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'KDPROP',
        'NAMAPROP',
        'KDJENIS',
        'JENIS',
        'KDKOTA',
        'NAMAKOTA',
        'KDCLUB',
        'IDCLUB', // Ensure this is fillable
        'NAMACLUB', // Ensure this is fillable
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
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Get the special user entry associated with this user.
     * Using 'email' as the foreign key in SpecialUser.
     */
    public function specialUser()
    {
        return $this->hasOne(SpecialUser::class, 'email', 'email');
    }

    /**
     * Check if the user is currently an active special user based on the SpecialUser table.
     * @return bool
     */
    public function isSpecialUserActive(): bool
    {
        // Check if there's an entry in special_users for this user's email that hasn't expired.
        // Using exists() is efficient as it only checks for presence, not fetches the whole record.
        return $this->specialUser()->where('expired_at', '>', Carbon::now())->exists();
    }
}
