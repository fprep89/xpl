 @if($orders->total()!=0)
        <div class="table-responsive ">
          <table class="table table-bordered mb-0">
            <thead>
              <tr>
                <th scope="col">#({{$orders->total()}})</th>
                <th scope="col">Order ID </th>
                <th scope="col">Credited</th>
                <th scope="col">Status</th>
                <th scope="col">Created at</th>
              </tr>
            </thead>
            <tbody>
              @foreach($orders as $key=>$order)  
              <tr>
                <th scope="row">{{ $orders->currentpage() ? ($orders->currentpage()-1) * $orders->perpage() + ( $key + 1) : $key+1 }}</th>
                <td>
                  <a href=" {{ route('order.show',$order->order_id) }} ">
                  {{ $order->order_id }}
                  </a>
                </td>
                <td>{{ $order->credit_count }}</td>
                <td>
                  @if($order->status==0)
                    <span class="badge badge-warning">Pending</span>
                  @elseif($order->status ==1)
                    <span class="badge badge-success">Success</span>
                  @else
                    <span class="badge badge-danger">Failure</span>
                  @endif
                </td>
                <td>{{ ($order->created_at) ? $order->created_at->diffForHumans() : '' }}</td>
              </tr>
              @endforeach      
            </tbody>
          </table>
        </div>
        @else
        <div class="card card-body bg-light">
          No transactions listed
        </div>
        @endif
        <nav aria-label="Page navigation  " class="card-nav @if($orders->total() > config('global.no_of_records'))mt-3 @endif">
        {{$orders->appends(request()->except(['page','search']))->links('vendor.pagination.bootstrap-4') }}
      </nav>
