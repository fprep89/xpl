<?php

namespace PacketPrep\Http\Controllers\Product;

use Illuminate\Http\Request;
use PacketPrep\Http\Controllers\Controller;
use PacketPrep\Models\Product\Product;
use PacketPrep\Models\Product\Order;
use PacketPrep\Models\Exam\Exam;
use PacketPrep\Models\Course\Course;
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

        $exams = Exam::where('status','2')->get();
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
        $exams = Exam::where('status','2')->get();
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
}
