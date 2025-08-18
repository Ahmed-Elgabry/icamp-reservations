<?php

namespace App\Http\Controllers;

use App\Models\Survey;
use App\Models\SurveyResponse;
use App\Models\SurveyAnswer;
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
        $request->validate([
            'responses' => 'required|array',
            'responses.*.question_id' => 'required|exists:survey_questions,id',
            'responses.*.answer' => 'required',
        ]);

        // Create survey response
        $surveyResponse = SurveyResponse::create([
            'survey_id' => $survey->id,
            'user_id' => auth()->id(),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'submitted_at' => now(),
        ]);

        // Create answers for each question
        foreach ($request->responses as $response) {
            $question = $survey->questions()->find($response['question_id']);

            SurveyAnswer::create([
                'survey_response_id' => $surveyResponse->id,
                'survey_question_id' => $response['question_id'],
                'answer_text' => is_array($response['answer']) ? null : $response['answer'],
                'answer_option' => is_array($response['answer']) ? $response['answer'] : null,
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
