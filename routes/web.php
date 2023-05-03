<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BatchController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\NoteController;
use App\Http\Controllers\ReferenceController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\PackageController;
use App\Http\Controllers\ClassroomController;
use App\Http\Controllers\BatchtimeController;
use App\Http\Controllers\BatchslotController;
use App\Http\Controllers\DivisionController;
use App\Http\Controllers\DistrictController;
use App\Http\Controllers\UpazilaController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\PaymentReportController;
use App\Http\Controllers\OtherPaymentController;
use App\Http\Controllers\PaymentTransferController;
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

Route::group(['middleware' => 'unknownUser'], function(){
    // Sign In, Login
    Route::get('/', [AuthenticationController::class,'signInForm'])->name('signInForm');
    Route::get('/sign-in', [AuthenticationController::class,'signInForm'])->name('signInForm');
    Route::get('/login', [AuthenticationController::class,'signInForm'])->name('signInForm');

    Route::post('/sign-in', [AuthenticationController::class,'signIn'])->name('logIn');
    Route::post('/login', [AuthenticationController::class,'signIn'])->name('logIn');

    // Sign Up, Registration
    Route::get('/sign-up', [AuthenticationController::class,'signUpForm'])->name('signUpForm');
    Route::get('/register', [AuthenticationController::class,'signUpForm'])->name('signUpForm');
    Route::get('/registration', [AuthenticationController::class,'signUpForm'])->name('signUpForm');
	Route::post('/registered', [AuthenticationController::class,'signUpStore'])->name('signUp');


    // Forgot Password
    Route::get('/forgot', [AuthenticationController::class,'forgotForm'])->name('forgotPasswordForm');
    Route::get('/forgot-pass', [AuthenticationController::class,'forgotForm'])->name('forgotPasswordForm');
    Route::get('/forgot-password', [AuthenticationController::class,'forgotForm'])->name('forgotPasswordForm');

    Route::post('/forgot-password', [AuthenticationController::class,'forgotPassword'])->name('forgotPassword');

    // Reset Password
    Route::get('/reset-password', [AuthenticationController::class,'resetPasswordForm'])->name('resetPasswordForm');
    Route::post('/reset-password', [AuthenticationController::class,'resetPassword'])->name('resetPassword');
});
Route::get('/sign-out', [AuthenticationController::class,'signOut'])->name('logOut');
Route::get('/logout', [AuthenticationController::class,'signOut'])->name('logOut');

