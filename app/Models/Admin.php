<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable; // وراثة من Authenticatable
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Admin extends Authenticatable implements JWTSubject
{
    use HasFactory;

    protected $table = 'admins'; // ربط بحدول المدير
    protected $fillable = ['name', 'email','password','created_at', 'updated_at']; // الحقول المطلوبة في حالة الإضافة والتحديث

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }
}
