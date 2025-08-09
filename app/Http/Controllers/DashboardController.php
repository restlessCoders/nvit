<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;

use App\Models\User;
use App\Models\Bill;
use App\Models\Company;
use App\Models\Customer;
use App\Models\Paymentdetail;
use App\Models\Student;
use App\Models\StudentCourse;
use Session;
use Carbon\Carbon;
use DB;

class DashboardController extends BaseController
{
  public function chartData()
  {
    $admissions = DB::table('student_batches')->selectRaw("DATE(student_batches.created_at) as date, students.executiveId,users.username, COUNT(*) as count")
      ->join('students', 'student_batches.student_id', '=', 'students.id')
      ->join('users', 'students.executiveId', '=', 'users.id')
      ->groupBy('date', 'executiveId')
      ->orderBy('date')
      ->get();
    /*echo '<pre>';
print_r($admissions);die;*/
    /* $dates = $admissions->pluck('date')->unique()->values();
    $executives = $admissions->pluck('username')->unique()->values();

    $data = [];

    foreach ($executives as $executive) {
      $execAdmissions = $admissions->where('username', $executive);

      $execData = [];
      foreach ($dates as $date) {
        $admission = $execAdmissions->where('date', $date)->first();
        $execData[] = $admission ? $admission->count : 0;
      }

      $data[] = [
        'executive' => $executive,
        'data' => $execData,
      ];
    }
    
    return response()->json([
      'dates' => $dates,
      'executives' => $executives,
      'data' => $data,
    ]);*/
    $data = [];

    foreach ($admissions as $admission) {
      $date = $admission->date;
      $executiveId = $admission->username;
      $count = $admission->count;

      if (!isset($data[$date])) {
        $data[$date] = [];
      }

      if (!isset($data[$date][$executiveId])) {
        $data[$date][$executiveId] = 0;
      }

      $data[$date][$executiveId] += $count;
    }

    return response()->json([
      'data' => $data,
    ]);
  }
  public function index()
  {
    $clients = Student::where('status', 1)->count();
    $revenue = Paymentdetail::sum('cpaidAmount');
    $due = DB::table('paymentdetails')
      ->selectRaw('SUM(cPayable) - (SUM(cpaidAmount) + SUM(discount)) as total')
      ->whereRaw('(cPayable - (discount + cpaidAmount)) > 0')
      ->first();

    $recall_students = Student::join('notes', 'students.id', 'notes.student_id')
      ->select('students.name', 'notes.student_id', 'notes.re_call_date', 'notes.note')
      ->whereDate('re_call_date', '=', now()->toDateString())
      ->paginate(8);
    return view('dashboard.superadmin_dashboard', compact('recall_students', 'clients', 'revenue', 'due'));
  }

  public function admin()
  {
    return view('dashboard.admin_dashboard');
  }

  public function frontdesk()
  {
    return view('dashboard.frontdesk_dashboard');
  }

  public function executive()
  {
    return view('dashboard.executive_dashboard');
  }
  public function accountmanager()
  {
    // Get date ranges
    $today = Carbon::today();
    $startOfWeek = Carbon::now()->startOfWeek();
    $endOfWeek = Carbon::now()->endOfWeek();
    $startOfMonth = Carbon::now()->startOfMonth();
    $endOfMonth = Carbon::now()->endOfMonth();
    $startOfYear = Carbon::now()->startOfYear();
    $endOfYear = Carbon::now()->endOfYear();

    // Collection summaries
    $collections = [
      'today' => $this->getCollectionAmount($today, $today),
      'week' => $this->getCollectionAmount($startOfWeek, $endOfWeek),
      'month' => $this->getCollectionAmount($startOfMonth, $endOfMonth),
      'year' => $this->getCollectionAmount($startOfYear, $endOfYear),
    ];

    // Today's collections by executive with payment mode breakdown
    $todaysCollectionsByExecutive = $this->getTodaysCollectionsByExecutive($today);

    // Payment method breakdown
    $paymentMethods = $this->getPaymentMethodBreakdown();

    // Outstanding dues
    $dues = $this->getTopDues(10);
    $totalDues = $dues->sum('due_amount');

    // Recent payments
    $recentPayments = $this->getRecentPayments(10);

    // Collection trend data (last 7 days)
    $collectionTrend = $this->getCollectionTrend(15);

    return view('dashboard.accountmanager_dashboard', [
      'collections' => $collections,
      'totalDues' => $totalDues,
      'todaysCollectionsByExecutive' => $todaysCollectionsByExecutive,
      'paymentMethods' => $paymentMethods,
      'dues' => $dues,
      'recentPayments' => $recentPayments,
      'collectionTrend' => $collectionTrend
    ]);
  }

