<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class   Group extends Model
{
    protected $fillable = ['size', 'group_average'];

    /**
     * Return cohort in this group
     * @return BelongsTo
     */

    /**
     * Return All user in this group
     * @return BelongsToMany
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'group_user');
    }
}
