<?php

namespace App\Http\Controllers\Dashboard;

use App\Helpers\SurveyHelper;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Survey;
use App\Models\SurveyQuestion;
use Barryvdh\Debugbar\Facades\Debugbar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class SurveyController extends Controller
{
    /**
     * Display the survey settings page.
     *
     * @return \Illuminate\Http\Response
     */
    public function settings()
    {
        $survey = Survey::find(1);
        // Get schedule settings from database or use defaults
        $settings = [
            'days_after_completion' => $survey->settings['days_after_completion'] ?? 1,
            'send_time' => $survey->settings['send_time'] ?? '15:00',
            'enabled' => $survey->settings['enabled'] ?? 1,
        ];

        // Get SMTP and from settings from config (which reads from .env)
        $smtpSettings = [
            'smtp_host' => config('mail.mailers.smtp.host'),
            'smtp_port' => config('mail.mailers.smtp.port'),
            'smtp_username' => config('mail.mailers.smtp.username'),
            'smtp_encryption' => config('mail.mailers.smtp.encryption'),
            'smtp_password' => config('mail.mailers.smtp.password'),
            'from_email' => config('mail.from.address'),
            'from_name' => config('mail.from.name'),
        ];

        // Merge all settings
        $allSettings = array_merge($smtpSettings, $settings);

        return view('dashboard.surveys.settings')->with('settings', $allSettings);
    }

    /**
     * Update the survey settings.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Survey  $survey
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateSettings(Request $request, Survey $survey)
    {
        $validated = $request->validate([
            'smtp_host' => 'nullable|string|max:255',
            'smtp_port' => 'nullable|integer|min:1|max:65535',
            'smtp_username' => 'nullable|string|max:255',
            'smtp_password' => 'nullable|string|max:255',
            'smtp_encryption' => 'nullable', // Fixed validation rule
            'from_email' => 'nullable|email|max:255',
            'from_name' => 'nullable|string|max:255',
            'days_after_completion' => 'required|integer|min:0|max:30',
            'send_time' => 'required|date_format:H:i',
            'enabled' => 'nullable|boolean',
        ]);

        // Convert enabled to boolean
        $validated['enabled'] = isset($validated['enabled']) ? 1 : 0;

        // Update .env for SMTP and from settings
        $envData = [
            'MAIL_HOST' => $validated['smtp_host'] ?? config('mail.mailers.smtp.host'),
            'MAIL_PORT' => $validated['smtp_port'] ?? config('mail.mailers.smtp.port'),
            'MAIL_USERNAME' => $validated['smtp_username'] ?? config('mail.mailers.smtp.username'),
            'MAIL_PASSWORD' => $validated['smtp_password'] ?? config('mail.mailers.smtp.password'),
            'MAIL_ENCRYPTION' => $validated['smtp_encryption'] ?? config('mail.mailers.smtp.encryption'),
            'MAIL_FROM_ADDRESS' => $validated['from_email'] ?? config('mail.from.address'),
            'MAIL_FROM_NAME' => $validated['from_name'] ?? config('mail.from.name'),
        ];

        // Update .env file
        $this->updateEnv($envData);

        // Clear config cache to apply changes
        Artisan::call('config:clear');

        // Update the database for schedule settings only
        $scheduleSettings = [
            'days_after_completion' => $validated['days_after_completion'],
            'send_time' => $validated['send_time'],
            'enabled' => $validated['enabled'],
        ];

        // Get current settings from the survey
        $currentSettings = $survey->settings ?? [];
        // Merge the schedule settings
        $survey->settings = array_merge($currentSettings, $scheduleSettings);
        $survey->save();

        return redirect()->route('surveys.settings')
            ->with('success', __('dashboard.settings_updated_successfully'));
    }

    /**
     * Update the .env file with the given data.
     *
     * @param array $data
     * @return void
     */
    private function updateEnv(array $data)
    {
        $envFile = app()->environmentFilePath();
        $str = file_get_contents($envFile);

        if (count($data) > 0) {
            foreach ($data as $key => $value) {
                // Escape the value if it contains spaces or special characters
                $value = str_replace('"', '\"', $value);
                $str = preg_replace("/^{$key}=.*/m", "{$key}=\"{$value}\"", $str);
            }
        }

        file_put_contents($envFile, $str);
    }

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
            ->whereIn('question_type', ['radio', 'checkbox', 'select', 'stars', 'rating'])
            ->get()
            ->map(function ($question) {
                $formattedOptions = [];

                if (in_array($question->question_type, ['stars', 'rating'])) {
                    // For stars and rating, always generate options from 1 to 5
                    for ($i = 1; $i <= $question->settings['points']; $i++) {
                        if ($question->question_type === 'stars') {
                            $formattedOptions[] = str_repeat('★', $i); // ★, ★★, ★★★, etc.
                        } else {
                            $formattedOptions[] = (string)$i; // 1, 2, 3, etc.
                        }
                    }
                } elseif (is_array($question->options)) {
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
        \Barryvdh\Debugbar\Facades\Debugbar::disable();

        $survey = Survey::with(['questions' => function ($query) {
            $query->where('hidden', 0); // only non-hidden questions
        }])->find(1);

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
     * Update the specified survey in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Survey  $survey
     * @return \Illuminate\Http\Response
     */
// In your SurveyController.php, update the update method
// In your SurveyController.php, update the update method
public function update(Request $request, Survey $survey)
{
    $validated = $request->validate([
        'survey.title' => 'required|string|max:255',
        'survey.description' => 'nullable|string',
        'survey.questions' => 'required|array',
        'survey.questions.*.id' => 'nullable|string', // Add this validation
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

    // Get existing question IDs
    $existingQuestionIds = $survey->questions()->pluck('id')->toArray();
    $incomingQuestionIds = [];

    // Process each question
    foreach ($validated['survey']['questions'] as $index => $questionData) {
        // Extract database ID from frontend ID if it exists
        $questionId = null;
        if (isset($questionData['id']) && strpos($questionData['id'], 'field_') === 0) {
            $parts = explode('_', $questionData['id']);
            if (count($parts) == 2 && is_numeric($parts[1])) {
                $questionId = (int)$parts[1];
            }
        }

        // Check if this is an existing question
        if ($questionId && in_array($questionId, $existingQuestionIds)) {
            // Update existing question
            $question = SurveyQuestion::find($questionId);
            $question->update([
                'question_text' => $questionData['question_text'],
                'question_type' => $questionData['question_type'],
                'placeholder' => $questionData['placeholder'] ?? null,
                'help_text' => $questionData['help_text'] ?? null,
                'error_message' => $questionData['error_message'] ?? null,
                'options' => $questionData['options'] ?? null,
                'settings' => $questionData['settings'] ?? null,
                'order' => $index,
            ]);
            $incomingQuestionIds[] = $questionId;
        } else {
            // Create new question
            $newQuestion = SurveyQuestion::create([
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
            $incomingQuestionIds[] = $newQuestion->id;
        }
    }

    // Find questions to delete (those not in the incoming list)
    $questionsToDelete = array_diff($existingQuestionIds, $incomingQuestionIds);

    if (!empty($questionsToDelete)) {
        // Check if any of these questions have answers
        $questionsWithAnswers = SurveyQuestion::whereIn('id', $questionsToDelete)
            ->whereHas('answers')
            ->pluck('id')
            ->toArray();

        if (!empty($questionsWithAnswers)) {
            // Instead of deleting, mark them as hidden
            // SurveyQuestion::whereIn('id', $questionsWithAnswers)->update(['hidden' => true]);
            SurveyQuestion::whereIn('id', $questionsWithAnswers)->delete();

            // Log which questions were hidden instead of deleted
            \Log::info('Hidden questions with answers instead of deleting', [
                'survey_id' => $survey->id,
                'question_ids' => $questionsWithAnswers
            ]);
        }

        // Delete questions that don't have answers
        $questionsWithoutAnswers = array_diff($questionsToDelete, $questionsWithAnswers);
        if (!empty($questionsWithoutAnswers)) {
            SurveyQuestion::whereIn('id', $questionsWithoutAnswers)->delete();
        }
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
        $survey = Survey::with(['questions' => function ($query) {
            $query->where('hidden', 0); // only non-hidden questions
        }])->find(1);

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
        $survey = Survey::with(['questions' => function ($query) {
            $query->where('hidden', 0); // only non-hidden questions
        }])->find(1);
        $response = $survey->responses()->with(['answers.question', 'order.customer'])->findOrFail($id);
        return view('dashboard.surveys.answer', compact('survey','response'));
    }


    public function settings_update(Request $request) {

    }

}
