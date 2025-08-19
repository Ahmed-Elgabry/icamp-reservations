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
        // Validate required questions
        $rules = [];
        $validated = $request->validate($rules);

        // Create survey response
        $response = SurveyResponse::create([
            'survey_id' => $survey->id,
            'reservation_id' => $request->order_id,
            'user_id' => auth()->id() ?? null, // Allow null for non-authenticated users
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'submitted_at' => now(),
        ]);

        // Save answers
        foreach ($survey->questions as $question) {
            $answerValue = null;

            // Check for both prefixes
            if ($request->has("question_{$question->id}")) {
                $answerValue = $request->input("question_{$question->id}");
            } elseif ($request->has("field_{$question->id}")) {
                $answerValue = $request->input("field_{$question->id}");
            }

            // Handle checkbox arrays
            if ($question->question_type === 'checkbox') {
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
        return view('dashboard.surveys.thankyou', compact('survey'));
    }
}
