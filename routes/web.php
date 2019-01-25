<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use PacketPrep\Http\Middleware\RequestFilter;



Route::group(['middleware' => [RequestFilter::class]], function () {
	
	Route::get('/', function(){ return view('welcome'); })->name('root');
	Route::post('/', 'Product\OrderController@callback');
	Route::get('/instamojo', 'Product\OrderController@instamojo')->middleware('auth');
	Route::get('/order_payment', 'Product\OrderController@instamojo_return');
	Route::post('/order_payment', 'Product\OrderController@instamojo_return');
	Route::post('/contactform', 'System\UpdateController@contact')->name('contactform');


	Route::get('/dashboard','Product\ProductController@welcome')->name('dashboard')->middleware('auth');
	Route::get('/terms',function(){ return view('appl.pages.terms'); })->name('terms');
	Route::get('/premium','Product\ProductController@premium')->name('premium');
	Route::get('/privacy',function(){ return view('appl.product.pages.privacy'); })->name('privacy');
	Route::get('/refund',function(){ return view('appl.product.pages.refund'); })->name('refund');
	Route::get('/disclaimer',function(){ return view('appl.product.pages.disclaimer'); })->name('disclaimer');
	Route::get('/about',function(){ return view('appl.pages.about'); })->name('about');
	Route::get('/faq',function(){ return view('appl.product.pages.faq'); })->name('faq')->middleware('corporate');;
	Route::get('/checkout','Product\OrderController@checkout')->name('checkout')->middleware('auth');
	Route::get('/checkout-success',function(){ return view('appl.product.pages.checkout_success'); })->name('checkout-success')->middleware('auth');
	Route::get('/credit-rates',function(){ return view('appl.product.pages.credit_rates'); })->name('credit-rate')->middleware('auth');;


	Route::get('/payment/status', 'Product\OrderController@status')->name('payment.status');
	Route::post('/payment/order', 'Product\OrderController@order')->name('payment.order');
	Route::get('/transactions', 'Product\OrderController@transactions')->name('order.transactions')->middleware('auth');
	Route::get('/transactions/{order_id}', 'Product\OrderController@transaction')->name('order.transaction')->middleware('auth');
	Route::get('/admin/transactions', 'Product\OrderController@list_transactions')->name('order.list');
	Route::get('/admin/transactions/{order_id}', 'Product\OrderController@show_transaction')->name('order.show');
	Route::get('/admin/buy', 'Product\OrderController@buycredits')->name('order.buy');
	Route::get('/admin/ordersuccess', 'Product\OrderController@ordersuccess')->name('order.success');
	Route::get('/admin/orderfailure', 'Product\OrderController@orderfailure')->name('order.failure');
	Route::get('admin/image','Product\AdminController@image')->name('admin.image')->middleware('auth');
	Route::post('admin/image','Product\AdminController@imageupload')->name('admin.image')->middleware('auth');
	Route::get('admin/user','Product\AdminController@user')->name('admin.user')->middleware('auth');
	Route::get('admin/user/list','Product\AdminController@listuser')->name('admin.listuser')->middleware('auth');
	
	Route::get('admin/adduser','Product\AdminController@adduser')->name('admin.user.create')->middleware('auth');
	Route::post('admin/adduser','Product\AdminController@storeuser')->name('admin.user.store')->middleware('auth');
	Route::get('admin/edituser/{user}','Product\AdminController@edituser')->name('admin.user.edit')->middleware('auth');
	Route::put('admin/updateuser/{user}','Product\AdminController@updateuser')->name('admin.user.update')->middleware('auth');
	Route::get('admin/user/{user}','Product\AdminController@viewuser')->name('admin.user.view')->middleware('auth');
	Route::get('u/{user}','Product\AdminController@printuser')->name('admin.user.print')->middleware('auth');
	Route::get('admin/user/{user}/product','Product\AdminController@userproduct')->name('admin.user.product')->middleware('auth');
	Route::post('admin/user/{user}/product','Product\AdminController@storeuserproduct')->name('admin.user.product')->middleware('auth');
	Route::get('admin/user/{user}/product/{id}','Product\AdminController@edit_userproduct')->name('admin.user.product.edit')->middleware('auth');
	Route::post('admin/user/{user}/product/{id}','Product\AdminController@update_userproduct')->name('admin.user.product.update')->middleware('auth');

	Route::get('/pricing',function(){ return view('appl.product.pages.pricing'); })->name('pricing');

	Route::get('/about-corporate',function(){ return view('appl.product.pages.about'); })->name('about-corporate');

	Route::get('/terms-corporate',function(){ return view('appl.product.pages.terms'); })->name('terms-corporate');

	Route::get('/contact-corporate',function(){ return view('appl.product.pages.contact'); })->name('contact-corporate');

	Route::get('/downloads-corporate',function(){ return view('appl.product.pages.downloads'); })->name('downloads');


	/*test */
	Route::get('/onlinetest', 'Product\TestController@main')->name('onlinetest');
	Route::get('/onlinetest/{test}/instructions','Product\TestController@instructions')->name('onlinetest.instructions')->middleware('auth');
	Route::get('/onlinetest/{test}/questions','Product\TestController@index')->name('onlinetest.questions')->middleware('auth');
	Route::get('/onlinetest/{test}/questions/{id}','Product\TestController@index')->name('onlinetest.questions.id');
	Route::get('/onlinetest/{test}/questions/{id}/save','Product\TestController@save')->name('onlinetest.questions.save');
	Route::get('/onlinetest/{test}/questions/{id}/clear','Product\TestController@clear')->name('onlinetest.questions.clear');
	Route::get('/onlinetest/{test}/submit','Product\TestController@submit')->name('onlinetest.submit');
	Route::get('/onlinetest/{test}/analysis','Product\TestController@analysis')->name('onlinetest.analysis')->middleware('auth');
	Route::get('/onlinetest/{test}/solutions','Product\TestController@solutions')->name('onlinetest.solutions')->middleware('auth');
	Route::get('/onlinetest/{test}/solutions/{question}','Product\TestController@solutions')->name('onlinetest.solutions.q')->middleware('auth');


	Auth::routes();




	Route::get('/home', function () { return redirect('/'); })->name('home');
	Route::get('/apply', function () { return view('welcome'); })->name('apply');
	Route::get('team','User\TeamController@index')->name('team');

	Route::resource('product','Product\ProductController')->middleware('auth');
	Route::get('productpage','Product\ProductController@products')->name('products');
	Route::get('productpage/{product}','Product\ProductController@page')->name('productpage');
	Route::resource('client','Product\ClientController')->middleware('auth');
	Route::resource('client/{client}/clientuser','Product\ClientuserController')->middleware('auth');
	Route::post('client/image','Product\ClientController@imageupload')->name('client.image')->middleware('auth');
	Route::get('admin','Product\AdminController@index')->name('admin.index')->middleware('auth');
	Route::get('admin/settings','Product\AdminController@settings')->name('admin.settings')->middleware('auth');
	Route::post('admin/settings','Product\AdminController@settings_store')->name('admin.settings')->middleware('auth');

	Route::resource('role','User\RoleController')->middleware('auth');
	Route::resource('tracks','Content\DocController',['names' => [
        'index' => 'docs.index',
        'store' => 'docs.store',
        'create' => 'docs.create',
        'show' => 'docs.show',
        'edit'=> 'docs.edit',
        'update'=>'docs.update',
        'destroy'=>'docs.destroy',
    ]]);
	Route::resource('tracks/{doc}/chapter','Content\ChapterController',['names' => [
        'index' => 'chapter.index',
        'store' => 'chapter.store',
        'create' => 'chapter.create',
        'show' => 'chapter.show',
        'edit'=> 'chapter.edit',
        'update'=>'chapter.update',
        'destroy'=>'chapter.destroy',
    ]]);

	Route::get('/proficiency-test', 'Product\TestController@proficiency_test')->name('proficiency_test');
	Route::get('/updates', 'System\UpdateController@public_updates')->name('updates');
	Route::get('/updates/{id}', 'System\UpdateController@public_view')->name('updates.view');
	Route::get('/system', 'System\UpdateController@system')->name('system')->middleware('auth');
	Route::resource('system/update','System\UpdateController')->middleware('auth');
	Route::resource('system/finance','System\FinanceController')->middleware('auth');
	Route::resource('system/goal','System\GoalController')->middleware('auth');
	Route::get('system/report/week','System\ReportController@week')->middleware('auth')->name('report.week');
	Route::resource('system/report','System\ReportController')->middleware('auth');


	Route::resource('zone','College\ZoneController')->middleware('auth');
	Route::resource('branch','College\BranchController')->middleware('auth');
	Route::resource('college','College\CollegeController')->middleware('auth');
	Route::resource('metric','College\MetricController')->middleware('auth');
	Route::resource('service','College\ServiceController')->middleware('auth');
	Route::get('college/{college}/userlist','College\CollegeController@userlist')->middleware('auth')->name('college.userlist');
	Route::post('productactivate','Product\ProductController@activate')->middleware('auth')->name('product.activate');

	Route::resource('exam','Exam\ExamController')->middleware('auth');
	Route::resource('examtype','Exam\ExamtypeController')->middleware('auth');
	Route::resource('exam/{exam}/sections','Exam\SectionController')->middleware('auth');
	Route::get('exam/{exam}/question','Dataentry\QuestionController@exam')->middleware('auth')->name('exam.questions');
	Route::get('exam/createexam','Exam\ExamController@createExam')->middleware('auth')->name('exam.createexam');
	Route::post('exam/createexam','Exam\ExamController@storeExam')->middleware('auth')->name('exam.save');
	Route::get('exam/{exam}/question/{id}','Dataentry\QuestionController@exam')->middleware('auth')->name('exam.question');

	Route::get('certificate/{exam}/{user}','Exam\AssessmentController@certificate')->name('certificate');
	Route::get('certificate/sample','Exam\AssessmentController@certificate_sample')->name('certificate.sample');
	Route::get('report/{exam}/{user}','Exam\AssessmentController@report')->name('report');
	Route::get('test','Exam\AssessmentController@index')->name('assessment.index');
	Route::get('test/{test}/submit','Exam\AssessmentController@submit')->name('assessment.submit');
	Route::get('test/{test}/analysis','Exam\AssessmentController@analysis')->name('assessment.analysis')->middleware('auth');
	Route::get('test/{test}/solutions','Exam\AssessmentController@solutions')->name('assessment.solutions')->middleware('auth');
	Route::get('test/{test}/solutions/{question}','Exam\AssessmentController@solutions')->name('assessment.solutions.q')->middleware('auth');
	Route::get('test/{test}','Exam\AssessmentController@try')->middleware('auth')->name('assessment.try');
	Route::get('test/{test}/details','Exam\AssessmentController@show')->name('assessment.show');
	Route::get('test/{test}/instructions','Exam\AssessmentController@instructions')->middleware('auth')->name('assessment.instructions');

	Route::get('test/{test}/{id}','Exam\AssessmentController@try')->name('assessment.try.id');
	Route::get('test/{test}/{id}/save','Exam\AssessmentController@save')->name('assessment.save');
	Route::get('test/{test}/{id}/clear','Exam\AssessmentController@clear')->name('assessment.clear');
	
	
	Route::resource('/coupon', 'Product\CouponController')->middleware('auth');
	Route::get('/coupon/getamount/{amount}/{code}', 'Product\CouponController@getamount');
	
	Route::get('/social', 'Social\MediaController@social')->name('social')->middleware('auth');
	Route::post('/social/imageupload', 'Social\BlogController@image_upload')->name('imageupload');
	Route::get('/social/imageremove', 'Social\BlogController@image_remove')->name('imageremove');
	Route::resource('blog','Social\BlogController')->middleware('auth');
	Route::resource('social/media','Social\MediaController')->middleware('auth');
	

	Route::get('/user/activate/{token_name}', 'Auth\RegisterController@activateUser')->name('activateuser');


	Route::get('/material', 'Dataentry\ProjectController@material')->name('material');
	Route::get('dataentry/qdb','Dataentry\QdbController@index')->middleware('auth')->name('qdb.index');
	Route::get('dataentry/qdb/export','Dataentry\QdbController@exportQuestion')->middleware('auth')->name('qdb.export');
	Route::get('dataentry/qdb/import','Dataentry\QdbController@importQuestion')->middleware('auth')->name('qdb.import');
	Route::get('dataentry/qdb/replace','Dataentry\QdbController@replacement')->middleware('auth')->name('qdb.replace');

	Route::get('dataentry/fork','Dataentry\ProjectController@fork')->middleware('auth')->name('dataentry.fork');
	Route::resource('dataentry','Dataentry\ProjectController')->middleware('auth');
	Route::resource('dataentry/{project}/category','Dataentry\CategoryController')->middleware('auth');
	Route::get('dataentry/{project}/category/{category}/question','Dataentry\QuestionController@category')->middleware('auth')->name('category.question');
	Route::get('dataentry/{project}/category/{category}/question/{id}','Dataentry\QuestionController@category')->middleware('auth')->name('category.question');
	Route::resource('dataentry/{project}/tag','Dataentry\TagController')->middleware('auth');
	Route::get('dataentry/{project}/tag/{tag}/question','Dataentry\QuestionController@tag')->middleware('auth')->name('tag.question');
	Route::get('dataentry/{project}/tag/{tag}/question/{id}','Dataentry\QuestionController@tag')->middleware('auth')->name('tag.question');
	Route::resource('dataentry/{project}/passage','Dataentry\PassageController')->middleware('auth');
	Route::resource('dataentry/{project}/question','Dataentry\QuestionController')->middleware('auth');
	Route::get('question/attach/{question}/{category}','Dataentry\QuestionController@attachCategory');
	Route::get('question/detach/{question}/{category}','Dataentry\QuestionController@detachCategory');

	Route::get('question/attachsection/{question}/{section}','Dataentry\QuestionController@attachSection');
	Route::get('question/detachsection/{question}/{section}','Dataentry\QuestionController@detachSection');

	Route::resource('library','Library\RepositoryController')->middleware('auth');
	Route::resource('library/{repository}/structure','Library\structureController')->middleware('auth');
	Route::get('library/{repository}/structure/{structure}/question','Library\LquestionController@structure')->middleware('auth')->name('structure.question');
	Route::get('library/{repository}/structure/{structure}/question/{id}','Library\LquestionController@structure')->middleware('auth')->name('structure.question');
	Route::resource('library/{repository}/ltag','Library\LtagController')->middleware('auth');
	Route::get('library/{repository}/ltag/{tag}/question','Library\LquestionController@tag')->middleware('auth')->name('ltag.question');
	Route::get('library/{repository}/ltag/{tag}/question/{id}','Library\LquestionController@tag')->middleware('auth')->name('ltag.question');
	Route::resource('library/{repository}/lpassage','Library\LpassageController')->middleware('auth');
	Route::resource('library/{repository}/lquestion','Library\LquestionController')->middleware('auth');
	Route::resource('library/{repository}/version','Library\VersionController')->middleware('auth');
	Route::resource('library/{repository}/video','Library\VideoController')->middleware('auth');
	Route::resource('library/{repository}/document','Library\DocumentController')->middleware('auth');

	Route::resource('course','Course\CourseController');
	//Route::resource('course/{course}/index','Course\IndexController');
	Route::get('course/{course}/{category}/view','Course\CourseController@video')->name('course.category.video')->middleware('auth');
	Route::get('course/{project}/{category}/practice','Dataentry\QuestionController@categoryCourse')->name('course.question')->middleware('auth');
	Route::get('course/{project}/{category}/practice/{id}','Dataentry\QuestionController@categoryCourse')->name('course.question')->middleware('auth');
	Route::post('course/{project}/{category}/practice/{id}','Dataentry\QuestionController@categoryCourseSave')->name('course.question')->middleware('auth');

	Route::get('/recruit', 'Recruit\JobController@recruit')->name('recruit');
	Route::resource('job','Recruit\JobController');
	Route::resource('form','Recruit\FormController');

    Route::get('/{username}', 'User\UserController@index')->name('profile');
	Route::get('/{username}/edit', 'User\UserController@edit')->name('profile.edit');
	Route::get('/{username}/manage', 'User\UserController@manage')->name('profile.manage');
	Route::put('/{username}', 'User\UserController@update')->name('profile.update');
	Route::delete('/{username}', 'User\UserController@destroy')->name('profile.delete');

});






