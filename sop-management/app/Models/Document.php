<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    protected $fillable = [
        'document_number',
        'department',
        'document_date',
        'name',
        'type',
        'file_path',
        'status',
    ];
}
