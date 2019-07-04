<?php

namespace PacketPrep\Http\Controllers\Product;

use Illuminate\Http\Request;
use PacketPrep\Http\Controllers\Controller;
use PacketPrep\Models\Product\Product;
use PacketPrep\Models\Product\Order;
use PacketPrep\Models\Exam\Exam;
use PacketPrep\Models\Course\Course;
use PacketPrep\Models\College\Service;
use PacketPrep\User;

use PacketPrep\Models\College\College;
use PacketPrep\Models\College\Zone;
use PacketPrep\Models\College\Metric;
use PacketPrep\Models\College\Branch;

use Illuminate\Support\Facades\Mail;
use PacketPrep\Mail\OrderSuccess;
use PacketPrep\Mail\OrderCreated;
use Illuminate\Support\Facades\DB;
use Instamojo as Instamojo;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Product $product,Request $request)
    {

        $this->authorize('view', $product);

        $search = $request->search;
        $item = $request->item;
        
        $products = $product->where('name','LIKE',"%{$item}%")
                    ->orderBy('created_at','desc ')
                    ->paginate(config('global.no_of_records'));   
        $view = $search ? 'list': 'index';

        return view('appl.product.product.'.$view)
        ->with('products',$products)->with('product',$product);
    }

    public function statistics(Request $request){
      //$slug = subdomain();
        //$client = client::where('slug',$slug)->first();
        //$this->authorize('view', $client);
        $users = new ProductController;
        $users->total = User::count();

        $last_year = (new \Carbon\Carbon('first day of last year'))->year;
        $this_year = (new \Carbon\Carbon('first day of this year'))->year;


        $last_year_first_day = (new \Carbon\Carbon('first day of January '.$last_year))->startofMonth()->toDateTimeString();
        $this_year_first_day = (new \Carbon\Carbon('first day of January '.$this_year))->startofMonth()->toDateTimeString();
        $users->last_year  = User::where('created_at','>', $last_year_first_day)->where('created_at','<', $this_year_first_day)->count();
        $users->this_year  = User::where(DB::raw('YEAR(created_at)'), '=', $this_year)->count();

        


        $last_month_first_day = (new \Carbon\Carbon('first day of last month'))->startofMonth()->toDateTimeString();
        $this_month_first_day = (new \Carbon\Carbon('first day of this month'))->startofMonth()->toDateTimeString();
        
        $users->last_month  = User::where('created_at','>', $last_month_first_day)->where('created_at','<', $this_month_first_day)->count();
        

        $users->this_month  = User::where(DB::raw('MONTH(created_at)'), '=', date('n'))->count();

        $metrics = Metric::all();
        

        return view('appl.product.pages.stats')->with('users',$users)->with('metrics',$metrics);
    }


    public function premium(Request $request)
    {
        $product = Product::where('slug','premium-access')->first();
        $user = \Auth::user();
        $entry=null;
        if($user){
        $entry = DB::table('product_user')
                ->where('product_id', $product->id)
                ->where('user_id', $user->id)
                ->first();
        }
        return view('appl.pages.premium')->with('entry',$entry);
    }


    public function activate(Request $request)
    {
        $code = $request->get('code');
        $user_id = $request->get('user_id');
        $user = User::where('id',$user_id)->first();
        $entry = DB::table('service_user')
                ->where('code', $code)
                ->where('user_id', $user_id)
                ->first();
        if($entry){

          $services = DB::table('service_user')
                ->where('code', $code)
                ->where('user_id', $user_id)
                ->get();
          foreach($services as $service){
            $s= Service::where('id',$service->service_id)->first();
            
              $product = Product::where('id',$s->product_id)->first();
              $pid = $product->id;
                        $valid_till = date('Y-m-d H:i:s', strtotime(date("Y-m-d H:i:s") .' + '.(24*31).' days'));
                        if(!$user->products->contains($pid)){
                            $product = Product::where('id',$pid)->first();
                            if($product->status!=0)
                            $user->products()->attach($pid,['validity'=>24,'created_at'=>date("Y-m-d H:i:s"),'valid_till'=>$valid_till,'status'=>1]);
                        }

          }

          DB::table('service_user')
                ->where('code', $code)
                ->where('user_id', $user_id)
                ->update(['status'=>1]);

          
        }else
          abort('404','Your Code is Invalid');

      
        return view('appl.product.pages.productactivation');
    }

    public function welcome(Request $request)
    {

      
        $api = new Instamojo\Instamojo('dd96ddfc50d8faaf34b513d544b7bee7', 'd2f1beaacf12b2288a94558c573be485');
      try {

          $user = \auth::user();
          $orders = Order::where('user_id',$user->id)->get();

          foreach($orders as $order){
            if($order->status == 0)
            {
                $response = $api->paymentRequestStatus($order->order_id);

                if($response['status']=='Completed')
                  { 
                
                    $product = Product::where('id',$order->product_id)->first();

                    $order->payment_mode = $response['payments'][0]['instrument_type'];
                    $order->bank_txn_id = $response['payments'][0]['payment_id'];
                    $order->bank_name = $response['payments'][0]['billing_instrument'];
                    $order->txn_id = $response['payments'][0]['payment_id'];
                    if($response['status']=='Completed'){
                      $order->status = 1;
                      $valid_till = date('Y-m-d H:i:s', strtotime(date("Y-m-d H:i:s") .' + '.(24*31).' days'));

                      if(!$user->products->contains($product->id)){

                        

                        if($product->slug=='premium-access'){
                          $products = Product::all();
                          foreach($products as $product){
                              if(!$user->products->contains($product->id))
                              if($product->status!=0)
                              $user->products()->attach($product->id,['validity'=>24,'created_at'=>date("Y-m-d H:i:s"),'valid_till'=>$valid_till,'status'=>1]);
                          }
                        }else{
                            $user->products()->attach($order->product_id,['validity'=>24,'created_at'=>date("Y-m-d H:i:s"),'valid_till'=>$valid_till,'status'=>1]);
                        }
                      }
                    }
                    $order->save();
                    if ($response['status']=='Completed') {
                      $order->payment_status = 'Successful';
                      Mail::to($user->email)->send(new OrderSuccess($user,$order));
                    }
                }
            }
            
            }
        }
        catch (Exception $e) {
            print('Error: ' . $e->getMessage());
        }


        return view('welcome2');
    }

    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $product = new Product();
        $this->authorize('create', $product);

        $exams = Exam::all();
        $courses = Course::all();

        return view('appl.product.product.createedit')
                ->with('stub','Create')
                ->with('exams',$exams)
                ->with('courses',$courses)
                ->with('jqueryui',true)
                ->with('editor',true)
                ->with('product',$product);
    }
   

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Product $product, Request $request)
    {
         try{

            if(!$request->slug )
            $request->slug  = $request->name;
            $request->slug = strtolower(str_replace(' ', '-', $request->slug));

            $exams = $request->get('exams');
            $courses = $request->get('courses');

            $product->name = $request->name;
            $product->slug = $request->slug;
            $product->description = ($request->description) ? $request->description: null;
            $product->price = $request->price;
            $product->status = $request->status;
            $product->discount = $request->discount;
            $product->validity = $request->validity;
            $product->save(); 

             if($exams){
                $product->exams()->detach();
                foreach($exams as $exam){
                if(!$product->exams->contains($exam))
                    $product->exams()->attach($exam);
                }
            }
            else{
                
            }

            if($courses){
                $product->courses()->detach();
                foreach($courses as $course){
                    if(!$product->courses->contains($course))
                        $product->courses()->attach($course);
                }
            }else{
                $product->courses()->detach();
            }

            flash('A new product('.$request->name.') is created!')->success();
            return redirect()->route('product.index');
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
        $product= Product::where('slug',$id)->first();

        
        $this->authorize('view', $product);

        if($product)
            return view('appl.product.product.show')
                    ->with('product',$product);
        else
            abort(404);
    }


    public function products(Product $product,Request $request)
    {

        $search = $request->search;
        $item = $request->item;
        
        $products = $product->where('name','LIKE',"%{$item}%")->where('status',1)
                    ->orderBy('created_at','desc ')
                    ->paginate(config('global.no_of_records'));   
        $view = $search ? 'list2': 'index2';

        return view('appl.product.product.'.$view)
        ->with('products',$products)->with('product',$product);

    }

    public function page($id)
    {
        $product= Product::where('slug',$id)->first();


        $user = \Auth::user();
        $entry=null;
        if($user)
        $entry = DB::table('product_user')
                    ->where('product_id', $product->id)
                    ->where('user_id', $user->id)
                    ->orderBy('id','desc')
                    ->first();


        if($product)
            return view('appl.product.product.page')
                    ->with('entry',$entry)
                    ->with('product',$product);
        else
            return view('appl.product.product.pageerror')
                    ->with('product',$product);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $product= Product::where('slug',$id)->first();
        $this->authorize('update', $product);
        $exams = Exam::all();
        $courses = Course::all();



        if($product)
            return view('appl.product.product.createedit')
                ->with('stub','Update')
                ->with('jqueryui',true)
                ->with('editor',true)
                ->with('exams',$exams)
                ->with('courses',$courses)
                ->with('product',$product);
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
            $product = Product::where('slug',$slug)->first();

            $this->authorize('update', $product);

            $exams = $request->get('exams');
            $courses = $request->get('courses');
            $product->name = $request->name;
            $product->slug = $request->slug;
            $product->description = ($request->description) ? $request->description: null;
            $product->price = $request->price;
             $product->validity = $request->validity;
             $product->discount = $request->discount;
            $product->status = $request->status;

            if($exams){
                $product->exams()->detach();
                foreach($exams as $exam){
                if(!$product->exams->contains($exam))
                    $product->exams()->attach($exam);
                }
            }
            else{
                
            }

            if($courses){
                $product->courses()->detach();
                foreach($courses as $course){
                    if(!$product->courses->contains($course))
                        $product->courses()->attach($course);
                }
            }else{
                $product->courses()->detach();
            }


            $product->save(); 

            flash('Product (<b>'.$request->name.'</b>) Successfully updated!')->success();
            return redirect()->route('product.show',$request->slug);
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
        $product = Product::where('id',$id)->first();
        $this->authorize('update', $product);
        $product->delete();

        flash('Product Successfully deleted!')->success();
        return redirect()->route('product.index');
    }


     public function editor(Request $request)
    {
        $code = $request->get('code');

        $data = null;
        if($code){
          $data = $this->compile($code);
        }

        return view('appl.product.pages.editor')
                ->with('editor',true)
                ->with('code',true)
                ->with('data',$data);

    }

    public function compile($code){
      // Get cURL resource
      $curl = curl_init();
      // Set some options - we are passing in a useragent too here

      $code = json_encode($code);

      $headers = [
          'Authorization: Token bba456d8-b9c9-4c80-bb84-39d44c5b0acb',
          'Content-type: application/json'
      ];

      curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
      curl_setopt_array($curl, [
          CURLOPT_RETURNTRANSFER => 1,
          CURLOPT_URL => 'https://run.glot.io/languages/c/latest',
          CURLOPT_POST => 1,
      ]);

      //$data ='{"command": "clang main.c && ./a.out 33","files": [{"name": "main.c", "content": '.$code.'}]}';
      $data ='{"files": [{"name": "main.c", "content": '.$code.'}]}';
      //echo $data;
      curl_setopt($curl, CURLOPT_POSTFIELDS, $data);

      // Send the request & save response to $resp
      $resp = json_decode(curl_exec($curl));
      

      // Close request to clear up some resources
      curl_close($curl);

      if($resp->error){
        $data = "";
        $data = $data.$resp->stderr;
      }else{
          $data = "";
        $data = $data.$resp->stdout;
      }


      return $data;
    }
  }
