<?php

namespace App\Http\Controllers;
use App\Http\Requests\newUserRequest;
use App\Http\Requests\updateUserRequest;
use App\Http\Requests\User\ResetUserPasswordRequest;
use App\Http\Requests\User\ResetUserPersonalRequest;
use App\Http\Requests\User\ResetUserAccountRequest;
use App\Http\Traits\ResponseTrait;
use App\Http\Traits\ImageHandleTraits;
use Illuminate\Http\Request;
use App\Models\Role;
use App\Models\UserDetail;
use App\Models\User;

use App\Models\Company;
use App\Models\State;
use App\Models\Zone;

use Exception;
use Carbon\Carbon;
use DB;


use App\Mail\TestEmail;
use Mail;

class UserController extends Controller
{
    use ResponseTrait, ImageHandleTraits;
    
    public function index(){
		
        $allUser;
        if(currentUser() == 'superadmin'){
            $allUser = User::with('role')->orderBy('id', 'DESC')->paginate(25);
        }elseif(currentUser() == 'executive' || currentUser() == 'accountmanager' || currentUser() == 'marketingmanager' || currentUser() == 'admin'){
            $allUser = User::where(['userCreatorId' => encryptor('decrypt', request()->session()->get('user'))])->with('role')->orderBy('id', 'DESC')->paginate(25);
        }else{
            $allUser = User::where([
                'userCreatorId' => encryptor('decrypt', request()->session()->get('user')),
                'companyId' => encryptor('decrypt', request()->session()->get('companyId'))
            ])->with('role')->orderBy('id', 'DESC')->paginate(25);
        }
        return view('user.index', compact('allUser'));
    }

    public function addForm(){
		$roles = [];
        if(currentUser() == 'superadmin'){
            $roles = Role::whereIn('identity', ['superadmin','admin','dataentry'])->get();
        }
        elseif(currentUser() == 'admin'){
            $roles = Role::whereIn('identity', ['executive'])->get();
        }
        elseif(currentUser() == 'executive'){
            $roles = Role::whereIn('identity', ['accountmanager','marketingmanager'])->get();
        }
        elseif(currentUser() == 'marketingmanager'){
            $roles = Role::whereIn('identity', ['owner'])->get();
        }
        elseif(currentUser() == 'owner'){
            $roles = Role::whereIn('identity', ['salesmanager'])->get();
        }
        elseif(currentUser() == 'salesmanager'){
            $roles = Role::whereIn('identity', ['salesman'])->get();
        }
        
        $allState = State::orderBy('name', 'ASC')->get();
        $allZone = Zone::orderBy('name', 'ASC')->get();
        return view('user.add_new', compact(['roles','allState','allZone']));
    }

