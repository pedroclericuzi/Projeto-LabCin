<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\StatusBaia;

class Baia extends Model
{
    protected $table = 'baia';

    public function status(){
        return $this->hasOne(StatusBaia::class, 'id', 'status_baia_id');
    }
}
