<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Tenant\TenantManager;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    protected function credentials(Request $request)
    {
        $data =  $request->only($this->username(), 'password');
        $tenantManager = app(TenantManager::class);
        if($tenantManager->getTenant() && !$tenantManager->isSubdomainException()){
            $data['tenant_id'] = $tenantManager->getTenant()->id;
            $this->redirectTo = $tenantManager->getTenant()->sub_domain.'/home';
        }
        return $data;
    }

    public function logout(Request $request)
    {
        $this->guard()->logout();

        $request->session()->invalidate();
        $tenantManager = app(TenantManager::class);
        return $this->loggedOut($request) ?: redirect($tenantManager->getTenant()->sub_domain.'/home');
    }
}
