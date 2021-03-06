<?php

namespace PacketPrep\Http\Controllers\College;

use Illuminate\Http\Request;
use PacketPrep\Http\Controllers\Controller;
use PacketPrep\Models\College\College as Obj;
use PacketPrep\Models\College\Zone;
use PacketPrep\Models\College\Branch;
use PacketPrep\Models\College\Metric;
use PacketPrep\Models\Course\Course;
use PacketPrep\User;
use PacketPrep\Models\User\User_Details;


class CollegeController extends Controller
{
    public function __construct(){
        $this->app      =   'college';
        $this->module   =   'college';
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Obj $obj,Request $request)
    {

        $this->authorize('view', $obj);

        $search = $request->search;
        $item = $request->item;
        
     
        $objs = $obj->where('name','LIKE',"%{$item}%")->withCount('users')->orderBy('users_count', 'desc')->paginate(config('global.no_of_records')); 



        $view = $search ? 'list': 'index';

        return view('appl.'.$this->app.'.'.$this->module.'.'.$view)
                ->with('objs',$objs)
                ->with('obj',$obj)
                ->with('app',$this);
    }

    public function top30(Obj $obj,Request $request)
    {

        if(!\auth::user()->checkRole(['administrator','investor','patron','promoter','employee','client-owner','client-manager','manager']))
        {
             abort(403,'Unauthorised Access');   
        }


        $search = $request->search;
        $item = $request->item;
        
        $objs = $obj->where('name','LIKE',"%{$item}%")->where('type','btech')->where('name','!=','- Not in List -')->withCount('users')->orderBy('users_count', 'desc')->paginate(25); 

        

        //dd($objs);
        $view = $search ? 'list': 'index';

        return view('appl.'.$this->app.'.'.$this->module.'.top30')
                ->with('objs',$objs)
                ->with('obj',$obj)
                ->with('app',$this);
    }
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $obj = new Obj();
        $this->authorize('create', $obj);
        $zones = Zone::all();
        $branches = Branch::all();
        $courses = Course::all();


