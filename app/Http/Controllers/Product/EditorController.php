<?php

namespace PacketPrep\Http\Controllers\Product;

use Illuminate\Http\Request;
use PacketPrep\Http\Controllers\Controller;

class EditorController extends Controller
{
    public function tcscode(Request $request)
    {
    	$cpp =$data =null;
        return view('appl.product.editor.tcscode')
                ->with('editor',true)
                ->with('code',true)
                ->with('cpp',$cpp)
                ->with('data',$data);
    }

    public function tcstestcase(Request $request)
    {
    	$code = $request->get('code');
    	if($request->get('testcase')==1){
    		$input = 14;
    		$output = 17;
    	}
    	elseif($request->get('testcase')==2){
    		$input = 5;
    		$output = 2;
    	}else{
    		$input = 10;
    		$output = 11;

    	}
    	$data = $this->run_internal($code,$input);
    	$json = json_decode($data);
    	if($json->stderr)
    		print $data;
    	else{
    		if($json->stdout == $output)
    			$json->success = 1;
    		else
    			$json->success = 0;

    		print json_encode($json);

    	}

    }

    public function run_internal($code,$input){


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

      $data ='{"command": "clang main.c && ./a.out '.$input.'","files": [{"name": "main.c", "content": '.$code.'}]}';
      //$data ='{"files": [{"name": "main.c", "content": '.$code.'}]}';
      //echo $data;
      curl_setopt($curl, CURLOPT_POSTFIELDS, $data);

      // Send the request & save response to $resp
      $data = curl_exec($curl);
      
      // Close request to clear up some resources
      curl_close($curl);

      return $data;

    }

    public function run(Request $request){

      $code = $request->get('code');
      $input = $request->get('input');

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

      $data ='{"command": "clang main.c && ./a.out '.$input.'","files": [{"name": "main.c", "content": '.$code.'}]}';
      //$data ='{"files": [{"name": "main.c", "content": '.$code.'}]}';
      //echo $data;
      curl_setopt($curl, CURLOPT_POSTFIELDS, $data);

      // Send the request & save response to $resp
      print(curl_exec($curl));
      
      // Close request to clear up some resources
      curl_close($curl);

    }
  
}
