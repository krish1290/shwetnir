<?php

namespace Modules\Essentials\Entities;

use Illuminate\Database\Eloquent\Model;

class TrainingDocument extends Model
{
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'training_docs';



}
