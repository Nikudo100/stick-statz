<?php

namespace App\Http\Controllers;

use App\Http\Requests\AutoAnswerRequest;
use App\Models\TemplateAnswerFeedback;
use Illuminate\Http\Request;

class AutoAnswerController extends Controller
{
    function create(AutoAnswerRequest $request)
    {
        $validated = $request->validated();
        TemplateAnswerFeedback::create($validated);

        return redirect()->back();
    }
    function update(AutoAnswerRequest $request, TemplateAnswerFeedback $autoAnswer)
    {
        $validated = $request->validated();
        $autoAnswer->update($validated);

        return redirect()->back();
    }

    function destroy(TemplateAnswerFeedback $autoAnswer)
    {
        $autoAnswer->delete();

        return redirect()->back();
    }
}