// Super Admin
Route::group(['middleware' => 'isSuperAdmin'], function(){
    Route::prefix('superadmin')->group(function () {
        Route::get('/dashboard', [DashboardController::class,'index'])->name('superadminDashboard');
		
        Route::prefix('user')->group(function () {
            Route::get('/all', [UserController::class,'index'])->name('superadmin.allUser');
            Route::get('/add', [UserController::class,'addForm'])->name('superadmin.addNewUserForm');
            Route::post('/add', [UserController::class,'store'])->name('superadmin.addNewUser');
            Route::get('/edit/{name}/{id}', [UserController::class,'editForm'])->name('superadmin.editUser');
            Route::post('/update', [UserController::class,'update'])->name('superadmin.updateUser');
            Route::get('/delete/{name}/{id}', [UserController::class,'UserController@delete'])->name('superadmin.deleteUser');
        });
        Route::get('/profile', [UserController::class,'userProfile'])->name('superadmin.userProfile');
        Route::post('/profile', [UserController::class,'storeProfile'])->name('superadmin.storeProfile');
        Route::post('/changePass', [UserController::class,'changePass'])->name('superadmin.changePass');
        Route::post('/changePer', [UserController::class,'changePer'])->name('superadmin.changePer');
        Route::post('/changeAcc', [UserController::class,'changeAcc'])->name('superadmin.changeAcc');

		

        Route::prefix('student')->group(function () {
            //Student Controller
            Route::get('/all',  [StudentController::class,'index'])->name('superadmin.allStudent');
            Route::get('/add', [StudentController::class,'addForm'])->name('superadmin.addNewStudentForm');
            Route::post('/add', [StudentController::class,'store'])->name('superadmin.addNewStudent');
            Route::get('/edit/{id}', [StudentController::class,'editForm'])->name('superadmin.editStudent');
            Route::post('/update/{id}', [StudentController::class,'update'])->name('superadmin.updateStudent');
            Route::put('/dump/{id}', [StudentController::class,'dump'])->name('superadmin.dumpStudent');
            Route::put('/active/{id}', [StudentController::class,'active'])->name('superadmin.activeStudent');
            Route::get('/course/assign/{id}', [StudentController::class,'studentCourseAssign'])->name('superadmin.studentCourseAssign');
            Route::post('/course/assign/{id}', [StudentController::class,'addstudentCourseAssign'])->name('superadmin.addstudentCourseAssign');
            Route::delete('/enroll/delete/{id}', [StudentController::class,'deleteEnroll'])->name('superadmin.enrollment.destroy');
        });

        Route::resource('/notes',NoteController::class,["as" => "superadmin"]);
        
        Route::get('/batch/all',[BatchController::class,'all'])->name('superadmin.allBatches');
        Route::resource('/batch',BatchController::class,["as" => "superadmin"]);
        Route::get('/batchById',[BatchController::class,'batchById'])->name('superadmin.batchById');

        /*==Student Transfer==*/
        Route::get('/student/transfer/list', [StudentController::class,'studentTransferList'])->name('superadmin.studentTransferList');
        Route::get('/student/transfer', [StudentController::class,'studentTransfer'])->name('superadmin.studentTransfer');
        Route::get('/student/executive', [StudentController::class,'studentExecutive'])->name('superadmin.studentExecutive');
        Route::post('/student/transfer/save', [StudentController::class,'stTransfer'])->name('superadmin.stTransfer');
        



        /*==Batch Transfer==*/
        Route::get('/student/batch/transfer/list', [StudentController::class,'batchTransferList'])->name('superadmin.batchTransferList');
        Route::get('/student/batch/transfer', [StudentController::class,'batchTransfer'])->name('superadmin.batchTransfer');
        Route::get('/student/batch/enroll', [StudentController::class,'studentEnrollBatch'])->name('superadmin.studentEnrollBatch');
        Route::post('/student/transfer', [StudentController::class,'transfer'])->name('superadmin.transfer');

        /*Course Wise Enroll */
        Route::post('/course/wise/enroll', [StudentController::class,'courseEnroll'])->name('superadmin.courseEnroll');

        /*Course Preference */
        Route::post('/course/preference/', [StudentController::class,'coursePreference'])->name('superadmin.coursePreference');

        /*==Course Search==*/
        Route::post('/course/search',[CourseController::class,'courseSearch'])->name('superadmin.courseSearch');

        Route::resource('/package',PackageController::class,["as" => "superadmin"]);
        Route::resource('/batch',BatchController::class,["as" => "superadmin"]);
        Route::resource('/reference',ReferenceController::class,["as" => "superadmin"]);

        Route::resource('/course',CourseController::class,["as" => "superadmin"]);
        Route::resource('/classroom',ClassRoomController::class,["as" => "superadmin"]);
        Route::resource('/batchtime',BatchtimeController::class,["as" => "superadmin"]);
        Route::resource('/batchslot',BatchslotController::class,["as" => "superadmin"]);
        Route::resource('/division',DivisionController::class,["as" => "superadmin"]);
        Route::resource('/district',DistrictController::class,["as" => "superadmin"]);
        Route::resource('/upazila',UpazilaController::class,["as" => "superadmin"]);
        Route::resource('/payment',PaymentController::class,["as" => "superadmin"]);

        /*===Report Data===*/
        Route::get('/batch/wise/enroll', [ReportController::class,'batchwiseEnrollStudent'])->name('superadmin.batchwiseEnrollStudent');
        Route::post('/batch/wise/enroll', [ReportController::class,'batchwiseEnrollStudent'])->name('superadmin.batchwiseEnrollStudent');

        /*===Payment report==*/
        Route::get('/daily/collection/report',[PaymentReportController::class,'daily_collection_report'])->name('superadmin.daily_collection_report');
        Route::get('/daily/collection/report/mr',[PaymentReportController::class,'daily_collection_report_by_mr'])->name('superadmin.daily_collection_report_by_mr');

        /*Attendance Report */
        Route::get('/batch/wise/attendance', [ReportController::class,'batchwiseAttendance'])->name('superadmin.batchwiseAttendance');
        Route::get('/batch/wise/attendance/report', [ReportController::class,'batchwiseAttendanceReport'])->name('superadmin.batchwiseAttendanceReport');

        /*=== Course Report= ==*/
        Route::get('/course/wise/report', [ReportController::class,'coursewiseStudent'])->name('superadmin.coursewiseStudent');
        Route::post('/course/wise/report', [ReportController::class,'coursewiseStudent'])->name('superadmin.coursewiseStudent');
	});
});

