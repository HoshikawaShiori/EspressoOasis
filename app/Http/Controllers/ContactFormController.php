<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactFormMail;
use App\Models\Contact;

class ContactFormController extends Controller
{
    public function submitForm(Request $request)
    {
        $fromAddress = 'jprofeta000@gmail.com';
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);
        Mail::to('jprofeta000@gmail.com')->send(new ContactFormMail($validatedData));
        return redirect()->back()->with('success', 'Your message has been sent successfully!');
    }
}
