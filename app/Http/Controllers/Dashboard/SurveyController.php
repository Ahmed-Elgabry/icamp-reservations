<?php

namespace App\Http\Controllers\Dashboard;

use App\Helpers\SurveyHelper;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Survey;
use App\Models\SurveyQuestion;
use Barryvdh\Debugbar\Facades\Debugbar;
use Illuminate\Http\Request;

class SurveyController extends Controller
{
    /**
     * Display a listing of surveys.
     *
     * @return \Illuminate\Http\Response
     */
    public function statistics()
    {
        $survey = Survey::with('questions')->find(1);
        $responses = $survey->responses()->with('answers.question')->get();
        $totalResponses = $responses->count();

        // Get question types distribution
        $questionTypes = [
            'text' => 0,
            'textarea' => 0,
            'radio' => 0,
            'checkbox' => 0,
            'select' => 0,
            'stars' => 0,
            'rating' => 0
        ];

        foreach ($survey->questions as $question) {
            if (isset($questionTypes[$question->question_type])) {
                $questionTypes[$question->question_type]++;
            }
        }

        // Get responses by date for the last 7 days
        $timelineData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $dateStr = $date->format('Y-m-d');
            $timelineData[$dateStr] = $responses->where('created_at', '>=', $date->startOfDay())
                ->where('created_at', '<=', $date->endOfDay())
                ->count();
        }

        // Get popular questions with answer counts
        $popularQuestions = $survey->questions()
            ->withCount(['answers' => function($query) {
                $query->whereNotNull('answer_text');
            }])
            ->orderBy('answers_count', 'desc')
            ->take(5)
            ->get();

        // Prepare the popular questions for the chart
        $popularQuestionsData = $popularQuestions->map(function ($question) {
            return [
                'title' => SurveyHelper::getLocalizedText($question->question_text),
                'answers_count' => $question->answers_count
            ];
        });

        // Get all questions with answer counts for the table
        $allQuestions = $survey->questions()
            ->withCount(['answers' => function($query) {
                $query->whereNotNull('answer_text');
            }])
            ->orderBy('answers_count', 'desc')
            ->get();

        // Prepare the data for the table
        $allQuestionsData = $allQuestions->map(function ($question) {
            return [
                'id' => $question->id,
                'title' => SurveyHelper::getLocalizedText($question->question_text),
                'type' => $question->question_type,
                'answers_count' => $question->answers_count
            ];
        });

        // Get questions with options for the select dropdown
        $questionsWithOptions = $survey->questions()
            ->whereIn('question_type', ['radio', 'checkbox', 'select'])
            ->get()
            ->map(function ($question) {
                // Ensure options are properly formatted as strings
                $formattedOptions = [];
                if (is_array($question->options)) {
                    foreach ($question->options as $key => $option) {
                        // Handle different option formats
                        if (is_array($option)) {
                            // If option is an array, try to get the label
                            if (isset($option['label'])) {
                                $formattedOptions[] = SurveyHelper::getLocalizedText($option['label']);
                            } else {
                                $formattedOptions[] = is_string($key) ? $key : json_encode($option);
                            }
                        } elseif (is_object($option)) {
                            // If option is an object, convert to array and handle
                            $optionArray = (array)$option;
                            if (isset($optionArray['label'])) {
                                $formattedOptions[] = SurveyHelper::getLocalizedText($optionArray['label']);
                            } else {
                                $formattedOptions[] = is_string($key) ? $key : json_encode($optionArray);
                            }
                        } else {
                            // If option is a simple value, use it directly
                            $formattedOptions[] = (string)$option;
                        }
                    }
                }

                return [
                    'id' => $question->id,
                    'title' => SurveyHelper::getLocalizedText($question->question_text),
                    'type' => $question->question_type,
                    'options' => $formattedOptions
                ];
            });

        return view('dashboard.surveys.statistics')
            ->with('survey', $survey)
            ->with('totalResponses', $totalResponses)
            ->with('questionTypes', $questionTypes)
            ->with('timelineData', $timelineData)
            ->with('popularQuestionsData', $popularQuestionsData)
            ->with('allQuestionsData', $allQuestionsData)
            ->with('responses', $responses)
            ->with('questionsWithOptions', $questionsWithOptions);
    }

    /**
     * Show the form for creating a new survey.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $survey = Survey::with('questions')->find(1);

        // Transform the data to match JavaScript expectations
        if ($survey) {
            $surveyArray = $survey->toArray();
            // Rename questions to fields for JavaScript compatibility
            $surveyArray['fields'] = $surveyArray['questions'];
            unset($surveyArray['questions']);
            $survey = $surveyArray;
        }

        Debugbar::disable();
        return view('dashboard.surveys.builder')
        ->with('survey', $survey);
    }

    /**
     * Update the specified survey in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Survey  $survey
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Survey $survey)
    {
        $validated = $request->validate([
            'survey.title' => 'required|string|max:255',
            'survey.description' => 'nullable|string',
            'survey.questions' => 'required|array',
            'survey.questions.*.question_text' => 'required|array',
            'survey.questions.*.question_type' => 'required|string',
            'survey.questions.*.placeholder' => 'nullable|array',
            'survey.questions.*.help_text' => 'nullable|array',
            'survey.questions.*.error_message' => 'nullable|string',
            'survey.questions.*.options' => 'nullable|array',
            'survey.questions.*.settings' => 'nullable|array',
        ]);

        // Update survey
        $survey->update([
            'title' => $validated['survey']['title'],
            'description' => $validated['survey']['description'] ?? null,
        ]);

        // Delete existing questions
        $survey->questions()->delete();

        // Create new questions
        foreach ($validated['survey']['questions'] as $index => $questionData) {
            SurveyQuestion::create([
                'survey_id' => $survey->id,
                'question_text' => $questionData['question_text'],
                'question_type' => $questionData['question_type'],
                'placeholder' => $questionData['placeholder'] ?? null,
                'help_text' => $questionData['help_text'] ?? null,
                'error_message' => $questionData['error_message'] ?? null,
                'options' => $questionData['options'] ?? null,
                'settings' => $questionData['settings'] ?? null,
                'order' => $index,
            ]);
        }

        return response()->json(['success' => true]);
    }

    /**
     * Display the specified survey.
     *
     * @param  \App\Models\Survey  $survey
     * @return \Illuminate\Http\Response
     */
    public function show(Order $order)
    {
        $survey = Survey::with('questions')->find(1);

        // Transform the data to match JavaScript expectations
        if ($survey) {
            $surveyArray = $survey->toArray();
            // Rename questions to fields for JavaScript compatibility
            $surveyArray['fields'] = $surveyArray['questions'];
            unset($surveyArray['questions']);
            $survey = $surveyArray;
        }
        return view('dashboard.surveys.show')
        ->with('order',$order)
        ->with('survey',$survey);
    }

    /**
     * Display survey results.
     *
     * @param  \App\Models\Survey  $survey
     * @return \Illuminate\Http\Response
     */
    public function results(Survey $survey)
    {
        $responses = $survey->responses()->with(['answers.question', 'order.customer'])->latest()->paginate(10);
        return view('dashboard.surveys.results', compact('survey', 'responses'));
    }

    /**
     * Display survey answer details.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function answer($id)
    {
        $survey = Survey::with('questions')->find(1);
        $response = $survey->responses()->with(['answers.question', 'order.customer'])->findOrFail($id);
        return view('dashboard.surveys.answer', compact('survey','response'));
    }
}
