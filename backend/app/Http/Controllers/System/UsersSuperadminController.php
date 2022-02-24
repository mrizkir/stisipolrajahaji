<?php

namespace App\Http\Controllers\System;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\System\UsersController;

use App\Models\User;
use App\Models\DetailUser;
use Spatie\Permission\Models\Role;

use App\Helpers\HelperAuth;

class UsersSuperadminController extends UsersController
{         
	const LOG_CHANNEL = 'system-user';
	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index(Request $request)
	{           
		$this->hasPermissionTo('SYSTEM-USERS-SUPERADMIN_BROWSE');
		
		$sortdesc = $request->filled('sortdesc') ? $request->query('sortdesc', false) : false;
		$orderby = $sortdesc == 'true' ? 'desc' : 'asc';
		$sortby = $request->filled('sortby') ? $request->query('sortby', 'nama') : 'nama';
		
		$data = User::where('page','sa')
			->orderBy($sortby, $orderby);  
		
		if ($request->filled('search')) {
			$search = $request->query('search');
			$data = $data->whereRaw("page='sa' AND nama LIKE '%$search%'")
			->orWhereRaw("page='sa' AND email LIKE '%$search%'")
			->orWhereRaw("page='sa' AND username LIKE '%$search%'");			
		}
		$data = $data->paginate(10);

		$role = Role::findByName('superadmin');

		return Response()->json([
				'status'=>1,
				'pid'=>'fetchdata',
				'role'=>$role,
				'result'=>$data,
				'user'=>$this->getUsername(),
				'message'=>'Fetch data user superadmin berhasil diperoleh'
			], 200);  
	}    	
	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request)
	{
		$this->hasPermissionTo('SYSTEM-USERS-SUPERADMIN_STORE');

		$this->validate($request, [
			'nama'=>'required',
			'email'=>'required|string|email|unique:user',			
			'username'=>'required|string|unique:user',
			'password'=>'required',			
		]);
		$user = \DB::transaction(function () use ($request) {			
			$now = \Carbon\Carbon::now()->toDateTimeString(); 
			
			$data = HelperAuth::createHashPassword($request->input('password'));
			$salt = $data['salt'];
			$password = $data['password'];           
			
			$user=User::create([
				'idbank'=>0,				
				'username'=> $request->input('username'),
				'userpassword'=>$password,            
				'salt'=>$salt,            
				'nama'=>$request->input('nama'),
				'email'=>$request->input('email'),
				'page'=>'sa',
				'group_id'=>0,
				'kjur'=>0,
				'active'=>1,
				'isdeleted'=>1,
				'theme'=>'cube',
				'default_role'=>'superadmin',
				'foto'=>'resources/userimages/no_photo.png',
				'logintime'=>$now,				
				'date_added'=>$now, 				
			]);       
			$role='superadmin';   
			$user->assignRole($role);          
			
			$permission=Role::findByName('superadmin')->permissions;
			$permissions=$permission->pluck('name');
			$user->givePermissionTo($permissions);    

			DetailUser::create([
				'user_id' => $user->userid,				
			]);

			\Log::channel(self::LOG_CHANNEL)->info("User dengan id ({$user->id} a.n nama ({$user->nama}) role superadmin berhasil disimpan oleh {$this->getUsername()}");

			return $user;
		});

		return Response()->json([
			'status'=>1,
			'pid'=>'store',
			'user'=>$user,    
			'message'=>"Data user Manajemen ({$user->nama}) berhasil disimpan."
		], 200);
	}
	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request, $id)
	{
		$this->hasPermissionTo('SYSTEM-USERS-SUPERADMIN_UPDATE');

		$user = User::find($id);
		if (is_null($user))
		{
			return Response()->json([
				'status'=>0,				
				'message'=>["User ID ($id) gagal diupdate"]
			], 422); 
		}
		else
		{
			$this->validate($request, [
				'username'=>[
					'required',
					'unique:user,username,'.$user->userid.',userid,page,m'
					],           
				'nama'=>'required',
				'email'=>'required|string|email|unique:user,email,'.$user->userid.',userid,page,m',				
			]); 
			
			$user = \DB::transaction(function () use ($request, $user) {
				$user->nama = $request->input('nama');
				$user->email = $request->input('email');				
				$user->username = $request->input('username');   
				if (!empty(trim($request->input('password'))))
				{
					$password = HelperAuth::createHashPassword($request->input('password'));
					$user->salt = $password['salt'];
					$user->userpassword = $password['password'];
				}
				$user->save();

				$user->assignRole('superadmin'); 

				$detail = $user->detail;
				if (is_null($detail))
				{
					DetailUser::create([
						'user_id' => $user->userid,				
					]);
				}				

				\Log::channel(self::LOG_CHANNEL)->info("User dengan id ({$user->id} a.n nama ({$user->nama}) role superadmin berhasil diupdate oleh {$this->getUsername()}");

				return $user;
			});
			
			return Response()->json([
				'status'=>1,
				'pid'=>'update',
				'user'=>$user,      
				'message'=>'Data user superadmin '.$user->username.' berhasil diubah.'
			], 200); 
		}
	}
	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy(Request $request,$id)
	{
		$this->hasPermissionTo('SYSTEM-USERS-SUPERADMIN_DESTROY');

		$user = User::find($id); 
		
		if (is_null($user))
		{
			\Log::channel(self::LOG_CHANNEL)->error("User dengan id ($id) gagal dihapus oleh {$this->getUsername()} karena var user=null");

			return Response()->json([
				'status'=>0,				
				'message'=>["User ID ($id) gagal dihapus"]
			], 422); 
		}
		else if ($user->isdeleted == 0)
		{
			\Log::channel(self::LOG_CHANNEL)->warning("User dengan id ({$user->id} a.n ({$user->nama}) memiliki flag isdeleted=1 jadi gagal di hapus oleh {$this->getUsername()}");

			return Response()->json([
				'status'=>0,				
				'message'=>["User ID ($id) gagal dihapus karena memiliki flag isdeleted=0"]
			], 422); 
		}
		else
		{
			$username=$user->username;
			$user->delete();

			\Log::channel(self::LOG_CHANNEL)->info("User ($username) berhasil di hapus oleh {$this->getUsername()}");
		
			return Response()->json([
				'status'=>1,
				'pid'=>'destroy',    
				'message'=>"User superadmin ($username) berhasil dihapus"
			], 200);    
		}
				  
	}
}