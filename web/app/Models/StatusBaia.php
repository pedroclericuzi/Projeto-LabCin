<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StatusBaia extends Model
{
    protected $fillable = ['id', 'descricao'];
    protected $table = 'Status_Baia';
}
