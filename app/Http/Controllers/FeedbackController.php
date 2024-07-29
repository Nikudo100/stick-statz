<?php

namespace App\Http\Controllers;

use App\Models\Feedback;
use Illuminate\Http\Request;

class FeedbackController extends Controller
{
    function show()
    {
        $feedbacks = Feedback::all();
        return view('feedbacks.show', compact('feedbacks'));
    }
}
