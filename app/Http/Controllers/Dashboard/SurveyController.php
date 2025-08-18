<?php

namespace App\Http\Controllers\Dashboard;

use App\Helpers\SurveyHelper;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Survey;
use App\Models\SurveyQuestion;
use Illuminate\Http\Request;

class SurveyController extends Controller
{
    /**
     * Display a listing of surveys.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $surveys = Survey::latest()->paginate(10);
        return view('dashboard.surveys.index', compact('surveys'));
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

        return view('dashboard.surveys.builder')
        ->with('survey', $survey);
    }

    /**
     * Store a newly created survey in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'survey.title' => 'required|string|max:255',
            'survey.description' => 'nullable|string',
            'survey.is_active' => 'boolean',
            'survey.starts_at' => 'nullable|date',
            'survey.ends_at' => 'nullable|date|after_or_equal:starts_at',
            'survey.questions' => 'required|array',
            'survey.questions.*.question_text' => 'required|string',
            'survey.questions.*.question_type' => 'required|string',
            'survey.questions.*.placeholder' => 'nullable|string',
            'survey.questions.*.help_text' => 'nullable|string',
            'survey.questions.*.validation_type' => 'nullable|string',
            'survey.questions.*.min_length' => 'nullable|integer|min:0',
            'survey.questions.*.max_length' => 'nullable|integer|min:1',
            'survey.questions.*.error_message' => 'nullable|string',
            'survey.questions.*.options' => 'nullable|array',
            'survey.questions.*.settings' => 'nullable|array',
        ]);

        // Create survey
        $survey = Survey::create([
            'title' => $validated['survey']['title'],
            'description' => $validated['survey']['description'] ?? null,
            'is_active' => $validated['survey']['is_active'] ?? true,
            'starts_at' => $validated['survey']['starts_at'] ?? null,
            'ends_at' => $validated['survey']['ends_at'] ?? null,
        ]);

        // Create questions
        foreach ($validated['survey']['questions'] as $index => $questionData) {
            SurveyQuestion::create([
                'survey_id' => $survey->id,
                'question_text' => $questionData['question_text'],
                'question_type' => $questionData['question_type'],
                'placeholder' => $questionData['placeholder'] ?? null,
                'help_text' => $questionData['help_text'] ?? null,
                'validation_type' => $questionData['validation_type'] ?? 'none',
                'min_length' => $questionData['min_length'] ?? null,
                'max_length' => $questionData['max_length'] ?? null,
                'error_message' => $questionData['error_message'] ?? null,
                'options' => $questionData['options'] ?? null,
                'settings' => $questionData['settings'] ?? null,
                'order' => $index,
            ]);
        }

        return response()->json(['id' => $survey->id]);
    }

    /**
     * Show the form for editing the specified survey.
     *
     * @param  \App\Models\Survey  $survey
     * @return \Illuminate\Http\Response
     */
    public function edit(Survey $survey)
    {
        return view('dashboard.surveys.builder', compact('survey'));
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
            'survey.is_active' => 'boolean',
            'survey.starts_at' => 'nullable|date',
            'survey.ends_at' => 'nullable|date|after_or_equal:starts_at',
            'survey.questions' => 'required|array',
            'survey.questions.*.question_text' => 'required|string',
            'survey.questions.*.question_type' => 'required|string',
            'survey.questions.*.placeholder' => 'nullable|string',
            'survey.questions.*.help_text' => 'nullable|string',
            'survey.questions.*.validation_type' => 'nullable|string',
            'survey.questions.*.min_length' => 'nullable|integer|min:0',
            'survey.questions.*.max_length' => 'nullable|integer|min:1',
            'survey.questions.*.error_message' => 'nullable|string',
            'survey.questions.*.options' => 'nullable|array',
            'survey.questions.*.settings' => 'nullable|array',
        ]);

        // Update survey
        $survey->update([
            'title' => $validated['survey']['title'],
            'description' => $validated['survey']['description'] ?? null,
            'is_active' => $validated['survey']['is_active'] ?? true,
            'starts_at' => $validated['survey']['starts_at'] ?? null,
            'ends_at' => $validated['survey']['ends_at'] ?? null,
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
                'validation_type' => $questionData['validation_type'] ?? 'none',
                'min_length' => $questionData['min_length'] ?? null,
                'max_length' => $questionData['max_length'] ?? null,
                'error_message' => $questionData['error_message'] ?? null,
                'options' => $questionData['options'] ?? null,
                'settings' => $questionData['settings'] ?? null,
                'order' => $index,
            ]);
        }

        return response()->json(['success' => true]);
    }

    /**
     * Remove the specified survey from storage.
     *
     * @param  \App\Models\Survey  $survey
     * @return \Illuminate\Http\Response
     */
    public function destroy(Survey $survey)
    {
        $survey->delete();
        return redirect()->route('admin.surveys.index')->with('success', 'Survey deleted successfully');
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
        $responses = $survey->responses()->with('answers.question')->latest()->paginate(10);
        return view('dashboard.surveys.results', compact('survey', 'responses'));
    }
}
