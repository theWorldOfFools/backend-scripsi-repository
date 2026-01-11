<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\UpdateProfileRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Request;
use Tymon\JWTAuth\Facades\JWTAuth;


class AuthController extends Controller
{
    public function register(RegisterRequest $request)
    {
        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'no_telepon'=>$request->no_telepon
        ]);


        return response()->json([
            'user'  => $user,
        ]);
    }

    public function login(LoginRequest $request)
    {
        $credentials = $request->only('email', 'password');

        if (!$token = JWTAuth::attempt($credentials)) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        return response()->json([
            'user'  => Auth::user(),
            'token' => $token,
        ]);
    }

        // 1. Get profile by user_id
    public function profile($id)
    {
        $user = User::findOrFail($id);
        return response()->json($user);
    }

    // 2. Update profile
    public function updateProfile(UpdateProfileRequest $request, $id)
    {
        $user = User::findOrFail($id);
        $user->update($request->validated());
        return response()->json(['message' => 'Profil berhasil diperbarui', 'user' => $user]);
    }

    public function logout()
    {
        JWTAuth::invalidate(JWTAuth::getToken());

        return response()->json(['message' => 'Logged out successfully']);
    }

public function updatePassword(Request $request, $user_id)
{
    $request->validate([
        'current_password' => 'required',
        'new_password' => 'required|min:6|confirmed', // perlu new_password_confirmation
    ]);

    $user = User::findOrFail($user_id);

    if (!Hash::check($request->current_password, $user->password)) {
        return response()->json(['message' => 'Password saat ini salah.'], 403);
    }

    $user->password = Hash::make($request->new_password);
    $user->save();

    return response()->json(['message' => 'Password berhasil diperbarui.']);
}

}


?>