// Frontdesk|dataentry
Route::group(['middleware' => 'isFrontdesk'], function(){
    Route::prefix('frontdesk')->group(function () {
        Route::get('/', [DashboardController::class,'frontdesk']);
        Route::get('/dashboard', [DashboardController::class,'frontdesk'])->name('frontdeskDashboard');
       
        Route::get('/profile', [UserController::class,'userProfile'])->name('frontdesk.userProfile');
        Route::post('/profile', [UserController::class,'storeProfile'])->name('frontdesk.storeProfile');
        Route::post('/changePass', [UserController::class,'changePass'])->name('frontdesk.changePass');
        Route::post('/changePer', [UserController::class,'changePer'])->name('frontdesk.changePer');
        Route::post('/changeAcc', [UserController::class,'changeAcc'])->name('frontdesk.changeAcc');

        Route::prefix('student')->group(function () {
            //Student Controller
            Route::get('/add', [StudentController::class,'addForm'])->name('frontdesk.addNewStudentForm');
            Route::post('/add', [StudentController::class,'store'])->name('frontdesk.addNewStudent');
            Route::get('/all',  [StudentController::class,'index'])->name('frontdesk.allStudent');
        });
        Route::resource('/batch',BatchController::class,["as" => "frontdesk"])->only(['index']);

        /*===Report Data===*/
        Route::get('/batch/wise/enroll', [ReportController::class,'batchwiseEnrollStudent'])->name('frontdesk.batchwiseEnrollStudent');
        Route::post('/batch/wise/enroll', [ReportController::class,'batchwiseEnrollStudent'])->name('frontdesk.batchwiseEnrollStudent');
        
    });
});

