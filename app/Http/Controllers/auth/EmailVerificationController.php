<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class EmailVerificationController extends Controller
{
    public function notice()
    {
        return view('auth.verify-email');
    }

    public function verify(Request $request, $id)
    {
        try {
            DB::beginTransaction();
            
            // Find the user
            $user = User::findOrFail($id);
            
            // Verify hash matches
            if (!hash_equals(sha1($user->getEmailForVerification()), $request->hash)) {
                throw new \Exception('Invalid verification link');
            }

            // Check if already verified
            if ($user->hasVerifiedEmail()) {
                return redirect()->route('login')
                    ->with('success', 'Email already verified. You can now login.');
            }

            // Mark email as verified
            $user->forceFill([
                'email_verified_at' => Carbon::now()
            ])->save();

            DB::commit();

            return redirect()->route('login')
                ->with('success', 'Email verified successfully! Please wait for admin approval before logging in.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('login')
                ->with('error', 'Verification failed: ' . $e->getMessage());
        }
    }

    public function resend(Request $request)
    {
        $request->user()->sendEmailVerificationNotification();
        return back()->with('status', 'verification-link-sent');
    }
}