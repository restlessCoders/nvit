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
use App\Http\Controllers\BundelCourseController;
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
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\RefundController;
use App\Http\Controllers\CertificateController;
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

Route::group(['middleware' => 'unknownUser'], function () {
    // Sign In, Login
    Route::get('/', [AuthenticationController::class, 'signInForm'])->name('signInForm');
    Route::get('/sign-in', [AuthenticationController::class, 'signInForm'])->name('signInForm');
    Route::get('/login', [AuthenticationController::class, 'signInForm'])->name('signInForm');

    Route::post('/sign-in', [AuthenticationController::class, 'signIn'])->name('logIn');
    Route::post('/login', [AuthenticationController::class, 'signIn'])->name('logIn');

    // Sign Up, Registration
    Route::get('/sign-up', [AuthenticationController::class, 'signUpForm'])->name('signUpForm');
    Route::get('/register', [AuthenticationController::class, 'signUpForm'])->name('signUpForm');
    Route::get('/registration', [AuthenticationController::class, 'signUpForm'])->name('signUpForm');
    Route::post('/registered', [AuthenticationController::class, 'signUpStore'])->name('signUp');


    // Forgot Password
    Route::get('/forgot', [AuthenticationController::class, 'forgotForm'])->name('forgotPasswordForm');
    Route::get('/forgot-pass', [AuthenticationController::class, 'forgotForm'])->name('forgotPasswordForm');
    Route::get('/forgot-password', [AuthenticationController::class, 'forgotForm'])->name('forgotPasswordForm');

    Route::post('/forgot-password', [AuthenticationController::class, 'forgotPassword'])->name('forgotPassword');

    // Reset Password
    Route::get('/reset-password', [AuthenticationController::class, 'resetPasswordForm'])->name('resetPasswordForm');
    Route::post('/reset-password', [AuthenticationController::class, 'resetPassword'])->name('resetPassword');
});
Route::get('/sign-out', [AuthenticationController::class, 'signOut'])->name('logOut');
Route::get('/logout', [AuthenticationController::class, 'signOut'])->name('logOut');

