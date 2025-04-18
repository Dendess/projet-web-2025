<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class   Group extends Model
{
    protected $fillable = ['size','group_average', 'cohort_id'];

    /**
     * Return cohort in this group
     * @return BelongsTo
     */

    /**
     * Return All user in this group
     * @return BelongsToMany
     */
    //Definit la relation Many to Many entre les users et les groupes
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'users_groups');
    }
}
