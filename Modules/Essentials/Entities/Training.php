<?php

namespace Modules\Essentials\Entities;

use Illuminate\Database\Eloquent\Model;

class Training extends Model
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
    protected $table = 'trainings';

    /**
     * Get all the documents of the trainings.
     */
    public function documents()
    {
        return $this->hasMany(\Modules\Essentials\Entities\TrainingDocument::class, 'training_id');
    }
    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'attachment' => 'array',
    ];
    // protected function attachment(): Attribute
    // {
    //     return Attribute::make(
    //         get:fn($value) => json_decode($value, true),
    //         set:fn($value) => json_encode($value),
    //     );
    // }
}