// Super Admin
Route::group(['middleware' => 'isSuperAdmin'], function () {
    Route::prefix('superadmin')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('superadminDashboard');

        Route::prefix('user')->group(function () {
            Route::get('/all', [UserController::class, 'index'])->name('superadmin.allUser');
            Route::get('/add', [UserController::class, 'addForm'])->name('superadmin.addNewUserForm');
            Route::post('/add', [UserController::class, 'store'])->name('superadmin.addNewUser');
            Route::get('/edit/{name}/{id}', [UserController::class, 'editForm'])->name('superadmin.editUser');
            Route::post('/update', [UserController::class, 'update'])->name('superadmin.updateUser');
            Route::get('/delete/{name}/{id}', [UserController::class, 'UserController@delete'])->name('superadmin.deleteUser');
        });
        Route::get('/profile', [UserController::class, 'userProfile'])->name('superadmin.userProfile');
        Route::post('/profile', [UserController::class, 'storeProfile'])->name('superadmin.storeProfile');
        Route::post('/changePass', [UserController::class, 'changePass'])->name('superadmin.changePass');
        Route::post('/changePer', [UserController::class, 'changePer'])->name('superadmin.changePer');
        Route::post('/changeAcc', [UserController::class, 'changeAcc'])->name('superadmin.changeAcc');



        Route::prefix('student')->group(function () {
            //Student Controller
            Route::get('/all',  [StudentController::class, 'index'])->name('superadmin.allStudent');
            Route::get('/add', [StudentController::class, 'addForm'])->name('superadmin.addNewStudentForm');
            Route::post('/add', [StudentController::class, 'store'])->name('superadmin.addNewStudent');
            Route::get('/edit/{id}', [StudentController::class, 'editForm'])->name('superadmin.editStudent');
            Route::post('/update/{id}', [StudentController::class, 'update'])->name('superadmin.updateStudent');
            Route::put('/dump/{id}', [StudentController::class, 'dump'])->name('superadmin.dumpStudent');
            Route::put('/active/{id}', [StudentController::class, 'active'])->name('superadmin.activeStudent');
            Route::get('/course/assign/{id}', [StudentController::class, 'studentCourseAssign'])->name('superadmin.studentCourseAssign');
            Route::post('/course/assign/{id}', [StudentController::class, 'addstudentCourseAssign'])->name('superadmin.addstudentCourseAssign');
            Route::delete('/enroll/delete/{id}', [StudentController::class, 'deleteEnroll'])->name('superadmin.enrollment.destroy');
        });

        Route::resource('/notes', NoteController::class, ["as" => "superadmin"]);

        Route::get('/batch/all', [BatchController::class, 'all'])->name('superadmin.allBatches');
        Route::resource('/batch', BatchController::class, ["as" => "superadmin"]);
        Route::get('/batchById', [BatchController::class, 'batchById'])->name('superadmin.batchById');

        /*==Student Transfer==*/
        Route::get('/student/transfer/list', [StudentController::class, 'studentTransferList'])->name('superadmin.studentTransferList');
        Route::get('/student/transfer', [StudentController::class, 'studentTransfer'])->name('superadmin.studentTransfer');
        Route::get('/student/executive', [StudentController::class, 'studentExecutive'])->name('superadmin.studentExecutive');
        Route::post('/student/transfer/save', [StudentController::class, 'stTransfer'])->name('superadmin.stTransfer');




        /*==Batch Transfer==*/
        Route::get('/student/batch/transfer/list', [StudentController::class, 'batchTransferList'])->name('superadmin.batchTransferList');
        Route::get('/student/batch/transfer', [StudentController::class, 'batchTransfer'])->name('superadmin.batchTransfer');
        Route::get('/student/batch/enroll', [StudentController::class, 'studentEnrollBatch'])->name('superadmin.studentEnrollBatch');
        Route::post('/student/transfer', [StudentController::class, 'transfer'])->name('superadmin.transfer');

        /*Course Wise Enroll */
        Route::post('/course/wise/enroll', [StudentController::class, 'courseEnroll'])->name('superadmin.courseEnroll');
        Route::post('/course/wise/enroll/update', [StudentController::class, 'courseEnrollUpdate'])->name('superadmin.courseEnrollUpdate');
        Route::delete('/course/wise/enroll/delete/{id}', [StudentController::class, 'courseEnrollDelete'])->name('superadmin.courseEnrollDelete');

        Route::get('/get-courses', [CourseController::class, 'get_courses'])->name('superadmin.get_courses');
        Route::get('/get-slots', [BatchSlotController::class, 'get_batchslot'])->name('superadmin.get_batchslot');
        Route::get('/get/batchtime', [BatchTimeController::class, 'get_batchtime'])->name('superadmin.get_batchtime');

        /*Course Preference */
        Route::post('/course/preference/', [StudentController::class, 'coursePreference'])->name('superadmin.coursePreference');
        Route::post('/course/preference/edit/{id}', [StudentController::class, 'coursePreferencEdit'])->name('superadmin.coursePreferencEdit');

        /*==Course Search==*/
        Route::post('/course/search', [CourseController::class, 'courseSearch'])->name('superadmin.courseSearch');
        Route::post('/batch/search', [BatchController::class, 'batchSearch'])->name('superadmin.batchSearch');
        Route::post('/package/search', [PackageController::class, 'packageSearch'])->name('superadmin.packageSearch');

        Route::resource('/package', PackageController::class, ["as" => "superadmin"]);
        Route::resource('/batch', BatchController::class, ["as" => "superadmin"]);
        Route::resource('/reference', ReferenceController::class, ["as" => "superadmin"]);

        Route::resource('/course', CourseController::class, ["as" => "superadmin"]);
        Route::resource('/bundelcourse', BundelCourseController::class, ["as" => "superadmin"]);
        Route::resource('/classroom', ClassRoomController::class, ["as" => "superadmin"]);
        Route::resource('/batchtime', BatchtimeController::class, ["as" => "superadmin"]);
        Route::resource('/batchslot', BatchslotController::class, ["as" => "superadmin"]);
        Route::resource('/division', DivisionController::class, ["as" => "superadmin"]);
        Route::resource('/district', DistrictController::class, ["as" => "superadmin"]);
        Route::resource('/upazila', UpazilaController::class, ["as" => "superadmin"]);
        Route::resource('/payment', PaymentController::class, ["as" => "superadmin"]);

        /*===Report Data===*/
        Route::get('/batch/wise/enroll', [ReportController::class, 'batchwiseEnrollStudent'])->name('superadmin.batchwiseEnrollStudent');
        Route::get('/batch/edit/enroll/{id}', [ReportController::class, 'editEnrollStudent'])->name('superadmin.editEnrollStudent');
        Route::post('/batch/wise/enroll', [ReportController::class, 'batchwiseEnrollStudent'])->name('superadmin.batchwiseEnrollStudent');

        /*===Payment report==*/
        Route::get('/daily/collection/report', [PaymentReportController::class, 'daily_collection_report'])->name('superadmin.daily_collection_report');
        Route::get('/daily/collection/report/mr', [PaymentReportController::class, 'daily_collection_report_by_mr'])->name('superadmin.daily_collection_report_by_mr');
        

        /*Attendance Report */
        Route::get('/batch/wise/attendance', [ReportController::class, 'batchwiseAttendance'])->name('superadmin.batchwiseAttendance');
        Route::get('/batch/wise/attendance/report', [ReportController::class, 'batchwiseAttendanceReport'])->name('superadmin.batchwiseAttendanceReport');

        /*=== Course Report= ==*/
        Route::get('/course/wise/report', [ReportController::class, 'coursewiseStudent'])->name('superadmin.coursewiseStudent');
        Route::post('/course/wise/report', [ReportController::class, 'coursewiseStudent'])->name('superadmin.coursewiseStudent');
        /*Course Enroll Report */
        Route::get('/course/wise/enroll/list', [ReportController::class, 'coursewiseEnrollStudent'])->name('superadmin.coursewiseEnrollStudent');
        Route::post('/course/wise/enroll/list', [ReportController::class, 'coursewiseEnrollStudent'])->name('superadmin.coursewiseEnrollStudent');


        /*Note History */
        Route::get('/note/history', [NoteController::class, 'note_by_student_id'])->name('superadmin.noteHistoryByStId');

        /*Payment Report */
        Route::get('/payment/report/all', [PaymentReportController::class, 'allPaymentReportBySid'])->name('superadmin.allPaymentReportBySid');
        Route::get('/payment/report/course/all', [PaymentReportController::class, 'allPaymentCourseReportBySid'])->name('superadmin.allPaymentCourseReportBySid');
        
        /* Refund*/
        Route::resource('/refund', RefundController::class, ["as" => "superadmin"]);
        /*Chart Data */
        //Route::get('/chart-data', [DashboardController::class, 'chartData'])->name('superadmin.chartData');

        /*Transaction  */
        Route::resource('/transaction', TransactionController::class, ["as" => "superadmin"]);

        /*Batch Completion Report */
        Route::get('/batch/wise/completion', [ReportController::class, 'batchwiseCompletion'])->name('superadmin.batchwiseCompletion');
        Route::get('/batch/wise/completion/report', [ReportController::class, 'batchwiseCompletionReport'])->name('superadmin.batchwiseCompletionReport');

        /*=== Withdraw | Drop ===*/
        Route::get('/student/batch/withdraw/', [StudentController::class, 'withdraw'])->name('superadmin.withdraw');
        Route::get('/student/batch/undo/withdraw/', [StudentController::class, 'withdraw_undo'])->name('superadmin.withdraw_undo');

        /*== Secret Login ==*/
        Route::get('secret/login/{id}', [UserController::class, 'secretLogin'])->name('superadmin.secretLogin');

        /*Certificate Controller */
        Route::resource('/certificate', CertificateController::class, ["as" => "superadmin"]);
    });
});

