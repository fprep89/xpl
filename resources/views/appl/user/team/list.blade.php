 @if($users->total()!=0)
        <div class="table-responsive">
          <table class="table table-bordered ">
            <thead>
              <tr>
                <th scope="col">#({{$users->total()}})</th>
                <th scope="col">Name </th>
                <th scope="col">Joined</th>
              </tr>
            </thead>
            <tbody>
              @foreach($users as $key=>$user)  
              <tr>
                <th scope="row">{{ $users->currentpage() ? ($users->currentpage()-1) * $users->perpage() + ( $key + 1) : $key+1 }}</th>
                <td>
                  <a href=" {{ route('profile','@'.$user->username) }} ">
                  {{ $user->name }}
                  </a>
                </td>
                <td>{{ ($user->created_at) ? $user->created_at->diffForHumans() : '' }}</td>
              </tr>
              @endforeach      
            </tbody>
          </table>
        </div>
        @else
        <div class="card card-body bg-light">
          No Users listed
        </div>
        @endif
        <nav aria-label="Page navigation example">
        {{$users->appends(request()->except(['page','search']))->links('vendor.pagination.bootstrap-4') }}
      </nav>