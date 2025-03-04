<?php

namespace Modules\DocumentSign\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentSignReceipt extends Model
{
    use HasFactory;

    protected $fillable = [];

    protected static function newFactory()
    {
        return \Modules\DocumentSign\Database\factories\DocumentSignFactory::new ();
    }
    public function DocumentSign()
    {
        return $this->belongTo(DocumentSign::class, 'document_id');
    }
}