// Frontdesk|dataentry
Route::group(['middleware' => 'isFrontdesk'], function () {
    Route::prefix('frontdesk')->group(function () {
        Route::get('/', [DashboardController::class, 'frontdesk']);
        Route::get('/dashboard', [DashboardController::class, 'frontdesk'])->name('frontdeskDashboard');

        Route::get('/profile', [UserController::class, 'userProfile'])->name('frontdesk.userProfile');
        Route::post('/profile', [UserController::class, 'storeProfile'])->name('frontdesk.storeProfile');
        Route::post('/changePass', [UserController::class, 'changePass'])->name('frontdesk.changePass');
        Route::post('/changePer', [UserController::class, 'changePer'])->name('frontdesk.changePer');
        Route::post('/changeAcc', [UserController::class, 'changeAcc'])->name('frontdesk.changeAcc');

        Route::prefix('student')->group(function () {
            //Student Controller
            Route::get('/add', [StudentController::class, 'addForm'])->name('frontdesk.addNewStudentForm');
            Route::post('/add', [StudentController::class, 'store'])->name('frontdesk.addNewStudent');
            Route::get('/all',  [StudentController::class, 'index'])->name('frontdesk.allStudent');
        });
        Route::resource('/batch', BatchController::class, ["as" => "frontdesk"])->only(['index']);
        Route::post('/batch/search', [BatchController::class, 'batchSearch'])->name('frontdesk.batchSearch');

        /*===Report Data===*/
        Route::get('/batch/wise/enroll', [ReportController::class, 'batchwiseEnrollStudent'])->name('frontdesk.batchwiseEnrollStudent');
        Route::post('/batch/wise/enroll', [ReportController::class, 'batchwiseEnrollStudent'])->name('frontdesk.batchwiseEnrollStudent');

        /*Payment Report */
        Route::get('/payment/report/all', [PaymentReportController::class, 'allPaymentReportBySid'])->name('frontdesk.allPaymentReportBySid');
        Route::get('/payment/report/course/all', [PaymentReportController::class, 'allPaymentCourseReportBySid'])->name('frontdesk.allPaymentCourseReportBySid');
    });
});

// Sales Manager
Route::group(['middleware' => 'isSalesManager'], function () {
    Route::prefix('sales-manager')->group(function () {
        Route::get('/', [DashboardController::class, 'salesManager']);
        Route::get('/dashboard', [DashboardController::class, 'salesManager'])->name('salesmanagerDashboard');

        Route::prefix('user')->group(function () {
            Route::get('/all', [UserController::class, 'index'])->name('salesmanager.allUser');
            Route::get('/add', [UserController::class, 'addForm'])->name('salesmanager.addNewUserForm');
            Route::post('/add', [UserController::class, 'store'])->name('salesmanager.addNewUser');
            Route::get('/edit/{name}/{id}', [UserController::class, 'editForm'])->name('salesmanager.editUser');
            Route::post('/update', [UserController::class, 'update'])->name('salesmanager.updateUser');
            Route::get('/delete/{name}/{id}', [UserController::class, 'UserController@delete'])->name('salesmanager.deleteUser');
        });

        Route::get('/profile', [UserController::class, 'userProfile'])->name('salesmanager.userProfile');
        Route::post('/profile', [UserController::class, 'storeProfile'])->name('salesmanager.storeProfile');
        Route::post('/changePass', [UserController::class, 'changePass'])->name('salesmanager.changePass');
        Route::post('/changePer', [UserController::class, 'changePer'])->name('salesmanager.changePer');
        Route::post('/changeAcc', [UserController::class, 'changeAcc'])->name('salesmanager.changeAcc');

        Route::prefix('student')->group(function () {
            //Student Controller
            Route::get('/all',  [StudentController::class, 'index'])->name('salesmanager.allStudent');
            Route::get('/add', [StudentController::class, 'addForm'])->name('salesmanager.addNewStudentForm');
            Route::post('/add', [StudentController::class, 'store'])->name('salesmanager.addNewStudent');
            Route::get('/edit/{id}', [StudentController::class, 'editForm'])->name('salesmanager.editStudent');
            Route::post('/update/{id}', [StudentController::class, 'update'])->name('salesmanager.updateStudent');
            Route::put('/dump/{id}', [StudentController::class, 'dump'])->name('salesmanager.dumpStudent');
            Route::put('/active/{id}', [StudentController::class, 'active'])->name('salesmanager.activeStudent');
            Route::get('/course/assign/{id}', [StudentController::class, 'studentCourseAssign'])->name('salesmanager.studentCourseAssign');
            Route::post('/course/assign/{id}', [StudentController::class, 'addstudentCourseAssign'])->name('salesmanager.addstudentCourseAssign');
            Route::delete('/enroll/delete/{id}', [StudentController::class, 'deleteEnroll'])->name('salesmanager.enrollment.destroy');
        });

        Route::resource('/notes', NoteController::class, ["as" => "salesmanager"]);

        Route::get('/batch/all', [BatchController::class, 'all'])->name('salesmanager.allBatches');
        Route::resource('/batch', BatchController::class, ["as" => "salesmanager"])->only(['index']);
        Route::get('/batchById', [BatchController::class, 'batchById'])->name('salesmanager.batchById');

        Route::resource('/package', PackageController::class, ["as" => "salesmanager"]);
        Route::resource('/batch', BatchController::class, ["as" => "salesmanager"]);
        Route::resource('/reference', ReferenceController::class, ["as" => "salesmanager"]);

        Route::resource('/course', CourseController::class, ["as" => "salesmanager"]);
        Route::resource('/bundelcourse', BundelCourseController::class, ["as" => "salesmanager"]);
        Route::resource('/classroom', ClassRoomController::class, ["as" => "salesmanager"]);
        Route::resource('/batchtime', BatchtimeController::class, ["as" => "salesmanager"]);
        Route::resource('/batchslot', BatchslotController::class, ["as" => "salesmanager"]);
        Route::resource('/division', DivisionController::class, ["as" => "salesmanager"]);
        Route::resource('/district', DistrictController::class, ["as" => "salesmanager"]);
        Route::resource('/upazila', UpazilaController::class, ["as" => "salesmanager"]);


        /*Course Preference */
        Route::post('/course/preference/', [StudentController::class, 'coursePreference'])->name('salesmanager.coursePreference');
        Route::post('/course/preference/edit/{id}', [StudentController::class, 'coursePreferencEdit'])->name('salesmanager.coursePreferencEdit');

        /*==Course Search==*/
        Route::post('/course/search', [CourseController::class, 'courseSearch'])->name('salesmanager.courseSearch');
        Route::post('/batch/search', [BatchController::class, 'batchSearch'])->name('salesmanager.batchSearch');
        Route::post('/package/search', [PackageController::class, 'packageSearch'])->name('salesmanager.packageSearch');

        /*Course Wise Enroll */
        Route::post('/course/wise/enroll', [StudentController::class, 'courseEnroll'])->name('salesmanager.courseEnroll');
        Route::post('/course/wise/enroll/update', [StudentController::class, 'courseEnrollUpdate'])->name('salesmanager.courseEnrollUpdate');
        Route::delete('/course/wise/enroll/delete/{id}', [StudentController::class, 'courseEnrollDelete'])->name('salesmanager.courseEnrollDelete');

        /*===Report Data===*/
        Route::get('/batch/wise/enroll', [ReportController::class, 'batchwiseEnrollStudent'])->name('salesmanager.batchwiseEnrollStudent');
        Route::post('/batch/wise/enroll', [ReportController::class, 'batchwiseEnrollStudent'])->name('salesmanager.batchwiseEnrollStudent');

        /*===Payment report==*/
        Route::get('/daily/collection/report', [PaymentReportController::class, 'daily_collection_report'])->name('salesmanager.daily_collection_report');
        Route::get('/daily/collection/report/mr', [PaymentReportController::class, 'daily_collection_report_by_mr'])->name('salesmanager.daily_collection_report_by_mr');

        /*=== Course Report= ==*/
        Route::get('/course/wise/report', [ReportController::class, 'coursewiseStudent'])->name('salesmanager.coursewiseStudent');
        Route::post('/course/wise/report', [ReportController::class, 'coursewiseStudent'])->name('salesmanager.coursewiseStudent');
        /*Course Enroll Report */
        Route::get('/course/wise/enroll/list', [ReportController::class, 'coursewiseEnrollStudent'])->name('salesmanager.coursewiseEnrollStudent');
        Route::post('/course/wise/enroll/list', [ReportController::class, 'coursewiseEnrollStudent'])->name('salesmanager.coursewiseEnrollStudent');

        /*Attendance Report */
        Route::get('/batch/wise/attendance', [ReportController::class, 'batchwiseAttendance'])->name('salesmanager.batchwiseAttendance');
        Route::get('/batch/wise/attendance/report', [ReportController::class, 'batchwiseAttendanceReport'])->name('salesmanager.batchwiseAttendanceReport');

        /*Note History */
        Route::get('/note/history', [NoteController::class, 'note_by_student_id'])->name('salesmanager.noteHistoryByStId');
        /*Payment Report */
        Route::get('/payment/report/all', [PaymentReportController::class, 'allPaymentReportBySid'])->name('salesmanager.allPaymentReportBySid');
        Route::get('/payment/report/course/all', [PaymentReportController::class, 'allPaymentCourseReportBySid'])->name('salesmanager.allPaymentCourseReportBySid');

        /*=== Withdraw | Drop ===*/
        Route::get('/student/batch/withdraw/', [StudentController::class, 'withdraw'])->name('salesmanager.withdraw');
        Route::get('/student/batch/undo/withdraw/', [StudentController::class, 'withdraw_undo'])->name('salesmanager.withdraw_undo');

        /*Certificate Controller */
        Route::resource('/certificate', CertificateController::class, ["as" => "salesmanager"]);
    });
});

