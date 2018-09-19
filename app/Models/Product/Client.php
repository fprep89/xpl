<?php

namespace PacketPrep\Models\Product;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Client extends Model
{
     protected $fillable = [
        'name',
        'slug',
        'user_id_creator',
        'user_id_owner',
        'user_id_manager',
        'status',
        // add all other fields
    ];

    public function users()
    {
        return $this->belongsToMany('PacketPrep\User');
    }

    public function courses(){
        return $this->belongsToMany('PacketPrep\Models\Course\Course')->withPivot('visible');;
    }

    public function updateVisibility($client_id,$course_id=null,$visible){

        if($course_id)
        return DB::table('client_course')
                ->where('client_id', $client_id)
                ->where('course_id', $course_id)
                ->update(['visible' => $visible]);
        else
        return DB::table('client_course')
                ->where('client_id', $client_id)
                ->update(['visible' => $visible]);

    }

    public function getPackageRate(){

        $slug = $this->slug;
        $user = \auth::user();

        $o = Order::where('client_id',$user->client_id())->where(function ($query) {
                $query->where('package', '=', 'flex')
                      ->orWhere('package', '=', 'basic')
                      ->orWhere('package', '=', 'pro')
                      ->orWhere('package', '=', 'ultimate');
            })->first();

        if($o)
            return $o->credit_rate;
        else
            return '200';
    }


    public function getCreditPoints(){

        $slug = $this->slug;
        $user = \auth::user();

        $sum = Order::where('client_id',$user->client_id())->Where('status',1)->sum('credit_count');
        return $sum;
    }

    public function getPackageName(){

         $slug = $this->slug;
        $user = \auth::user();

        $o = Order::where('client_id',$user->client_id())->where(function ($query) {
                $query->where('package', '=', 'flex')
                      ->orWhere('package', '=', 'basic')
                      ->orWhere('package', '=', 'pro')
                      ->orWhere('package', '=', 'ultimate');
            })->first();

        if($o)
            return $o->package;
        else
            return null;
    }




}
