<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Paymentdetail extends Model
{
    use HasFactory;
    public function payment()
    {
        return $this->hasone(Payment::class,'id','paymentId');
    }
    public function batch()
    {
        return $this->hasone(Batch::class,'id','batchId');
    }
}