// Sales Executive
Route::group(['middleware' => 'isSalesExecutive'], function () {
    Route::prefix('sales-executive')->group(function () {
        Route::get('/', [DashboardController::class, 'salesExecutive']);
        Route::get('/dashboard', [DashboardController::class, 'salesExecutive'])->name('salesexecutiveDashboard');

        Route::get('/profile', [UserController::class, 'userProfile'])->name('salesexecutive.userProfile');
        Route::post('/profile', [UserController::class, 'storeProfile'])->name('salesexecutive.storeProfile');
        Route::post('/changePass', [UserController::class, 'changePass'])->name('salesexecutive.changePass');
        Route::post('/changePer', [UserController::class, 'changePer'])->name('salesexecutive.changePer');
        Route::post('/changeAcc', [UserController::class, 'changeAcc'])->name('salesexecutive.changeAcc');

        Route::prefix('student')->group(function () {
            //Student Controller
            Route::get('/all',  [StudentController::class, 'index'])->name('salesexecutive.allStudent');
            Route::get('/add', [StudentController::class, 'addForm'])->name('salesexecutive.addNewStudentForm');
            Route::post('/add', [StudentController::class, 'store'])->name('salesexecutive.addNewStudent');
            Route::get('/edit/{id}', [StudentController::class, 'editForm'])->name('salesexecutive.editStudent');
            Route::post('/update/{id}', [StudentController::class, 'update'])->name('salesexecutive.updateStudent');
            Route::put('/dump/{id}', [StudentController::class, 'dump'])->name('salesexecutive.dumpStudent');
            Route::put('/active/{id}', [StudentController::class, 'active'])->name('salesexecutive.activeStudent');
            Route::get('/course/assign/{id}', [StudentController::class, 'studentCourseAssign'])->name('salesexecutive.studentCourseAssign');
            Route::post('/course/assign/{id}', [StudentController::class, 'addstudentCourseAssign'])->name('salesexecutive.addstudentCourseAssign');
            Route::delete('/enroll/delete/{id}', [StudentController::class, 'deleteEnroll'])->name('salesexecutive.enrollment.destroy');
        });

        Route::resource('/notes', NoteController::class, ["as" => "salesexecutive"]);

        Route::resource('/bundelcourse', BundelCourseController::class, ["as" => "salesexecutive"])->only(['index']);

        Route::resource('/course', CourseController::class, ["as" => "salesexecutive"])->only(['index']);
        Route::resource('/batch', BatchController::class, ["as" => "salesexecutive"])->only(['index']);
        Route::resource('/package', PackageController::class, ["as" => "salesexecutive"])->only(['index']);

        Route::get('/batch/all', [BatchController::class, 'all'])->name('salesexecutive.allBatches');
        Route::resource('/batch', BatchController::class, ["as" => "salesexecutive"])->only(['index']);
        Route::get('/batchById', [BatchController::class, 'batchById'])->name('salesexecutive.batchById');

        /*===Report Data===*/
        Route::get('/batch/wise/enroll', [ReportController::class, 'batchwiseEnrollStudent'])->name('salesexecutive.batchwiseEnrollStudent');
        Route::post('/batch/wise/enroll', [ReportController::class, 'batchwiseEnrollStudent'])->name('salesexecutive.batchwiseEnrollStudent');

        /*Course Preference */
        Route::post('/course/preference/', [StudentController::class, 'coursePreference'])->name('salesexecutive.coursePreference');
        Route::post('/course/preference/edit/{id}', [StudentController::class, 'coursePreferencEdit'])->name('salesexecutive.coursePreferencEdit');

        /*==Course Search==*/
        Route::post('/course/search', [CourseController::class, 'courseSearch'])->name('salesexecutive.courseSearch');
        Route::post('/batch/search', [BatchController::class, 'batchSearch'])->name('salesexecutive.batchSearch');
        Route::post('/package/search', [PackageController::class, 'packageSearch'])->name('salesexecutive.packageSearch');

        /*Course Wise Enroll */
        Route::post('/course/wise/enroll', [StudentController::class, 'courseEnroll'])->name('salesexecutive.courseEnroll');
        Route::post('/course/wise/enroll/update', [StudentController::class, 'courseEnrollUpdate'])->name('salesexecutive.courseEnrollUpdate');
        Route::delete('/course/wise/enroll/delete/{id}', [StudentController::class, 'courseEnrollDelete'])->name('salesexecutive.courseEnrollDelete');

        /*===Payment report==*/
        Route::get('/daily/collection/report', [PaymentReportController::class, 'daily_collection_report'])->name('salesexecutive.daily_collection_report');
        Route::get('/daily/collection/report/mr', [PaymentReportController::class, 'daily_collection_report_by_mr'])->name('salesexecutive.daily_collection_report_by_mr');

        /*=== Course Report= ==*/
        Route::get('/course/wise/report', [ReportController::class, 'coursewiseStudent'])->name('salesexecutive.coursewiseStudent');
        Route::post('/course/wise/report', [ReportController::class, 'coursewiseStudent'])->name('salesexecutive.coursewiseStudent');
        /*Course Enroll Report */
        Route::get('/course/wise/enroll/list', [ReportController::class, 'coursewiseEnrollStudent'])->name('salesexecutive.coursewiseEnrollStudent');
        Route::post('/course/wise/enroll/list', [ReportController::class, 'coursewiseEnrollStudent'])->name('salesexecutive.coursewiseEnrollStudent');

        /*Note History */
        Route::get('/note/history', [NoteController::class, 'note_by_student_id'])->name('salesexecutive.noteHistoryByStId');
        /*Payment Report */
        Route::get('/payment/report/all', [PaymentReportController::class, 'allPaymentReportBySid'])->name('salesexecutive.allPaymentReportBySid');
        Route::get('/payment/report/course/all', [PaymentReportController::class, 'allPaymentCourseReportBySid'])->name('salesexecutive.allPaymentCourseReportBySid');

        /*Certificate Controller */
        Route::resource('/certificate', CertificateController::class, ["as" => "salesexecutive"]);
    });
});


