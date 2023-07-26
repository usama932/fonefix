<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;


class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'is_admin'
    ];
    public function permission()
    {
        return $this->belongsTo(Role::class,'role_id');
    }
    public function shop()
    {
        return $this->belongsTo(User::class,'parent_id');
    }
    public function country()
    {
        return $this->belongsTo(Country::class,'country_id');
    }
    public function province()
    {
        return $this->belongsTo(Provinces::class,'province_id');
    }
    public function countries(){
        return $this->belongsToMany('App\Models\Country');
    }
    public function provinces(){
        return $this->belongsToMany('App\Models\Provinces');
    }
    public function brands(){
        return $this->hasMany(UserBrand::class,'user_id');
    }
    public function pendingInvoices(){
        return $this->hasMany(Invoice::class,'customer_id')->where("payment_method",3);
    }
    public function cards(){
        return $this->hasMany(UserCard::class,'user_id');
    }
    public function jobSetting(){
        return $this->hasOne(JobSetting::class,'user_id');
    }
    public function basicSetting(){
        return $this->hasOne(BasicSetting::class,'user_id');
    }
    public function cmsSetting(){
        return $this->hasOne(CmsSetting::class,'user_id');
    }
    public function smsSetting(){
        return $this->hasOne(SmsSetting::class,'user_id');
    }
    public function mailSetting(){
        return $this->hasOne(MailSetting::class,'user_id');
    }
    public function whatsappSetting(){
        return $this->hasOne(WhatsappSetting::class,'user_id');
    }
    public function status(){
        return $this->hasOne(Status::class,'user_id');
    }
    public function category()
    {
        return $this->hasOne('App\Models\Category','shop_id','id');
    }
    public function product()
    {
        return $this->hasOne('App\Models\Product','user_id','id');
    }
    public function compatible()
    {
        return $this->hasOne('App\Models\Compatible','shop_id','id');
    }

    public function assign_images()
    {
        return $this->belongsToMany(AssignImage::class, 'assign_image');
    }

    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function images()
    {
        return $this->belongsToMany(Image::class, 'user_images', 'user_id', 'image_id');
    }


}
