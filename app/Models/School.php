<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class School extends Model
{
    protected $table        = 'schools';
    protected $fillable     = ['user_id', 'name', 'description'];
    public function cohorts()
    {
        return $this->hasMany(Cohort::class);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

}
