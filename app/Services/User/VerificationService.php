<?php

namespace App\Services\User;

use App\Mail\VerificationCodeMail;
use Illuminate\Support\Facades\Mail;
use App\Models\User;

class VerificationService{
    public function sendVerificationCode(User $user): void
    {
        Mail::to($user->email)->send(new VerificationCodeMail($user->verification_code));
    }
}
