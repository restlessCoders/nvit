<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Payment;
use App\Models\Batch;
use App\Models\Paymentdetail;

use Illuminate\Http\Request;
use DB;
use Carbon\Carbon;

use View;
class PaymentReportController extends Controller
{
    public function daily_collection_report_by_mr(Request $request)
    {
        //dd($request->paymentDate);
        
        $users = User::whereIn('roleId', [1, 3, 5, 9])->get();
        $batches = Batch::where('status',1)->get();
        
        $payments = 
       
        
        //Payment::with('paymentDetail')->orderby('mrNo','desc');
        Payment::join('paymentdetails', 'payments.id', '=', 'paymentdetails.paymentId')
        ->leftjoin('students', 'paymentdetails.studentId', '=', 'students.id')
        ->leftjoin('users', 'payments.executiveId', '=', 'users.id')
        ->leftjoin('batches', 'paymentdetails.batchId', '=', 'batches.id')
        ->leftjoin('courses', 'paymentdetails.course_id', '=', 'courses.id')
        ->select('payments.paymentDate','payments.mrNo','payments.invoiceId','paymentdetails.*','batches.id as bid','batches.batchId','courses.courseName','students.name','students.contact','students.executiveId','users.username')
        ->orderby('payments.mrNo','desc')
        ->where('paymentdetails.deduction','>=',0);
       
        if($request->studentId){
            $payments->where('students.name', 'like', '%'.$request->sdata.'%')
            ->where('students.id', $request->studentId)
            ->orWhere('students.name', 'like', '%'.$request->studentId.'%')
            ->orWhere('students.contact', 'like', '%'.$request->studentId.'%');
        }
        /*if($request->paymentDate){
            $payments->where('payments.paymentDate', '=', \Carbon\Carbon::createFromTimestamp(strtotime($request->paymentDate))->format('Y-m-d'));
        }*/
        if (isset($request->date_range)) {
            $date_range = explode('-', $request->date_range);
            $from = \Carbon\Carbon::createFromTimestamp(strtotime($date_range[0]))->format('Y-m-d');
            $to = \Carbon\Carbon::createFromTimestamp(strtotime($date_range[1]))->format('Y-m-d');
            //print_r($date_range);die;
            //$postingDate = Attendance::whereBetween('postingDate', [$from, $to]);
            $payments->whereBetween('payments.paymentDate', [$from, $to]);
        }
            
        if($request->executiveId){
            $payments->where('payments.executiveId',$request->executiveId);
        }
        if($request->batch_id){
            $payments->whereHas('paymentDetail', function ($query) use ($request) {
                $query->where('paymentdetails.batchId', $request->batch_id);
            });
        }
        if($request->invoiceId){
            $payments->where('payments.invoiceId', $request->invoiceId);
        }
        if($request->mrNo){
            $payments->where('payments.mrNo', $request->mrNo);
        }
        if($request->feeType){
            if ($request->feeType == 3) {
                $payments = $payments->where(function($query) {
                    $query->where('paymentdetails.feeType', '!=', 1)
                        ->whereRaw("(paymentdetails.cPayable) - (COALESCE(paymentdetails.discount, 0) + paymentdetails.cpaidAmount) > 0")
                        ->whereIn('paymentdetails.id', function($subquery) {
                            $subquery->select(DB::raw('MAX(id)'))
                                ->from('paymentdetails as pd')
                                ->whereRaw('pd.studentId = paymentdetails.studentId')
                                ->whereRaw('pd.batchId = paymentdetails.batchId');
                        });
                });
            } else {
                $payments->whereHas('paymentDetail', function ($query) use ($request) {
                    $query->where('paymentdetails.feeType', $request->feeType);
                });
            }                       
        }
        if(strtolower(currentUser()) == 'salesexecutive'){
            $payments->where('payments.executiveId', '=', currentUserId());
        }
        if ($request->year) {
            $payments = $payments->whereYear('payments.paymentDate', $request->year);
        }
        if ($request->month) {
            $payments = $payments->whereMonth('payments.paymentDate', $request->month);
        }     
        if ($request->payment_type) {
            $payments = $payments->whereMonth('paymentdetails.payment_type', $request->payment_type);
        }  
        $payments = $payments->whereNull('paymentdetails.deleted_at');
        $perPage = $request->perPage?$request->perPage:25;
        $payments = $payments->paginate($perPage)->appends([
            'executiveId' => $request->executiveId,
            'studentId' => $request->studentId,
            'batch_id' => $request->batch_id,
            'date_range' => $request->date_range,
            'year' => $request->year,
            'month' => $request->month,
            'payment_type' => $request->payment_type,
            'feeType' => $request->feeType,
        ]);
        /* echo '<pre>';
        print_r($payments->toArray());die;*/
        return view('report.accounts.daily_collection_by_mr', compact('payments', 'users', 'batches'));
    }
    public function daily_collection_report_by_mr_report_print(Request $request){
        $users = User::whereIn('roleId', [1, 3, 5, 9])->get();
        $batches = Batch::where('status',1)->get();
        
        $payments = 
       
        
        //Payment::with('paymentDetail')->orderby('mrNo','desc');
        Payment::join('paymentdetails', 'payments.id', '=', 'paymentdetails.paymentId')
        ->leftjoin('students', 'paymentdetails.studentId', '=', 'students.id')
        ->leftjoin('users', 'payments.executiveId', '=', 'users.id')
        ->leftjoin('batches', 'paymentdetails.batchId', '=', 'batches.id')
        ->leftjoin('courses', 'paymentdetails.course_id', '=', 'courses.id')
        ->select('payments.paymentDate','payments.mrNo','payments.invoiceId','paymentdetails.*','batches.id as bid','batches.batchId','courses.courseName','students.name','students.contact','students.executiveId','users.username')
        ->orderby('payments.mrNo','desc')
        ->where('paymentdetails.deduction','>=',0);
       
        if($request->studentId){
            $payments->where('students.name', 'like', '%'.$request->sdata.'%')
            ->where('students.id', $request->studentId)
            ->orWhere('students.name', 'like', '%'.$request->studentId.'%')
            ->orWhere('students.contact', 'like', '%'.$request->studentId.'%');
        }
        if($request->paymentDate){
            $payments->where('payments.paymentDate', '=', \Carbon\Carbon::createFromTimestamp(strtotime($request->paymentDate))->format('Y-m-d'));
        }
        if($request->executiveId){
            $payments->where('payments.executiveId',$request->executiveId);
        }
        if($request->batch_id){
            $payments->whereHas('paymentDetail', function ($query) use ($request) {
                $query->where('paymentdetails.batchId', $request->batch_id);
            });
        }
        if($request->invoiceId){
            $payments->where('payments.invoiceId', $request->invoiceId);
        }
        if($request->mrNo){
            $payments->where('payments.mrNo', $request->mrNo);
        }
        if($request->feeType){
            if ($request->feeType == 3) {
                $payments = $payments->where(function($query) {
                    $query->where('paymentdetails.feeType', '!=', 1)
                        ->whereRaw("(paymentdetails.cPayable) - (COALESCE(paymentdetails.discount, 0) + paymentdetails.cpaidAmount) > 0")
                        ->whereIn('paymentdetails.id', function($subquery) {
                            $subquery->select(DB::raw('MAX(id)'))
                                ->from('paymentdetails as pd')
                                ->whereRaw('pd.studentId = paymentdetails.studentId')
                                ->whereRaw('pd.batchId = paymentdetails.batchId');
                        });
                });
            } else {
                $payments->whereHas('paymentDetail', function ($query) use ($request) {
                    $query->where('paymentdetails.feeType', $request->feeType);
                });
            }                       
        }
        if(strtolower(currentUser()) == 'salesexecutive'){
            $payments->where('payments.executiveId', '=', currentUserId());
        }
        if ($request->year) {
            $payments = $payments->whereYear('payments.paymentDate', $request->year);
        }
        if ($request->month) {
            $payments = $payments->whereMonth('payments.paymentDate', $request->month);
        }     
        if ($request->payment_type) {
            $payments = $payments->whereMonth('paymentdetails.payment_type', $request->payment_type);
        }  
        $payments = $payments->get();

        return View::make("report.accounts.daily_collection_by_mr_report_print", compact('payments'))
        ->render();
    }
    public function daily_collection_report(Request $request)
    {
        /*$users = User::whereIn('roleId', [1, 3, 5, 9])->get();
        $batches = Batch::all();

        $payments = DB::table('payments')
            ->select('payments.paymentDate','payments.executiveId','users.name as executiveName', DB::raw('SUM(payments.paidAmount) as paidAmount,SUM(payments.tPayable) as tPayable'),DB::raw('SUM(discount) as discount'))
            ->join('paymentdetails','paymentdetails.paymentId','payments.id')
            ->join('users','users.id','payments.executiveId')
            ;
            
        if ($request->year) {
            $payments = $payments->whereYear('paymentDate', $request->year);
        }
        if ($request->month) {
            $payments = $payments->whereMonth('paymentDate', $request->month);
        }
        if ($request->month) {
            $payments = $payments->whereDate('paymentDate', $request->date);
        }
        $payments = $payments->groupBy('payments.paymentDate')->get();

        return view('report.accounts.daily_collection_report', compact('payments', 'users', 'batches'));*/

        $users = User::whereIn('roleId', [1, 3, 5, 9])->get();

        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;


        $payments = DB::table('payments')
            ->join('paymentdetails', 'paymentdetails.paymentId', 'payments.id')
            ->select('paymentDate', DB::raw('SUM(paidAmount) as paidAmount,SUM(tPayable) as tPayable'))
            ->select('payments.id','payments.paymentDate', 'payments.executiveId', DB::raw('SUM(payments.paidAmount) as paidAmount,SUM(payments.tPayable) as tPayable'), DB::raw('SUM(discount) as discount'))
            ->groupBy('payments.paymentDate');
            
            if ($request->year) {
                $currentYear = $request->year;
                $payments = $payments->whereYear('paymentDate', $request->year);
            }
            if ($request->month) {
                $currentMonth = $request->month;
                $payments = $payments->whereMonth('paymentDate', $request->month);
            }
            if($request->executiveId){
                $payments->where('payments.executiveId',$request->executiveId);
            }
            if(strtolower(currentUser()) == 'salesexecutive'){
                $payments->where('executiveId', '=', currentUserId());
            }
            if(empty($request->year) && empty($request->month)){
                $payments->whereMonth('payments.paymentDate', '=', $currentMonth);
                $payments->whereYear('payments.paymentDate', '=', $currentYear);
            }  
            $payments = $payments->get();
            $salespersons = DB::table('payments')
            ->select('payments.executiveId', 'users.username')
            ->join('users', 'payments.executiveId', '=', 'users.id');
            if(strtolower(currentUser()) == 'salesexecutive'){
                $salespersons = $salespersons->where('payments.executiveId', '=', currentUserId())->groupBy('payments.executiveId')->get();
            }else{
                $salespersons = $salespersons->groupBy('payments.executiveId')->get();
            }
            //print_r($salespersons);die;
        return view('report.accounts.daily_collection_report', compact('payments', 'salespersons', 'users','currentMonth','currentYear'));
    }
    public function allPaymentReportBySid(Request $request)
    {
        
    }

