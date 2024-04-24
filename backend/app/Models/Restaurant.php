<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class Restaurant extends Model
{
    use HasFactory,HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'tourns',
        'address',
        'location',
        'postal_code',
        'tel_num',
        'email',
        'tourns',
        'capacity',
        'category'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'opening_hours' => 'array',
        'tourn_state' => 'boolean',
    ];
    protected $hidden = [
        'password',
    ];

    public function employees()
    {
        return $this->hasMany(Employee::class);
    }
    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }
    public function designs()
    {
        return $this->hasMany(Design::class);
    }
}
