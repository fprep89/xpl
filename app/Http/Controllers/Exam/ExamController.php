<?php

namespace PacketPrep\Http\Controllers\Exam;

use Illuminate\Http\Request;
use PacketPrep\Http\Controllers\Controller;
use PacketPrep\Models\Exam\Exam;
use PacketPrep\Models\Course\Course;
use PacketPrep\Models\Exam\Section;
use PacketPrep\Models\Exam\Examtype;
use PacketPrep\Models\Dataentry\Category;
use PacketPrep\Models\Dataentry\Question;

class ExamController extends Controller
{
    public function __construct(){
        $this->app      =   'exam';
        $this->module   =   'exam';
        $this->cache_path =  '../storage/app/cache/exams/';
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Exam $exam,Request $request)
    {
        $this->authorize('view', $exam);

        $search = $request->search;
        $item = $request->item;
        
        if($request->get('refresh')){
            $objs = $exam->orderBy('created_at','desc')
                        ->get();  
            
            foreach($objs as $obj){ 
                $filename = $obj->slug.'.json';
                $filepath = $this->cache_path.$filename;
                $obj->sections = $obj->sections;
                $obj->products = $obj->products;
                $obj->product_ids = $obj->products->pluck('id')->toArray();
                foreach($obj->sections as $m=>$section){
                    $obj->sections->questions = $section->questions;
                    foreach($obj->sections->questions as $k=>$question){
                       $obj->sections->questions[$k]->passage = $question->passage; 
                    }
                
                }
                file_put_contents($filepath, json_encode($obj,JSON_PRETTY_PRINT));
            }
           
            flash('Product Pages Cache Updated')->success();
        }

        $exams = $exam->where('name','LIKE',"%{$item}%")->orderBy('created_at','desc ')->paginate(config('global.no_of_records'));   
        $view = $search ? 'list': 'index';

        return view('appl.exam.exam.'.$view)
        ->with('exams',$exams)->with('exam',$exam);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $exam = new Exam();
        $examtypes = Examtype::all();
        $courses = Course::all();
        $this->authorize('create', $exam);


        return view('appl.exam.exam.createedit')
                ->with('stub','Create')
                ->with('jqueryui',true)
                ->with('editor',true)
                ->with('exam',$exam)
                ->with('courses',$courses)
                ->with('examtypes',$examtypes);
    }


    public function sample(){
        $green = "rgba(60, 120, 40, 0.8)";
        $red = "rgba(219, 55, 50, 0.9)";
        $yellow = "rgba(255, 206, 86, 0.9)";
        $blue ="rgba(60, 108, 208, 0.8)";

        $section = new Exam;
        $section->section_id = 1;
        $section->one = 100;$section->one_color = $green;
        $section->two = 20;$section->two_color = $red;
        $section->three = 60;$section->three_color = $yellow;
        $section->four = 80;$section->four_color = $blue;
        $section->labels = ["Listening","Speaking","Reading","Writing"];
        $section->average = 60;
        $section->suggestion = "- The Candidate is <b><span class='text-success'>Excellent</span></b> in Listening.<br> - The speaking skills are however <b><span class='text-danger'>not upto mark</span></b>.<br> - In reading <span class='text-info'>paraphrase</span> & <span class='text-info'>understanding details</span> are the areas that <b><span class='text-warning'>need attention</span></b>.<br> - Writing shows promise in <b><span class='text-success'>Grammatical accuracy</span></b> but is <b><span class='text-danger'>poor</span></b> in <span class='text-info'>spellings</span>, <span class='text-info'>parallel structures</span> and <span class='text-info'>referents</span>";
        $secs['English'] = $section;


        $section = new Exam;
        $section->section_id = 2;
        $section->one = 30;$section->one_color = $red;
        $section->two = 90;$section->two_color = $green;
        $section->three = 70;$section->three_color = $blue;
        $section->four = 50;$section->four_color = $yellow;
        $section->average = 50;
        $section->suggestion = "- The Candidate is <b><span class='text-success'>Excellent</span></b> in Reasoning.<br> - The qunatitative skills are however <b><span class='text-danger'>not upto mark</span></b>.<br> - In programming <span class='text-info'>code logic</span> & <span class='text-info'>syntax errors</span> are the areas that <b><span class='text-warning'>need attention</span></b>.<br> - Verbal shows promise in <b><span class='text-success'>Vocabulary </span></b> but is <b><span class='text-info'>average</span></b> in <span class='text-info'>sentence completion</span> and <span class='text-info'>reading comprehension</span> ";
        $section->labels = ["Quantitative","Reasoning","Verbal","Programming"];
        $secs['Aptitude'] = $section;

        $section = new Exam;
        $section->section_id = 3;
        $section->one = 30;$section->one_color = $red;
        $section->two = 95;$section->two_color = $green;
        $section->three = 20;$section->three_color = $red;
        $section->four = 20;$section->four_color = $red;
        $section->average = 55;
        $section->suggestion = "- The Candidate is <b><span class='text-success'>Excellent</span></b> in Tech Support.<br> - However  marketing, frontdesk and operations are <b><span class='text-danger'>not upto  mark</span></b>.";
        $section->labels = ["Marketing","Tech Support","Frontdesk","Operations"];
        $secs['Domain Knowledge'] = $section;

       
     


        $section = new Exam;
        $section->section_id = 4;
        $section->one = 40;$section->one_color = $red;
        $section->two = 85;$section->two_color = $blue;
        $section->three = 50;$section->three_color = $yellow;
        $section->four = 70;$section->four_color = $blue;
        $section->average = 45;
        $section->suggestion = "- The candidate shows great tendency towards <b><span class='text-info'>Commitment</span></b> and <b><span class='text-info'>Time Management</span></b> .<br> - However discipline is <b><span class='text-warning'>average</span></b> and integrity is <b><span class='text-danger'>not upto  mark</span></b>.";
        $section->labels = ["Integrity","Commitment","Discipline","Time Management"];
        $secs['Attitude'] = $section;

        


        return view('appl.product.test.sample')->with('secs',$secs);
    }