// Operation Manager
Route::group(['middleware' => 'isOperationmanager'], function () {
    Route::prefix('operation-manager')->group(function () {
        Route::get('/', [DashboardController::class, 'operationgmanager']);
        Route::get('/dashboard', [DashboardController::class, 'operationmanager'])->name('operationmanagerDashboard');

        Route::prefix('user')->group(function () {
            Route::get('/all', [UserController::class, 'index'])->name('operationmanager.allUser');
            Route::get('/add', [UserController::class, 'addForm'])->name('operationmanager.addNewUserForm');
            Route::post('/add', [UserController::class, 'store'])->name('operationmanager.addNewUser');
            Route::get('/edit/{name}/{id}', [UserController::class, 'editForm'])->name('operationmanager.editUser');
            Route::post('/update', [UserController::class, 'update'])->name('operationmanager.updateUser');
            Route::get('/delete/{name}/{id}', [UserController::class, 'UserController@delete'])->name('operationmanager.deleteUser');
        });

        Route::get('/profile', [UserController::class, 'userProfile'])->name('operationmanager.userProfile');
        Route::post('/profile', [UserController::class, 'storeProfile'])->name('operationmanager.storeProfile');
        Route::post('/changePass', [UserController::class, 'changePass'])->name('operationmanager.changePass');
        Route::post('/changePer', [UserController::class, 'changePer'])->name('operationmanager.changePer');
        Route::post('/changeAcc', [UserController::class, 'changeAcc'])->name('operationmanager.changeAcc');

        Route::prefix('student')->group(function () {
            //Student Controller
            Route::get('/all',  [StudentController::class, 'index'])->name('operationmanager.allStudent');
            Route::get('/add', [StudentController::class, 'addForm'])->name('operationmanager.addNewStudentForm');
            Route::post('/add', [StudentController::class, 'store'])->name('operationmanager.addNewStudent');
            Route::get('/edit/{id}', [StudentController::class, 'editForm'])->name('operationmanager.editStudent');
            Route::post('/update/{id}', [StudentController::class, 'update'])->name('operationmanager.updateStudent');
            Route::put('/dump/{id}', [StudentController::class, 'dump'])->name('operationmanager.dumpStudent');
            Route::put('/active/{id}', [StudentController::class, 'active'])->name('operationmanager.activeStudent');
            Route::get('/course/assign/{id}', [StudentController::class, 'studentCourseAssign'])->name('operationmanager.studentCourseAssign');
            Route::post('/course/assign/{id}', [StudentController::class, 'addstudentCourseAssign'])->name('operationmanager.addstudentCourseAssign');
            Route::delete('/enroll/delete/{id}', [StudentController::class, 'deleteEnroll'])->name('operationmanager.enrollment.destroy');
        });

        Route::resource('/notes', NoteController::class, ["as" => "operationmanager"]);


        Route::resource('/package', PackageController::class, ["as" => "operationmanager"]);
        Route::resource('/reference', ReferenceController::class, ["as" => "operationmanager"]);

        Route::get('/batch/all', [BatchController::class, 'all'])->name('operationmanager.allBatches');
        Route::resource('/batch', BatchController::class, ["as" => "operationmanager"]);
        Route::get('/batchById', [BatchController::class, 'batchById'])->name('operationmanager.batchById');

        Route::resource('/course', CourseController::class, ["as" => "operationmanager"]);

        /*Course Preference */
        Route::post('/course/preference/', [StudentController::class, 'coursePreference'])->name('operationmanager.coursePreference');
        Route::post('/course/preference/edit/{id}', [StudentController::class, 'coursePreferencEdit'])->name('operationmanager.coursePreferencEdit');

        /*==Course Search==*/
        Route::post('/course/search', [CourseController::class, 'courseSearch'])->name('operationmanager.courseSearch');
        Route::post('/batch/search', [BatchController::class, 'batchSearch'])->name('operationmanager.batchSearch');
        Route::post('/package/search', [PackageController::class, 'packageSearch'])->name('operationmanager.packageSearch');

        Route::resource('/classroom', ClassRoomController::class, ["as" => "operationmanager"]);
        Route::resource('/bundelcourse', BundelCourseController::class, ["as" => "operationmanager"]);
        Route::resource('/batchtime', BatchtimeController::class, ["as" => "operationmanager"]);
        Route::resource('/batchslot', BatchslotController::class, ["as" => "operationmanager"]);
        Route::resource('/division', DivisionController::class, ["as" => "operationmanager"]);
        Route::resource('/district', DistrictController::class, ["as" => "operationmanager"]);
        Route::resource('/upazila', UpazilaController::class, ["as" => "operationmanager"]);

        /*==Student Transfer==*/
        Route::get('/student/transfer/list', [StudentController::class, 'studentTransferList'])->name('operationmanager.studentTransferList');
        Route::get('/student/transfer', [StudentController::class, 'studentTransfer'])->name('operationmanager.studentTransfer');
        Route::get('/student/executive', [StudentController::class, 'studentExecutive'])->name('operationmanager.studentExecutive');
        Route::post('/student/transfer/save', [StudentController::class, 'stTransfer'])->name('operationmanager.stTransfer');

        /*Course Wise Enroll */
        Route::post('/course/wise/enroll', [StudentController::class, 'courseEnroll'])->name('operationmanager.courseEnroll');
        Route::post('/course/wise/enroll/update', [StudentController::class, 'courseEnrollUpdate'])->name('operationmanager.courseEnrollUpdate');
        Route::delete('/course/wise/enroll/delete/{id}', [StudentController::class, 'courseEnrollDelete'])->name('operationmanager.courseEnrollDelete');

        /*==Batch Transfer==*/
        Route::get('/student/batch/transfer/list', [StudentController::class, 'batchTransferList'])->name('operationmanager.batchTransferList');
        Route::get('/student/batch/transfer', [StudentController::class, 'batchTransfer'])->name('operationmanager.batchTransfer');
        Route::get('/student/batch/enroll', [StudentController::class, 'studentEnrollBatch'])->name('operationmanager.studentEnrollBatch');
        Route::post('/student/transfer', [StudentController::class, 'transfer'])->name('operationmanager.transfer');

        /*===Report Data===*/
        Route::get('/batch/wise/enroll', [ReportController::class, 'batchwiseEnrollStudent'])->name('operationmanager.batchwiseEnrollStudent');
        Route::get('/batch/edit/enroll/{id}', [ReportController::class, 'editEnrollStudent'])->name('operationmanager.editEnrollStudent');
        Route::post('/batch/assign/{id}', [ReportController::class, 'assign_batch_toEnrollStudent'])->name('operationmanager.assign_batch_toEnrollStudent');
        Route::post('/batch/single/assign/{id}', [ReportController::class, 'assign_single_batch_toEnrollStudent'])->name('operationmanager.assign_single_batch_toEnrollStudent');
        Route::post('/batch/wise/enroll', [ReportController::class, 'batchwiseEnrollStudent'])->name('operationmanager.batchwiseEnrollStudent');

        /*==Batch Transfer==*/
        Route::get('/student/batch/transfer', [StudentController::class, 'batchTransfer'])->name('operationmanager.batchTransfer');
        Route::get('/student/batch/enroll', [StudentController::class, 'studentEnrollBatch'])->name('operationmanager.studentEnrollBatch');
        Route::post('/student/transfer', [StudentController::class, 'transfer'])->name('operationmanager.transfer');

        /*===Payment report==*/
        Route::get('/daily/collection/report', [PaymentReportController::class, 'daily_collection_report'])->name('operationmanager.daily_collection_report');
        Route::get('/daily/collection/report/mr', [PaymentReportController::class, 'daily_collection_report_by_mr'])->name('operationmanager.daily_collection_report_by_mr');

        /*Attendance Report */
        Route::get('/batch/wise/attendance', [ReportController::class, 'batchwiseAttendance'])->name('operationmanager.batchwiseAttendance');
        Route::get('/batch/wise/attendance/report', [ReportController::class, 'batchwiseAttendanceReport'])->name('operationmanager.batchwiseAttendanceReport');

        /*=== Course Report= ==*/
        Route::get('/course/wise/report', [ReportController::class, 'coursewiseStudent'])->name('operationmanager.coursewiseStudent');
        Route::post('/course/wise/report', [ReportController::class, 'coursewiseStudent'])->name('operationmanager.coursewiseStudent');
        /*Course Enroll Report */
        Route::get('/course/wise/enroll/list', [ReportController::class, 'coursewiseEnrollStudent'])->name('operationmanager.coursewiseEnrollStudent');
        Route::post('/course/wise/enroll/list', [ReportController::class, 'coursewiseEnrollStudent'])->name('operationmanager.coursewiseEnrollStudent');

        /*Note History */
        Route::get('/note/history', [NoteController::class, 'note_by_student_id'])->name('operationmanager.noteHistoryByStId');
        /*Payment Report */
        Route::get('/payment/report/all', [PaymentReportController::class, 'allPaymentReportBySid'])->name('operationmanager.allPaymentReportBySid');
        Route::get('/payment/report/course/all', [PaymentReportController::class, 'allPaymentCourseReportBySid'])->name('operationmanager.allPaymentCourseReportBySid');

        /*Batch Completion Report */
        Route::get('/batch/wise/completion', [ReportController::class, 'batchwiseCompletion'])->name('operationmanager.batchwiseCompletion');
        Route::get('/batch/wise/completion/report', [ReportController::class, 'batchwiseCompletionReport'])->name('operationmanager.batchwiseCompletionReport');

        /* Refund*/
        Route::resource('/refund', RefundController::class, ["as" => "operationmanager"]);
        /*=== Withdraw | Drop ===*/
        Route::get('/student/batch/withdraw/', [StudentController::class, 'withdraw'])->name('operationmanager.withdraw');
        Route::get('/student/batch/undo/withdraw/', [StudentController::class, 'withdraw_undo'])->name('operationmanager.withdraw_undo');

        /*Certificate Controller */
        Route::resource('/certificate', CertificateController::class, ["as" => "operationmanager`"]);
    });
});

