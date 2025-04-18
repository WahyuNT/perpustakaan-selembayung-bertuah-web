<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Cookie;

class LoginController extends Controller
{
    public function loginPage(Request $request)
    {
        $token = $request->bearerToken() ?? $request->cookie('token');
        if ($token) {

            return redirect()->route('admin-manajemen-user');
        } else {
            return view('pages.login');
        }


        return view('pages.login');
    }
    public function registerPage(Request $request)
    {

        $token = $request->bearerToken() ?? $request->cookie('token');

        if ($token) {

            return redirect()->route('admin-manajemen-buku');
        } else {
            return view('pages.register');
        }
    }
    public function login(Request $request)
    {
        $token = $request->bearerToken() ?? $request->cookie('token');
        if ($token) {

            return redirect()->route('admin.dashboard');
        } else {
            return view('pages.login');
        }


        return view('pages.login');
    }

    public function register()
    {
        return view('pages.register');
    }

    public function registerProses(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:users,name',
            'email' => 'required|email|unique:users,email',
            'password' => 'required',
            'nis' => 'required|unique:users,nis',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }


        $data = new User();
        $data->name = $request->input('name');
        $data->email = $request->input('email');
        $data->nis = $request->input('nis');

        $data->password = bcrypt($request->input('password'));

        if ($data->save()) {

            return redirect('/login')->with('success', 'Pendaftaran akun berhasil, Silahkan Login.');
        } else {

            return redirect('/register')->with('error', 'Pendaftaran akun gagal, Silahkan Ulangi.');
        }
    }

    public function loginStore(Request $request)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'email' => 'required',
            'password' => 'required'
        ]);

        // Jika validasi gagal
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        // Mengambil credentials dari request
        $credentials = $request->only('email', 'password');

        try {
            // Coba membuat token menggunakan credentials yang diberikan
            if (!$token = JWTAuth::attempt($credentials)) {

                return redirect('/login')->with('error', 'Email dan Password salah.')->withInput();
            }
        } catch (JWTException $e) {

            return redirect('/login')->with('error', 'Silahkan Ulangi.')->withInput();
        }

        // Mengatur cookie dengan token JWT
        $cookie = $this->getCookieWithToken($token);

        // Mengembalikan response dengan cookie
        return redirect()->route('dashboard')->withCookie($cookie);
    }

    public function logout(Request $request)
    {
        // Menghapus token dari cookie
        $cookie = Cookie::forget('token');

        return redirect('/login')->withCookie($cookie);
    }

    protected function getCookieWithToken($token)
    {
        return cookie(
            'token',
            $token,
            5760,
            null,
            null,
            false,
            true,
            false,
            'Strict'
        );
    }
}
