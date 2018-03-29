 @if($questions->total()!=0)
        <div class="table-responsive">
          <table class="table table-bordered mb-0">
            <thead>
              <tr>
                <th scope="col">#({{$questions->total()}})</th>
                <th scope="col">Slug </th>
                <th scope="col">Question </th>
                <th scope="col">Created at</th>
              </tr>
            </thead>
            <tbody>
              @foreach($questions as $key=>$question)  
              <tr>
                <th scope="row">{{ $questions->currentpage() ? ($questions->currentpage()-1) * $questions->perpage() + ( $key + 1) : $key+1 }}</th>
                <td>
                  <a href=" {{ route('question.show',[$project->slug,$question->id]) }} ">
                  <div>
                    {!! $question->slug !!}
                  </div>
                  </a>
                </td>
                <td>
                  <div>
                    {!! $question->question !!}
                  </div>
                </td>
                <td>{{ ($question->created_at) ? $question->created_at->diffForHumans() : '' }}</td>
              </tr>
              @endforeach      
            </tbody>
          </table>
        </div>
        @else
        <div class="card card-body bg-light">
          No Questions Listed
        </div>
        @endif
        <nav aria-label="Page navigation  " class="card-nav @if($questions->total() > config('global.no_of_records'))mt-3 @endif">
        {{$questions->appends(request()->except(['page','search']))->links('vendor.pagination.bootstrap-4') }}
      </nav>
