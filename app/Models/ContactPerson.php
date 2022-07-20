<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\User;

class ContactPerson extends Model
{
    use HasFactory, SoftDeletes;

    public $table = 'contact_persons';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'phone_number',
        'user_id',
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }
}