// Sales Manager
Route::group(['middleware' => 'isSalesManager'], function(){
    Route::prefix('sales-manager')->group(function () {
        Route::get('/', [DashboardController::class,'salesManager']);
        Route::get('/dashboard', [DashboardController::class,'salesManager'])->name('salesmanagerDashboard');
       
        Route::prefix('user')->group(function () {
            Route::get('/all', [UserController::class,'index'])->name('salesmanager.allUser');
            Route::get('/add', [UserController::class,'addForm'])->name('salesmanager.addNewUserForm');
            Route::post('/add', [UserController::class,'store'])->name('salesmanager.addNewUser');
            Route::get('/edit/{name}/{id}', [UserController::class,'editForm'])->name('salesmanager.editUser');
            Route::post('/update', [UserController::class,'update'])->name('salesmanager.updateUser');
            Route::get('/delete/{name}/{id}', [UserController::class,'UserController@delete'])->name('salesmanager.deleteUser');
        });

        Route::get('/profile', [UserController::class,'userProfile'])->name('salesmanager.userProfile');
        Route::post('/profile', [UserController::class,'storeProfile'])->name('salesmanager.storeProfile');
        Route::post('/changePass', [UserController::class,'changePass'])->name('salesmanager.changePass');
        Route::post('/changePer', [UserController::class,'changePer'])->name('salesmanager.changePer');
        Route::post('/changeAcc', [UserController::class,'changeAcc'])->name('salesmanager.changeAcc');

        Route::prefix('student')->group(function () {
            //Student Controller
            Route::get('/all',  [StudentController::class,'index'])->name('salesmanager.allStudent');
            Route::get('/add', [StudentController::class,'addForm'])->name('salesmanager.addNewStudentForm');
            Route::post('/add', [StudentController::class,'store'])->name('salesmanager.addNewStudent');
            Route::get('/edit/{id}', [StudentController::class,'editForm'])->name('salesmanager.editStudent');
            Route::post('/update/{id}', [StudentController::class,'update'])->name('salesmanager.updateStudent');
            Route::put('/dump/{id}', [StudentController::class,'dump'])->name('salesmanager.dumpStudent');
            Route::put('/active/{id}', [StudentController::class,'active'])->name('salesmanager.activeStudent');
            Route::get('/course/assign/{id}', [StudentController::class,'studentCourseAssign'])->name('salesmanager.studentCourseAssign');
            Route::post('/course/assign/{id}', [StudentController::class,'addstudentCourseAssign'])->name('salesmanager.addstudentCourseAssign');
            Route::delete('/enroll/delete/{id}', [StudentController::class,'deleteEnroll'])->name('salesmanager.enrollment.destroy');
        });

        Route::resource('/notes',NoteController::class,["as" => "salesmanager"]);

        Route::resource('/package',PackageController::class,["as" => "salesmanager"]);
        Route::resource('/batch',BatchController::class,["as" => "salesmanager"]);
        Route::resource('/reference',ReferenceController::class,["as" => "salesmanager"]);

        Route::resource('/course',CourseController::class,["as" => "salesmanager"]);
        Route::resource('/classroom',ClassRoomController::class,["as" => "salesmanager"]);
        Route::resource('/batchtime',BatchtimeController::class,["as" => "salesmanager"]);
        Route::resource('/batchslot',BatchslotController::class,["as" => "salesmanager"]);
        Route::resource('/division',DivisionController::class,["as" => "salesmanager"]);
        Route::resource('/district',DistrictController::class,["as" => "salesmanager"]);
        Route::resource('/upazila',UpazilaController::class,["as" => "salesmanager"]);

        /*===Report Data===*/
        Route::get('/batch/wise/enroll', [ReportController::class,'batchwiseEnrollStudent'])->name('salesmanager.batchwiseEnrollStudent');
        Route::post('/batch/wise/enroll', [ReportController::class,'batchwiseEnrollStudent'])->name('salesmanager.batchwiseEnrollStudent');

        /*===Payment report==*/
        Route::get('/daily/collection/report',[PaymentReportController::class,'daily_collection_report'])->name('salesmanager.daily_collection_report');
        Route::get('/daily/collection/report/mr',[PaymentReportController::class,'daily_collection_report_by_mr'])->name('salesmanager.daily_collection_report_by_mr');

        /*=== Course Report= ==*/
        Route::get('/course/wise/report', [ReportController::class,'coursewiseStudent'])->name('salesmanager.coursewiseStudent');
        Route::post('/course/wise/report', [ReportController::class,'coursewiseStudent'])->name('salesmanager.coursewiseStudent');

         /*Attendance Report */
         Route::get('/batch/wise/attendance', [ReportController::class,'batchwiseAttendance'])->name('salesmanager.batchwiseAttendance');
         Route::get('/batch/wise/attendance/report', [ReportController::class,'batchwiseAttendanceReport'])->name('salesmanager.batchwiseAttendanceReport');
    });
});

