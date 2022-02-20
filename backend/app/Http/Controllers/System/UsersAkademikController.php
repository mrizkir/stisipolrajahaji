<?php

namespace App\Http\Controllers\System;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserDosen;
use Spatie\Permission\Models\Role;
use Ramsey\Uuid\Uuid;
use Illuminate\Validation\Rule;

use App\Helpers\HelperAuth;

class UsersAkademikController extends Controller
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
				'message'=>'Fetch data user Akademik berhasil diperoleh'
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
				'pid'=>'fetchdata',    
				'message'=>["User ID ($id) gagal diperoleh"]
			], 422); 
		}
		else
		{
			return Response()->json([
				'status'=>1,
				'pid'=>'fetchdata', 
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
			'name'=>'required',
			'email'=>'required|string|email|unique:user',
			'nomor_hp'=>'required|string|unique:user',
			'username'=>'required|string|unique:user',
			'password'=>'required',
			'prodi_id'=>'required',
		]);
		$user = \DB::transaction(function () use ($request) {
			$now = \Carbon\Carbon::now()->toDateTimeString();   
			$user=User::create([
				'id'=>Uuid::uuid4()->toString(),
				'name'=>$request->input('name'),
				'email'=>$request->input('email'),
				'nomor_hp'=>$request->input('nomor_hp'),
				'username'=> $request->input('username'),
				'password'=>Hash::make($request->input('password')),            
				'theme'=>'default',
				'default_role'=>'manajemen',
				'foto'=> 'storage/images/users/no_photo.png',
				'created_at'=>$now, 
				'updated_at'=>$now
			]);       
			$role='manajemen';   
			$user->assignRole($role);          
			
			$permission=Role::findByName('manajemen')->permissions;
			$permissions=$permission->pluck('name');
			$user->givePermissionTo($permissions);    

			$user_id=$user->id;
			$daftar_prodi=json_decode($request->input('prodi_id'), true);
			foreach($daftar_prodi as $v)
			{
				$sql = "
					INSERT INTO usersprodi (                    
						user_id, 
						prodi_id,
						kode_prodi,
						nama_prodi,
						nama_prodi_alias,
						kode_jenjang,
						nama_jenjang,            
						created_at, 
						updated_at
					) 
					SELECT
						'$user_id',        
						id,
						kode_prodi,
						nama_prodi,
						nama_prodi_alias,
						kode_jenjang,
						nama_jenjang,              
						NOW() AS created_at,
						NOW() AS updated_at
					FROM pe3_prodi                    
					WHERE 
						id='$v' 
				";

				\DB::statement($sql); 
			}

			$daftar_roles=json_decode($request->input('role_id'), true);
			foreach($daftar_roles as $v)
			{
				if ($v=='dosen' || $v=='dosenwali' )
				{
					$user->assignRole($v);          
					$permission=Role::findByName($v)->permissions;
					$permissions=$permission->pluck('name');
					$user->givePermissionTo($permissions);

					if ($v=='dosen')
					{
						UserDosen::create([
							'user_id'=>$user->id,
							'nama_dosen'=>$request->input('name'),                
						]);
						if ($v=='dosenwali')
						{
							\DB::table('pe3_dosen')
								->where('user_id',$user->id)
								->update(['is_dw'=>true]);
						}
					}          
				}
			}

			\App\Models\System\ActivityLog::log($request,[
											'object' => $this->guard()->user(), 
											'object_id' => $this->guard()->user()->id, 
											'user_id' => $this->getUserid(), 
											'message' => 'Menambah user Akademik('.$user->username.') berhasil'
										]);

			return $user;
		});

		return Response()->json([
			'status'=>1,
			'pid'=>'store',
			'user'=>$user,    
			'message'=>'Data user Akademik berhasil disimpan.'
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
				'pid'=>'update',    
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

				\Log::channel(self::LOG_CHANNEL)->info("User dengan id ({$user->id} a.n nama ({$user->nama}) role manajemen berhasil diupdate oleh {$this->getUsername()}");

				return $user;
			});
			
			return Response()->json([
				'status'=>1,
				'pid'=>'update',
				'user'=>$user,      
				'message'=>'Data user Akademik '.$user->username.' berhasil diubah.'
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
				'pid'=>'destroy',    
				'message'=>["User ID ($id) gagal dihapus"]
			], 422); 
		}
		else if ($user->isdeleted == 0)
		{
			\Log::channel(self::LOG_CHANNEL)->warning("User dengan id ({$user->id} a.n ({$user->nama}) memiliki flag isdeleted=1 jadi gagal di hapus oleh {$this->getUsername()}");

			return Response()->json([
				'status'=>0,
				'pid'=>'destroy',    
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
				'message'=>"User Akademik ($username) berhasil dihapus"
			], 200);    
		}
				  
	}
}