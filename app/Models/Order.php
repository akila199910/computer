<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
            'job_no',
            'customer_id',
            'technician_id',
            'created_by',
            'approved_by',
            'order_date',
            'description',
            'total_cost',
            'status'
        ];

    public function customer_info(){

        return $this->belongsTo(User::class, 'customer_id', 'id');
    }

    public function technician_info(){

        return $this->belongsTo(User::class, 'technician_id', 'id');
    }

    public function created_by_info(){

        return $this->belongsTo(User::class, 'created_by', 'id');
    }


    public function approved_by_info(){

        return $this->belongsTo(User::class, 'approved_by', 'id');
    }
}