    public function createExam()
    {
        
        $examtypes = Examtype::all();
       /* 
       for($i=1;$i<4;$i++){
            $this->createExamLoop($i);
       }*/

       // Quantitative Aptitude
       return view('appl.exam.exam.createexam')
                ->with('stub','Create')
                ->with('jqueryui',true)
                ->with('editor',true)->with('examtypes',$examtypes);
       

    }

    public function storeExam(Request $request)
    {
        
       for($i=$request->get('l_start');$i<$request->get('l_end');$i++){
            $this->createExamLoop($request,$i);
       }

       return view('appl.exam.exam.message');
    }



    public function get_questions($slug){

       $result = array();
       $ques= array();
       $k=0;
       $category = Category::where('slug',$slug)->first();
       $siblings = $category->descendants()->withDepth()->having('depth', '=', 1)->get();


       if($slug == 'general-english' )
       foreach($siblings as $s){
            $inner = $s->descendants()->get();

            $result[$s->name] = $s->questions->pluck('id')->toArray();
                if(count($result[$s->name])!=0){
                   $id = array_rand($result[$s->name],1);
                   $ques[++$k] = $result[$s->name][$id]; 
                }
       }

       if($slug == 'logical-reasoning' || $slug == 'mental-ability')
       foreach($siblings as $s){
            $inner = $s->descendants()->get();

            $result[$s->name] = array();
            foreach($inner as $in){
                $result[$in->name] = $in->questions->pluck('id')->toArray();

                if(count($result[$in->name])!=0){
                   $id = array_rand($result[$in->name],1);
                   $ques[++$k] = $result[$in->name][$id]; 
                }
            }
       }

       if($slug == 'quantitative-aptitude' )
       foreach($siblings as $s){
            $inner = $s->descendants()->get();

            $result[$s->name] = array();
            foreach($inner as $in){

                $result[$s->name] = array_merge($result[$s->name] , $in->questions->pluck('id')->toArray());
            }

            if(count($result[$s->name])!=0){
               $id = array_rand($result[$s->name],1);
               $ques[++$k] = $result[$s->name][$id]; 
            }
            
       }

       foreach($ques as $id => $q){

            $q = Question::find($q);
            if($q->type !='mcq'){
                unset($ques[$id]);
            }
          
       }

       return $ques;
    }