// Sales Executive
Route::group(['middleware' => 'isSalesExecutive'], function(){
    Route::prefix('sales-executive')->group(function () {
        Route::get('/', [DashboardController::class,'salesExecutive']);
        Route::get('/dashboard', [DashboardController::class,'salesExecutive'])->name('salesexecutiveDashboard');
        
        Route::get('/profile', [UserController::class,'userProfile'])->name('salesexecutive.userProfile');
        Route::post('/profile', [UserController::class,'storeProfile'])->name('salesexecutive.storeProfile');
        Route::post('/changePass', [UserController::class,'changePass'])->name('salesexecutive.changePass');
        Route::post('/changePer', [UserController::class,'changePer'])->name('salesexecutive.changePer');
        Route::post('/changeAcc', [UserController::class,'changeAcc'])->name('salesexecutive.changeAcc');

        Route::prefix('student')->group(function () {
            //Student Controller
            Route::get('/all',  [StudentController::class,'index'])->name('salesexecutive.allStudent');
            Route::get('/add', [StudentController::class,'addForm'])->name('salesexecutive.addNewStudentForm');
            Route::post('/add', [StudentController::class,'store'])->name('salesexecutive.addNewStudent');
            Route::get('/edit/{id}', [StudentController::class,'editForm'])->name('salesexecutive.editStudent');
            Route::post('/update/{id}', [StudentController::class,'update'])->name('salesexecutive.updateStudent');
            Route::put('/dump/{id}', [StudentController::class,'dump'])->name('salesexecutive.dumpStudent');
            Route::put('/active/{id}', [StudentController::class,'active'])->name('salesexecutive.activeStudent');
            Route::get('/course/assign/{id}', [StudentController::class,'studentCourseAssign'])->name('salesexecutive.studentCourseAssign');
            Route::post('/course/assign/{id}', [StudentController::class,'addstudentCourseAssign'])->name('salesexecutive.addstudentCourseAssign');
            Route::delete('/enroll/delete/{id}', [StudentController::class,'deleteEnroll'])->name('salesexecutive.enrollment.destroy');
        });
        
        Route::resource('/notes',NoteController::class,["as" => "salesexecutive"]);

        Route::get('/batch/all',[BatchController::class,'all'])->name('salesexecutive.allBatches');
        Route::resource('/batch',BatchController::class,["as" => "salesexecutive"])->only(['index']);
        Route::get('/batchById',[BatchController::class,'batchById'])->name('salesexecutive.batchById');

        /*===Report Data===*/
        Route::get('/batch/wise/enroll', [ReportController::class,'batchwiseEnrollStudent'])->name('salesexecutive.batchwiseEnrollStudent');
        Route::post('/batch/wise/enroll', [ReportController::class,'batchwiseEnrollStudent'])->name('salesexecutive.batchwiseEnrollStudent');

        /*Course Preference */
        Route::post('/course/preference/', [StudentController::class,'coursePreference'])->name('salesexecutive.coursePreference');

        /*Course Wise Enroll */
        Route::post('/course/wise/enroll', [StudentController::class,'courseEnroll'])->name('salesexecutive.courseEnroll');

        /*===Payment report==*/
        Route::get('/daily/collection/report',[PaymentReportController::class,'daily_collection_report'])->name('salesexecutive.daily_collection_report');
        Route::get('/daily/collection/report/mr',[PaymentReportController::class,'daily_collection_report_by_mr'])->name('salesexecutive.daily_collection_report_by_mr');

        /*=== Course Report= ==*/
        Route::get('/course/wise/report', [ReportController::class,'coursewiseStudent'])->name('salesexecutive.coursewiseStudent');
        Route::post('/course/wise/report', [ReportController::class,'coursewiseStudent'])->name('salesexecutive.coursewiseStudent');
    });
});


