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
use Illuminate\Support\Facades\DB;


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

        ($request->order) ? $order = $request->order : $order = 'desc';
        ($request->orderby) ? $orderby = $request->orderby : $orderby = 'created_at';

        $questions = $question
                        ->where(function ($query) use ($item) {
                                $query->where('question','LIKE',"%{$item}%")
                                      ->orWhere('reference', 'LIKE', "%{$item}%");
                            })
                        ->where('project_id',$this->project->id)
                        ->orderBy($orderby,$order)
                        ->paginate(config('global.no_of_records'));

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

        // Question Types
        $allowed_types = ['mcq','naq','maq','eq'];
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
            $question = Question::create($request->except(['category','tag']));

            // create categories
            if($categories)
            foreach($categories as $category){
                if(!$question->categories->contains($category))
                    $question->categories()->attach($category);
            }

            // create tags
            if($tags)
            foreach($tags as $tag){
                if(!$question->tags->contains($tag))
                    $question->tags()->attach($tag);
            }

            flash('A new question is created!')->success();
            return redirect()->route('question.index',$this->project->slug);
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
    public function show($project_slug,$id)
    {
        $question = Question::where('id',$id)->first();


      
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
            return view('appl.dataentry.question.show')
                    ->with('project',$this->project)
                    ->with('mathjax',true)
                    ->with('question',$question)
                    ->with('passage',$passage)
                    ->with('details',$details)
                    ->with('questions',$questions);
        }
        else
            abort(404,'Question not found');
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

            $practice = new Practice;
            $practice->qid = $id;
            $practice->course_id = request()->get('course_id');
            $practice->user_id = \auth::user()->id;
            $practice->response = strtoupper(request()->get('response'));
            $practice->answer = strtoupper($question->answer);

            $now =  microtime(true);
            $start = session('start');
            $practice->time = $now-$start;
            ($practice->answer == $practice->response)? $practice->accuracy  = 1:$practice->accuracy  = 0;
            $practice->save();
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
            }else
                $id=$category->questions()->pluck('id')->toArray()[0];

             
        }
        
        

        if($id){

           
            $question = Question::where('id',$id)->first();



           // $this->authorize('view', $question);

            if($question){

                 if(request()->get('publish'))
                {
                    $question->status = 2;
                    $question->save();
                }

                $passage = Passage::where('id',$question->passage_id)->first();
                $questions = $category->getQuestions();
                //dd($question);

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

                session(['start' => microtime(true)]) ;

                return view('appl.dataentry.question.show_course')
                        ->with('project',$this->project)
                        ->with('mathjax',true)
                        ->with('question',$question)
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
                        ->with('questions',$questions);
            }else
                abort('404','Question not found');
            
        }
        else
            abort(403);

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


        // merge answer for maq question
        if($question->type=='maq'){
            $answer = explode(",",$question->answer);
            $question->answer = $answer;
        }

        $passage = Passage::where('id',$question->passage_id)->first();

        $passages = Passage::where('project_id',$this->project->id)->orderBy('created_at','desc ')
                        ->paginate(config('global.no_of_records'));

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
        $question->tags = $question->tags->pluck('id')->toArray();         

        if($question)
            return view('appl.dataentry.question.createedit')
                    ->with('project',$this->project)
                    ->with('question',$question)
                    ->with('passages',$passages)
                    ->with('passage',$passage)
                    ->with('categories',$categories)
                    ->with('tags',$tags)
                    ->with('type',$question->type)
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

        try{

            $question = Question::where('id',$id)->first();
            $question->reference = strtoupper($request->reference);
            $question->question = $request->question;
            $question->a = $request->a;
            $question->b = $request->b;
            $question->c = $request->c;
            $question->d = $request->d;
            $question->e = $request->e;
            $question->answer = $request->answer;
            $question->explanation = $request->explanation;
            $question->dynamic = $request->dynamic;
            $question->passage_id= ($request->passage_id)?$request->passage_id:null;
            $question->status = $request->status;
            $question->save(); 

            // Categories
            $category_parent =  Category::where('slug',$this->project->slug)->first();   
            $category_list = Category::defaultOrder()->descendantsOf($category_parent->id)->pluck('id');
            // update categories
            if($categories)
            foreach($category_list as $category){
                if(in_array($category, $categories)){
                    if(!$question->categories->contains($category))
                        $question->categories()->attach($category);
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

            flash('Question (<b>'.$question->slug.'</b>) Successfully updated!')->success();
            return redirect()->route('question.show',[$project_slug,$id]);
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
    public function destroy($project_slug,$id)
    {
        $question = Question::where('id',$id)->first();
        $this->authorize('view', $question);
        $question->delete();
        flash('Question Successfully deleted!')->success();
        return redirect()->route('question.index',$project_slug);
    }
}