    public function allPaymentCourseReportBySid(Request $request)
    {
        DB::connection()->enableQueryLog();
        $payments = DB::table('paymentdetails')
            ->select('student_courses.price','courses.courseName', 'paymentdetails.*', 'payments.invoiceId','payments.mrNo','payments.paymentDate','payments.accountNote')
            ->join('student_courses', 'paymentdetails.course_id', '=', 'student_courses.course_id')
            ->join('courses', 'paymentdetails.course_id', '=', 'courses.id')
            ->join('payments', 'paymentdetails.paymentId', '=', 'payments.id')
            ->where('paymentdetails.studentId', $request->sId)
            ->where('paymentdetails.deduction','>=',0);

        if ($request->systmVal) {
            $payments->where('student_courses.systemId', $request->systmVal);
        }
        if ($request->course_id) {
            $payments->where('student_courses.course_id', $request->course_id);
            $payments->where('paymentdetails.course_id', $request->course_id);
        }
        //if ($request->feeType) {
            /*Registration Fee Or Course Fee*/
            //$payments->where('paymentdetails.feeType', $request->feeType);
        //}

        $payments = $payments->get();/*->groupBy('student_batches.batch_id','student_batches.systemId')*/
        $queries = \DB::getQueryLog();
dd($payments);
    //dd($queries);
        //return response()->json(array('data' =>$payments));
        $data = '<h5 style="font-size:18px;line-height:20px;">Payment History</h5>';
        $data .= '<table class="table table-bordered mb-3 text-center">
                <thead>
                    <tr>
                        <th>SL.</th>
                        <th width="120px">Invoice & Date</th>
                        <th width="120px">MR & Date</th>
                        <th>Note</th>
                        <th>Course</th>
                        <th>Invoice Amt.</th>
                        <th>Paid</th>
                        <th>Dis</th>
                        <th>Due</th>
                        <th>Fee Type</th>
                        <th>Due Date</th>
                        <!--<th>Others</th>
                        <th>Action</th>-->
                    </tr>
                </thead>';
        $sl = 1;
        foreach ($payments as $key => $p) {
            $data .= '<tr>';
            $data .= '<td>' . $sl . '</td>';
            /*$data .= '<td>No# ' . $p->paymentId . '<p class="p-0 m-1">' . date('d M Y', strtotime($p->paymentDate)) . '</p>
                        <strong class="text-danger" style="font-size:11px;">Next Payment Date: ' . date('d M Y', strtotime($p->dueDate)) . '</strong></td>';*/
            if(!empty($p->invoiceId)){
                $data .= '<td>' . $p->invoiceId . '<p class="p-0 m-1">' . date('d M Y', strtotime($p->paymentDate)) . '</p></td>';
            }else{
                $data .= '<td>-</td>';
            }
                        
            $data .= '<td>' . $p->mrNo . '<p class="p-0 m-1">' . date('d M Y', strtotime($p->paymentDate)) . '</p></td>';
            $data .= '<td>' . $p->accountNote . '</td>';
            $data .= '<td>' . $p->courseName . '</td>';
            $data .= '<td>' . /*$p->cPayable*/$p->price . '</td>';
            $data .= '<td>' . $p->cpaidAmount . '</td>';
            $data .= '<td>' . $p->discount . '</td>';
            $data .= '<td>' . ($p->cPayable - ($p->cpaidAmount + $p->discount)) . '</td>';
            if ($p->feeType == 1)
                $text = "Registration";
            else
                $text = "Invoice";
            $data .= '<td>' . $text . '</td>';/*->format('F j, Y \a\t h:i A') */
            if($p->feeType ==2 && $p->cPayable > ($p->cpaidAmount + $p->discount)){
                if(!empty($p->dueDate))
                $data .= '<td><strong class="text-danger">' . date('d M Y', strtotime($p->dueDate)) . '</strong></td>';
                else
                $data .= '<td><strong class="">-</strong></td>';
            }else{
                $data .= '<td>-</td>';  
            }
            

            $data .= '</tr>';
            $sl++;
        }
        $data .= '</table>';
        return response()->json(array('data' => $data));
    }

