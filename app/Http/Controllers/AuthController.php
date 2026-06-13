<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',array:5 [▼ // app/Http/Controllers/AuthController.php:28
  "attempt_result" => true
  "auth_check" => true
  "auth_user" => App\Models
\
User {
#1553 ▼
    #connection: "mysql"
    #table: "users"
    #primaryKey: "id"
    #keyType: "int"
    +incrementing: true
    #with: []
    #withCount: []
    +preventsLazyLoading: false
    #perPage: 15
    +exists: true
    +wasRecentlyCreated: false
    #escapeWhenCastingToString: false
    #attributes: array:10 [▶]
    #original: array:10 [▶]
    #changes: []
    #previous: []
    #casts: array:1 [▶]
    #classCastCache: []
    #attributeCastCache: []
    #dateFormat: null
    #appends: []
    #dispatchesEvents: []
    #observables: []
    #relations: []
    #touches: []
    #relationAutoloadCallback: null
    #relationAutoloadContext: null
    +timestamps: true
    +usesUniqueIds: false
    #hidden: array:2 [▶]
    #visible: []
    #fillable: array:5 [▼
      0 => "username"
      1 => "nama_lengkap"
      2 => "email"
      3 => "password"
      4 => "role"
    ]
    #guarded: array:1 [▶]
    #authPasswordName: "password"
    #rememberTokenName: "remember_token"
    -roleClass: null
    -permissionClass: null
    -wildcardClass: null
    -wildcardPermissionsIndex: ? array
  }
  "session_id" => "oXis3x4rRflqY2tFHyv8z0oO3NKBeR8l9sP9Auej"
  "credentials" => array:2 [▼
    "username" => "admin"
    "password" => "12345"
  ]
]


            'password' => 'required|string',
        ]);

        if (Auth::attempt(['username' => $request->username, 'password' => $request->password], $request->remember)) {
            $request->session()->regenerate();
            \Log::info('Login successful for user: ' . $request->username, [
                'session_id' => $request->session()->getId(),
                'is_auth' => Auth::check(),
            ]);
            return redirect()->route('dashboard');
        }

        return back()->withErrors([
            'username' => 'Username atau password salah.',
        ])->onlyInput('username');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}
