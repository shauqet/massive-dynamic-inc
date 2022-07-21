<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    public const ADMINISTRATOR = 1;
    public const SECRETARY = 2;
    public const CLIENT = 3;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'role',
        'client_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function contactPersons(){
        return $this->hasMany(ContactPerson::class);
    }

    public function documents(){
        return $this->hasMany(Document::class);
    }

    public function scopeSearch($query, $searchKeword) {
        return
            $query->where(function ($query) use ($searchKeword) {
                $query->where("name", "LIKE", "%$searchKeword%")
                    ->orWhere("username", "LIKE", "%$searchKeword%")
                    ->orWhere("email", "LIKE", "%$searchKeword%")
                    ->orWhere("client_id", "LIKE", "%$searchKeword%");
                })->when(auth()->user()->role===User::SECRETARY, function ($query){
                    $query->where("role",User::CLIENT);
                })
                ->orderBy("id", "DESC")
                ->paginate(10);
    }
}
