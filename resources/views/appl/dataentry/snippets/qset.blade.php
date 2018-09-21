<div class="card bg-light mb-3">
	<div class="card-body">
		<div class="text-bold ">{{ ucfirst($details['display_type']) }}</div>
		<h2>
		@if($details['display_type'] == 'project')
			{{ $project->name }}
		@elseif($details['display_type'] == 'category')
			{{ $category->name }}	
		@elseif($details['display_type'] == 'tag')	
			{{ $tag->name.' : '.$tag->value }}
		@endif
		</h2>
		<br>

		<div class="border p-2 mb-2 rounded">
		<div class="row ">
			<div class="col-3">
				@if($details['prev'])
				<a href="{{ $details['prev'] }}">
				<div class=" w100 p-1 text-center pl-2"><i class="fa fa-angle-double-left"></i></div>
				</a>
				@endif
			</div>
			<div class="col-6"> <div class="mt-1 text-center">Q({{ count($questions) }})</div></div>
			<div class="col-3"> 
				@if($details['next'])
				<a href="{{ $details['next'] }}">
				<div class=" w100 p-1 text-center mr-3"><i class="fa fa-angle-double-right"></i></div>
				</a>
				@endif
			</div>
		</div>
		</div>
		<div class="qset" style="max-height: 170px;overflow-y: auto;">
		<div class="row no-gutters qitems">
			@foreach($questions as $key => $q)
			<div class="col-3 mb-1">
				
				@if($details['display_type'] == 'project')
				<a href="{{ route('question.show',[$project->slug,$q->id]) }}">
				@elseif($details['display_type'] == 'category')
				<a href="{{ route('category.question',[$project->slug,$category->slug,$q->id]) }}">
				@elseif($details['display_type'] == 'tag')	
				<a href="{{ route('tag.question',[$project->slug,$tag->id,$q->id]) }}">
				@endif
				<div class="pr-1">
				<div class="border w100 p-1 text-center rounded @if($q->id==$question->id) active @endif @if($q->status == 0) @elseif($q->status == 1) border-secondary @else border-success  @endif" style="border:1px solid #eee; 
				    " id="q{{ ($q->id )}}">{{ ($key + 1 ) }}</div>
				</div>
				</a>
			</div>
			@endforeach
		</div>
		</div>
	</div>
</div>
