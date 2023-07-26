<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
    use HasFactory;
    public function getStatusAttribute($value){
        $status='';
        if($value==1){
            $status='Accepted';
        }elseif($value==2){
            $status='Progressing';
        }elseif($value==3){
            $status='Completed';
        }
        return $status;
    }
    public function preRepairs(){
        return $this->hasMany(JobPreRepair::class,'job_id');
    }
    public function parts(){
        return $this->hasMany(UsePart::class,'job_id');
    }
    public function cards(){
        return $this->hasMany(UserCard::class,'job_id');
    }
    public function invoices(){
        return $this->hasMany(Invoice::class,'job_id');
    }
    public function invoice(){
        return $this->hasOne(Invoice::class,'job_id');
    }
    public function credit(){
        return $this->hasOne(Invoice::class,'job_id')->where("payment_method",3);
    }
    public function notPaid(){
        return $this->hasOne(UsePart::class,'job_id')->whereNull("invoice_id");
    }
    public function customer()
    {
        return $this->belongsTo(User::class,'customer_id');
    }
    public function stat()
    {
        return $this->belongsTo(Status::class,'status_id','id');
    }
    public function shop()
    {
        return $this->belongsTo(User::class,'user_id');
    }
    public function device()
    {
        return $this->belongsTo(Device::class,'device_id');
    }
    public function brand()
    {
        return $this->belongsTo(Brand::class,'brand_id');
    }
}