    public function store(newUserRequest $request){
        try {
            $userCreator = User::with('role')->find(encryptor('decrypt', $request->userId));
            $lastCreatedUser = User::max('companyId');
            $shopcount=User::where('zone_id',explode(',',$request->zone_id)[0])->where('roleId',7)->count()+1;
          
            $user = new User;
            $user->roleId = $request->role;
            if($userCreator->role->identity == 'marketingmanager'){
				if($request->role==7)
					$user->companyId = $lastCreatedUser + 1;
            }elseif($userCreator->role->identity == 'owner' || $userCreator->role->identity == 'salesmanager'){
                $user->companyId = $userCreator->companyId;
            }
			// check if user mentor executive or telemarketer
			if($userCreator->role->identity == 'admin')
				$user->adminId = encryptor('decrypt', $request->userId);
			elseif($userCreator->role->identity == 'executive')
				$user->executiveId = encryptor('decrypt', $request->userId);
			elseif($userCreator->role->identity == 'marketingmanager')
				$user->marketingmanagerId = encryptor('decrypt', $request->userId);
			
            $user->name = $request->fullName;
            $user->username = $request->username;
            $user->email = $request->email;
            $user->mobileNumber = $request->mobileNumber;
            $user->timezone = $request->timezone;
            $user->state_id = $request->state_id;
            $user->zone_id = explode(',',$request->zone_id)[0];
            $user->password = md5($request->password);
            $user->status = $request->status;
            $user->userCreatorId = encryptor('decrypt', $request->userId);
            $user->created_at = Carbon::now();

            if(!!$user->save()){
				$userd = new UserDetail;
				$userd->userId = $user->id;
				
				if($request->has('photo')) $userd->photo = $this->uploadImage($request->file('photo'), 'user/photo');
				$userd->address = $request->address;
				$userd->nid = $request->nid;
				$userd->save();
				
				if($request->role==7){
					$com= new Company;
					$com->shopCode=explode(',',$request->zone_id)[1].str_pad($shopcount,5,"0",STR_PAD_LEFT );;
					$com->companyId=$user->companyId;
					$com->userId=$user->id;
					$com->save();
					Mail::to($request->email)->send(new TestEmail($com->shopCode,$request->role, $request->username));
				}
				return redirect(route(currentUser().'.allUser'))->with($this->responseMessage(true, null, 'User created'));
			}
        } catch (Exception $e) {
            dd($e);
            return redirect(route(currentUser().'.allUser'))->with($this->responseMessage(false, 'error', 'Please try again!'));
            return false;
        }

    }

    public function editForm($name, $id){
        $roles = [];
        if(currentUser() == 'superadmin'){
            $roles = Role::whereIn('identity', ['superadmin','admin','dataentry'])->get();
        }
        elseif(currentUser() == 'admin'){
            $roles = Role::whereIn('identity', ['executive'])->get();
        }
        elseif(currentUser() == 'executive'){
            $roles = Role::whereIn('identity', ['accountmanager','marketingmanager'])->get();
        }
        elseif(currentUser() == 'marketingmanager'){
            $roles = Role::whereIn('identity', ['owner'])->get();
        }
        elseif(currentUser() == 'owner'){
            $roles = Role::whereIn('identity', ['salesmanager'])->get();
        }
        elseif(currentUser() == 'salesmanager'){
            $roles = Role::whereIn('identity', ['salesman'])->get();
        }
		
        $allState = State::orderBy('name', 'ASC')->get();
        $allZone = Zone::orderBy('name', 'ASC')->get();
        $user = User::find(encryptor('decrypt', $id));
        return view('user.edit', compact(['user','roles','allState','allZone']));
    }

    public function update(updateUserRequest $request){
        try {
            $user = User::find(encryptor('decrypt', $request->id));
            $user->roleId = $request->role;
            $user->name = $request->fullName;
            $user->username = $request->username;
			if(currentUser() == 'superadmin'){
				$user->email = $request->email;
			}
            $user->mobileNumber = $request->mobileNumber;
            $user->timezone = $request->timezone;
            $user->state_id = $request->state_id;
            $user->zone_id = $request->zone_id;
            $user->password = $request->password == $user->password ? $user->password : md5($request->password);
            $user->status = $request->status;
            $user->userCreatorId = encryptor('decrypt', $request->userId);
            $user->updated_at = Carbon::now();

            if(!!$user->save()){
				if($user->details){
					$userd = UserDetail::find($user->details->id);
				}else{
					$userd = new UserDetail;
					$userd->userId = encryptor('decrypt', $request->id);
				}
				if($request->has('photo')) 
					if($this->deleteImage($userd->photo, 'user/photo'))
						$userd->photo = $this->uploadImage($request->file('photo'), 'user/photo');
					else
						$userd->photo = $this->uploadImage($request->file('photo'), 'user/photo');
				
				$userd->address = $request->address;
				$userd->nid = $request->nid;
				$userd->save();
					
				return redirect(route(currentUser().'.allUser'))->with($this->responseMessage(true, null, 'User updated'));
			}
        } catch (Exception $e) {
            dd($e);
            return redirect(route(currentUser().'.allUser'))->with($this->responseMessage(false, 'error', 'Please try again!'));
            return false;
        }

    }

