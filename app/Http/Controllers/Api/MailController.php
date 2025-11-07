<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class MailController extends Controller
{
    public function send(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email',
            'message' => 'required|string|max:1000',
        ]);

        // ✅ Send auto-reply to client
        Mail::raw("Hi {$validated['name']},\n\nThanks for reaching out! We'll get back to you soon.", function ($mail) use ($validated) {
            $mail->to($validated['email'])
                ->subject('Thanks for contacting Zamsam Engineering');
        });

        // ✅ Send notification to admin
        Mail::raw("New contact form submission:\n\nName: {$validated['name']}\nEmail: {$validated['email']}\nMessage:\n{$validated['message']}", function ($mail) {
            $mail->to('micky.mpd@gmail.com')
                ->subject('New Contact Form Submission');
        });

        return response()->json(['message' => 'Mail sent successfully']);
    }
}