        return view('appl.'.$this->app.'.'.$this->module.'.createedit')
                ->with('stub','Create')
                ->with('jqueryui',true)
                ->with('obj',$obj)
                ->with('zones',$zones)
                ->with('branches',$branches)
                ->with('courses',$courses)
                ->with('app',$this);
    }
   

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Obj $obj, Request $request)
    {
         try{

            $zone_id = $request->get('zone_id');
            $branches = $request->get('branches');


            $obj = $obj->create($request->except(['zone_id','branches']));

            //branches
            $branch_list =  Branch::orderBy('created_at','desc ')
                        ->get()->pluck('id')->toArray();
            if($branches)
            foreach($branch_list as $branch){
                if(in_array($branch, $branches)){
                    if(!$obj->branches->contains($branch))
                        $obj->branches()->attach($branch);
                }else{
                    if($obj->branches->contains($branch))
                        $obj->branches()->detach($branch);
                }
                
            }else{
                $obj->branches()->detach();
            } 

            //zone

            if(!$obj->zones->contains($zone_id))
                $obj->zones()->attach($zone_id);

            $courses = $request->get('courses');
            if($courses){
                $obj->courses()->detach();
                foreach($courses as $course){
                    if(!$obj->courses->contains($course))
                        $obj->courses()->attach($course);
                }
            }else{
                $obj->courses()->detach();
            }

            flash('A new ('.$this->app.'/'.$this->module.') item is created!')->success();
            return redirect()->route($this->module.'.index');
        }
        catch (QueryException $e){
           $error_code = $e->errorInfo[1];
            if($error_code == 1062){
                flash('Some error in Creating the record')->error();
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
        $obj = Obj::where('id',$id)->first();

        //dd($obj->users);
        $this->authorize('view', $obj);
        if($obj)
            return view('appl.'.$this->app.'.'.$this->module.'.show')
                    ->with('obj',$obj)->with('app',$this);
        else
            abort(404);
    }


    public function analysis(Request $request)
    {


        //$slug = subdomain();
        //$client = client::where('slug',$slug)->first();
        //$this->authorize('view', $client);

        if(!\auth::user()->checkRole(['administrator','investor','patron','promoter','employee','client-owner','client-manager','manager']))
        {
             abort(403,'Unauthorised Access');   
        }


        $users = new CollegeController;
        $users->total = User::count();

        $amb = User::whereHas('roles', function ($query)  {
                                $query->where('name', '=', 'Campus Ambassador');
                            })->get();

        $i=0;
        foreach($amb as $k => $u){
            if($u->referrals()->count()>49)
                $i++;
        }

        $users->ambassadors = $i;

        $metrics = Metric::all();
        $branches = Branch::all();
        $zones = Zone::all();
        

        return view('appl.'.$this->app.'.'.$this->module.'.analysis')
                    ->with('users',$users)
                    ->with('metrics',$metrics)
                    ->with('branches',$branches)
                    ->with('zones',$zones);
        
    }




    public function show2($id,Request $request)
    {
        $obj = Obj::where('id',$id)->first();
        $this->authorize('view', $obj);

        $metrics = Metric::all();
        $data = array();

       

            $user_college = $obj->users->pluck('id')->toArray();

            foreach($obj->branches as $b){
                $user_branch = $b->users->pluck('id')->toArray();
                $data['branches'][$b->name] = count(array_intersect($user_college, $user_branch));
            /*$data['branches'][$b->name] = $obj->users()->whereHas('branches', function ($query) use ($b) {
                            $query->where('name', '=', $b->name);
                        })->count(); */
            }

            foreach($metrics as $m){
                $user_metric = $m->users->pluck('id')->toArray();
                $data['metrics'][$m->name]= count(array_intersect($user_college, $user_metric));
                /*$data['metrics'][$m->name] = $obj->users()->whereHas('metrics', function ($query) use ($m) {
                                $query->where('name', '=', $m->name);
                            })->count(); */
            }

            $data['users']['all'] = count($user_college);
            $data['users']['pro'] =  0;/*$obj->users()->whereHas('services', function ($query) use ($m) {
                                $query->where('name', '=', 'Pro Access');
                            })->count();*/
            $data['users']['premium'] = 0;/*$obj->users()->whereHas('services', function ($query) use ($m) {
                                $query->where('name', '=', 'Premium Access');
                            })->count();*/
            
   

        

        

        
        if($obj)
            return view('appl.'.$this->app.'.'.$this->module.'.show2')
                    ->with('college',$obj)->with('app',$this)
                    ->with('obj',$obj)->with('app',$this)
                    ->with('metrics',$metrics)
                    ->with('data',$data);
        else
            abort(404);
    }



    public function students($id,Request $request)
    {
        $obj = Obj::where('id',$id)->first();
        $branch = $request->get('branch');
        $metric= $request->get('metric');
        $m = Metric::where('name',$metric)->first();
        $b = Branch::where('name',$branch)->first();
        $users = $obj->users()->get();



        $obj_users = $obj->users()->pluck('id')->toArray();
        
        if($branch){
            $branch_users = $b->users()->pluck('id')->toArray();
            $u= array_intersect($obj_users,$branch_users);
            $users = User::whereIn('id',$u)->paginate(config('global.no_of_records'));
             $total = count($u);
        }

        if($metric){
            $metric_users = $m->users()->pluck('id')->toArray();
            $u= array_intersect($obj_users,$metric_users);
            $users = User::whereIn('id',$u)->paginate(config('global.no_of_records'));
             $total = count($u);
        }

        if(!$metric && !$branch)
        {
            $total = count($obj_users);
            $users = $obj->users()->paginate(config('global.no_of_records'));
        }



        
        $this->authorize('view', $obj);
        if($obj)
            return view('appl.'.$this->app.'.'.$this->module.'.student')
                    ->with('obj',$obj)->with('app',$this)->with('users',$users)->with('total',$total)->with('metric',$m)->with('branch',$b);
        else
            abort(404);
    }


    public function userlist($id)
    {
        $obj = Obj::where('id',$id)->first();

        
        $this->authorize('view', $obj);
        if($obj)
            return view('appl.'.$this->app.'.'.$this->module.'.userlist')
                    ->with('obj',$obj)->with('app',$this);
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
        $obj= Obj::where('id',$id)->first();
        $this->authorize('update', $obj);
        $zones = Zone::all();
        $branches = Branch::all();
        $courses = Course::all();


        if($obj)
            return view('appl.'.$this->app.'.'.$this->module.'.createedit')
                ->with('stub','Update')
                ->with('jqueryui',true)
                ->with('branches',$branches)
                ->with('zones',$zones)
                ->with('courses',$courses)
                ->with('obj',$obj)->with('app',$this);
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
    public function update(Request $request, $id)
    {
        try{
            $obj = Obj::where('id',$id)->first();

            $this->authorize('update', $obj);

            $zone_id = $request->get('zone_id');
            $branches = $request->get('branches');

            //branches
            $branch_list =  Branch::orderBy('created_at','desc ')
                        ->get()->pluck('id')->toArray();
            if($branches)
            foreach($branch_list as $branch){
                if(in_array($branch, $branches)){
                    if(!$obj->branches->contains($branch))
                        $obj->branches()->attach($branch);
                }else{
                    if($obj->branches->contains($branch))
                        $obj->branches()->detach($branch);
                }
                
            }else{
                $obj->branches()->detach();
            } 

            $courses = $request->get('courses');
            if($courses){
                $obj->courses()->detach();
                foreach($courses as $course){
                    if(!$obj->courses->contains($course))
                        $obj->courses()->attach($course);
                }
            }else{
                $obj->courses()->detach();
            }


            //zone
            $obj->zones()->detach();
            if(!$obj->zones->contains($zone_id))
                $obj->zones()->attach($zone_id);

            $obj = $obj->update($request->except(['zone_id','branches'])); 
            flash('('.$this->app.'/'.$this->module.') item is updated!')->success();
            return redirect()->route($this->module.'.show',$id);
        }
        catch (QueryException $e){
           $error_code = $e->errorInfo[1];
            if($error_code == 1062){
                 flash('Some error in updating the record')->error();
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
        $obj = Obj::where('id',$id)->first();
        $this->authorize('update', $obj);
        $obj->delete();

        flash('('.$this->app.'/'.$this->module.') item  Successfully deleted!')->success();
        return redirect()->route($this->module.'.index');
    }
}
