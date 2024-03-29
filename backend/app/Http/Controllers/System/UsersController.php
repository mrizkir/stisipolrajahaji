<?php

namespace App\Http\Controllers\System;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\User;
use App\Models\DetailUser;

class UsersController extends Controller
{	
	const LOG_CHANNEL = 'system-user';
	/**
	 * digunakan untuk mendapatkan detail user
	 */
	public function show(Request $request, $id)
	{
		if($this->hasRole('manajemen'))
		{
			$this->hasPermissionTo('SYSTEM-USERS-AKADEMIK_SHOW');
		}

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
			$user->detail;
			$user->permissions;			
			return Response()->json([
				'status'=>1,
				'user'=>$user,
				'message'=>["User ID ($id) berhasil diperoleh"]
			], 200); 
		}
	}
	/**
	 * digunakan untuk mendapatkan role user
	 */
	public function roles(Request $request,$id)
	{
		$user = User::find($id);
		if (is_null($user))
		{
			return Response()->json([
				'status'=>0,
				'pid'=>'fetchdata',
				'message'=>["User ID ($id) gagal diperoleh"]
			], 422); 
		}
		else
		{
			$roles=$user->getRoleNames();           
			return Response()->json([
										'status'=>1,
										'pid'=>'fetchdata',
										'roles'=>$roles,                                
										'message'=>"daftar roles user ($user->username) berhasil diperoleh"
									], 200); 
		}
	}
	/**
	 * Store user permissions resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function syncallpermissions(Request $request)
	{      
		$this->hasPermissionTo('USER_STOREPERMISSIONS');
		$this->validate($request, [            
			'role_name'=>'required|exists:roles,name',    
		]);
		$role_name=$request->input('role_name');
		$permission=Role::findByName($role_name)->permissions;
		$permissions=$permission->pluck('name');
		switch($role_name)
		{
			case 'mahasiswabaru':
				$this->validate($request, [           
					'TA'=>'required',
					'prodi_id'=>'required'
				]);                

				$ta=$request->input('TA');
				$prodi_id=$request->input('prodi_id');
				$data = User::where('default_role','mahasiswabaru')
						->select(\DB::raw('users.id'))
						->join('pe3_formulir_pendaftaran','pe3_formulir_pendaftaran.user_id','users.id')
						->where('users.ta',$ta)
						->where('kjur1',$prodi_id)
						->get();

				foreach ($data as $user)
				{
					\DB::table('model_has_permissions')->where('model_id',$user->id)->delete();
					$user->givePermissionTo($permissions);                 
				}                
			break;
			case 'mahasiswa':
				$this->validate($request, [           
					'TA'=>'required',
					'prodi_id'=>'required'
				]);  
				$ta=$request->input('TA');
				$prodi_id=$request->input('prodi_id');
				$data = User::where('default_role','mahasiswa')
						->select(\DB::raw('users.id'))
						->join('pe3_register_mahasiswa','pe3_register_mahasiswa.user_id','users.id')
						->where('pe3_register_mahasiswa.tahun',$ta)
						->where('pe3_register_mahasiswa.kjur',$prodi_id)
						->get();

				foreach ($data as $user)
				{
					\DB::table('model_has_permissions')->where('model_id',$user->id)->delete();
					$user->givePermissionTo($permissions);   
				}                
			break;
			case 'pmb':
				$data = User::role('pmb')
						->select(\DB::raw('users.id'))                        
						->where('active',1)
						->get();

				foreach ($data as $user)
				{
					\DB::table('model_has_permissions')->where('model_id',$user->id)->delete();
					$user->givePermissionTo($permissions);                 
				}                
			break;
			case 'akademik':
				$data = User::role('akademik')
						->select(\DB::raw('users.id'))                        
						->where('active',1)
						->get();

				foreach ($data as $user)
				{
					\DB::table('model_has_permissions')->where('model_id',$user->id)->delete();
					$user->givePermissionTo($permissions);                 
				}                
			break;
			case 'programstudi':
				$data = User::role('programstudi')
						->select(\DB::raw('users.id'))                        
						->where('active',1)
						->get();

				foreach ($data as $user)
				{
					\DB::table('model_has_permissions')->where('model_id',$user->id)->delete();
					$user->givePermissionTo($permissions);                 
				}                
			break;
			case 'puslahta':
				$data = User::role('puslahta')
						->select(\DB::raw('users.id'))                        
						->where('active',1)
						->get();

				foreach ($data as $user)
				{
					\DB::table('model_has_permissions')->where('model_id',$user->id)->delete();
					$user->givePermissionTo($permissions);                 
				}                
			break;
			case 'keuangan':
				$data = User::role('keuangan')
						->select(\DB::raw('users.id'))                        
						->where('active',1)
						->get();

				foreach ($data as $user)
				{
					\DB::table('model_has_permissions')->where('model_id',$user->id)->delete();
					$user->givePermissionTo($permissions);                 
				}                
			break;
			case 'perpustakaan':
				$data = User::role('perpustakaan')
						->select(\DB::raw('users.id'))                        
						->where('active',1)
						->get();

				foreach ($data as $user)
				{
					\DB::table('model_has_permissions')->where('model_id',$user->id)->delete();
					$user->givePermissionTo($permissions);                 
				}                
			break;
			case 'lppm':
				$data = User::role('lppm')
						->select(\DB::raw('users.id'))                        
						->where('active',1)
						->get();

				foreach ($data as $user)
				{
					\DB::table('model_has_permissions')->where('model_id',$user->id)->delete();
					$user->givePermissionTo($permissions);                 
				}                
			break;
			case 'dosen':
				$data = User::role('dosen')
						->select(\DB::raw('users.id'))                        
						->where('active',1)
						->get();

				foreach ($data as $user)
				{
					\DB::table('model_has_permissions')->where('model_id',$user->id)->delete();
					$user->givePermissionTo($permissions);                 
				}                
			break;
			case 'dosenwali':
				$data = User::role('dosenwali')
						->select(\DB::raw('users.id'))                        
						->where('active',1)
						->get();

				foreach ($data as $user)
				{
					\DB::table('model_has_permissions')->where('model_id',$user->id)->delete();
					$user->givePermissionTo($permissions);                 
				}                
			break;
			case 'alumni':
				$data = User::role('alumni')
						->select(\DB::raw('users.id'))                        
						->where('active',1)
						->get();

				foreach ($data as $user)
				{
					\DB::table('model_has_permissions')->where('model_id',$user->id)->delete();
					$user->givePermissionTo($permissions);                 
				}                
			break;
			case 'orangtuawali':
				$data = User::role('orangtuawali')
						->select(\DB::raw('users.id'))                        
						->where('active',1)
						->get();

				foreach ($data as $user)
				{
					\DB::table('model_has_permissions')->where('model_id',$user->id)->delete();
					$user->givePermissionTo($permissions);                 
				}                
			break;
		}       
		return Response()->json([
									'status'=>1,
									'pid'=>'update',                                                                             
									'message'=>"Permission seluruh user role ($role_name) berhasil disinkronisasi."
								], 200); 
	}
	/**
	 * @return daftar role permission beserta permission users
	 */
	public function userrolepermission(Request $request, $id) {
		
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
		else if (is_null($user->roles()->first())) {
			
			\Log::channel(self::LOG_CHANNEL)->error("User dengan id ($id) gagal diperoleh oleh {$this->getUsername()} karena belum terdaftar dimanapun");

			return Response()->json([
				'status'=>0,
				'pid'=>'fetchdata',
				'message'=>["User a.n {$user->nama} belum terdaftar di role manapun silahkan daftarkan"]
			], 422);
		}
		else
		{
			$role_id = $user->roles()->first()->id;
			$subquery=\DB::table('model_has_permissions')
			->select(\DB::raw('
				permission_id				
			'))
			->where('model_id', $user->userid); 

			$permissions=\DB::table('role_has_permissions AS A')
				->select(\DB::raw('
					B.id,
					B.name,    
					B.guard_name,
					CASE 
						WHEN user_permission.permission_id IS NOT NULL THEN							
							"true"
					END AS selected,
					CASE 
						WHEN user_permission.permission_id IS NOT NULL THEN							
							"true"
					END AS selected2
				'))
				->join('permissions AS B', 'A.permission_id', 'B.id')
				->leftJoinSub($subquery,'user_permission',function($join) {
					$join->on('user_permission.permission_id','=','A.permission_id');
				})
				->where('A.role_id', $role_id)
				->orderByRaw('CASE WHEN selected IS NULL THEN 1 ELSE 0 END')
				->orderBy('B.name', 'ASC')
				->get();

			return Response()->json([
				'status'=>1,
				'pid'=>'fetchdata',
				'user' => $user,
				'permissions' => $permissions,
				'message'=>'Permission dari user '.$user->username.' berhasil diperoleh.'
			], 200)->setEncodingOptions(FILTER_VALIDATE_BOOLEAN); 
		}
	}
	/**
	 * Store user permissions resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function storeuserpermissions(Request $request)
	{      
		$this->hasPermissionTo('USER_STOREPERMISSIONS');
		
		$this->validate($request, [
			'userid'=>'required|exists:user,userid',						
		]);
		$post = $request->all();
		$user_id = $post['userid'];
		$user = User::find($user_id);
		
		$permissions = isset($post['chkpermission'])?$post['chkpermission']:[];
		
		foreach($permissions as $k=>$v)
		{
			$records[]=$v['name'];
		}       
		
		$user->givePermissionTo($records);

		\Log::channel(self::LOG_CHANNEL)->error("Permission user dengan id ($user_id)  berhasil disimpan oleh {$this->getUsername()}");

		return Response()->json([
			'status'=>1,
			'pid'=>'store',
			'message'=>'Permission user '.$user->nama.' berhasil disimpan.'
		], 200); 
	}
	/**
	 * Store user permissions resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function revokeuserpermissions(Request $request)
	{      
		$this->hasPermissionTo('USER_REVOKEPERMISSIONS');

		$this->validate($request, [
			'user_id'=>'required|exists:user,userid',
			'name'=>'required|exists:permissions,name',
		]);

		$post = $request->all();
		$name = $post['name'];
		$user_id = $post['user_id'];
	  
		$user = User::find($user_id);
		$user->revokePermissionTo($name);
		
		\Log::channel(self::LOG_CHANNEL)->error("Permission user dengan id ($user_id) berhasil dihapus oleh {$this->getUsername()}");

		return Response()->json([
			'status'=>1,
			'pid'=>'destroy',
			'message'=>'Role user '.$user->username.' berhasil di revoke.'
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
				'pid'=>'update',
				'message'=>["User ID ($id) gagal diupdate"]
			], 422); 
		}
		else
		{
			 $this->validate($request, [
										'username'=>[
														'required',
														'unique:users,username,'.$user->id
													],   
										'name'=>'required',    
										'email'=>'required|string|email|unique:users,email,'.$user->id,
										'nomor_hp'=>'required|string|unique:users,nomor_hp,'.$user->id,                           
									]);  
			
			$user = \DB::transaction(function () use ($request,$user){
				$user->name = strtoupper($request->input('name'));
				$user->email = $request->input('email');
				$user->username = $request->input('username');                        
				$user->nomor_hp = $request->input('nomor_hp');                        
				if (!empty(trim($request->input('password')))) {
					$user->password = Hash::make($request->input('password'));
				}    
				$user->updated_at = \Carbon\Carbon::now()->toDateTimeString();
				$user->save();                
				
				$daftar_roles=json_decode($request->input('role_id'),true);
				if (($key= array_search('dosen',$daftar_roles))===false)
				{
					$key= array_search('dosenwali',$daftar_roles);
					if ($key)
					{
						unset($daftar_roles[$key]);
					}
				}
				$user->syncRoles($daftar_roles);
				
				$dosen=UserDosen::find($user->id);
				
				foreach($daftar_roles as $v)
				{
					if ($v!='superadmin')
					{              
						$permission=Role::findByName($v)->permissions;
						$permissions=$permission->pluck('name');
						$user->givePermissionTo($permissions);

						if ($v=='dosen' && is_null($dosen))
						{
							UserDosen::create([
								'user_id'=>$user->id,
								'nama_dosen'=>$request->input('name'),                                    
							]);
						}
						else if ($v=='dosen' && !is_null($dosen))
						{
							$dosen->active=1;
							$dosen->save();
						}
						else if (!is_null($dosen))
						{
							$dosen->active=0;
							$dosen->save();
						}
						if ($v=='dosenwali' && $v=='dosen')
						{
							\DB::table('pe3_dosen')
								->where('user_id',$user->id)
								->update(['is_dw'=>true]);
						}
						else
						{
							\DB::table('pe3_dosen')
								->where('user_id',$user->id)
								->update(['is_dw'=>false]);
						}
					}
				}
				
				\App\Models\System\ActivityLog::log($request,[
																'object' => $this->guard()->user(), 
																'object_id' => $this->guard()->user()->id, 
																'user_id' => $this->getUserid(), 
																'message' => 'Mengubah data user ('.$user->username.') berhasil'
															]);

				return Response()->json([
											'status'=>1,
											'pid'=>'update',
											'user'=>$user,            
											'message'=>'Data user '.$user->username.' berhasil diubah.'
										], 200); 
			});
		}
	}
	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function updatepassword(Request $request, $id)
	{
		$user = User::find($id);

		if (is_null($user))
		{
			return Response()->json([
									'status'=>0,
									'pid'=>'update',
									'message'=>["Password User ID ($id) gagal diupdate"]
								], 422); 
		}
		else
		{
			$this->validate($request, [            
				'password'=>'required',
			]); 

			$user->password = Hash::make($request->input('password'));                
			$user->save();

			\App\Models\System\ActivityLog::log($request,[
															'object' => $this->guard()->user(), 
															'object_id' => $this->getUserid(), 
															'user_id' => $this->getUserid(), 
															'message' => 'Mengubah data password user ('.$user->username.') berhasil'
														]);

			return Response()->json([
										'status'=>1,
										'pid'=>'update',
										'user'=>$user,            
										'message'=>'Password user '.$user->username.' berhasil diubah.'
									], 200); 
		}
	}
	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function updateprofil(Request $request)
	{
		$user = \Auth::user();
		$id = $user->id;

		$this->validate($request, [        
			'email'=>'required|string|email|unique:users,email,'.$id,  
		]);
		
		$user->email = $request->input('email');
		if (!empty(trim($request->input('password1')))) {
			$user->password = \Hash::make($request->input('password1'));
		}    
		$user->updated_at = \Carbon\Carbon::now()->toDateTimeString();
		$user->save();

		if ($request->ajax()) 
		{
			return response()->json([
				'success'=>true,
				'message'=>'Data ini telah berhasil diubah.'
			]);
		}
		else
		{
			return redirect(route('users.profil',['id'=>$id]))->with('success',"Data profil telah berhasil diubah.");
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

		$user = User::where('isdeleted',true)
					->find($id); 

		if (is_null($user))
		{
			return Response()->json([
									'status'=>0,
									'pid'=>'destroy',              
									'message'=>["User dengan id ($id) gagal dihapus"]
								], 422);    
		}
		else
		{
			$username=$user->username;
			$user->delete();

			\App\Models\System\ActivityLog::log($request,[
																'object' => $this->guard()->user(), 
																'object_id' => $this->guard()->user()->id, 
																'user_id' => $this->getUserid(), 
																'message' => 'Menghapus user ('.$username.') berhasil'
															]);

			return Response()->json([
									'status'=>1,
									'pid'=>'destroy',  
									'user'=>$user,      
									'message'=>"User ($username) berhasil dihapus"
								], 200);    
		}
			 
				  
	}
	public function uploadfoto (Request $request,$id)
	{
		$user = User::find($id); 
		
		if ($user == null)
		{
			return Response()->json([
									'status'=>0,
									'pid'=>'store',
									'message'=>["Data User tidak ditemukan."]
								], 422);         
		}
		else
		{
			$this->validate($request, [        
				'foto'=>'required',  
			]);
			$username=$user->username;
			$foto = $request->file('foto');
			$mime_type=$foto->getMimeType();
			if ($mime_type=='image/png' || $mime_type=='image/jpeg')
			{
				$folder=Helper::public_path('images/users/');
				$file_name=uniqid('img').".".$foto->getClientOriginalExtension();
				$foto->move($folder,$file_name);

				$old_file=$user->foto;
				$user->foto="storage/images/users/$file_name";
				$user->save();

				if ($old_file != 'storage/images/users/no_photo.png')
				{
					$old_file=str_replace('storage/','',$old_file);
					if (is_file(Helper::public_path($old_file)))
					{
						unlink(Helper::public_path($old_file));
					}
				}
				return Response()->json([
											'status'=>0,
											'pid'=>'store',
											'user'=>$user,
											'message'=>"Foto User ($username)  berhasil diupload"
										], 200);    
			}
			else
			{
				return Response()->json([
										'status'=>1,
										'pid'=>'store',
										'message'=>["Extensi file yang diupload bukan jpg atau png."]
									], 422); 
				

			}
		}
	}
	public function resetfoto(Request $request,$id)
	{
		$user = User::find($id); 
		
		if ($user == null)
		{
			return Response()->json([
									'status'=>0,
									'pid'=>'store',
									'message'=>["Data User tidak ditemukan."]
								], 422);         
		}
		else
		{
			$username=$user->username;
			$old_file=$user->foto;
			$user->foto="storage/images/users/no_photo.png";
			$user->save();

			if ($old_file != 'storage/images/users/no_photo.png')
			{
				$old_file=str_replace('storage/','',$old_file);
				if (is_file(Helper::public_path($old_file)))
				{
					unlink(Helper::public_path($old_file));
				}
			}
			
			return Response()->json([
										'status'=>1,
										'pid'=>'store',
										'user'=>$user,
										'message'=>"Foto User ($username)  berhasil direset"
									], 200); 
		}
	}
	public function usersprodi (Request $request,$id)
	{
		$user = User::find($id); 

		if ($user == null)
		{
			return Response()->json([
									'status'=>0,
									'pid'=>'store',
									'message'=>["Data User tidak ditemukan."]
								], 422);         
		}
		else
		{
			$username = $user->username;            
			$prodi=$user->prodi;            
			return Response()->json([
										'status'=>1,
										'pid'=>'fetchdata',
										'daftar_prodi'=>$prodi,
										'message'=>"Daftar Prodi dari username ($username)  berhasil diperoleh"
									], 200); 
		}
	}
}