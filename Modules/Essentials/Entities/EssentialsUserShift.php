<?php

namespace Modules\Essentials\Entities;

use Illuminate\Database\Eloquent\Model;

class EssentialsUserShift extends Model
{
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    public function user()
    {
        return $this->belongsTo(\App\User::class);
    }

    public function user_shifts($value = '')
    {
        return $this->hasOne(\Modules\Essentials\Entities\Shift::class, 'id','essentials_shift_id');
    }
}
