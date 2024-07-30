<?php

namespace App\Http\Controllers;

use App\Http\Requests\FeedbackRequest;
use App\Models\Feedback;
use App\Models\TemplateAnswerFeedback;
use App\Services\Fetch\Wb\Feedbacks;
use Illuminate\Http\Request;

class FeedbackController extends Controller
{
    function __construct(protected Feedbacks $feedbacksWbService)
    {
    }

    function show()
    {
        $feedbacks = Feedback::whereState('none')->orderByDesc('wb_created_at')->get();
        return view('feedbacks.show', compact('feedbacks'));
    }

    function update(Feedback $feedback, FeedbackRequest $request)
    {
        $validated = $request->validated();
        $validated['answer_at'] = now();
        $validated['user_id'] = $request->user()->id;

        $this->feedbacksWbService->editFeedback($feedback->wb_id, $validated['answer']);
        $feedback->update($validated);

        return redirect()->back();
    }

    function autoAnswer(Feedback $feedback)
    {
        $templateAnswer = TemplateAnswerFeedback::whereStars($feedback->product_valuation)
            ->get()
            ->random();

        if (!$templateAnswer)
            return redirect()->back();

        $this->feedbacksWbService->editFeedback($feedback->wb_id, $templateAnswer->answer);
        $feedback->update([
            'answer' => $templateAnswer->answer,
            'template_answer_feedback_id' => $templateAnswer->id
        ]);

        return redirect()->back();
    }
}
