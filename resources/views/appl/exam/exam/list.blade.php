
 @if($exams->total()!=0)
        <div class="table-responsive">
          <table class="table table-bordered mb-0">
            <thead>
              <tr>
                <th scope="col">#({{$exams->total()}})</th>
                <th scope="col">Exam </th>
                <th scope="col">Status</th>
                <th scope="col">Created at</th>
              </tr>
            </thead>
            <tbody>
              @foreach($exams as $key=>$exam)  
              <tr>
                <th scope="row">{{ $exams->currentpage() ? ($exams->currentpage()-1) * $exams->perpage() + ( $key + 1) : $key+1 }}</th>
                <td>
                  <a href=" {{ route('exam.show',$exam->slug) }} ">
                  {{ $exam->name }}
                  </a>
                </td>
                <td>
                  @if($exam->status==0)
                    <span class="badge badge-warning">Draft</span>
                  @else
                    <span class="badge badge-success">Published</span>
                  @endif
                </td>
                <td>{{ ($exam->created_at) ? $exam->created_at->diffForHumans() : '' }}</td>
              </tr>
              @endforeach      
            </tbody>
          </table>
        </div>
        @else
        <div class="card card-body bg-light">
          No Exams listed
        </div>
        @endif
        <nav aria-label="Page navigation  " class="card-nav @if($exams->total() > config('global.no_of_records'))mt-3 @endif">
        {{$exams->appends(request()->except(['page','search']))->links('vendor.pagination.bootstrap-4') }}
      </nav>