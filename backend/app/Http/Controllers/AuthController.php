<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;

class AuthController extends Controller
{
    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $this->validate($request, [            
            'username'=>'required',
            'password'=>'required',
            'page'=>'required'                        
        ]);
        
        $username=$request->input('username');
        $password=$request->input('password');
        $page=$request->input('page');

        $result=User::where('username',$username)
                    ->first();
        
        switch ($page) {
            case 'mh' :
                $pass=md5($password);
                if ($result->k_status=='A' || $result->k_status=='C' || $result->k_status=='N') {                                        			
                    $message="Gagal. Silahkan masukan username dan password dengan benar.";
                }else{
                    $message="Mohon maaf status Anda diluar AKTIF. Hubungi Bagian Administrasi.";		
                }
            break;
            case 'al' :
                $pass=md5($password);
                if ($result->k_status=='L') {                                        			
                    $message="Gagal. Silahkan masukan username dan password dengan benar.";
                }else{
                    $message="Mohon maaf status Anda diluar LULUS. Hubungi Bagian Administrasi.";		
                }
            break;
			case 'mb' :
				$pass=hash('sha256', $result->salt . hash('sha256', $password));
				$_SESSION['userpassword_mb']=$password;
                $message="Gagal. Silahkan masukan username dan password dengan benar.";
			break;
            case 'pmb' :
            case 'dw' :
            case 'd' :
            case 'k' :
            case 'on' :
            case 'api' :
            case 'sa' :
                $pass=hash('sha256', $result->salt . hash('sha256', $password));
                $message="Gagal. Silahkan masukan username dan password dengan benar.";
            break;
            case 'm' :
                if ($result->salt=='') {
                    $pass=md5($password);
                }else{
                    $pass=hash('sha256', $result->salt . hash('sha256', $password));
                }
                $message="Gagal. Silahkan masukan username dan password dengan benar.";
            break;
            default :
                $message="Gagal. Silahkan masukan username dan password dengan benar.";
                $pass=md5($password);
        }    
		if ($result->userpassword==$pass && $result->active==1) {
            $token = $this->guard()->login($result);
            return $this->respondWithToken($token);
        }else{			
            return response()->json([
                                    'page' => 'login',
                                    'error' => $message,                                    
                                ], 401);        
        }        
        
    }
    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        $user = $this->guard()->user()->toArray();
        // if ($this->hasRole('mahasiswabaru'))
        // {
        //     $formulir = \App\Models\SPMB\FormulirPendaftaranModel::find($user['id']);
        //     $user['idsmt']=$formulir->idsmt;
        // }
        // $user['role']=$this->getRoleNames();
        // $user['issuperadmin']=$this->hasRole('superadmin');
        // $user['isdw']=$this->hasRole('dosenwali');
        // $user['permissions']=$this->guard()->user()->permissions->pluck('id','name')->toArray();
        return response()->json($user);
    }
    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
    {
        //log user logout
        // \App\Models\System\ActivityLog::log($request,[
        //     'object' => $this->guard()->user(),             
        //     'object_id' => $this->getUserid(), 
        //     'user_id' => $this->getUserid(), 
        //     'message' => 'user '.$this->guard()->user()->username.' berhasil logout'
        // ],1);

        $this->guard()->logout();

        return response()->json(['message' => 'Successfully logged out'],200);
    }
    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken($this->guard()->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => $this->guard()->factory()->getTTL() * 60
        ]);
    }
}