// Accounts Manager
Route::group(['middleware' => 'isAccountmanager'], function () {
    Route::prefix('accounts-manager')->group(function () {
        Route::get('/', [DashboardController::class, 'accountmanager']);
        Route::get('/dashboard', [DashboardController::class, 'accountmanager'])->name('accountmanagerDashboard');

        Route::get('/profile', [UserController::class, 'userProfile'])->name('accountmanager.userProfile');
        Route::post('/profile', [UserController::class, 'storeProfile'])->name('accountmanager.storeProfile');
        Route::post('/changePass', [UserController::class, 'changePass'])->name('accountmanager.changePass');
        Route::post('/changePer', [UserController::class, 'changePer'])->name('accountmanager.changePer');
        Route::post('/changeAcc', [UserController::class, 'changeAcc'])->name('accountmanager.changeAcc');

        Route::prefix('student')->group(function () {
            //Student Controller
            Route::get('/all',  [StudentController::class,'index'])->name('accountmanager.allStudent');
            Route::get('/student/enroll/details/{id}',  [StudentController::class, 'studentenrollById'])->name('accountmanager.studentenrollById');
            Route::get('/payment/{id}/{entryDate}',  [StudentController::class, 'paymentStudent'])->name('accountmanager.paymentStudent');
        });
        Route::resource('/bundelcourse', BundelCourseController::class, ["as" => "accountmanager"])->only(['index']);
        Route::resource('/course', CourseController::class, ["as" => "accountmanager"])->only(['index']);
        Route::resource('/batch', BatchController::class, ["as" => "accountmanager"])->only(['index']);
        Route::resource('/package', PackageController::class, ["as" => "accountmanager"])->only(['index']);

        /*==Course Search==*/
        Route::post('/course/search', [CourseController::class, 'courseSearch'])->name('accountmanager.courseSearch');
        Route::post('/batch/search', [BatchController::class, 'batchSearch'])->name('accountmanager.batchSearch');
        Route::post('/package/search', [PackageController::class, 'packageSearch'])->name('accountmanager.packageSearch');

        /*====Batch Payment====*/
        Route::get('/payment/invoice', [PaymentController::class, 'searchStData'])->name('accountmanager.searchStData');
        Route::get('/payment/enroll', [PaymentController::class, 'enrollData'])->name('accountmanager.enrollData');
        Route::get('/payment/databySystemId', [PaymentController::class, 'databySystemId'])->name('accountmanager.databySystemId');
        Route::get('/payment/data', [PaymentController::class, 'paymentData'])->name('accountmanager.paymentData');

        Route::resource('/payment', PaymentController::class, ["as" => "accountmanager"]);

        /*=== Payment Edit====*/
        Route::get('/payment/report/{id}/{sId}', [PaymentController::class, 'edit'])->name('accountmanager.payment.edit');
        Route::get('/payment/course/report/{id}/{sId}', [PaymentController::class, 'courseEdit'])->name('accountmanager.payment.course.edit');
        /*===Payment report==*/
        Route::get('/payment/report/all', [PaymentReportController::class, 'allPaymentReportBySid'])->name('accountmanager.allPaymentReportBySid');
        Route::get('/payment/report/bundel', [PaymentReportController::class, 'allPaymentCourseReportBySid'])->name('accountmanager.allPaymentCourseReportBySid');
        Route::get('/daily/collection/report', [PaymentReportController::class, 'daily_collection_report'])->name('accountmanager.daily_collection_report');
        Route::get('/daily/collection/report/mr', [PaymentReportController::class, 'daily_collection_report_by_mr'])->name('accountmanager.daily_collection_report_by_mr');




        /*===Report Data===*/
        Route::get('/batch/wise/enroll', [ReportController::class, 'batchwiseEnrollStudent'])->name('accountmanager.batchwiseEnrollStudent');
        Route::post('/batch/wise/enroll', [ReportController::class, 'batchwiseEnrollStudent'])->name('accountmanager.batchwiseEnrollStudent');


        /*===Other Payment===*/
        Route::prefix('other')->name('accountmanager.')->group(function () {
            Route::resource('payments', OtherPaymentController::class);

            Route::get('/payment/invoice/search', [OtherPaymentController::class, 'searchStudent'])->name('searchStudent');
            Route::get('/payment/invoice/enroll', [OtherPaymentController::class, 'stData'])->name('stData');
            Route::get('/payment/invoice/databyStudentId', [OtherPaymentController::class, 'databyStudentId'])->name('databyStudentId');
            Route::get('/payment/invoice/other', [OtherPaymentController::class, 'otherPaymentByStudentId'])->name('otherPaymentByStudentId');
            Route::get('/payment/invoice/course', [OtherPaymentController::class, 'coursePaymentByStudentId'])->name('coursePaymentByStudentId');
            Route::post('/payment/course/', [OtherPaymentController::class, 'coursestore'])->name('payments.coursestore');
        });

        Route::resource('/payment-transfer', PaymentTransferController::class, ["as" => "accountmanager"]);
        Route::get('/payment-transfer-data', [PaymentTransferController::class,'payment_transfer_data'])->name('accountmanager.payment_transfer_data');

        /*===Report Data===*/
        Route::get('/batch/wise/enroll', [ReportController::class, 'batchwiseEnrollStudent'])->name('accountmanager.batchwiseEnrollStudent');
        Route::post('/batch/wise/enroll', [ReportController::class, 'batchwiseEnrollStudent'])->name('accountmanager.batchwiseEnrollStudent');

         /*Course Enroll Report */
         Route::get('/course/wise/enroll/list', [ReportController::class, 'coursewiseEnrollStudent'])->name('accountmanager.coursewiseEnrollStudent');
         Route::post('/course/wise/enroll/list', [ReportController::class, 'coursewiseEnrollStudent'])->name('accountmanager.coursewiseEnrollStudent');

         /* Refund*/
        Route::resource('/refund', RefundController::class, ["as" => "accountmanager"]);
        Route::get('/student/enrolldata/', [RefundController::class, 'databySystemId'])->name('accountmanager.enrolldata');

        /*Transaction  */
        Route::resource('/transaction', TransactionController::class, ["as" => "accountmanager"]);

        /*Course Wise Student Enroll Data Delete */
        Route::post('/course/wise/enroll/list/delete', [ReportController::class, 'course_wise_student_enroll_data_delete'])->name('accountmanager.course_wise_student_enroll_data_delete');

        /* Batch wise enroll payment report */
        Route::get('/payment/report/batchwise/enroll/batch', [PaymentReportController::class, 'allPaymentReportBySid_for_batch_enroll_report'])->name('accountmanager.allPaymentReportBySid_for_batch_enroll_report');
        /* Course wise enroll payment report */
        Route::get('/payment/report/batchwise/enroll/course', [PaymentReportController::class, 'allPaymentCourseReportBySid_for_batch_enroll_report'])->name('accountmanager.allPaymentCourseReportBySid_for_batch_enroll_report');
        
    });
});

