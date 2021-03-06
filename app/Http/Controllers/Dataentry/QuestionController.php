<?php

namespace PacketPrep\Http\Controllers\Dataentry;

use Illuminate\Http\Request;
use PacketPrep\Http\Controllers\Controller;
use PacketPrep\Models\Dataentry\Question;
use PacketPrep\Models\Dataentry\Project;
use PacketPrep\Models\Dataentry\Passage;
use PacketPrep\Models\Dataentry\Category;
use PacketPrep\Models\Dataentry\Tag;
use PacketPrep\Models\Course\Course;
use PacketPrep\Models\Course\Practice;
use PacketPrep\Models\Exam\Exam;
use PacketPrep\Models\Exam\Section;
use PacketPrep\Models\Product\Product;
use PacketPrep\Models\Course\Practices_Course;
use PacketPrep\Models\Course\Practices_Topic;
use Illuminate\Support\Facades\DB;
use PDF;

use DOMDocument;
use DOMXpath;


class QuestionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

        public $project;
    

    public function __construct(){
        $this->project='';
        if(request()->route('project')){
            $this->project = Project::get(request()->route('project'));
        } 

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Question $question)
    {
        $search = $request->search;
        $item = $request->item;

        ($request->category_slug) ? $category_slug = $request->category_slug : $category_slug = null;
        ($request->order) ? $order = $request->order : $order = 'desc';
        ($request->orderby) ? $orderby = $request->orderby : $orderby = 'created_at';

        if($category_slug){
            $category = Category::where('slug',$category_slug)->first();
        $qset = $category->questions->pluck('id')->toArray();
        $questions = $question
                        ->where(function ($query) use ($item) {
                                $query->where('question','LIKE',"%{$item}%")
                                      ->orWhere('reference', 'LIKE', "%{$item}%");
                            })
                        ->whereIn('id',$qset)
                        ->where('project_id',$this->project->id)
                        ->orderBy($orderby,$order)
                        ->paginate(500);
    }else{
        $questions = $question
                        ->where(function ($query) use ($item) {
                                $query->where('question','LIKE',"%{$item}%")
                                      ->orWhere('reference', 'LIKE', "%{$item}%");
                            })
                        ->where('project_id',$this->project->id)
                        ->orderBy($orderby,$order)
                        ->paginate(500);
    }
        
        

        $view = $search ? 'list': 'index';

        $question->project_id = $this->project->id;
        $this->authorize('view', $question);

        return view('appl.dataentry.question.'.$view)
        ->with('project',$this->project)
        ->with('question',$question)
        ->with('questions',$questions);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Question $question)
    {

        $question->project_id = $this->project->id;
        if($this->project->slug!='default')
        $this->authorize('create', $question);

        $passages = Passage::where('project_id',$this->project->id)->orderBy('created_at','desc ')
                        ->paginate(config('global.no_of_records'));

        // Categories
        $category_parent =  Category::where('slug',$this->project->slug)->first();   
        $category_node = Category::defaultOrder()->descendantsOf($category_parent->id)->toTree();
        //$node = Category::defaultOrder()->get()->toTree();
        if(count($category_node))
            $categories = Category::displayUnorderedCheckList($category_node,['project_slug'=>$this->project->slug]);
        else
            $categories =null;

        //tags
        $tags =  Tag::where('project_id',$this->project->id)
                        ->orderBy('created_at','desc ')
                        ->get()->groupBy(function($item)
                        {
                          return $item->name;
                        });

        // exams
        if(request()->get('exam'))
            $exams =  Exam::where('id',request()->get('exam'))->orderBy('name','desc ')->get();
        else
        $exams =  Exam::orderBy('name','desc ')->get();

        // Question Types
        $allowed_types = ['mcq','naq','maq','eq','code','fillup'];
        if(in_array(request()->get('type'), $allowed_types)){
            $type = request()->get('type');
        }
        else
            $type='mcq';             

        return view('appl.dataentry.question.createedit')
                ->with('project',$this->project)
                ->with('passages',$passages)
                ->with('passage','')
                ->with('type',$type)
                ->with('tags',$tags)
                ->with('question',$question)
                ->with('categories',$categories)
                ->with('exams',$exams)
                ->with('code',true)
                ->with('editor',true)
                ->with('stub','Create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // merge answer for maq question
        if(is_array($request->answer)){
            $answer = implode(",",$request->answer);
            $request->merge(['answer' => $answer]);
        }

        if(!$request->passage_id){
            $request->merge(['passage_id' => null]);
        }

        $categories = $request->get('category');
        $tags = $request->get('tag');
        $sections = $request->get('sections');

        if($request->get('exam')){
            $exam = Exam::where('id',$request->get('exam'))->first();
        }

         try{

            $question_exists = Question::where('question',$request->question)
                            ->where('project_id',$request->project_id)
                            ->first();
            if($question_exists){
                flash('Question already exists. Create unique Question.')->error();
                return redirect()->back()->withInput();
            }


            if(!$request->get('reference')){
                flash('Kindly add a reference to the question.')->error();
                return redirect()->back()->withInput();
            }

            // keep the reference in capitals
            $request->merge(['reference' => strtoupper($request->reference)]);

            $quest = summernote_imageupload(\auth::user(),$request->question);
            $request->merge(['question'=>$quest]);
            $a = summernote_imageupload(\auth::user(),$request->a);
            $request->merge(['a'=>$a]);
            $b = summernote_imageupload(\auth::user(),$request->b);
            $request->merge(['b'=>$b]);
            $c = summernote_imageupload(\auth::user(),$request->c);
            $request->merge(['c'=>$c]);
            $d = summernote_imageupload(\auth::user(),$request->d);
            $request->merge(['d'=>$d]);
            $e = summernote_imageupload(\auth::user(),$request->e);
            $request->merge(['e'=>$e]);

            $explanation = summernote_imageupload(\auth::user(),$request->explanation);
            $request->merge(['explanation'=>$explanation]);

            $question = Question::create($request->except(['category','tag','sections']));

            if($request->dynamic){
                $question->dynamic_code_save();
            }

            // create categories
            if($categories)
            foreach($categories as $category){
                if(!$question->categories->contains($category))
                    $question->categories()->attach($category,array('intest' => $request->intest));
            }

            // create tags
            if($tags)
            foreach($tags as $tag){
                if(!$question->tags->contains($tag))
                    $question->tags()->attach($tag);
            }

            // create tags
            if($sections)
            foreach($sections as $section){
                if(!$question->sections->contains($section))
                    $question->sections()->attach($section);
            }

            flash('A new question is created!')->success();
            

            if(!request()->get('url'))
                return redirect()->route('question.index',$this->project->slug);
            else
                return redirect()->route('exam.question',[$exam->slug,$question->id]);
        }
        catch (QueryException $e){
           flash('There is some error in storing the data...kindly retry.')->error();
            return redirect()->back()->withInput();
        }
    }

    public function export($id,Request $r){
        $topic = $r->get('topic');
        $category = Category::where('slug',$topic)->first();

        if(!$category)
            abort('403','No category');

        $json_ques = json_encode($category->questions);
        print($json_ques);
    }

    public function import($id,Request $r){
        
        $topic = $r->get('topic');
        $section_id = $r->get('section_id');
        $category = Category::where('slug',$topic)->first();

        $section = Section::where('id',$section_id)->first();
        $url = $r->get('url');

        if(!$category)
            abort('403','No category');

        $json_ques = json_decode(file_get_contents($url));
        
        
        foreach($json_ques as $q){
            $ques = new Question();
            foreach($q as $a=>$b){
                if($a!='pivot' && $a!='id')
                $ques->$a = $b;
            }
            $ques->project_id = $this->project->id;
            
            $ques->save();

            if(!$ques->categories->contains($category->id))
                $ques->categories()->attach($category->id);

            if($section){
                if(!$ques->sections->contains($section->id))
                    $ques->sections()->attach($section->id);
            }
        }
    }

    public function copy($id,Request $r){

        $topic_id = $r->session()->get('topic_id');

        if(!$topic_id){
            abort('403','Topic Not Defined');
        }

        $category = Category::where('id',$topic_id)->first();

        $project = Project::where('slug',$r->session()->get('course_slug'))->first();

        $q = Question::where('id',$id)->first();
        $old_project = Project::where('id',$q->project_id)->first();

        $passage = Passage::where('id',$q->passage_id)->first();
        
        if($passage){
             $p = Passage::where('passage',$passage->passage)
                    ->where('project_id',$project->id)
                    ->first();
             if(!$p){
                 $p = new Passage();
                 $p->name = $passage->name;
                 $p->passage = $passage->passage;
                 $p->user_id = \auth::user()->id;
                 $p->project_id = $project->id;
                 $p->stage = $passage->stage;
                 $p->status = $passage->status;
                 $p->save();
             }
             $q->passage_id = $p->id;    
        }

        try{
            $question = new Question();
            $question->reference = strtoupper($q->reference);
            $question->question = $q->question;
            $question->type = $q->type;
            $question->a = $q->a;
            $question->b = $q->b;
            $question->c = $q->c;
            $question->d = $q->d;
            $question->e = $q->e;
            $question->answer = $q->answer;
            $question->explanation = $q->explanation;
            $question->dynamic = $q->dynamic;
            $question->passage_id= $q->passage_id;
            $question->project_id= $project->id;
            $question->stage = 0;
            $question->slug = str_random(10);
            $question->user_id = \auth::user()->id;
            $question->status = $q->status;
            $question->level = $q->level;
            if($q->topic){
               $question->topic = $q->topic; 
           }else{
            if(count($q->categories)!=0){
               foreach($q->categories as $m=>$cat)
                {
                    if($m==0)
                    $c = $cat->name;
                    else
                        $c = $c.', '.$cat->name;
                } 
            } 
            $question->topic = $c;
           }


            
            $question->intest = $q->intest;
            
            $question->save(); 

            if(!$q->intest)
                $intest = 0;
            else
                $intest = $q->intest;

            if(!$question->categories->contains($category->id))
                $question->categories()->attach($category->id,array('intest' => $intest)); 

            flash('Question (<b>'.$q->slug.'</b>) Successfully Copied to '.$category->name.'!')->success();
            
            return redirect()->back();
  
        }
        catch (QueryException $e){
            flash('There is some error in storing the data...kindly retry.')->error();
            return redirect()->back()->withInput();
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show2($id)
    {
        $question = Question::where('id',$id)->first();
        $passage = Passage::where('id',$question->passage_id)->first();

            return  view('appl.dataentry.question.show2')
                    ->with('mathjax',true)
                    ->with('question',$question)
                    ->with('passage',$passage);
            
  
    }


     public function show($project_slug,$id)
    {
        $question = Question::where('id',$id)->first();

        $question = $question->dynamic_variable_replacement();



        $course = Course::where('slug',$project_slug)->first();

        if($course)
            $exams = $course->exams;
        else    
        $exams =  Exam::orderBy('name','desc')->get();
      
        
        $this->authorize('view', $question);

        if($question){

            if(request()->get('publish'))
            {
                $question->status = 2;
                $question->save();
            }

            $passage = Passage::where('id',$question->passage_id)->first();
            $questions = Question::select('id','status')
                                ->where('project_id',$this->project->id)
                                ->orderBy('created_at','desc ')
                                ->get();
            $details = ['curr'=>null,'prev'=>null,'next'=>null,'qno'=>null,'display_type'=>'project']; 

            $details['curr'] = route('question.show',[$project_slug,$question->id]);
            foreach($questions as $key=>$q){

                if($q->id == $question->id){

                    if($key!=0)
                        $details['prev'] = route('question.show',[$project_slug,$questions[$key-1]->id]);

                    if(count($questions) != $key+1)
                        $details['next'] = route('question.show',[$project_slug,$questions[$key+1]->id]);

                    $details['qno'] = $key + 1 ;

                

                }
            } 
            return  view('appl.dataentry.question.show')
                    ->with('project',$this->project)
                    ->with('mathjax',true)
                    ->with('question',$question)
                    ->with('passage',$passage)
                    ->with('details',$details)
                    ->with('exams',$exams)
                    ->with('questions',$questions);
            
        }
        else
            abort(404,'Question not found');
    }

    public function parseToArray($xpath,$class)
{
    $xpathquery="//span[@class='".$class."']";
    $elements = $xpath->query($xpathquery);

    if (!is_null($elements)) {  
        $resultarray=array();
        foreach ($elements as $element) {
            $nodes = $element->childNodes;
            foreach ($nodes as $node) {
              $resultarray[] = $node->nodeValue;
            }
        }
        return $resultarray;
    }
}

         /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function categoryCourseSave($project_slug,$category_slug,$id)
    {
        $category = Category::where('slug',$category_slug)->first();
        $question = Question::where('id',$id)->first();

        if($question){

            $practice = Practice::where('user_id',\auth::user()->id)->where('qid',$id)->first();
            if(!$practice){
                $practice = new Practice;
                $practice->qid = $id;
                $practice->course_id = request()->get('course_id');
                $practice->user_id = \auth::user()->id;
                $practice->response = strtoupper(request()->get('response'));
                $practice->answer = strtoupper($question->answer);
                $practice->category_id = $question->categories->last()->id;

                $now =  microtime(true);
                $start = session('start');
                $practice->time = $now-$start;
                ($practice->answer == $practice->response)? $practice->accuracy  = 1:$practice->accuracy  = 0;
                $practice->save();


                //update in practices_course
                $practices_course = Practices_Course::where('user_id',\auth::user()->id)->where('course_id',request()->get('course_id'))->first();

                if(!$practices_course){
                    $practices_course = new Practices_Course;
                }

                $practices_course->user_id = \auth::user()->id;
                $practices_course->course_id = request()->get('course_id');
                $practices_course->attempted += 1;
                if($practice->accuracy==1)
                    $practices_course->correct += 1;
                else
                    $practices_course->incorrect += 1;
                $practices_course->time += $practice->time;

                $practices_course->save();

                //update in practices_topic
                $practices_topic = Practices_Topic::where('user_id',\auth::user()->id)->where('category_id',$practice->category_id)->first();

                if(!$practices_topic){
                    $practices_topic = new Practices_Topic;
                    $practices_topic->category_id = $practice->category_id;
                }


                $practices_topic->user_id = \auth::user()->id;
                $practices_topic->attempted += 1;
                if($practice->accuracy==1)
                    $practices_topic->correct += 1;
                else
                    $practices_topic->incorrect += 1;
                $practices_topic->time += $practice->time;

                $practices_topic->save();

            }
            

        }
        return redirect()->route('course.question',[$project_slug,$category_slug,$id]);


    }

        /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function categoryCourse($project_slug,$category_slug,$id=null)
    {
        
        $tests = ['test1','test2','test3','test4','test5'];
        $course = Course::where('slug',$project_slug)->first();


        $user = \Auth::user();
        $entry=null;
        if($user){
                $entry = DB::table('product_user')
                    ->whereIn('product_id', $course->products()->pluck('id')->toArray())
                    ->where('user_id', $user->id)
                    ->orderBy('valid_till','desc')
                    ->first();


                if($entry){
                    if(strtotime($entry->valid_till) < strtotime(date('Y-m-d'))){

                        
                        return view('appl.course.course.access');
                    }else if($entry->status===0)
                        return view('appl.course.course.access');
                }
                else{

                    if($course->products()->first()->price!=0)
                    return view('appl.course.course.access');
                }
        }

        
        if(!$entry)
        {
            if($course->products()->first()->price!=0)
            return view('appl.course.course.access');
        }


        

        
        if($category_slug == 'uncategorized')
        {
            $category = new Category();
            $category->name = 'Uncategorized';
            $category->slug = 'uncategorized';
            $category_slug = 'uncategorized';
            $category->questions = Category::getUncategorizedQuestions($this->project);

        }else
            $category = Category::where('slug',$category_slug)->first();


       


        if($id==null){
            if($category_slug=='uncategorized')
                $id = $category->questions->first()->id;
            elseif($category->questions){
                if(isset($category->questions[0]))
                $id = $category->questions[0]->id;
                else
                $id = null ;

            }else
                $id=null;

            $exam = session('exam');


            if($exam && $exam!='all'){
            $ques_category = DB::table('category_question')->where('category_id', $category->id)->distinct()->get(['question_id'])->pluck('question_id')->toArray();
            $tag = Tag::where('value',$exam)->first();
            if($tag)
            $ques_tag = DB::table('question_tag')->where('tag_id', $tag->id)->distinct()->get(['question_id'])->pluck('question_id')->toArray();
            else
                $ques_tag =0;

            $list = array_intersect($ques_tag, $ques_category);
            $id = reset($list);
            }else{
                $id=$category->questions()->wherePivot('intest','!=',1)->pluck('id')->toArray()[0];

            }



             
        }
        
        

        if($id){

           
            $question = Question::where('id',$id)->first();
            $question = $question->dynamic_variable_replacement();

           // $this->authorize('view', $question);

            if($question){

                 if(request()->get('publish'))
                {
                    $question->status = 2;
                    $question->save();
                }

                $passage = Passage::where('id',$question->passage_id)->first();
                $questions = $category->questions()->wherePivot('intest','!=',1)->get();

                //if(request()->get('debug'))
                //dd($questions);

                $details = ['curr'=>null,'prev'=>null,'next'=>null,'qno'=>null,'display_type'=>'category']; 
            
                $details['curr'] = route('course.question',[$project_slug,$category_slug,$question->id]);
                foreach($questions as $key=>$q){

                    if($q->id == $question->id){

                        if($key!=0)
                            $details['prev'] = route('course.question',[$project_slug,$category_slug,$questions[$key-1]->id]);

                        if(count($questions) != $key+1)
                            $details['next'] = route('course.question',[$project_slug,$category_slug,$questions[$key+1]->id]);

                        $details['qno'] = $key + 1 ;

                    }

                } 

                $details['display_type'] = 'Topic';
                $details['course'] = Course::where('slug',$project_slug)->first();
                $details['response'] = $question->practice($question->id);

                //dd($details);
                session(['start' => microtime(true)]) ;

                
                return view('appl.dataentry.question.show_course')
                        ->with('project',$this->project)
                        ->with('mathjax',true)
                        ->with('question',$question)
                        ->with('tests',$tests)
                        ->with('passage',$passage)
                        ->with('details',$details)
                        ->with('category',$category)
                        ->with('questions',$questions);
            }else
                abort('404','Question not found');
            
        }
        else
            abort(403);

    }




        /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function category($project_slug,$category_slug,$id=null)
    {
        
        $mode = \request()->session()->get('mode');
        $change = \request()->get('change');

        if($change){
            \request()->session()->put('mode',\request()->get('mode'));
        }

        if(!$mode){
            \request()->session()->put('mode','reference');
            $mode = \request()->session()->get('mode');
        }


        if($category_slug == 'uncategorized')
        {
            $category = new Category();
            $category->name = 'Uncategorized';
            $category->slug = 'uncategorized';
            $category_slug = 'uncategorized';
            $category->questions = Category::getUncategorizedQuestions($this->project);

        }else
            $category = Category::where('slug',$category_slug)->first();

        if($id==null){
            if($category_slug=='uncategorized')
                $id = $category->questions->first()->id;
            elseif($category->questions){
                if(isset($category->questions[0]))
                $id = $category->questions[0]->id;
                else
                $id = null ;

            }else
                $id=null;
        }
        
        $course = Course::where('slug',$project_slug)->first();
        if($course)
            $exams = $course->exams()->orderby('id','desc')->get();
        else    
        $exams =  Exam::orderBy('name','desc ')->get();

        $list = $category->descendants;


        if($id){
            $question = Question::where('id',$id)->first();
            $question = $question->dynamic_variable_replacement();

            $this->authorize('view', $question);

            if($question){

                 if(request()->get('publish'))
                {
                    $question->status = 2;
                    $question->save();
                }

                $passage = Passage::where('id',$question->passage_id)->first();
                $questions = $category->questions;

                $details = ['curr'=>null,'prev'=>null,'next'=>null,'qno'=>null,'display_type'=>'category']; 
            
                $details['curr'] = route('category.question',[$project_slug,$category_slug,$question->id]);
                foreach($questions as $key=>$q){

                    if($q->id == $question->id){

                        if($key!=0)
                            $details['prev'] = route('category.question',[$project_slug,$category_slug,$questions[$key-1]->id]);

                        if(count($questions) != $key+1)
                            $details['next'] = route('category.question',[$project_slug,$category_slug,$questions[$key+1]->id]);

                        $details['qno'] = $key + 1 ;
                    }
                } 

                return view('appl.dataentry.question.show')
                        ->with('project',$this->project)
                        ->with('mathjax',true)
                        ->with('question',$question)
                        ->with('passage',$passage)
                        ->with('details',$details)
                        ->with('category',$category)
                        ->with('list',$list)
                        ->with('exams',$exams)
                        ->with('stub','Create')
                        ->with('questions',$questions);
            }else
                abort('404','Question not found');
            
        }
        else
            abort(403);

    }
        /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function tag($project_slug,$tag_id,$id=null)
    {
        
        $tag = Tag::where('id',$tag_id)->first();

        if($id==null){
            if($tag->questions){
                if(isset($tag->questions[0]))
                $id = $tag->questions[0]->id;
                else
                $id = null ;
            }else
                $id=null;
        }

        $exams =  Exam::orderBy('name','desc ')->get();
        


        if($id){
            $question = Question::where('id',$id)->first();
            $this->authorize('view', $question);

            if($question){

                 if(request()->get('publish'))
                {
                    $question->status = 2;
                    $question->save();
                }
            
                $passage = Passage::where('id',$question->passage_id)->first();
                $questions = $tag->questions;

                $details = ['curr'=>null,'prev'=>null,'next'=>null,'qno'=>null,'display_type'=>'tag']; 
            
                $details['curr'] = route('tag.question',[$project_slug,$tag_id,$question->id]);
                foreach($questions as $key=>$q){

                    if($q->id == $question->id){

                        if($key!=0)
                            $details['prev'] = route('tag.question',[$project_slug,$tag_id,$questions[$key-1]->id]);

                        if(count($questions) != $key+1)
                            $details['next'] = route('tag.question',[$project_slug,$tag_id,$questions[$key+1]->id]);

                        $details['qno'] = $key + 1 ;
                    }
                } 

                return view('appl.dataentry.question.show')
                        ->with('project',$this->project)
                        ->with('mathjax',true)
                        ->with('question',$question)
                        ->with('passage',$passage)
                        ->with('details',$details)
                        ->with('tag',$tag)
                        ->with('exams',$exams)
                        ->with('questions',$questions);
            }else
                abort('404','Question not found');
            
        }
        else
            abort(403);

    }   


    public function qlist($exam_slug,$id=null){
        $exam = Exam::where('slug',$exam_slug)->first();
        $exams =  Exam::orderBy('name','desc ')->get();

        if($id == null)
        if(count($exam->sections)!=0)
        foreach($exam->sections as $section){
            if(count($section->questions)!=0)
            foreach($section->questions as $question)
            {
                $id = $question->id;
                break; 
            }else
                $id = null;
            break;
        }else
            $id = null;
        

        if($id){
            $question = Question::where('id',$id)->first();
            $this->authorize('view', $question);

            if($question){

                 if(request()->get('publish'))
                {
                    $question->status = 2;
                    $question->save();
                }
            
                $passage = Passage::where('id',$question->passage_id)->first();
                //$questions = $tag->questions;
                $questions = array();
                $i=0;
                foreach($exam->sections as $section){
                    foreach($section->questions as $q){
                        $questions[$i] = $q;
                        $i++;
                    }
                }
                

                $details = ['curr'=>null,'prev'=>null,'next'=>null,'qno'=>null,'display_type'=>'tag']; 
            
                $details['curr'] = route('exam.question',[$exam_slug,$question->id]);
                foreach($questions as $key=>$q){

                    if($q->id == $question->id){

                        if($key!=0)
                            $details['prev'] = route('exam.question',[$exam_slug,$questions[$key-1]->id]);

                        if(count($questions) != $key+1)
                            $details['next'] = route('exam.question',[$exam_slug,$questions[$key+1]->id]);

                        $details['qno'] = $key + 1 ;
                    }
                } 

                return view('appl.dataentry.question.show_qlist')
                        ->with('mathjax',true)
                        ->with('passage',$passage)
                        ->with('details',$details)
                        ->with('exam',$exam)
                        ->with('exams',$exams)
                        ->with('questions',$questions);
            }else
                abort('404','Question not found');
            
        }
        else
            abort(403);
    }

            /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function exam($exam_slug,$id=null)
    {
        
        $exam = Exam::where('slug',$exam_slug)->first();
        $exams =  Exam::orderBy('name','desc ')->get();

        if($id == null)
        if(count($exam->sections)!=0)
        foreach($exam->sections as $section){
            if(count($section->questions)!=0)
            foreach($section->questions as $question)
            {
                $id = $question->id;
                break; 
            }else
                $id = null;
            break;
        }else
            $id = null;
        

        if($id){
            $question = Question::where('id',$id)->first();

            if(request()->get('remove'))
            {
                if(count($exam->sections)!=0)
                foreach($exam->sections as $section){
                    if(count($section->questions)!=0)
                    foreach($section->questions as $ques)
                    {
                        if($id == $ques->id)
                            $sec = $section;
                            break; 
                    }
                }

                if(isset($sec)){
                    $sec->questions()->detach($id);
                    return redirect()->route('exam.questions',$exam_slug);
                }
            }

            $this->authorize('view', $question);

            if($question){

                 if(request()->get('publish'))
                {
                    $question->status = 2;
                    $question->save();
                }
            
                $passage = Passage::where('id',$question->passage_id)->first();
                //$questions = $tag->questions;
                $questions = array();
                $i=0;
                foreach($exam->sections as $section){
                    foreach($section->questions as $q){
                        $questions[$i] = $q;
                        $i++;
                    }
                }
                

                $details = ['curr'=>null,'prev'=>null,'next'=>null,'qno'=>null,'display_type'=>'tag']; 
            
                $details['curr'] = route('exam.question',[$exam_slug,$question->id]);
                foreach($questions as $key=>$q){

                    if($q->id == $question->id){

                        if($key!=0)
                            $details['prev'] = route('exam.question',[$exam_slug,$questions[$key-1]->id]);

                        if(count($questions) != $key+1)
                            $details['next'] = route('exam.question',[$exam_slug,$questions[$key+1]->id]);

                        $details['qno'] = $key + 1 ;
                    }
                } 

                return view('appl.dataentry.question.show_exam')
                        ->with('mathjax',true)
                        ->with('question',$question)
                        ->with('passage',$passage)
                        ->with('details',$details)
                        ->with('exam',$exam)
                        ->with('exams',$exams)
                        ->with('highlight',true)
                        ->with('questions',$questions);
            }else
                abort('404','Question not found');
            
        }
        else{
            return view('appl.dataentry.question.create_ques')->with('exam',$exam);
            
        }

    } 


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($project_slug,$id)
    {
        $question = Question::where('id',$id)->first();
        $this->authorize('update', $question);

        $question = Question::where('id',$id)->first();

        

        // merge answer for maq question
        if($question->type=='maq'){
            $answer = explode(",",$question->answer);
            $question->answer = $answer;
        }

        $passage = Passage::where('id',$question->passage_id)->first();

        $passages = Passage::where('project_id',$this->project->id)->orderBy('created_at','desc ')
                        ->paginate(config('global.no_of_records'));

        if(!is_array($question->answer))
        $question->answer = strtoupper(strip_tags(trim(preg_replace('/\s\s+/', ' ', $question->answer))));

        // Categories
        $category_parent =  Category::where('slug',$this->project->slug)->first();   
        $category_node = Category::defaultOrder()->descendantsOf($category_parent->id)->toTree();
        if(count($category_node))
            $categories = Category::displayUnorderedCheckList($category_node,['category_id'=>$question->categories->pluck('id')->toArray()]);
        else
            $categories =null;

        //tags
        $tags =  Tag::where('project_id',$this->project->id)
                        ->orderBy('created_at','desc ')
                        ->get()->groupBy(function($item)
                        {
                          return $item->name;
                        });

         if(request()->get('exam'))
            $exams =  Exam::where('id',request()->get('exam'))->orderBy('name','desc ')->get();
        else
        $exams =  Exam::orderBy('name','desc ')->get();

        $question->tags = $question->tags->pluck('id')->toArray();         

        if($question)
            return view('appl.dataentry.question.createedit')
                    ->with('project',$this->project)
                    ->with('question',$question)
                    ->with('passages',$passages)
                    ->with('passage',$passage)
                    ->with('categories',$categories)
                    ->with('tags',$tags)
                    ->with('exams',$exams)
                    ->with('type',$question->type)
                    ->with('code',true)
                    ->with('editor',true)
                    ->with('stub','Update');
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
    public function update(Request $request,$project_slug, $id)
    {
        // merge answer for maq question
        if(is_array($request->answer)){
            $answer = implode(",",$request->answer);
            $request->merge(['answer' => $answer]);
        }



        $categories = $request->get('category');
        $tags = $request->get('tag');
        $sections = $request->get('sections');


        try{

            $question = Question::where('id',$id)->first();
            $question->reference = strtoupper($request->reference);
            $question->question = summernote_imageupload(\auth::user(),$request->question);
            $question->question_b = $request->question_b;
            $question->question_c = $request->question_c;
            $question->question_d = $request->question_d;
            $question->passage = $request->passage;
            $question->a = summernote_imageupload(\auth::user(),$request->a);
            $question->b = summernote_imageupload(\auth::user(),$request->b);
            $question->c = summernote_imageupload(\auth::user(),$request->c);
            $question->d = summernote_imageupload(\auth::user(),$request->d);
            $question->e = summernote_imageupload(\auth::user(),$request->e);
            $question->answer = $request->answer;
            $question->type = $request->type;
            $question->explanation = summernote_imageupload(\auth::user(),$request->explanation);
            $question->dynamic = $request->dynamic;
            $question->passage_id= ($request->passage_id)?$request->passage_id:null;
            $question->status = $request->status;
            $question->level = $request->level;
            $question->intest = $request->intest;
            $question->topic = $request->topic;
            $question->save(); 

            if($request->dynamic){
                $question->dynamic_code_save();
            }


            

            // Categories
            $category_parent =  Category::where('slug',$this->project->slug)->first();   
            $category_list = Category::defaultOrder()->descendantsOf($category_parent->id)->pluck('id');
            // update categories
            if($categories)
            foreach($category_list as $category){
                if(in_array($category, $categories)){
                    if(!$question->categories->contains($category))
                        $question->categories()->attach($category,array('intest' => $request->intest));
                    else{
                       $question->categories()->updateExistingPivot($category, array('intest' => $request->intest)); 
                    }
                }else{
                    if($question->categories->contains($category))
                        $question->categories()->detach($category);
                }
                
            }   

            $tag_list =  Tag::where('project_id',$this->project->id)
                        ->orderBy('created_at','desc ')
                        ->get()->pluck('id')->toArray();
            //update tags
            if($tags)
            foreach($tag_list as $tag){
                if(in_array($tag, $tags)){
                    if(!$question->tags->contains($tag))
                        $question->tags()->attach($tag);
                }else{
                    if($question->tags->contains($tag))
                        $question->tags()->detach($tag);
                }
                
            } 

            $section_list =  Section::orderBy('created_at','desc ')
                        ->get()->pluck('id')->toArray();
            //update exam sections
            if($sections)
            foreach($section_list as $section){
                if(in_array($section, $sections)){
                    if(!$question->sections->contains($section))
                        $question->sections()->attach($section);
                }else{
                    if($question->sections->contains($section))
                        $question->sections()->detach($section);
                }
                
            }else{
                $question->sections()->detach();
            } 

            flash('Question (<b>'.$question->slug.'</b>) Successfully updated!')->success();
            //dd(request()->get('url'));
            if(!request()->get('url'))
            return redirect()->route('question.show',[$project_slug,$id]);
            else
            return redirect(request()->get('url'));
        }
        catch (QueryException $e){
            flash('There is some error in storing the data...kindly retry.')->error();
            return redirect()->back()->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function attachCategory($question_id,$category_id)
    {
        $question = Question::where('id',$question_id)->first();
        if(!$question->categories->contains($category_id))
            $question->categories()->attach($category_id);
    }


     /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function detachCategory($question_id,$category_id)
    {
        $question = Question::where('id',$question_id)->first();
        if($question->categories->contains($category_id))
            $question->categories()->detach($category_id);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function attachSection($question_id,$section_id)
    {
        $question = Question::where('id',$question_id)->first();
        if(!$question->sections->contains($section_id))
            $question->sections()->attach($section_id);
    }


     /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function detachSection($question_id,$section_id)
    {
        $question = Question::where('id',$question_id)->first();
        if($question->sections->contains($section_id))
            $question->sections()->detach($section_id);
    }


     public function addTest($question_id)
    {
        $question = Question::where('id',$question_id)->first();
        $question->intest = 1;
        if($question->categories->first())
        {
            $category  = $question->categories->first()->id;
             $question->categories()->updateExistingPivot($category, array('intest' => $question->intest)); 
        }

       
        $question->save();
    }

     public function removeTest($question_id)
    {
        $question = Question::where('id',$question_id)->first();
        $question->intest = 0;
        if($question->categories->first())
        {
            $category  = $question->categories->first()->id;
             $question->categories()->updateExistingPivot($category, array('intest' => $question->intest)); 
        }
        
        $question->save();
    }


    public function pdf(Request $request)
    {
        ini_set('max_execution_time', 300); //300 seconds = 5 minutes
        $topic  = 'antonyms';
        
        if($request->get('topic'))
            $topic = $request->get('topic');
        else
            abort('404');
        $category = Category::where('slug',$topic)->first();
        $topicname = $category->name;

        $questions = $category->questions()->get();
        
        foreach($questions as $k=>$question){
            $questions[$k]->question = str_replace('In following question, choose the word opposite to the meaning to the given word.', '', $questions[$k]->question);
            $questions[$k]->question = str_replace('In following question consists of a word or phase which is italicised bold in the sentence given. It is followed by certain words or phases. Select the work or phase which is closest to the opposite in meaning of the italicised word or phase.', '', $questions[$k]->question);
            
            $questions[$k]->question = str_replace('In each of the following sentences, choose the word opposite in meaning to the italicised word to fill in the blanks.', '', $questions[$k]->question);
            $questions[$k]->question  = preg_replace( '/^<[^>]+>|<\/[^>]+>$/', '', $questions[$k]->question  );
            $questions[$k]->question=str_ireplace('<p>','',$questions[$k]->question);
            $questions[$k]->question=str_ireplace('</p>','',$questions[$k]->question);
            $questions[$k]->question=str_ireplace('<span style="font-size: 1rem;">','<b>',$questions[$k]->question);
             $questions[$k]->question=str_ireplace('</span>','</b>',$questions[$k]->question);

        }


        $pdf = PDF::loadView('appl.content.chapter.pdf',compact('questions','topicname'));
        $pdf->save($topic.'.pdf'); 
        

        
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($project_slug,$id)
    {
        $question = Question::where('id',$id)->first();
        $this->authorize('view', $question);
        $question->tags();
        $question->categories();
        $question->sections();
        $question->delete();
        flash('Question Successfully deleted!')->success();

        if(request()->get('exam')){
            $exam = Exam::where('id',request()->get('exam'))->first();
        }

        if(!request()->get('url'))
            return redirect()->route('question.index',$project_slug);
        else
            return redirect()->route('exam.questions',[$exam->slug]);
        
    }
}
