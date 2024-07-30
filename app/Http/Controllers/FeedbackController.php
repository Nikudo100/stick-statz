<?php

namespace App\Http\Controllers;

use App\Http\Requests\FeedbackRequest;
use App\Models\Feedback;
use Illuminate\Http\Request;

class FeedbackController extends Controller
{
    function show()
    {
        $feedbacks = Feedback::whereState('none')->orderByDesc('wb_created_at')->get();
        return view('feedbacks.show', compact('feedbacks'));
    }

    // TODO: подвязать апи ответа вб
    function update(Feedback $feedback, FeedbackRequest $request)
    {
        $validated = $request->validated();
        $validated['answer_at'] = now();
        $validated['user_id'] = $request->user()->id;
        $feedback->update($validated);

        return redirect()->back();
    }

    // TODO: сделать метод автоматического ответа
}