    public function allPaymentReportBySid_for_batch_enroll_report(Request $request)
    {
        DB::connection()->enableQueryLog();
        $stData = DB::table('student_batches')
            ->select('student_batches.course_id','student_batches.course_price','batches.batchId as batchName', 'student_batches.student_id','student_batches.batch_id')
           
            ->leftjoin('batches', 'student_batches.batch_id', '=', 'batches.id')
            ->where('student_batches.student_id', '=', $request->sId)
            ->where('student_batches.systemId', '=', $request->systmVal)
            ->where('student_batches.acc_approve', '!=',3);
            //->distinct('student_batches.batch_id');


        $stData = $stData->get();/*->groupBy('student_batches.batch_id','student_batches.systemId')*/
        $queries = \DB::getQueryLog();
        /*echo '<pre>';
print_r($stData);die;*/
    //dd($queries);
        //return response()->json(array('data' =>$payments));
        $data = '<h5 style="font-size:18px;line-height:20px;">Payment History</h5>';
        $data .= '<table class="table table-bordered mb-3 text-center">
                <thead>
                    <tr>
                        <th>SL.</th>
                        <th width="120px">Invoice & Date</th>
                        <th width="120px">MR & Date</th>
                        <th>Note</th>
                        <th>Batch</th>
                        <th>Invoice Amt.</th>
                        <th>Paid</th>
                        <th>Dis</th>
                        <th>Due</th>
                        <th>Fee Type</th>
                        <th>Due Date</th>
                        <!--<th>Others</th>
                        <th>Action</th>-->
                    </tr>
                </thead>';
        $sl = 1;
        foreach ($stData as $key => $s) {
            $payments = DB::table('paymentdetails')
            ->selectRaw('paymentdetails.*, payments.invoiceId,payments.mrNo,payments.paymentDate,payments.accountNote,paymentdetails.deleted_at')
            ->join('payments', 'paymentdetails.paymentId', '=', 'payments.id')
            
            ->where(['paymentdetails.studentId' => $s->student_id,'paymentdetails.batchId' => $s->batch_id])
            ->where(['paymentdetails.studentId' => $s->student_id,'paymentdetails.course_id' => $s->course_id])
            ->where('paymentdetails.cpaidAmount', '!=',0)
            ->whereNull('paymentdetails.deleted_at')
            ->get();

            foreach($payments as $p){
               
            $data .= '<tr>';
            $data .= '<td>' . $sl . '</td>';
            /*$data .= '<td>No# ' . $p->paymentId . '<p class="p-0 m-1">' . date('d M Y', strtotime($p->paymentDate)) . '</p>
                        <strong class="text-danger" style="font-size:11px;">Next Payment Date: ' . date('d M Y', strtotime($p->dueDate)) . '</strong></td>';*/
            if(!empty($p->invoiceId)){
                $data .= '<td>' . $p->invoiceId . '<p class="p-0 m-1">' . date('d M Y', strtotime($p->paymentDate)) . '</p></td>';
            }else{
                $data .= '<td>-</td>';
            }
                        
            $data .= '<td>' . $p->mrNo . '<p class="p-0 m-1">' . date('d M Y', strtotime($p->paymentDate)) . '</p></td>';
            $data .= '<td>' . $p->accountNote . '</td>';
            if($p->batchId !=0){
                $data .= '<td>'.DB::table('batches')->where('id',$p->batchId)->first()->batchId.'</td>';
                $data .= '<td>'.DB::table('student_batches')->where('student_id',$p->studentId)->where('batch_id',$p->batchId)->first()->course_price.'</td>';
            }
           
            else{
                $data .= '<td>'.DB::table('courses')->where('id',$p->course_id)->first()->courseName.'</td>';
                $data .= '<td>'.DB::table('student_batches')->where('student_id',$p->studentId)->where('course_id',$p->course_id)->first()->course_price.'</td>';
            }

           
            $data .= '<td>' . $p->cpaidAmount . '</td>';
            $data .= '<td>' . $p->discount . '</td>';
            $data .= '<td>' . ($p->cPayable - ($p->cpaidAmount + $p->discount)) . '</td>';
            if ($p->feeType == 1)
                $text = "Registration";
            else
                $text = "Invoice";
            $data .= '<td>' . $text . '</td>';/*->format('F j, Y \a\t h:i A') */
            if($p->feeType ==2 && $p->cPayable > ($p->cpaidAmount + $p->discount)){
                if(!empty($p->dueDate))
                $data .= '<td><strong class="text-danger">' . date('d M Y', strtotime($p->dueDate)) . '</strong></td>';
                else
                $data .= '<td><strong class="">-</strong></td>';
            }else{
                $data .= '<td>-</td>';  
            }
            
            /*$data .= '<td width="150px">
                                    <p class="text-left m-0 p-0">Paid By:-</p>
                                    <p class="text-left m-0 p-0">Paid:' . \Carbon\Carbon::createFromTimestamp(strtotime($p->created_at))->format('j M, Y')  . '</p>
                                    <p class="text-left m-0 p-0">Updated By:-</p>
                                    <p class="text-left m-0 p-0">Update:' . \Carbon\Carbon::createFromTimestamp(strtotime($p->updated_at))->format('j M, Y')  . '</p>
                                </td>';
            $data .= '<td width="130px">
                                    <a href="" class="text-success" title="print"><i class="fas fa-print mr-1"></i></a>
                                    <a href="' . route(currentUser() . '.payment.edit', [encryptor('encrypt', $p->id), $p->studentId]) . '" class="text-success" title="edit"><i class="far fa-edit mr-1"></i></a>
                                    <a href="" class="text-danger" title="delete"><i class="far fa-trash-alt mr-1"></i></a>
                                    <a href="" class="text-warning" title="reverse"><i class="fas fa-redo-alt mr-1"></i></a>
                                    <a href="" class="text-info" title="refund"><i class="fas fa-exchange-alt"></i></a>
                                </td>';*/
            $data .= '</tr>';
            $sl++;
        }
        }
        $data .= '</table>';
        return response()->json(array('data' => $data));
    }
}