    public function createExamLoop($request,$n)
    {
        //create exam
        $exam = new Exam();
        $exam->name = $request->name.$n;
        $exam->slug = $request->slug.$n;
        $exam->user_id = \auth::user()->id;
        $exam->instructions = $request->instructions;
        $exam->status = $request->status;
        $exam->examtype_id = $request->examtype_id;//general
        $count = 15;
        $e = Exam::where('slug',$exam->slug)->first();

        if(!$e)
            $exam->save();
        else
            $exam =$e;


        //create sections
        for($k=1;$k<5;$k++){

            if($request->get('sec_'.$k)){
                $section = new Section();
                $section->exam_id = $exam->id;
                $section->name = $request->get('sec_'.$k);
                $section->mark = $request->get('sec_mark_'.$k);
                $section->user_id = \auth::user()->id;
                $section->negative = $request->get('sec_negative_'.$k);
                $section->time = $request->get('sec_time_'.$k);

                $c = Section::where('name',$section->name)->where('exam_id',$exam->id)->first();
                if(!$c){
                    $section->save();
                    $c = Section::where('name',$section->name)->where('exam_id',$exam->id)->first();
                }

                if(count($c->questions) ==0 )
                {

                   $topic = $request->get('sec_slug_'.$k);
                   $count = $request->get('sec_count_'.$k);
                    // questions connect
                   $ques_set = array();
               
                   $ques = $this->get_questions($topic);
                   if(count($ques) < $count)
                   {
                        while(1){
                         $ques = array_merge($ques,$this->get_questions($topic));
                         if(count($ques) > $count)
                            break;
                        }
                   }

                   $i =0;
                   foreach($ques as $q){
                        $ques_set[$i] = $q;

                        if($i == ($count - 1) )
                            break;
                        $i++;

                   }
                  
                   foreach($ques_set as $i => $q){
                        $question = Question::where('id',$q)->first();
                        if(!$question->sections->contains($c->id))
                            $question->sections()->attach($c->id);
                   }

                }
            }
            
        }

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Exam $exam,Request $request)
    {
        try{

            if(!$request->slug )
            $request->slug  = $request->name;
            $request->slug = strtolower(str_replace(' ', '-', $request->slug));

            $exam->name = $request->name;
            $exam->slug = $request->slug;
            $exam->user_id = $request->user_id;
            if($request->course_id)
            $exam->course_id = $request->course_id;
            $exam->examtype_id = $request->examtype_id;
            $exam->description = ($request->description) ? $request->description: null;
            $exam->instructions = ($request->instructions) ? $request->instructions : null;
            $exam->status = $request->status;
            $exam->code = strtoupper($request->code);
            $exam->save(); 

            flash('A new exam('.$request->name.') is created!')->success();
            return redirect()->route('exam.index');
        }
        catch (QueryException $e){
           $error_code = $e->errorInfo[1];
            if($error_code == 1062){
                flash('The slug(<b>'.$request->slug.'</b>) is already taken. Kindly use a different slug.')->error();
                 return redirect()->back()->withInput();;
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $exam= Exam::where('slug',$id)->first();

        
        $this->authorize('view', $exam);

        if($exam)
            return view('appl.exam.exam.show')
                    ->with('exam',$exam);
        else
            abort(404);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $exam= Exam::where('slug',$id)->first();
        $examtypes = Examtype::all();
        $courses = Course::all();

        $this->authorize('update', $exam);


        if($exam)
            return view('appl.exam.exam.createedit')
                ->with('stub','Update')
                ->with('jqueryui',true)
                ->with('editor',true)
                ->with('examtypes',$examtypes)
                ->with('courses',$courses)
                ->with('exam',$exam);
        else
            abort(404);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $slug)
    {
        try{
            $exam = Exam::where('slug',$slug)->first();

            $this->authorize('update', $exam);

            $exam->name = $request->name;
            $exam->slug = $request->slug;
            $exam->user_id = $request->user_id;
            if($request->course_id)
            $exam->course_id = $request->course_id;
            $exam->examtype_id = $request->examtype_id;
            $exam->description = ($request->description) ? $request->description: null;
            $exam->instructions = ($request->instructions) ? $request->instructions : null;
            $exam->status = $request->status;
            $exam->code = strtoupper($request->code);
            $exam->save(); 

            flash('Exam (<b>'.$request->name.'</b>) Successfully updated!')->success();
            return redirect()->route('exam.show',$request->slug);
        }
        catch (QueryException $e){
           $error_code = $e->errorInfo[1];
            if($error_code == 1062){
                flash('The slug(<b>'.$request->slug.'</b>) is already taken. Kindly use a different slug.')->error();
                 return redirect()->back()->withInput();
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $exam = Exam::where('id',$id)->first();
        $this->authorize('update', $exam);

        
        $exam->delete();

        flash('Exam Successfully deleted!')->success();
        return redirect()->route('exam.index');
    }
}
