<?php

namespace Modules\DocumentSign\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentSign extends Model
{
    use HasFactory;

    protected $fillable = [];

    protected static function newFactory()
    {
        return \Modules\DocumentSign\Database\factories\DocumentSignFactory::new ();
    }
    public function receipters()
    {
        return $this->hasMany(DocumentSignReceipt::class, 'document_id');
    }
    public function documents()
    {
        return $this->hasMany(\App\DocumentSignDocument::class, 'document_id');
    }
}
