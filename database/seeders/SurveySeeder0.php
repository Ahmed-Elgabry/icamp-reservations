<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Survey;
use App\Models\SurveyQuestion;

class SurveySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create a sample survey
        $survey = Survey::create([
            'title' => 'Customer Satisfaction Survey',
            'description' => 'Please help us improve our services by providing your feedback',
            'is_active' => true,
            'starts_at' => now(),
            'ends_at' => now()->addMonths(3),
        ]);

        // Add sample questions to the survey
        $questions = [
            [
                'question_text' => 'How would you rate your overall experience with our service?',
                'question_type' => 'radio',
                'is_required' => true,
                'options' => ['Excellent', 'Good', 'Average', 'Poor', 'Very Poor'],
            ],
            [
                'question_text' => 'What did you like most about our service?',
                'question_type' => 'textarea',
                'is_required' => false,
            ],
            [
                'question_text' => 'Would you recommend our service to others?',
                'question_type' => 'radio',
                'is_required' => true,
                'options' => ['Yes', 'No'],
            ],
            [
                'question_text' => 'Which of our services did you use?',
                'question_type' => 'checkbox',
                'is_required' => true,
                'options' => ['Camping', 'Equipment Rental', 'Guided Tours', 'Food Services'],
            ],
        ];

        foreach ($questions as $index => $questionData) {
            SurveyQuestion::create([
                'survey_id' => $survey->id,
                'question_text' => $questionData['question_text'],
                'question_type' => $questionData['question_type'],
                'is_required' => $questionData['is_required'],
                'options' => $questionData['options'] ?? null,
                'order' => $index,
            ]);
        }
    }
}