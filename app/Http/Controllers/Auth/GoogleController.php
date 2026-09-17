<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Contracts\User as SocialiteUser;
use Laravel\Socialite\Facades\Socialite;
use Symfony\Component\HttpFoundation\RedirectResponse as SymfonyRedirect;

class GoogleController extends Controller
{
    // Mengarahkan pengguna ke halaman login Google
    public function redirectToGoogle(): SymfonyRedirect
    {
        return Socialite::driver('google')->redirect();
    }

    // Menangani callback dari Google setelah login sukses
    public function handleGoogleCallback(): RedirectResponse
    {
        try {
            /** @var SocialiteUser $googleUser */
            $googleUser = Socialite::driver('google')->user();

            // Cari apakah user dengan google_id tersebut sudah ada
            $findUser = User::where('google_id', $googleUser->getId())->first();

            if ($findUser) {
                // Jika sudah ada, langsung loginkan
                Auth::login($findUser);

                return redirect()->intended('dashboard');
            } else {
                // Jika belum ada, cek berdasarkan email
                $userByEmail = User::where('email', $googleUser->getEmail())->first();

                if ($userByEmail) {
                    // Jika email sudah terdaftar (tanpa Google sebelumnya), hubungkan akunnya
                    $userByEmail->update(['google_id' => $googleUser->getId()]);
                    Auth::login($userByEmail);
                } else {
                    // Jika benar-benar pengguna baru, daftarkan akun baru
                    $newUser = User::create([
                        'name' => $googleUser->getName(),
                        'email' => $googleUser->getEmail(),
                        'google_id' => $googleUser->getId(),
                        'password' => null, // Kosong karena login via OAuth
                        'email_verified_at' => now(),
                    ]);

                    Auth::login($newUser);
                }

                return redirect()->intended('dashboard');
            }
        } catch (Exception $e) {
            return redirect()->route('login')->with('error', 'Gagal login menggunakan Google.');
        }
    }
}
