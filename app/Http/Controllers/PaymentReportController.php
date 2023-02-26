<?php

namespace App\Http\Controllers;
use App\Models\Payment;
use App\Models\Paymentdetail;

use Illuminate\Http\Request;
use DB;
use Carbon\Carbon;

class PaymentReportController extends Controller
{
    public function allPaymentReportBySid(Request $request){

        $payments = DB::table('paymentdetails')
        ->select('batches.batchId as batchName', 'paymentdetails.*','payments.invoiceId','payments.paymentDate')
        ->join('student_batches', 'paymentdetails.studentId', '=', 'student_batches.student_id')
        ->join('batches', 'paymentdetails.batchId', '=', 'batches.id')
        ->join('payments', 'paymentdetails.paymentId', '=', 'payments.id')
        ->where('paymentdetails.studentId', $request->sId);
        if($request->systmVal){
            $payments->where('student_batches.systemId', $request->systmVal);
        }
        if($request->batchId){
            $payments->where('student_batches.batch_id', $request->batchId);
            $payments->where('paymentdetails.batchId', $request->batchId);
        }
        if($request->feeType){
            /*Registration Fee Or Course Fee*/
            $payments->where('paymentdetails.feeType', $request->feeType);
        }
        
        $payments = $payments->groupBy('student_batches.batch_id','student_batches.systemId')->get();
        //return response()->json(array('data' =>$payments));
            $data ='<h5 style="font-size:18px;line-height:70px;">All Payment List</h5>';    
            $data .='<table class="table table-bordered mb-5 text-center">
                <thead>
                    <tr>
                        <th>SL.</th>
                        <th>Payment ID|Date</th>
                        <th>Invoice|MR</th>
                        <th>Batch</th>
                        <th>Course</th>
                        <th>Paid</th>
                        <th>Dis</th>
                        <th>Due</th>
                        <th>Fee</th>
                        <th>Others</th>
                        <th>Action</th>
                    </tr>
                </thead>';
                $sl = 1;
                foreach($payments as $key => $p){
                    $data .='<tr>';
                        $data .='<td>'.$sl .'</td>';
                        $data .='<td>No# '.$p->paymentId .'<p class="p-0 m-1">'. date('d M Y',strtotime($p->paymentDate)).'</p>
                        <strong class="text-danger" style="font-size:11px;">Next Payment Date: '.date('d M Y',strtotime($p->dueDate)).'</strong></td>';
                        $data .='<td>Inv:-'.$p->invoiceId .'<p class="p-0 m-1">Mr No:-'.$p->mrNo.'</p></td>';
                        $data .='<td>'.$p->batchName .'</td>';
                        $data .='<td>'.$p->cPayable .'</td>';
                        $data .='<td>'.$p->cpaidAmount .'</td>';
                        $data .='<td>'.$p->discount .'</td>';
                        $data .='<td>'.($p->cPayable-($p->cpaidAmount+$p->discount)) .'</td>';
                        if($p->feeType==1)
                        $text = "Registration";
                        else
                        $text = "Course";
                        $data .='<td>'.$text.'</td>';/*->format('F j, Y \a\t h:i A') */
                        $data .='<td width="150px">
                                    <p class="text-left m-0 p-0">Paid By:-</p>
                                    <p class="text-left m-0 p-0">Paid:' .\Carbon\Carbon::createFromTimestamp(strtotime($p->created_at))->format('j M, Y')  .'</p>
                                    <p class="text-left m-0 p-0">Updated By:-</p>
                                    <p class="text-left m-0 p-0">Update:'.\Carbon\Carbon::createFromTimestamp(strtotime($p->updated_at))->format('j M, Y')  .'</p>
                                </td>';
                        $data .='<td width="130px">
                                    <a href="" class="text-success" title="print"><i class="fas fa-print mr-1"></i></a>
                                    <a href="'.route(currentUser().'.payment.edit',[encryptor('encrypt', $p->id),$p->studentId]).'" class="text-success" title="edit"><i class="far fa-edit mr-1"></i></a>
                                    <a href="" class="text-danger" title="delete"><i class="far fa-trash-alt mr-1"></i></a>
                                    <a href="" class="text-warning" title="reverse"><i class="fas fa-redo-alt mr-1"></i></a>
                                    <a href="" class="text-info" title="refund"><i class="fas fa-exchange-alt"></i></a>
                                </td>';
                    $data .='</tr>';
                    $sl++;
                }
                $data .= '</table>';
            return response()->json(array('data' =>$data));
    }
}