  private function getCollectionAmount($startDate, $endDate)
  {
    return PaymentDetail::join('payments', 'payments.id', '=', 'paymentdetails.paymentId')
      ->whereNull('paymentdetails.deleted_at')
      ->whereBetween('payments.paymentDate', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')])
      ->sum('paymentdetails.cpaidAmount');
  }

  private function getTodaysCollectionsByExecutive($date)
  {
    return User::select(
      'users.id',
      'users.name',
      DB::raw('SUM(CASE WHEN paymentdetails.payment_mode = 1 THEN paymentdetails.cpaidAmount ELSE 0 END) as cash'),
      DB::raw('SUM(CASE WHEN paymentdetails.payment_mode = 2 THEN paymentdetails.cpaidAmount ELSE 0 END) as bkash'),
      DB::raw('SUM(CASE WHEN paymentdetails.payment_mode = 3 THEN paymentdetails.cpaidAmount ELSE 0 END) as card'),
      DB::raw('SUM(paymentdetails.cpaidAmount) as total')
    )
      ->join('payments', 'payments.executiveId', '=', 'users.id')
      ->join('paymentdetails', 'paymentdetails.paymentId', '=', 'payments.id')
      ->whereDate('payments.paymentDate', $date)
      ->whereNull('paymentdetails.deleted_at')
      ->groupBy('users.id', 'users.name')
      ->get();
  }

  private function getPaymentMethodBreakdown()
  {
    $methods = PaymentDetail::select('payment_mode', DB::raw('SUM(cpaidAmount) as total'))
      ->whereNull('deleted_at')
      ->groupBy('payment_mode')
      ->get();

    $labels = [];
    $values = [];

    foreach ($methods as $method) {
      $labels[] = $this->paymentModeMap($method->payment_mode);
      $values[] = $method->total;
    }

    return [
      'labels' => $labels,
      'values' => $values
    ];
  }

  private function paymentModeMap($mode)
  {
    return [
      1 => 'Cash',
      2 => 'Bkash',
      3 => 'Card'
    ][$mode] ?? 'Unknown';
  }

  private function getTopDues($limit)
  {
    return StudentCourse::select(
      'students.name as student_name',
      'courses.courseName',
      'student_courses.price',
      DB::raw('(student_courses.price - COALESCE(paid.paid_total, 0)) as due_amount'),
      DB::raw('(CASE WHEN MAX(paymentdetails.dueDate) < CURDATE() THEN 1 ELSE 0 END) as is_overdue')
    )
      ->join('students', 'students.id', '=', 'student_courses.student_id')
      ->join('courses', 'courses.id', '=', 'student_courses.course_id')
      ->leftJoin(
        DB::raw('(SELECT studentId, course_id, SUM(cpaidAmount) as paid_total 
                           FROM paymentdetails 
                           WHERE deleted_at IS NULL 
                           GROUP BY studentId, course_id) as paid'),
        function ($join) {
          $join->on('paid.studentId', '=', 'student_courses.student_id')
            ->on('paid.course_id', '=', 'student_courses.course_id');
        }
      )
      ->leftJoin('paymentdetails', function ($join) {
        $join->on('paymentdetails.studentId', '=', 'student_courses.student_id')
          ->on('paymentdetails.course_id', '=', 'student_courses.course_id');
      })
      ->whereRaw('student_courses.price > COALESCE(paid.paid_total, 0)')
      ->groupBy(
        'student_courses.student_id',
        'student_courses.course_id',
        'students.name',
        'courses.courseName',
        'student_courses.price',
        'paid.paid_total'
      )
      ->orderByDesc('due_amount')
      ->limit($limit)
      ->get();
  }

  private function getRecentPayments($limit)
  {
    return PaymentDetail::select('paymentdetails.*', 'payments.paymentDate', 'payments.mrNo', 'payments.executiveId')
      ->join('payments', 'payments.id', '=', 'paymentdetails.paymentId')
      ->with([
        'student' => function ($query) {
          $query->select('id', 'name');
        },
        'course' => function ($query) {
          $query->select('id', 'courseName');
        },
        'executive' => function ($query) {
          $query->select('id', 'name');
        }
      ])
      ->whereNull('paymentdetails.deleted_at')
      ->orderByDesc('payments.paymentDate')
      ->limit($limit)
      ->get()
      ->map(function ($detail) {
        return (object) [
          'mrNo' => $detail->mrNo,
          'paymentDate' => $detail->paymentDate,
          'student' => $detail->student,
          'course' => $detail->course,
          'payment_mode' => $detail->payment_mode,
          'cpaidAmount' => $detail->cpaidAmount,
          'executive' => $detail->executive
        ];
      });
  }

  private function getCollectionTrend($days)
  {
    $trendData = [];
    $labels = [];
    $values = [];

    for ($i = $days - 1; $i >= 0; $i--) {
      $date = Carbon::now()->subDays($i);
      $formattedDate = $date->format('M j');
      $amount = $this->getCollectionAmount($date->startOfDay(), $date->endOfDay());

      $labels[] = $formattedDate;
      $values[] = $amount;
    }

    return [
      'labels' => $labels,
      'values' => $values
    ];
  }


  public function trainingmanager()
  {
    return view('dashboard.trainingmanager_dashboard');
  }

  public function trainer()
  {
    return view('dashboard.trainer_dashboard');
  }

  public function operationmanager()
  {
    $clients = Student::where('status', 1)->where('executiveId', currentUserId())->count();
    $revenue =  DB::table('paymentdetails')->join('payments', 'payments.id', '=', 'paymentdetails.paymentId')
      ->where('payments.executiveId', currentUserId())->sum('paymentdetails.cpaidAmount');
    $due = DB::table('paymentdetails')->join('payments', 'payments.id', '=', 'paymentdetails.paymentId')
      ->selectRaw('SUM(paymentdetails.cPayable) - (SUM(paymentdetails.cpaidAmount) + SUM(paymentdetails.discount)) as total')
      ->whereRaw('(paymentdetails.cPayable - (paymentdetails.discount + paymentdetails.cpaidAmount)) > 0')
      ->where('payments.executiveId', currentUserId())
      ->first();

    return view('dashboard.operation_dashboard', compact('clients', 'revenue', 'due'));
  }

  public function owner()
  {
    $company_id = company()['companyId'];
    $company = Company::where(company())->orderBy('id', 'DESC')->first();
    $customer = DB::select(DB::raw("SELECT count(id) as ccount,month(`created_at`) as cmonth FROM `customers` where companyId=$company_id group by month(`created_at`) "));
    $suppliers = DB::select(DB::raw("SELECT count(id) as ccount,month(`created_at`) as cmonth FROM `suppliers` where companyId=$company_id group by month(`created_at`) "));
    $rev_date = DB::select(DB::raw("SELECT sum(total_dis) as dis, sum(`total_tax`) as tax,sum(`total_amount`) as tm,month(`bill_date`) as bd FROM `bills` where companyId=$company_id group by month(`bill_date`) "));
    $profit = DB::select(DB::raw("SELECT (sum(`amount`) - ((sum(`qty`)+sum(IFNULL(free,0))) * (select (sum(purchase_items.amount) / sum((IFNULL(purchase_items.qty,0)+IFNULL(purchase_items.free,0)))) from purchase_items where bill_items.batchId= purchase_items.batchId and bill_items.item_id=purchase_items.item_id))) as profit FROM `bill_items` where bill_items.companyId=$company_id GROUP BY bill_items.batchId,bill_items.item_id"));
    $todaySellSummary = Bill::select(DB::raw("SUM(bills.total_amount) as todayTotalSellAmount"), DB::raw("SUM(bill_items.basic) as todayTotalProductAmount"))->join('bill_items', 'bill_items.bill_id', '=', 'bills.id')
      ->whereDate('bills.bill_date', Carbon::today())->get();

    $dt_min = new \DateTime("last saturday"); // Edit
    $dt_max = clone ($dt_min);
    $dt_max->modify('+6 days');
    $start_date = $dt_min->format('Y-m-d');
    $end_date = $dt_max->format('Y-m-d');

    $billMonth = DB::select(DB::raw("SELECT sum(total_amount) as am, count(id) as cid FROM bills WHERE companyId=$company_id and YEAR(bill_date) = YEAR(CURRENT_DATE()) AND MONTH(bill_date) = MONTH(CURRENT_DATE()) "));
    $billWeek = DB::select(DB::raw("SELECT sum(total_amount) as am, count(id) as cid FROM bills WHERE companyId=$company_id and bill_date between '$start_date' and '$end_date' "));
    $billToday = DB::select(DB::raw("SELECT sum(total_amount) as am, count(id) as cid FROM bills WHERE companyId=$company_id and bill_date = date(now()) "));

    return view('dashboard.owner_dashboard', compact('todaySellSummary', 'rev_date', 'company', 'customer', 'suppliers', 'billToday', 'billWeek', 'billMonth', 'profit'));
  }

  public function salesManager()
  {
    /*$customer=DB::select(DB::raw("SELECT count(id) as ccount,month(`created_at`) as cmonth FROM `customers` where companyId=$company_id group by month(`created_at`) ")); 
		$suppliers=DB::select(DB::raw("SELECT count(id) as ccount,month(`created_at`) as cmonth FROM `suppliers` where companyId=$company_id group by month(`created_at`) ")); 
		$rev_date=DB::select(DB::raw("SELECT sum(total_dis) as dis, sum(`total_tax`) as tax,sum(`total_amount`) as tm,month(`bill_date`) as bd FROM `bills` where companyId=$company_id group by month(`bill_date`) ")); 
    $profit=DB::select(DB::raw("SELECT (sum(`amount`) - ((sum(`qty`)+sum(IFNULL(free,0))) * (select (sum(purchase_items.amount) / sum((IFNULL(purchase_items.qty,0)+IFNULL(purchase_items.free,0)))) from purchase_items where bill_items.batchId= purchase_items.batchId and bill_items.item_id=purchase_items.item_id))) as profit FROM `bill_items` where bill_items.companyId=$company_id GROUP BY bill_items.batchId,bill_items.item_id")); 
    $todaySellSummary = Bill::select(DB::raw("SUM(bills.total_amount) as todayTotalSellAmount"), DB::raw("SUM(bill_items.basic) as todayTotalProductAmount"))->join('bill_items', 'bill_items.bill_id', '=', 'bills.id')
		->whereDate('bills.bill_date',Carbon::today())->get();
        $dt_min = new \DateTime("last saturday"); // Edit
        $dt_max = clone($dt_min);
        $dt_max->modify('+6 days');
        $start_date=$dt_min->format('Y-m-d');
        $end_date=$dt_max->format('Y-m-d');
      
        $billMonth=DB::select(DB::raw("SELECT sum(total_amount) as am, count(id) as cid FROM bills WHERE companyId=$company_id and YEAR(bill_date) = YEAR(CURRENT_DATE()) AND MONTH(bill_date) = MONTH(CURRENT_DATE()) "));
        $billWeek=DB::select(DB::raw("SELECT sum(total_amount) as am, count(id) as cid FROM bills WHERE companyId=$company_id and bill_date between '$start_date' and '$end_date' "));
        $billToday=DB::select(DB::raw("SELECT sum(total_amount) as am, count(id) as cid FROM bills WHERE companyId=$company_id and bill_date = date(now()) "));
        
		return view('dashboard.salesmanager_dashboard',compact('todaySellSummary','profit','rev_date','company','customer','suppliers','billToday','billWeek','billMonth'));*/
    $clients = Student::where('status', 1)->where('executiveId', currentUserId())->count();
    $revenue =  DB::table('paymentdetails')->join('payments', 'payments.id', '=', 'paymentdetails.paymentId')
      ->where('payments.executiveId', currentUserId())->sum('paymentdetails.cpaidAmount');
    $due = DB::table('paymentdetails')->join('payments', 'payments.id', '=', 'paymentdetails.paymentId')
      ->selectRaw('SUM(paymentdetails.cPayable) - (SUM(paymentdetails.cpaidAmount) + SUM(paymentdetails.discount)) as total')
      ->whereRaw('(paymentdetails.cPayable - (paymentdetails.discount + paymentdetails.cpaidAmount)) > 0')
      ->where('payments.executiveId', currentUserId())
      ->first();

    return view('dashboard.salesmanager_dashboard', compact('clients', 'revenue', 'due'));
  }

  public function salesExecutive()
  {
    /*$student=DB::select(DB::raw("SELECT count(id) as ccount,month(`created_at`) as cmonth FROM `customers` where companyId=$company_id group by month(`created_at`) ")); 
		$suppliers=DB::select(DB::raw("SELECT count(id) as ccount,month(`created_at`) as cmonth FROM `suppliers` where companyId=$company_id group by month(`created_at`) ")); 
    $rev_date=DB::select(DB::raw("SELECT sum(total_dis) as dis, sum(`total_tax`) as tax,sum(`total_amount`) as tm,month(`bill_date`) as bd FROM `bills` where companyId=$company_id group by month(`bill_date`) ")); 
    $profit=DB::select(DB::raw("SELECT (sum(`amount`) - ((sum(`qty`)+sum(IFNULL(free,0))) * (select (sum(purchase_items.amount) / sum((IFNULL(purchase_items.qty,0)+IFNULL(purchase_items.free,0)))) from purchase_items where bill_items.batchId= purchase_items.batchId and bill_items.item_id=purchase_items.item_id))) as profit FROM `bill_items` where bill_items.companyId=$company_id GROUP BY bill_items.batchId,bill_items.item_id")); 
    $todaySellSummary = Bill::select(DB::raw("SUM(bills.total_amount) as todayTotalSellAmount"), DB::raw("SUM(bill_items.basic) as todayTotalProductAmount"))->join('bill_items', 'bill_items.bill_id', '=', 'bills.id')
		->whereDate('bills.bill_date',Carbon::today())->get();
        
        $dt_min = new \DateTime("last saturday"); // Edit
        $dt_max = clone($dt_min);
        $dt_max->modify('+6 days');
        $start_date=$dt_min->format('Y-m-d');
        $end_date=$dt_max->format('Y-m-d');
      
        $billMonth=DB::select(DB::raw("SELECT sum(total_amount) as am, count(id) as cid FROM bills WHERE companyId=$company_id and YEAR(bill_date) = YEAR(CURRENT_DATE()) AND MONTH(bill_date) = MONTH(CURRENT_DATE()) "));
        $billWeek=DB::select(DB::raw("SELECT sum(total_amount) as am, count(id) as cid FROM bills WHERE companyId=$company_id and bill_date between '$start_date' and '$end_date' "));
        $billToday=DB::select(DB::raw("SELECT sum(total_amount) as am, count(id) as cid FROM bills WHERE companyId=$company_id and bill_date = date(now()) "));
        
		return view('dashboard.salesman_dashboard',compact('todaySellSummary','profit','rev_date','company','customer','suppliers','billToday','billWeek','billMonth'));*/
    $clients = Student::where('status', 1)->where('executiveId', currentUserId())->count();
    $revenue =  DB::table('paymentdetails')->join('payments', 'payments.id', '=', 'paymentdetails.paymentId')
      ->where('payments.executiveId', currentUserId())->sum('paymentdetails.cpaidAmount');
    $due = DB::table('paymentdetails')->join('payments', 'payments.id', '=', 'paymentdetails.paymentId')
      ->selectRaw('SUM(paymentdetails.cPayable) - (SUM(paymentdetails.cpaidAmount) + SUM(paymentdetails.discount)) as total')
      ->whereRaw('(paymentdetails.cPayable - (paymentdetails.discount + paymentdetails.cpaidAmount)) > 0')
      ->where('payments.executiveId', currentUserId())
      ->first();

    $recall_students = Student::join('notes', 'students.id', 'notes.student_id')
      ->select('students.name', 'notes.student_id', 'notes.re_call_date', 'notes.note')
      ->whereDate('re_call_date', '=', now()->toDateString())
      ->where('executiveId', currentUserId())
      ->paginate(8);
    return view('dashboard.salesexecutive_dashboard', compact('recall_students', 'clients', 'revenue', 'due'));
  }
}