// Training Manager
Route::group(['middleware' => 'isTrainingmanager'], function () {
    Route::prefix('training-manager')->group(function () {
        Route::get('/', [DashboardController::class, 'trainingmanager']);
        Route::get('/dashboard', [DashboardController::class, 'trainingmanager'])->name('trainingmanagerDashboard');

        Route::get('/profile', [UserController::class, 'userProfile'])->name('trainingmanager.userProfile');
        Route::post('/profile', [UserController::class, 'storeProfile'])->name('trainingmanager.storeProfile');
        Route::post('/changePass', [UserController::class, 'changePass'])->name('trainingmanager.changePass');
        Route::post('/changePer', [UserController::class, 'changePer'])->name('trainingmanager.changePer');
        Route::post('/changeAcc', [UserController::class, 'changeAcc'])->name('trainingmanager.changeAcc');
    });
});

// Trainer
Route::group(['middleware' => 'isTrainer'], function () {
    Route::prefix('trainer')->group(function () {
        Route::get('/', [DashboardController::class, 'trainer']);
        Route::get('/dashboard', [DashboardController::class, 'trainer'])->name('trainerDashboard');

        Route::get('/profile', [UserController::class, 'userProfile'])->name('trainer.userProfile');
        Route::post('/profile', [UserController::class, 'storeProfile'])->name('trainer.storeProfile');
        Route::post('/changePass', [UserController::class, 'changePass'])->name('trainer.changePass');
        Route::post('/changePer', [UserController::class, 'changePer'])->name('trainer.changePer');
        Route::post('/changeAcc', [UserController::class, 'changeAcc'])->name('trainer.changeAcc');

        Route::resource('/batch', BatchController::class, ["as" => "trainer"])->only(['index']);
        Route::post('/batch/search', [BatchController::class, 'batchSearch'])->name('trainer.batchSearch');

        /*===Report Data===*/
        Route::get('/batch/wise/enroll', [ReportController::class, 'batchwiseEnrollStudent'])->name('trainer.batchwiseEnrollStudent');
        Route::post('/batch/wise/enroll', [ReportController::class, 'batchwiseEnrollStudent'])->name('trainer.batchwiseEnrollStudent');

        /*Payment Report */
        Route::get('/payment/report/all', [PaymentReportController::class, 'allPaymentReportBySid'])->name('trainer.allPaymentReportBySid');
        Route::get('/payment/report/course/all', [PaymentReportController::class, 'allPaymentCourseReportBySid'])->name('trainer.allPaymentCourseReportBySid');

        /*Attendance Report */
        Route::get('/batch/wise/attendance', [ReportController::class, 'batchwiseAttendance'])->name('trainer.batchwiseAttendance');
        Route::get('/batch/wise/attendance/report', [ReportController::class, 'batchwiseAttendanceReport'])->name('trainer.batchwiseAttendanceReport');

        /*Batch Completion Report */
        Route::get('/batch/wise/completion', [ReportController::class, 'batchwiseCompletion'])->name('trainer.batchwiseCompletion');
        Route::get('/batch/wise/completion/report', [ReportController::class, 'batchwiseCompletionReport'])->name('trainer.batchwiseCompletionReport');

        /*Certificate Controller */
        Route::resource('/certificate', CertificateController::class, ["as" => "trainer"]);
    });
});

