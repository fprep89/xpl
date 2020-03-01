@extends('layouts.app')
@section('content')
  <nav aria-label="breadcrumb">
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="{{ url('/home')}}">Home</a></li>
      <li class="breadcrumb-item"><a href="{{ route('profile','@'.$user->username)}}">User</a></li>
      <li class="breadcrumb-item active" aria-current="page">Edit Profile</li>
    </ol>
  </nav>
  @include('flash::message') 
  <div class="card">
    <div class="card-body">
      <h1>Edit Profile </h1>
       <form method="post" action="{{route('profile.update','@'.$user->username)}}" enctype="multipart/form-data">
      <div class="row">
        <div class="col-12 col-md-6">
          <div class="form-group">
            <label for="formGroupExampleInput ">Name</label>
            <input type="text" class="form-control" name="name"  value="{{ $user->name }}">
          </div>
        </div>
         <div class="col-12 col-md-6">
          <div class="form-group">
            <label for="formGroupExampleInput ">Designation</label>
            <input type="text" class="form-control" name="designation"  value="{{ ($user_details->designation)?$user_details->designation:'Not Assigned' }}" disabled>
          </div>
         </div>

      </div>

      <div class="row">
        <div class="col-12 col-md-6">
          <div class="form-group">
          <label for="formGroupExampleInput ">Phone</label>
          <input type="text" class="form-control" name="phone"  value="@if($user->details){{  $user->details->phone }} @endif" >
        </div>
        </div>
         <div class="col-12 col-md-6">
          <div class="form-group">
        <label for="formGroupExampleInput ">Email</label>
        <input type="text" class="form-control" name="email"  value="{{ $user->email }}" disabled>
      </div>
         </div>
      </div>

      <div class="row">
        <div class="col-12 col-md-6">
          <div class="form-group">
            <label for="formGroupExampleInput ">Username</label>
            <input type="text" class="form-control" name="username"  value="{{ $user->username }}" disabled>
          </div>
        </div>
         <div class="col-12 col-md-6">
          <div class="form-group">
            <label for="formGroupExampleInput ">Image</label>
            <input type="file" class="form-control" name="file_" id="formGroupExampleInput" >
         </div>
      </div>
      </div>

      <div class="row">
        <div class="col-12 col-md-6">
          <div class="form-group">
        <label for="formGroupExampleInput ">Password</label>
        <input type="password" class="form-control" name="password"  autocomplete="current-password" >
      </div>
        </div>
         <div class="col-12 col-md-6">
          <div class="form-group">
        <label for="formGroupExampleInput ">Re-Password</label>
        <input type="password" class="form-control" name="repassword"   autocomplete="current-password">
      </div>
         </div>
      </div>
      
      
      
      
      
      
      
      
      <div class="form-group">
        <label for="formGroupExampleInput2">Biodata</label>
         <textarea class="form-control summernote" name="bio"  rows="10">{{isset($user_details)?$user_details->bio:''}}</textarea>
      </div>

      <div class="row">
        <div class="col-12 col-md-6">
          <div class="form-group">
        <label for="formGroupExampleInput ">CurrentCity (optional)</label>
        <input type="text" class="form-control" name="city"  placeholder="Enter your city name" value="{{ $user->current_city }}">
      </div>
        </div>
         <div class="col-12 col-md-6">
            

       <div class="form-group">
        <label for="formGroupExampleInput ">Hometown (optional)</label>
        <input type="text" class="form-control" name="hometown"  placeholder="Enter your hometown name" value="{{ $user->hometown }}">
      </div>
         </div>
      </div>

      <div class="row">
        <div class="col-12 col-md-6">
          @if(isset($colleges))
      <div class="form-group">
        <label for="formGroupExampleInput ">College</label>
        <select class="form-control" name="college_id">
          @foreach($colleges as $c)
          <option value="{{$c->id}}" @if(isset($user)) @if($user->colleges->first()) @if($c->id == $user->colleges->first()->id ) selected @endif @endif @endif >{{ $c->name }}</option>
          
          @endforeach         
        </select>
      </div>
      @endif
        </div>
         <div class="col-12 col-md-6">
            @if(isset($branches))
      <div class="form-group ">
        <label for="formGroupExampleInput ">Branch</label><br>
        <select class="form-control" name="branches[]">
          @foreach($branches as $b)
          <option value="{{$b->id}}" @if(isset($user)) @if($user->branches->first()) @if($b->id == $user->branches->first()->id ) selected @endif @endif @endif >{{ $b->name }}</option>
          @endforeach         
        </select>
      </div>
      @endif
         </div>
      </div>

      <div class="row">
        <div class="col-12 col-md-6">
          <div class="form-group">
            <label for="formGroupExampleInput ">Gender</label>
            <select class="form-control" name="gender">
             
              <option value="male"  @if(isset($user_details)) @if($user->gender=='male')  selected @endif  @endif>Male</option>
              <option value="female"  @if(isset($user_details)) @if($user->gender=='female')  selected @endif  @endif>Female</option>
              <option value="transgender"  @if(isset($user_details)) @if($user->gender=='transgender')  selected @endif  @endif>Transgender</option>
                     
            </select>
          </div>
        </div>
         <div class="col-12 col-md-6">
            <div class="form-group ">
            <label for="formGroupExampleInput ">Date of Birth (DD-MM-YYYY)</label><br>
            <input type="text" minlength="1" class="form-control" name="dob" id="formGroupExampleInput" placeholder="Enter your date of birth (eg: 25-09-2001)" 
            value="{{ $user->dob }}">
          </div>
         </div>
      </div>

      <div class="row">
        <div class="col-12 col-md-6">
          <div class="form-group ">
            <label for="formGroupExampleInput ">Class 10th Percentage/CGPA</label><br>
            <input type="text" minlength="1" class="form-control" name="tenth" id="formGroupExampleInput" value="{{ $user->tenth }}">
          </div>
        </div>
         <div class="col-12 col-md-6">
          <div class="form-group ">
            <label for="formGroupExampleInput ">Class 12th Percentage/CGPA</label><br>
            <input type="text" minlength="1" class="form-control" name="twelveth" id="formGroupExampleInput" value="{{ $user->twelveth }}">
          </div>
         </div>
      </div>

      <div class="row">
        <div class="col-12 col-md-6">
            <div class="form-group ">
            <label for="formGroupExampleInput ">BTech Percentage/CGPA</label><br>
            <input type="text" minlength="1" class="form-control" name="graduation" id="formGroupExampleInput" value="{{ $user->bachelors }}">
          </div>
        </div>
         <div class="col-12 col-md-6">
          <div class="form-group ">
            <label for="formGroupExampleInput ">Masters Percentage/CGPA (optional)</label><br>
            <input type="text" minlength="1" class="form-control" name="masters" id="formGroupExampleInput" value="{{ $user->masters }}">
          </div>
         </div>
      </div>

      @if(\Auth::user()->checkRole(['administrator','manager','investor','patron','promoter','employee']))
      <div class="bg-light p-2 border">
      <div class="row">
        <div class="col-12 col-md-6">
          <div class="form-group ">
            <label for="formGroupExampleInput ">Video</label><br>
            <input type="text" minlength="1" class="form-control" name="video" id="formGroupExampleInput" value="{{ $user->video }}">
          </div>
          
        </div>
        <div class="col-12 col-md-6">
            <div class="form-group ">
            <label for="formGroupExampleInput ">language</label><br>
            <input type="text" minlength="1" class="form-control" name="language" id="formGroupExampleInput" value="{{ $user->language }}">
          </div>
         </div>
        
      </div>
      <div class="row">
        <div class="col-12 col-md-6">
          <div class="form-group ">
            <label for="formGroupExampleInput ">Confidence</label><br>
            <input type="text" minlength="1" class="form-control" name="confidence" id="formGroupExampleInput" value="{{ $user->confidence }}">
          </div>
          
        </div>
        <div class="col-12 col-md-6">
            <div class="form-group ">
            <label for="formGroupExampleInput ">Fluency</label><br>
            <input type="text" minlength="1" class="form-control" name="fluency" id="formGroupExampleInput" value="{{ $user->fluency }}">
          </div>
        </div>
         
      </div>
     
    </div>
      @endif

      <div class="row">
        <div class="col-12 col-md-6">
            <div class="form-group">
        <label for="formGroupExampleInput ">Facebook URL (optional)</label>
        <input type="text" class="form-control" name="facebook_link"  placeholder="Enter your Facebook profile url" value="{{ $user_details->facebook_link }}">
      </div>
        </div>
         <div class="col-12 col-md-6">
            <div class="form-group">
        <label for="formGroupExampleInput ">Twitter URL (optional)</label>
        <input type="text" class="form-control" name="twitter_link"  placeholder="Enter your Twitter profile url" value="{{ $user_details->twitter_link }}">
      </div>
         </div>
      </div>

      <div class="form-group">
        <label for="formGroupExampleInput ">Profile Visibility</label>
        <select class="form-control" name="privacy">
          <option value="0" @if($user_details->privacy==0) selected @endif>Public</option>
          <option value="1" @if($user_details->privacy==1) selected @endif>Site Members Only</option>
          <option value="2" @if($user_details->privacy==2) selected @endif>Private (Only Me)</option>
        </select>
      </div>

      <div class="form-group">
        <input type="hidden" name="_method" value="PUT">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
      </div>
      <button type="submit" class="btn btn-info">Update</button>
    </form>
    </div>
  </div>
@endsection