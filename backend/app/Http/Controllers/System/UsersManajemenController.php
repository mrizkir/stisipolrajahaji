<?php

namespace App\Http\Controllers\System;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;

use App\Models\User;
use App\Models\DetailUser;
use Spatie\Permission\Models\Role;

use App\Helpers\HelperAuth;

class UsersManajemenController extends Controller
{         
	const LOG_CHANNEL = 'system-user';
	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index(Request $request)
	{           
		$this->hasPermissionTo('SYSTEM-USERS-AKADEMIK_BROWSE');
		
		$sortdesc = $request->filled('sortdesc') ? $request->query('sortdesc', false) : false;
		$orderby = $sortdesc == 'true' ? 'desc' : 'asc';
		$sortby = $request->filled('sortby') ? $request->query('sortby', 'nama') : 'nama';
		
		$data = User::where('page','m')
			->orderBy($sortby, $orderby);  
		
		if ($request->filled('search')) {
			$search = $request->query('search');
			$data = $data->whereRaw("page='m' AND nama LIKE '%$search%'")
			->orWhereRaw("page='m' AND email LIKE '%$search%'")
			->orWhereRaw("page='m' AND username LIKE '%$search%'");			
		}
		$data = $data->paginate(10);

		$role = Role::findByName('manajemen');

		return Response()->json([
				'status'=>1,
				'pid'=>'fetchdata',
				'role'=>$role,
				'result'=>$data,
				'user'=>$this->getUsername(),
				'message'=>'Fetch data user manajemen berhasil diperoleh'
			], 200);  
	}    
	/**
	 * show the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function show(Request $request, $id)
	{
		$this->hasPermissionTo('SYSTEM-USERS-AKADEMIK_SHOW');

		$user = User::find($id);
		if (is_null($user))
		{
			\Log::channel(self::LOG_CHANNEL)->error("User dengan id ($id) gagal diperoleh oleh {$this->getUsername()} karena var user=null");

			return Response()->json([
				'status'=>0,				
				'message'=>["User ID ($id) gagal diperoleh"]
			], 422); 
		}
		else
		{
			return Response()->json([
				'status'=>1,
				'user'=>$user,
				'message'=>["User ID ($id) berhasil diperoleh"]
			], 200); 
		}
	}
	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request)
	{
		$this->hasPermissionTo('SYSTEM-USERS-AKADEMIK_STORE');

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
				'page'=>'m',
				'group_id'=>0,
				'kjur'=>0,
				'active'=>1,
				'isdeleted'=>1,
				'theme'=>'cube',
				'default_role'=>'manajemen',
				'foto'=>'resources/userimages/no_photo.png',
				'logintime'=>$now,				
				'date_added'=>$now, 				
			]);       
			$role='manajemen';   
			$user->assignRole($role);          
			
			$permission=Role::findByName('manajemen')->permissions;
			$permissions=$permission->pluck('name');
			$user->givePermissionTo($permissions);    

			DetailUser::create([
				'user_id' => $user->userid,				
			]);

			\Log::channel(self::LOG_CHANNEL)->info("User dengan id ({$user->id} a.n nama ({$user->nama}) role manajemen berhasil disimpan oleh {$this->getUsername()}");

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
		$this->hasPermissionTo('SYSTEM-USERS-AKADEMIK_UPDATE');

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

				$user->assignRole('manajemen'); 

				$detail = $user->detail;
				if (is_null($detail))
				{
					DetailUser::create([
						'user_id' => $user->userid,				
					]);
				}				

				\Log::channel(self::LOG_CHANNEL)->info("User dengan id ({$user->id} a.n nama ({$user->nama}) role manajemen berhasil diupdate oleh {$this->getUsername()}");

				return $user;
			});
			
			return Response()->json([
				'status'=>1,
				'pid'=>'update',
				'user'=>$user,      
				'message'=>'Data user manajemen '.$user->username.' berhasil diubah.'
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
		$this->hasPermissionTo('SYSTEM-USERS-AKADEMIK_DESTROY');

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
				'message'=>"User manajemen ($username) berhasil dihapus"
			], 200);    
		}
				  
	}
}