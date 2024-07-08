<?php

namespace App\Http\Controllers;
use App\Mail\VerificationEmail;
use Illuminate\Support\Facades\Mail;

abstract class Controller
{

public function sendVerificationEmail()
{
    $details = [
        'title' => 'Mail from MyApp',
        'body' => 'This is for testing email using smtp.'
    ];

    Mail::to('laravel@example.com')->send(new VerificationEmail($details));

    return 'Email sent successfully';
}

}
