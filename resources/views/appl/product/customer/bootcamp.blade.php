@extends('layouts.nowrap-product')
@section('title', 'Coding Bootcamp - Summer Internship for Engineering Students  | PacketPrep')
@section('description', 'Build your first commercial web application with us. A great opportunity to utilize your summer time to build a great realtime project')
@section('keywords', 'summer internship, coding, bootcamp, engineering students, ')

@section('content')
<div class="line" style="padding:1px;background:#eee"></div>
<div class=" p-4  mb-3 mb-md-4 border-bottom bg-white" >
	<div class="wrapper ">  
	<div class="container">
	<div class="row">
		<div class="col-12 col-md-6">
			<h1 class="mt-2 mb-4 mb-md-2">
			<i class="fa fa-code"></i> &nbsp;Coding Bootcamp - Summer Internship
			
			</h1>
      <p>Build your first commercial web application with us at packetprep.  A great opportunity to utilize your summer time to build a great realtime project, where you will learn to write code from scratch to the end. </p>
      <p> <b>Eligibility : </b> Btech All Branches, 1st year to 4th year<br>
       <b> Batches :</b> 
       <ul>
        <li>May 20th to May 31st  2019
          <ul>
           <li>Batch 1 - 9:00 am to 1:00 pm </li>
           <li>Batch 2 - 2:00 pm to 6:00 pm </li> 
          </ul>
        </li> 
       <li>June 3rd to June 14th  2019
          <ul>
           <li>Batch 3 - 9:00 am to 1:00 pm </li>
          </ul>
        </li> 
        </ul>  
       
       <b> Location:</b> PacketPrep Office, Tarnaka, Hyderabad.<br>  

    </p>
     
      <div class="p-3 rounded mb-3" style="background: #f2f2f2">      
        <h2 > <i class="fa fa-rupee"></i>  6,000 </h2>
        
      </div>
      <a href="{{ url('productpage/cb') }}">
      <button class="btn btn-lg btn-success mb-3"> Reserve your seat now</button>
      </a>

		</div>
		<div class="col-12 col-md-6">

		   <div class="embed-responsive embed-responsive-16by9 border" style="background: #eee;">
          <iframe src="//player.vimeo.com/video/327689457" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
          </div>

  		</div>
	</div>
	</div>

</div>

</div>