// Operation Manager
Route::group(['middleware' => 'isOperationmanager'], function(){
    Route::prefix('operation-manager')->group(function () {
        Route::get('/', [DashboardController::class,'operationgmanager']);
        Route::get('/dashboard', [DashboardController::class,'operationmanager'])->name('operationmanagerDashboard');
        
        Route::prefix('user')->group(function () {
            Route::get('/all', [UserController::class,'index'])->name('operationmanager.allUser');
            Route::get('/add', [UserController::class,'addForm'])->name('operationmanager.addNewUserForm');
            Route::post('/add', [UserController::class,'store'])->name('operationmanager.addNewUser');
            Route::get('/edit/{name}/{id}', [UserController::class,'editForm'])->name('operationmanager.editUser');
            Route::post('/update', [UserController::class,'update'])->name('operationmanager.updateUser');
            Route::get('/delete/{name}/{id}', [UserController::class,'UserController@delete'])->name('operationmanager.deleteUser');
        });

        Route::get('/profile', [UserController::class,'userProfile'])->name('operationmanager.userProfile');
        Route::post('/profile', [UserController::class,'storeProfile'])->name('operationmanager.storeProfile');
        Route::post('/changePass', [UserController::class,'changePass'])->name('operationmanager.changePass');
        Route::post('/changePer', [UserController::class,'changePer'])->name('operationmanager.changePer');
        Route::post('/changeAcc', [UserController::class,'changeAcc'])->name('operationmanager.changeAcc');

        Route::prefix('student')->group(function () {
            //Student Controller
            Route::get('/all',  [StudentController::class,'index'])->name('operationmanager.allStudent');
            Route::get('/add', [StudentController::class,'addForm'])->name('operationmanager.addNewStudentForm');
            Route::post('/add', [StudentController::class,'store'])->name('operationmanager.addNewStudent');
            Route::get('/edit/{id}', [StudentController::class,'editForm'])->name('operationmanager.editStudent');
            Route::post('/update/{id}', [StudentController::class,'update'])->name('operationmanager.updateStudent');
            Route::put('/dump/{id}', [StudentController::class,'dump'])->name('operationmanager.dumpStudent');
            Route::put('/active/{id}', [StudentController::class,'active'])->name('operationmanager.activeStudent');
            Route::get('/course/assign/{id}', [StudentController::class,'studentCourseAssign'])->name('operationmanager.studentCourseAssign');
            Route::post('/course/assign/{id}', [StudentController::class,'addstudentCourseAssign'])->name('operationmanager.addstudentCourseAssign');
            Route::delete('/enroll/delete/{id}', [StudentController::class,'deleteEnroll'])->name('operationmanager.enrollment.destroy');
        });

        Route::resource('/notes',NoteController::class,["as" => "operationmanager"]);

        
        Route::resource('/package',PackageController::class,["as" => "operationmanager"]);
        Route::resource('/reference',ReferenceController::class,["as" => "operationmanager"]);

        Route::get('/batch/all',[BatchController::class,'all'])->name('operationmanager.allBatches');
        Route::resource('/batch',BatchController::class,["as" => "operationmanager"]);
        Route::get('/batchById',[BatchController::class,'batchById'])->name('operationmanager.batchById');

        Route::resource('/course',CourseController::class,["as" => "operationmanager"]);
        /*Course Preference */
        Route::post('/course/preference/', [StudentController::class,'coursePreference'])->name('operationmanager.coursePreference');
        /*==Course Search==*/
        Route::post('/course/search',[CourseController::class,'courseSearch'])->name('operationmanager.courseSearch');

        Route::resource('/classroom',ClassRoomController::class,["as" => "operationmanager"]);
        Route::resource('/batchtime',BatchtimeController::class,["as" => "operationmanager"]);
        Route::resource('/batchslot',BatchslotController::class,["as" => "operationmanager"]);
        Route::resource('/division',DivisionController::class,["as" => "operationmanager"]);
        Route::resource('/district',DistrictController::class,["as" => "operationmanager"]);
        Route::resource('/upazila',UpazilaController::class,["as" => "operationmanager"]);

        /*==Student Transfer==*/
        Route::get('/student/transfer/list', [StudentController::class,'studentTransferList'])->name('operationmanager.studentTransferList');
        Route::get('/student/transfer', [StudentController::class,'studentTransfer'])->name('operationmanager.studentTransfer');
        Route::get('/student/executive', [StudentController::class,'studentExecutive'])->name('operationmanager.studentExecutive');
        Route::post('/student/transfer/save', [StudentController::class,'stTransfer'])->name('operationmanager.stTransfer');

        /*Course Wise Enroll */
        Route::post('/course/wise/enroll', [StudentController::class,'courseEnroll'])->name('operationmanager.courseEnroll');

        /*==Batch Transfer==*/
        Route::get('/student/batch/transfer/list', [StudentController::class,'batchTransferList'])->name('operationmanager.batchTransferList');
        Route::get('/student/batch/transfer', [StudentController::class,'batchTransfer'])->name('operationmanager.batchTransfer');
        Route::get('/student/batch/enroll', [StudentController::class,'studentEnrollBatch'])->name('operationmanager.studentEnrollBatch');
        Route::post('/student/transfer', [StudentController::class,'transfer'])->name('operationmanager.transfer');

        /*===Report Data===*/
        Route::get('/batch/wise/enroll', [ReportController::class,'batchwiseEnrollStudent'])->name('operationmanager.batchwiseEnrollStudent');
        Route::post('/batch/wise/enroll', [ReportController::class,'batchwiseEnrollStudent'])->name('operationmanager.batchwiseEnrollStudent');
        
        /*==Batch Transfer==*/
        Route::get('/student/batch/transfer', [StudentController::class,'batchTransfer'])->name('operationmanager.batchTransfer');
        Route::get('/student/batch/enroll', [StudentController::class,'studentEnrollBatch'])->name('operationmanager.studentEnrollBatch');
        Route::post('/student/transfer', [StudentController::class,'transfer'])->name('operationmanager.transfer');

        /*===Payment report==*/
        Route::get('/daily/collection/report',[PaymentReportController::class,'daily_collection_report'])->name('operationmanager.daily_collection_report');
        Route::get('/daily/collection/report/mr',[PaymentReportController::class,'daily_collection_report_by_mr'])->name('operationmanager.daily_collection_report_by_mr');

        /*Attendance Report */
        Route::get('/batch/wise/attendance', [ReportController::class,'batchwiseAttendance'])->name('operationmanager.batchwiseAttendance');
        Route::get('/batch/wise/attendance/report', [ReportController::class,'batchwiseAttendanceReport'])->name('operationmanager.batchwiseAttendanceReport');
        
        /*=== Course Report= ==*/
        Route::get('/course/wise/report', [ReportController::class,'coursewiseStudent'])->name('operationmanager.coursewiseStudent');
        Route::post('/course/wise/report', [ReportController::class,'coursewiseStudent'])->name('operationmanager.coursewiseStudent');


    });
});

