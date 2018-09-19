@extends('layouts.app')
@section('content')


@include('flash::message')
<div  class="row ">

  <div class="col-md-9">
 
    <div class="">
      <div class="mb-0">
        <nav class="navbar navbar-light bg-light justify-content-between border rounded p-3 mb-3">
          <a class="navbar-brand"><i class="fa fa-dashboard"></i> Admin </a>

        </nav>

        <div class="row no-gutters">
            <div class="col-12 col-md-6">
              <div class="card mb-3 mr-md-2">
                <div class="card-body">
                  <h3><u>Credit Points</u></h3>
                  <div class="display-1">0/200</div>
                  <hr>
                  <p>Are you falling short of credits? Contact us for exciting offers.</p>
                  <a href="#" class="btn btn-outline-primary">Buy Credits</a>
                </div>
              </div>
              
              <div class="card mb-3 mr-md-2">
                <div class="card-body">
                  <h3>Technical Support</h3>
                  <dl class="row">
                    <dt class="col-sm-3"><i class="fa fa-envelope"></i> Email</dt>
                    <dd class="col-sm-9"> administrator@onlinelibrary.co</dd>
                    <dt class="col-sm-4"><i class="fa fa-phone"></i> Phone</dt>
                    <dd class="col-sm-8"> +91 95151 25110</dd>
                  </dl>
                </div>
              </div>
              
            </div>
            <div class="col-12 col-md-6">
              <div class="card mb-3 ml-md-2">
                <div class="card-body">
                  <h3 class="card-title"><u>Account Details </u><a href="{{ route('admin.settings')}}">
                    <span class="float-right"><i class="fa fa-edit"></i></span></a> </h3>
                  <dl class="row">
                    <dt class="col-sm-4">Name</dt>
                    <dd class="col-sm-8"> <h2>{{ subdomain_name() }}</h2></dd>
                    <dt class="col-sm-4">Url</dt>
                    <dd class="col-sm-8"> <span class="badge badge-warning">{{ subdomain() }}.onlinelibrary.co</span></dd>
                    
                    <dt class="col-sm-4">Courses</dt>
                    <dd class="col-sm-8">
                      @foreach($client->courses as $course) 
                      @if($course->getVisibility($client->id,$course->id)==1)
                      <span class="text-success"><i class="fa fa-check-circle"></i></span>
                      @else
                       <span class="text-secondary"><i class="fa fa-times-circle"></i></span>
                       @endif
                      {{$course->name }}<br> 
                    @endforeach</dd>

                    <dt class="col-sm-4">Contact Details</dt>
                    <dd class="col-sm-8"> 
                      {!! html_entity_decode($client->contact) !!}
                    </dd>

                  </dl>
                </div>
              </div>
              <div class="card mb-3 ml-md-2">
                <div class="card-body">
                  <h3 class="card-title">Logo<a href="{{ route('admin.settings')}}">
                    <span class="float-right"><i class="fa fa-edit"></i></span></a> </h3>
                   @if(file_exists(public_path().'/img/clients/'.subdomain().'.png'))
              <img src="{{ asset('/img/clients/'.subdomain().'.png')}}" width="40px" class="logo-product ml-md-1" />
              @else
              <img src="{{ asset('/img/clients/logo_notfound.png')}}" width="150px" class="ml-md-1" /> 
              @endif
                </div>
              </div>
            </div>
            
        </div>

     </div>
   </div>
 </div>
  <div class="col-md-3 pl-md-0 mb-3">
      @include('appl.product.snippets.adminmenu')
    </div>
</div>

@endsection

