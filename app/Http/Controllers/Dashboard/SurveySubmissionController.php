<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Survey;
use App\Models\SurveyAnswer;
use App\Models\SurveyResponse;
use Illuminate\Http\Request;

class SurveySubmissionController extends Controller
{
    /**
     * Submit a survey response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Survey  $survey
     * @return \Illuminate\Http\Response
     */
    public function submit(Request $request, Survey $survey)
    {
        // Validate survey is active and within date range
        if (!$survey->is_active) {
            return redirect()->back()->with('error', 'هذا الاستبيان غير نشط حالياً');
        }

        if ($survey->starts_at && now()->lt($survey->starts_at)) {
            return redirect()->back()->with('error', 'هذا الاستبيان لم يبدأ بعد');
        }

        if ($survey->ends_at && now()->gt($survey->ends_at)) {
            return redirect()->back()->with('error', 'هذا الاستبيان انتهت صلاحيته');
        }

        // Validate required questions
        $rules = [];
        foreach ($survey->questions as $question) {
            if ($question->is_required) {
                $rules["question_{$question->id}"] = 'required';
            }
        }

        $validated = $request->validate($rules);

        // Create survey response
        $response = SurveyResponse::create([
            'survey_id' => $survey->id,
            'user_id' => auth()->id(),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'submitted_at' => now(),
        ]);

        // Save answers
        foreach ($survey->questions as $question) {
            $answerValue = $request->input("question_{$question->id}");

            // Handle different question types
            if (in_array($question->question_type, ['checkbox'])) {
                // Checkbox questions can have multiple values
                $answerValue = $request->input("question_{$question->id}", []);
            }

            SurveyAnswer::create([
                'survey_response_id' => $response->id,
                'survey_question_id' => $question->id,
                'answer_text' => is_array($answerValue) ? null : $answerValue,
                'answer_option' => is_array($answerValue) ? $answerValue : null,
            ]);
        }

        return redirect()->route('surveys.thankyou', $survey);
    }

    /**
     * Show thank you page after survey submission.
     *
     * @param  \App\Models\Survey  $survey
     * @return \Illuminate\Http\Response
     */
    public function thankyou(Survey $survey)
    {
        return view('surveys.thankyou', compact('survey'));
    }
}