    public function delete($name, $id){
        try {
            $user = User::find(encryptor('decrypt', $id));
            if(!!$user->delete()){
                return redirect(route(currentUser().'.allUser'))->with($this->responseMessage(true, null, 'User deleted'));
            }
        }catch (Exception $e) {
            dd($e);
            return redirect(route(currentUser().'.allUser'))->with($this->responseMessage(false, 'error', 'Please try again!'));
            return false;
        }

    }

	public function modList(){
		$allTm=User::select("name","id")->where("roleId",6)->orderBy("name","ASC")->get();
		$allUser = User::select("name","mobileNumber","username","email","id",DB::raw("(select name from users as u where u.id=users.telemarketerId) as tm"))->where("roleId",2)->orderBy("id","DESC")->paginate(25);
		
		return view('user.owner_list', compact(['allUser','allTm']));
    }
	
	public function modAssign($uid,$tid){
        try {
			$us = User::find(encryptor('decrypt', $uid));

            $us->telemarketerId = encryptor('decrypt', $tid);

            if(!!$us->save()) return redirect(route(currentUser().'.modList'))->with($this->responseMessage(true, null, 'owner has been assigned'));

        } catch (Exception $e) {
            return redirect(route(currentUser().'.modList'))->with($this->responseMessage(false, 'error', 'Please try again!'));
            return false;
        }

    }
	
	public function userProfile(){
	    $UserData=User::where("id",currentUserId())->first();
        return view('user.profile', compact(['UserData']));

    }
    public function changePass(ResetUserPasswordRequest $request){
        $pass = User::find(encryptor('decrypt', $request->id));
        try {
            if($pass['password'] == md5($request->oldpass)){
               $pass->password = md5($request->pass);
               if(!!$pass->save())return redirect()->back()->with($this->responseMessage(true, null, 'Password updated'));
            }else{
              return redirect()->back()->with($this->responseMessage(false, 'error', 'Old Password Mismathed!'));  
            }
    	} catch (Exception $e) {
            return redirect()->back()->with($this->responseMessage(false, 'error', 'Please try again!'));
            return false;
        }
        
    }
    public function changePer(ResetUserPersonalRequest $request){
		
        $persoanl = UserDetail::where('userId','=',encryptor('decrypt', $request->id))->first();
		

		$account = User::find(encryptor('decrypt', $request->id));
		
        try {
            if($request->has('photo')) 
                if($this->deleteImage($persoanl->photo, 'user/photo'))
                    $persoanl->photo = $this->uploadImage($request->file('photo'), 'user/photo');
                else
                    $persoanl->photo = $this->uploadImage($request->file('photo'), 'user/photo');
       
            $persoanl->nid = $request->nid;
            $persoanl->address = $request->address;
			
			
			$account->name = $request->name;
            $account->mobileNumber = $request->mobileNumber;
            $account->username = $request->username;
			$account->save();

            if(!!$persoanl->save())return redirect()->back()->with($this->responseMessage(true, null, 'Profile Information updated'));
           
            
    	} catch (Exception $e) {
            return redirect()->back()->with($this->responseMessage(false, 'error', 'Please try again!'));
            return false;
        }
    }
    public function changeAcc(ResetUserAccountRequest $request){
        $account = User::find(encryptor('decrypt', $request->id));
        try {
           $account->name = $request->name;
           $account->mobileNumber = $request->mobileNumber;
           $account->username = $request->username;
          // $account->email = $request->email;
           if(!!$account->save())return redirect()->back()->with($this->responseMessage(true, null, 'Account Information updated'));

    	} catch (Exception $e) {
            return redirect()->back()->with($this->responseMessage(false, 'error', 'Please try again!'));
            return false;
        }
    }

}