// Accounts Manager
Route::group(['middleware' => 'isAccountmanager'], function(){
    Route::prefix('accounts-manager')->group(function () {
        Route::get('/', [DashboardController::class,'accountmanager']);
        Route::get('/dashboard', [DashboardController::class,'accountmanager'])->name('accountmanagerDashboard');
       
        Route::get('/profile', [UserController::class,'userProfile'])->name('accountmanager.userProfile');
        Route::post('/profile', [UserController::class,'storeProfile'])->name('accountmanager.storeProfile');
        Route::post('/changePass', [UserController::class,'changePass'])->name('accountmanager.changePass');
        Route::post('/changePer', [UserController::class,'changePer'])->name('accountmanager.changePer');
        Route::post('/changeAcc', [UserController::class,'changeAcc'])->name('accountmanager.changeAcc');

        Route::prefix('student')->group(function () {
            //Student Controller
            //Route::get('/all',  [StudentController::class,'confirmStudents'])->name('accountmanager.allStudent');
            Route::get('/student/enroll/details/{id}',  [StudentController::class,'studentenrollById'])->name('accountmanager.studentenrollById');
            Route::get('/payment/{id}/{entryDate}',  [StudentController::class,'paymentStudent'])->name('accountmanager.paymentStudent');
        });
        Route::resource('/batch',BatchController::class,["as" => "accountmanager"])->only(['index']);

        /*====Batch Payment====*/
        Route::get('/payment/invoice',[PaymentController::class,'searchStData'])->name('accountmanager.searchStData');
        Route::get('/payment/enroll',[PaymentController::class,'enrollData'])->name('accountmanager.enrollData');
        Route::get('/payment/databySystemId',[PaymentController::class,'databySystemId'])->name('accountmanager.databySystemId');
        Route::get('/payment/data',[PaymentController::class,'paymentData'])->name('accountmanager.paymentData');
        
        Route::resource('/payment',PaymentController::class,["as" => "accountmanager"]);
        
        /*=== Payment Edit====*/
        Route::get('/payment/report/{id}/{sId}',[PaymentController::class,'edit'])->name('accountmanager.payment.edit');
        /*===Payment report==*/
        Route::get('/payment/report/all',[PaymentReportController::class,'allPaymentReportBySid'])->name('accountmanager.allPaymentReportBySid');
        Route::get('/daily/collection/report',[PaymentReportController::class,'daily_collection_report'])->name('accountmanager.daily_collection_report');
        Route::get('/daily/collection/report/mr',[PaymentReportController::class,'daily_collection_report_by_mr'])->name('accountmanager.daily_collection_report_by_mr');


        

        /*===Report Data===*/
        Route::get('/batch/wise/enroll', [ReportController::class,'batchwiseEnrollStudent'])->name('accountmanager.batchwiseEnrollStudent');
        Route::post('/batch/wise/enroll', [ReportController::class,'batchwiseEnrollStudent'])->name('accountmanager.batchwiseEnrollStudent');


        /*===Other Payment===*/
        Route::prefix('other')->name('accountmanager.')->group(function () {
            Route::resource('payments', OtherPaymentController::class);

            Route::get('/payment/invoice/search',[OtherPaymentController::class,'searchStudent'])->name('searchStudent');
            Route::get('/payment/invoice/enroll',[OtherPaymentController::class,'stData'])->name('stData');
            Route::get('/payment/invoice/databyStudentId',[OtherPaymentController::class,'databyStudentId'])->name('databyStudentId');
            Route::get('/payment/invoice/other',[OtherPaymentController::class,'otherPaymentByStudentId'])->name('otherPaymentByStudentId');
            Route::get('/payment/invoice/course',[OtherPaymentController::class,'coursePaymentByStudentId'])->name('coursePaymentByStudentId');
            Route::post('/payment/course/',[OtherPaymentController::class,'coursestore'])->name('payments.coursestore');
        });
        
        Route::resource('/payment-transfer',PaymentTransferController::class,["as" => "accountmanager"]);

    });
});

