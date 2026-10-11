<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ChatbotController extends Controller
{
    public function showchat()
    {
        return view('chatbot');
    }
}
