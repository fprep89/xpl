<?php

namespace PacketPrep\Models\Course;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use PacketPrep\Models\Dataentry\Tag;

class Course extends Model
{
     protected $fillable = [
        'name',
        'slug',
        'user_id',
        'description',
        'intro_youtube',
        'intro_vimeo',
        'priority',
        'weightage_min',
        'weightage_avg',
        'weightage_max',
        'price',
        'important_topics',
        'reference_books',
        'status',
        'image',
        // add all other fields
    ];

    public function users(){
        return $this->belongsToMany('PacketPrep\User');
    }

    public function products(){
        return $this->belongsToMany('PacketPrep\Models\Product\Product');
    }

    public function colleges(){
        return $this->belongsToMany('PacketPrep\Models\College\College');
    }

    public function clients(){
        return $this->belongsToMany('PacketPrep\Models\Product\Client')->withPivot('visible');
    }

    public function exams(){
        return $this->hasMany('PacketPrep\Models\Exam\Exam');
    }

    public function getVisibility($client_id,$course_id){

        if(!is_int($client_id))
        {
            $client_id = DB::table('clients')
                ->where('slug', $client_id)
                ->first()->id;
        }
        $entry =DB::table('client_course')
                ->where('client_id', $client_id)
                ->where('course_id', $course_id)
                ->first();

        if($entry)
            return $entry->visible;
        else

        return null;
    }

    
    public function validityExpired(){

        $course_id = $this->id;
        $user_id = \auth::user()->id;

        $entry =DB::table('course_user')
                ->where('course_id', $course_id)
                ->where('user_id', $user_id)
                ->first();


        if(strtotime($entry->valid_till) > strtotime(date('Y-m-d')))
            return false;
        else
            return true;

    }

    public static function getName($slug){
    	 return (new Course)->where('slug',$slug)->first()->name;
    }

    public static function get($slug){
        return (new Course)->where('slug',$slug)->first();
    }

    public static function getID($slug){
        return (new Course)->where('slug',$slug)->first()->id;
    }


    public static function attempted($course){

        $exam = session('exam');
        $tag = Tag::where('value',$exam)->first();
        if($tag){
            $ques_tag = DB::table('question_tag')->where('tag_id', $tag->id)->distinct()->get(['question_id'])->pluck('question_id')->toArray();
            return DB::table('practices')
                    ->where('course_id', $course->id)
                    ->where('user_id',\auth()->user()->id)
                    ->whereIn('qid',$ques_tag)
                    ->count();
        }
        else
        {
                return DB::table('practices')
                    ->where('course_id', $course->id)
                    ->where('user_id',\auth()->user()->id)
                    ->count();

        }


        
        
    }

    public static function time($course){
        
        $exam = session('exam');
        $tag = Tag::where('value',$exam)->first();
        if($tag){
            $ques_tag = DB::table('question_tag')->where('tag_id', $tag->id)->distinct()->get(['question_id'])->pluck('question_id')->toArray();
            return round(DB::table('practices')
                    ->where('course_id', $course->id)
                    ->where('user_id',\auth()->user()->id)
                    ->whereIn('qid',$ques_tag)
                    ->avg('time'),2);
        }
        else
        {
                return round(DB::table('practices')
                    ->where('course_id', $course->id)
                    ->where('user_id',\auth()->user()->id)
                    ->avg('time'),2);

        }

    }

    public static function accuracy($course){
        $exam = session('exam');
        $tag = Tag::where('value',$exam)->first();
        if($tag){
            $ques_tag = DB::table('question_tag')->where('tag_id', $tag->id)->distinct()->get(['question_id'])->pluck('question_id')->toArray();
            $sum = DB::table('practices')
                    ->where('course_id', $course->id)
                    ->where('user_id',\auth()->user()->id)
                    ->whereIn('qid',$ques_tag)
                    ->sum('accuracy');
            $count = DB::table('practices')
                    ->where('course_id', $course->id)
                    ->where('user_id',\auth()->user()->id)
                    ->whereIn('qid',$ques_tag)
                    ->count();
        }
        else
        {
            $sum = DB::table('practices')->where('course_id', $course->id)->where('user_id',\auth()->user()->id)->sum('accuracy');
            $count = DB::table('practices')->where('course_id', $course->id)->where('user_id',\auth()->user()->id)->count();
                

        }

         
         if($count){
            return round(($sum*100)/$count,2);
         }
         else
            return null;

    }
}