<div class="wrapper " >
    <div class="container pb-5" >  
   
	   <div class="bg-white p-4 border mb-3">
      <div class="row">

        <div class="col-12 col-md-8">
          <h1 class="mb-4"> What's Included?</h1>
      <div class="row mb-4">
        <div class="col-4 col-md-2"><i class="fa fa-4x fa-rocket"></i></div>
        <div class="col-8 col-md-10">
          <h2> 40 hour Classroom Training</h2>
          <p> It is 4hour/day intensive training for 10 days. (2 days extra for Non CS/IT students to teach basics)</p>
        </div>
      </div>
      <div class="row mb-4">
        <div class="col-4 col-md-2"><i class="fa fa-4x fa-globe"></i></div>
        <div class="col-8 col-md-10">
          <h2> Live Application</h2>
          <p> By the end of the course, your application will be live for public use</p>
        </div>
      </div>

      <div class="row mb-4">
        <div class="col-4 col-md-2"><i class="fa fa-4x fa-trophy"></i></div>
        <div class="col-8 col-md-10">
          <h2> Internship Certificate</h2>
          <p> On completion of training, you will recieve the summer internship certificate </p>
        </div>
      </div>
        </div>

        <div class="col-12 col-md-4">
          <h1 class="mb-4"> Training Highlights</h1>
          <ul>
            <li>You will learn PHP, MySql, HTML, CSS and JQuery. </li>
            <li> You will learn how to book domain name, hosting and deploying the code on global server</li>
            <li>The most critical aspect, how to integrate payment gateway for online transactions where money is directly sent to the bank account</li>
            <li> Small batch size and Personal Guidance </li>

          </ul>

        </div>

     </div>

   </div>

      <div class="bg-white p-4 border mb-4">
    <h2 class="mb-4">Training Schedule </h2>

        <div class="table-responsive ">
          <table class="table table-bordered mb-0">
            <thead>
              <tr>
                <th scope="col">#</th>
                <th scope="col" style="width: 10%">Day </th>
                <th scope="col">Module</th>
                <th scope="col">What you will Learn?</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <th scope="row"></th>
                <td> 16th &17th May </td>
                <td> Basics of programming (for Non CS/IT Students)</td>
                <td> Programming concepts like conditional statements, iterative loops, functions and datatypes. Code software installations. Errors and Debugging Methods. </td>
              </tr> 
              <tr>
                <th scope="row">1</th>
                <td> 20th May </td>
                <td> Drafting Project Requirements</td>
                <td> Understanding the importance of doing project requirement analysis, drawing storyboards for layouts, choosing flatcolorui for color scheme, page designs on paper and in photoshop.</td>
              </tr>  

              <tr>
                <th scope="row">2</th>
                <td> 21st May</td>
                <td> Creating the layout with HTML,CSS and JAVASCRIPT</td>
                <td>Converting the project draft to implementation using html and css, baiscs animations using javascript, usage  of library code like jquery. </td>
              </tr>  

              <tr>
                <th scope="row">3</th>
                <td> 22nd May </td>
                <td> Responsive Website coding with BOOTSTRAP </td>
                <td> Making the design adaptive for desktop, mobile and tablet devices. Bootstrap techonology baiscs and usage, foundation api basics and usage. </td>
              </tr>  

              <tr>
                <th scope="row">4</th>
                <td> 23rd May </td>
                <td> PHP Basics</td>
                <td>Writing clean code using PHP, procedural and object oriented code design, inserting php tags in html, core logic implementation</td>
              </tr>  

              <tr>
                <th scope="row">5 </th>
                <td> 24th May</td>
                <td> MySQL Basics </td>
                <td> Understanding how to use sql to make queries, data normalization, primary and foreign key importance, data coherence. </td>
              </tr>  

              <tr>
                <th scope="row">6</th>
                <td> 27th May </td>
                <td> MVC Architecture for Application</td>
                <td> Model,View and Controller architecture for request processing and data display in browser.</td>
              </tr>  

              <tr>
                <th scope="row">7</th>
                <td> 28th May</td>
                <td> Login System with security </td>
                <td>Form for registration, Form for login, security features like handling sql injection, cross site scripting and spam requests.</td>
              </tr>   
              <tr>
                <th scope="row">8</th>
                <td> 29th May</td>
                <td> Ecommerce Module </td>
                <td> Creaitng a gallery for products with checkout option, products and transactions table in sql, validation of payment receiving and delivering the service</td>
              </tr> 
              <tr>
                <th scope="row">9</th>
                <td> 30th May</td>
                <td> Payment Gateway Integration</td>
                <td> Working with Instamojo payment gateway api in php, connecting bank accounts for immediate withdrawl, trail transactions using API.</td>
              </tr> 
              <tr>
                <th scope="row">10</th>
                <td> 31st May</td>
                <td> Deployment on Server</td>
                <td>Registering a domian and hosting, Connecting domain to hosting with nameservers, usage of cpanel for server maintenance, uploading code using filezilla ftp, live website testing with console.</td>
              </tr> 
            </tbody>
          </table>
        </div>
       


   </div>


     <div class="bg-white p-4 border mb-3">
      <h1 class="bg-light border p-3 rounded mb-3"> Mini Projects</h1>
      <p> Students who undergo training in coding bootcamp will also be given an opportunity to do a FREE mini project for academic purpose. Insterested students can select of the following projects, we will give training/implementation support for extra 10 days. And best team's code will be integrated to packetprep's platfrom with your names as credits at the bottom.</p>
      <div class="row">
        
        <div class="col-12 col-md-4">
          <h2 class="mb-4"> Social Wall</h2>
          <p>The Social Wall project is a client-server Web application built over an RDBMS. It is an application that runs on a portal site, in which different users (and user groups) can publish and revise daily journal entries, and these entries will be made public for others to view.  In essence, it gives everyone his or her own personal editorial column to publish to the world. </p>
          <a href="https://drive.google.com/file/d/1Ug0fCPyVAsSU2waxirNUZixRkfzeZHo8/view?usp=sharing">
          <button class="btn btn-success"> Download Abstract</button>
        </a>
        </div>

        <div class="col-12 col-md-4">
          <h2 class="mb-4"> Q/A Forum</h2>
          <p>Everyone has questions to be answered. Our Question Answer Forum is the place where anyone can post a question and seek answers from the community. The best questions/answers can up voted and liked. This will the one stop platform for people to interact and answer users doubts. </p>
          <a href="https://drive.google.com/file/d/13VlC-wSG652h7jC6cALFsyUAIfk09RKt/view?usp=sharing">
          <button class="btn btn-success"> Download Abstract</button>
        </a>
        </div>
        
        <div class="col-12 col-md-4">
          <h2 class="mb-4"> Job Aggregator</h2>
          <p>Sometimes it’s hard to find the right jobs for the right category. This project solves this problem by listing out the jobs/openings based on various parameters. And User can search through the listing based on salary, location, role, experience etc. including government jobs, and bank jobs listing. </p>
          <a href="https://drive.google.com/file/d/1wkpTTc5F1pWyDyUvUUfYV7l9VPevpij_/view?usp=sharing">
          <button class="btn btn-success"> Download Abstract</button>
        </a>
        </div>

     </div>

   </div>
   

   
      <div class="row">
        
         <div class="col-12 col-md-6">
          <div class="bg-white border mb-4">
            
          <div class="embed-responsive embed-responsive-16by9 border" style="background: #eee;">
          <iframe  src="https://www.youtube.com/embed/M765gB_IUL0" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
          </div>
          <div class="p-3">
          <h1 >Coding & Career</h1>
          <p > Cracking job in a top Multi National Company is a tough task, but  consitent preparation and commitment can make you land in your dream job. This video answers the common questions students have regarding the career, learning apsects, growth.</p>
        </div>
          </div>
        </div>

        <div class="col-12 col-md-6">
          <div class="bg-white border mb-4">
            
          <div class="embed-responsive embed-responsive-16by9 border" style="background: #eee;">
          <iframe  src="https://www.youtube.com/embed/AKuu0IanGKU" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
          </div>
          <div class="p-3">
          <h1 >Coding & Money</h1>
          <p > Everybody dreams of an independent life, pocket full cash and happy going. If have good programming skill, you can easliy make money. This video explains different websites to look for parttime jobs, how to make good commercial projects and details on freelancing.</p>
        </div>
          </div>
        </div>

      </div>

      <div class="row">
        
        

         <div class="col-12 col-md-6">
          <div class="bg-white border mb-4">
            
          <div class="embed-responsive embed-responsive-16by9 border" style="background: #eee;">
          <iframe  src="https://www.youtube.com/embed/4nBO1yj8RAo" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
          </div>
          <div class="p-3">
          <h1 >Coding & Startup</h1>
          <p > Startup is the new big thing. Startup is the way forward for Digital India. If you have a great idea and dont know how to build your project, then coding bootcamp is the right place to learn the basics of building commercial application. </p>
        </div>
          </div>
        </div>

        <div class="col-12 col-md-6">
          <div class="bg-white border mb-4">
            
          <div class="embed-responsive embed-responsive-16by9 border" style="background: #eee;">
          <iframe  src="https://www.youtube.com/embed/HIYbB17wNmo" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
          </div>
          <div class="p-3">
          <h1 >Coding & Abroad Studies</h1>
          <p > If you have plans for abroad study, you can watch this video to understand the importance of coding, impact of mini and major project for MS application, how coding bootcamp can help you crack Research assitantship.</p>
        </div>
          </div>
        </div>
      </div>
      
 

   <div class="bg-light p-4 border ">
      In case of any query, you can whatsapp us at <i class="text-secondary">+91 95151 25110</i> or mail us at <i class="text-secondary">founder@packetprep.com</i>
      </div>
      
   </div>


     </div>   
</div>

@endsection           