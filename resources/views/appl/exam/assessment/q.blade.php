    <div class="question_block">

      @if($passage)
      <div class="card mb-3" style="background: #ddffef;border: 1px solid #caefdd;border-radius: 5px;">
        <div class="card-body">
          <b>Passage</b> <span class="btn view badge badge-warning" data-item="passage">view</span><br>
          <div class="passage pt-2" style="display: none;">
          {!! $passage->passage !!}
          </div>
        </div>
      </div>
      @endif
      <div class="card  mb-3">
        <div class="card-body ">
          
        <div class="row no-gutters">
          <div class="col-2 col-md-2">
            <div class="pr-3 pb-2 " >
              <div class="text-center p-1 rounded  w100 qno @if(!$details['response']) qyellow @else  qblue @endif "  style="" data-qqno="{{$question->id}}">
                {{ $details['qno'] }}
              </div>
            </div>
          </div>
          <div class="col-10 col-md-10"><div class="pt-1 question">{!! $question->question!!}</div>

          

        </div>
        </div>

        @if($question->a)
         <div class="row no-gutters">
          <div class="col-3 col-md-2">
            <div class="pr-3 pb-2" >
              <div class="text-center p-1 rounded bg-light w100 border" >
                
                <input class="form-check-input" type="radio" name="response" id="exampleRadios1" value="A" @if($details['response']=='A') checked @endif > A </div>
            </div>
          </div>
          <div class="col-9 col-md-10"><div class="pt-1 a">{!! $question->option_a!!}</div></div>
        </div>
        @endif

        @if($question->b)
         <div class="row no-gutters">
          <div class="col-3 col-md-2">
            <div class="pr-3 pb-2" >
              <div class="text-center p-1 rounded bg-light w100 border" >
                
                <input class="form-check-input" type="radio" name="response" id="exampleRadios1" value="B" @if($details['response']=='B') checked @endif>  B</div>
            </div>
          </div>
          <div class="col-9 col-md-10"><div class="pt-1 b">{!! $question->option_b!!}</div></div>
        </div>
        @endif

        @if($question->c)
         <div class="row no-gutters">
          <div class="col-3 col-md-2">
            <div class="pr-3 pb-2" >
              <div class="text-center p-1 rounded bg-light w100 border" >
                
                <input class="form-check-input" type="radio" name="response" id="exampleRadios1" value="C" @if($details['response']=='C')  checked @endif> C</div>
            </div>
          </div>
          <div class="col-9 col-md-10"><div class="pt-1 c">{!! $question->option_c!!}</div></div>
        </div>
        @endif
        
        @if($question->d)
         <div class="row no-gutters">
          <div class="col-3 col-md-2">
            <div class="pr-3 pb-2" >
              <div class="text-center p-1 rounded bg-light w100 border" >
                
                <input class="form-check-input" type="radio" name="response" id="exampleRadios1" value="D" @if($details['response']=='D') checked @endif>
                D</div>
            </div>
          </div>
          <div class="col-9 col-md-10"><div class="pt-1 d">{!! $question->option_d!!}</div></div>
        </div>
        @endif

        @if($question->e)
         <div class="row no-gutters">
          <div class="col-3 col-md-2">
            <div class="pr-3 pb-2" >
          <div class="text-center p-1 rounded bg-light w100 border" > 
                
                <input class="form-check-input" type="radio" name="response" id="exampleRadios1" value="E" @if($details['response']=='E') checked @endif
                >
                E
              </div>
            </div>
          </div>
          <div class="col-9 col-md-10"><div class="pt-1 e">{!! $question->option_e!!}</div></div>
        </div>
        @endif
         
        </div>
      </div>
  
    <div class="card mb-3">
         <div class="card-body">
          <button type="button" class="btn  btn-outline-primary mb-2 testqno @if(!$details['prev']) d-none @endif" data-qno="{{$details['prev']}}" data-testname="{{$exam->value}}">
            <i class="fa fa-angle-double-left"></i> Previous
        </button>
          
        <button type="button" class="btn  btn-secondary qno-clear mb-2" data-qno="{{$details['curr']}}">
            Clear Response
        </button>
        <a href="#" data-toggle="modal" data-target="#exampleModal">
        <button type="button" class="btn  btn-success qno-submit mb-2" data-qno="{{$details['curr']}}" data-tooltip="tooltip" data-placement="top" title="Submit">
            Submit Test
        </button></a>
        <button type="button" class="btn  btn-outline-primary mb-2 testqno @if(!$details['next']) d-none @endif" data-qno="{{$details['next']}}" data-testname="{{$exam->value}}" >
             Next <i class="fa fa-angle-double-right"></i>
        </button>
      </div>
    </div>

     </div>