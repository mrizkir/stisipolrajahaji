<?php

namespace App\Http\Controllers\System;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Models\User;

class RolesController extends Controller
{  
  const LOG_CHANNEL = 'system-user';  
  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index(Request $request)
  {   
    $this->hasPermissionTo('SYSTEM-SETTING-ROLES_DESTROY');
    $data = Role::select(\DB::raw('
      *,
      0 AS jumlah_permission,
      0 AS jumlah_pengguna
    '))
    ->get();

    $data->transform(function ($item, $key) {
      $role = Role::findByName($item->name);			
      $item->jumlah_permission = $role->permissions->count();
      $item->jumlah_pengguna = User::role($role->name)->count();
      return $item;
    });
    return Response()->json([
      'status'=>1,
      'pid'=>'fetchdata',
      'roles'=>$data,
      'message'=>'Fetch data stores berhasil diperoleh'
    ], 200);    
  }
  /**
   * Display the specified role by id.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function show($id)
  {
    $this->hasPermissionTo('SYSTEM-SETTING-ROLES_SHOW');
    $role=Role::findByID($id);
    if (is_null($role))
    {
      return Response()->json([
        'status'=>0,				
        'message'=>["Role ID ($id) gagal diperoleh"]
      ], 422); 
    }
    else
    {
      return Response()->json([
        'status'=>1,
        'pid'=>'fetchdata',
        'role'=>$role,
        'permissions'=>$role->permissions,
        'message'=>'Fetch permission role '.$role->name.' berhasil diperoleh.'
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
    $this->hasPermissionTo('SYSTEM-SETTING-ROLES_STORE');
    $this->validate($request, [
      'name'=>'required|unique:roles',
    ],[
      'name.required'=>'Nama role mohon untuk di isi',
      'name.unique'=>'Nama role telah ada, mohon untuk diganti dengan yang lain'
    ]
    );
    
    $role = new Role;
    $role->name = $request->input('name');
    $role->save();
     
    return Response()->json([
      'status'=>1,
      'pid'=>'store',
      'role'=>$role,    
      'message'=>'Data role berhasil disimpan.'
    ], 200); 

  }
  /**
   * Store a roles resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function storerolepermissions(Request $request)
  {      
    $this->hasPermissionTo('SYSTEM-SETTING-ROLES_STORE');

    $post = $request->all();
    $permissions = isset($post['chkpermission'])?$post['chkpermission']:[];
    $role_id = $post['role_id'];

    foreach($permissions as $k=>$v)
    {
      $records[]=$v['name'];
    }        
    
    $role = Role::find($role_id);
    $role->syncPermissions($records);

    \Log::channel(self::LOG_CHANNEL)->error("Permission role dengan id ($role_id)  berhasil disimpan oleh {$this->getUsername()}");

    return Response()->json([
      'status'=>1,
      'pid'=>'store',
      'message'=>'Permission role '.$role->name.' berhasil disimpan.'
    ], 200); 
  }
  /**
   * Store user permissions resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function revokerolepermissions(Request $request)
  {      
    $this->hasPermissionTo('SYSTEM-SETTING-ROLES_DESTROY');
    
    $this->validate($request, [
      'role_id'=>'required|exists:roles,id',
      'name'=>'required|exists:permissions,name',
    ]);

    $role = \DB::transaction(function () use ($request) {
      $post = $request->all();
      $name = $post['name'];
      $role_id = $post['role_id'];   
      
      $role = Role::find($role_id);
      $role->revokePermissionTo($name);
      
      \Log::channel(self::LOG_CHANNEL)->error("Permission role dengan id ($role_id) berhasil dihapus oleh {$this->getUsername()}");

      return $role;
    });
     
    return Response()->json([
                  'status'=>1,
                  'pid'=>'destroy',
                  'message'=>'Role '.$role->name.' berhasil di revoke.'
                ], 200); 
  }
  /**
   * Display the specified role permissions by id.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function rolepermissions($id)
  {
    $this->hasPermissionTo('SYSTEM-SETTING-ROLES_SHOW');
    $role=Role::findByID($id);
    if (is_null($role))
    {
      return Response()->json([
                  'status'=>0,   
                  'message'=>["Role ID ($id) gagal diperoleh"]
                ], 422); 
    }
    else
    {
      return Response()->json([
                    'status'=>1,
                    'pid'=>'fetchdata',
                    'permissions'=>$role->permissions,    
                    'message'=>'Fetch permission role '.$role->name.' berhasil diperoleh.'
                  ], 200); 
    }
  }    
  /**
   * Display the specified role permissions by id.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function roleallpermissions($id)
  {
    $this->hasPermissionTo('SYSTEM-SETTING-ROLES_SHOW');

    $role=Role::findByID($id);

    if (is_null($role))
    {
      return Response()->json([
        'status'=>0,  
        'message'=>["Role ID ($id) gagal diperoleh"]
      ], 422); 
    }
    else
    {
      $subquery=\DB::table('role_has_permissions AS B')
      ->select(\DB::raw('
        B.permission_id,
        B.role_id				
      '))
      ->where('B.role_id',$role->id);                      
      
      $permissions=\DB::table('permissions AS A')
        ->select(\DB::raw('
          A.id,
          A.name,    
          A.guard_name,
          CASE 
            WHEN B.permission_id IS NOT NULL THEN							
              "true"
          END AS selected,
          CASE 
            WHEN B.permission_id IS NOT NULL THEN							
              "true"
          END AS selected2
        '))   
        ->leftJoinSub($subquery,'B',function($join) {
          $join->on('B.permission_id','=','A.id');
        })
        ->orderByRaw('CASE WHEN  selected IS NULL THEN 1 ELSE 0 END')
        ->get();

      return Response()->json([
        'status'=>1,
        'pid'=>'fetchdata',
        'permissions'=>$permissions,    
        'message'=>'Fetch all permission dengan pilihan role '.$role->name.' berhasil diperoleh.'
      ], 200);
    }
  }    
  /**
   * Display the specified role permissions by name.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function rolepermissionsbyname($id)
  {
    $this->hasPermissionTo('SYSTEM-SETTING-ROLES_SHOW');
    $role=Role::findByName($id);
    if (is_null($role))
    {
      return Response()->json([
                  'status'=>0, 
                  'message'=>["Role ID ($id) gagal diperoleh"]
                ], 422); 
    }
    else
    {
      return Response()->json([
        'status'=>1,
        'pid'=>'fetchdata',
        'permissions'=>$role->permissions,    
        'message'=>'Fetch permission role '.$role->name.' berhasil diperoleh.'
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
  public function update(Request $request, $id)
  {
    $this->hasPermissionTo('SYSTEM-SETTING-ROLES_UPDATE');

    $role = Role::find($id);

    $this->validate($request, [                                
      'name'=>[
        'required',
        Rule::unique('roles')->ignore($role->name,'name')
      ], 
    ],[
      'name.required'=>'Nama role mohon untuk di isi',
    ]);   
     
    $role->name = $request->input('name');
    $role->save();

    return Response()->json([
                  'status'=>1,
                  'pid'=>'update',
                  'role'=>$role,    
                  'message'=>'Data role '.$role->name.' berhasil diubah.'
                ], 200); 
  }
}