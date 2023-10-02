<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

use App\Setting;
use Illuminate\Support\Facades\DB;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name', 'last_name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public $timestamps = false;

    public static function createSetting($user){

        $setting = Setting::where('key', '!=', 'colors')->get();

        foreach($setting as $set){
            DB::table('user_settings')->insert([
                'user_id'=>$user,
                'setting_id'=>$set->id,
                'value'=>$set->value
            ]);
        }
    }

    public static function createCategory($user){
        $category = DB::table('categories')->get();

        foreach($category as $cat){
            DB::table('user_categories')->insert([
                'user_id'=>$user,
                'category_id'=>$cat->id,
                'color'=>$cat->color
            ]);
        }
    }
}
