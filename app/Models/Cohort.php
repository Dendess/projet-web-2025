<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cohort extends Model
{
    protected $table    = 'cohorts';
    protected $fillable     = ['school_id', 'name', 'description', 'start_date', 'end_date'];
    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public static function getAllCohorts()
    {
        return self::all();
    }
    public function showCohorts()
    {
        $cohorts = Cohort::all();
        return view('cohorts.index', compact('cohorts'));
    }
}
