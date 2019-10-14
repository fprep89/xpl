@if(isset($details['users']))
		 <div class="rounded table-responsive">
		 @if(count($details['users']))
		 <table class="table mt-4  table-bordered bg-white" >
		  <thead>
		    <tr>
		      <th scope="col">#</th>
		      <th scope="col">Name</th>
		      <th scope="col" >Branch</th>
		      @if(isset($sections))
		      @foreach($sections as $s)
		      <th scope="col" >{{$s->name }}</th>
		      @endforeach
		      @endif
		      <th scope="col"  >Score</th>
		      <th scope="col" class=" {{$m=0}} " colspan="1">Performance</th>
		    </tr>
		  </thead>
		  <tbody>

		    @foreach($details['users'] as $k=>$user)

		    <tr>
		      <th scope="row">{{++$m}}</th>
		      <td>{{$user['name']}}  </td>
		      <td>{{$user['branch']}}  </td>
		      @if(isset($sections))
		      	@foreach($sections as $s)
		      		<td>{{ $details['section'][$s->id]['users'][$k]['score'] }}</td>
		      	@endforeach
		      @endif
		      
		      <td>{{$user['score']}} / {{$user['max']}}  </td>
		      
		      <td>

		      	@if($user['performance']=='need_to_improve')
		      	<img src="{{ asset('/img/medals/needtoimprove.png')}}" style="width:20px;"  />&nbsp;
		      		Need to Improve
		      	@else
		      	<img src="{{ asset('/img/medals/'.$user['performance'].'.png')}}" style="width:20px;"  />&nbsp;
		      	{{ ucfirst($user['performance'])}}
		      	@endif
		      	
		      </td>
		      </tr>
		     @endforeach
		  </tbody>
		</table>
		@else
		<div class="rounded border p-3 mt-4">No items Defined</div>
		@endif
		</div>
		@endif