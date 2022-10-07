<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;

use App\Models\User;
use App\Models\Bill;
use App\Models\Company;
use App\Models\Customer;
use Session;
use Carbon\Carbon;
use DB;

class DashboardController extends BaseController
{
    public function index(){
		return view('dashboard.superadmin_dashboard');
    }
	
    public function admin(){
		return view('dashboard.admin_dashboard');
    }
	
    public function frontdesk(){
		return view('dashboard.frontdesk_dashboard');
    }
	
    public function executive(){
		return view('dashboard.executive_dashboard');
    }
	
    public function accountmanager(){
		return view('dashboard.accountmanager_dashboard');
    }

    public function trainingmanager(){
      return view('dashboard.trainingmanager_dashboard');
    }

    public function trainer(){
      return view('dashboard.trainer_dashboard');
    }
	
    public function operationmanager(){
		return view('dashboard.operation_dashboard');
    }

    public function owner(){
        $company_id=company()['companyId'];
        $company = Company::where(company())->orderBy('id', 'DESC')->first();
        $customer=DB::select(DB::raw("SELECT count(id) as ccount,month(`created_at`) as cmonth FROM `customers` where companyId=$company_id group by month(`created_at`) ")); 
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
          
        return view('dashboard.owner_dashboard',compact('todaySellSummary','rev_date','company','customer','suppliers','billToday','billWeek','billMonth','profit'));
    }

    public function salesManager(){
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
    return view('dashboard.salesmanager_dashboard');
    }

    public function salesExecutive(){
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
    return view('dashboard.salesexecutive_dashboard');
    }
}