// Training Manager
Route::group(['middleware' => 'isTrainingmanager'], function(){
    Route::prefix('training-manager')->group(function () {
        Route::get('/', [DashboardController::class,'trainingmanager']);
        Route::get('/dashboard', [DashboardController::class,'trainingmanager'])->name('trainingmanagerDashboard');
       
        Route::get('/profile', [UserController::class,'userProfile'])->name('trainingmanager.userProfile');
        Route::post('/profile', [UserController::class,'storeProfile'])->name('trainingmanager.storeProfile');
        Route::post('/changePass', [UserController::class,'changePass'])->name('trainingmanager.changePass');
        Route::post('/changePer', [UserController::class,'changePer'])->name('trainingmanager.changePer');
        Route::post('/changeAcc', [UserController::class,'changeAcc'])->name('trainingmanager.changeAcc');
      
    });
});

// Trainer
Route::group(['middleware' => 'isTrainer'], function(){
    Route::prefix('trainer')->group(function () {
        Route::get('/', [DashboardController::class,'trainer']);
        Route::get('/dashboard', [DashboardController::class,'trainer'])->name('trainerDashboard');
       
        Route::get('/profile', [UserController::class,'userProfile'])->name('trainer.userProfile');
        Route::post('/profile', [UserController::class,'storeProfile'])->name('trainer.storeProfile');
        Route::post('/changePass', [UserController::class,'changePass'])->name('trainer.changePass');
        Route::post('/changePer', [UserController::class,'changePer'])->name('trainer.changePer');
        Route::post('/changeAcc', [UserController::class,'changeAcc'])->name('trainer.changeAcc');
      
    });
});