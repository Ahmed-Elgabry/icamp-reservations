<?php
// database/seeders/SurveySeeder.php

namespace Database\Seeders;

use App\Models\Survey;
use App\Models\SurveyQuestion;
use Illuminate\Database\Seeder;

class SurveySeeder extends Seeder
{
    public function run()
    {
        // Create a sample survey
        $survey = Survey::create([
            'title' => 'استبيان رضا العملاء',
            'description' => 'يساعدنا هذا الاستبيان في تحسين خدماتنا وتقديم تجربة أفضل لك',
            'is_active' => true,
            'starts_at' => now(),
            'ends_at' => now()->addMonths(1),
        ]);

        // Add questions to the survey
        $questions = [
            [
                'question_text' => 'كيف تقيم جودة خدماتنا بشكل عام؟',
                'question_type' => 'stars',
                'is_required' => true,
                'settings' => [
                    'points' => 5,
                    'lowLabel' => 'سيء جداً',
                    'highLabel' => 'ممتاز'
                ],
                'order' => 1,
            ],
            [
                'question_text' => 'ما مدى رضاك عن سرعة الاستجابة؟',
                'question_type' => 'emojis',
                'is_required' => true,
                'settings' => [
                    'points' => 5,
                    'lowLabel' => 'غير راضٍ',
                    'highLabel' => 'راضٍ جداً'
                ],
                'order' => 2,
            ],
            [
                'question_text' => 'ما هي الخدمة التي استخدمتها؟',
                'question_type' => 'select',
                'is_required' => true,
                'options' => [
                    ['label' => 'الدعم الفني', 'value' => 'technical_support'],
                    ['label' => 'المبيعات', 'value' => 'sales'],
                    ['label' => 'خدمة العملاء', 'value' => 'customer_service'],
                    ['label' => 'أخرى', 'value' => 'other'],
                ],
                'order' => 3,
            ],
            [
                'question_text' => 'كم مرة استخدمت خدماتنا في الشهر الماضي؟',
                'question_type' => 'radio',
                'is_required' => true,
                'options' => [
                    ['label' => 'هذه هي المرة الأولى', 'value' => 'first_time'],
                    ['label' => '1-2 مرات', 'value' => '1_2_times'],
                    ['label' => '3-5 مرات', 'value' => '3_5_times'],
                    ['label' => 'أكثر من 5 مرات', 'value' => 'more_than_5_times'],
                ],
                'order' => 4,
            ],
            [
                'question_text' => 'ما هي الميزات التي تود أن نضيفها؟',
                'question_type' => 'checkbox',
                'is_required' => false,
                'options' => [
                    ['label' => 'دردشة مباشرة', 'value' => 'live_chat'],
                    ['label' => 'دعم عبر الهاتف', 'value' => 'phone_support'],
                    ['label' => 'مكتبة المعرفة', 'value' => 'knowledge_base'],
                    ['label' => 'فيديوهات تعليمية', 'value' => 'tutorial_videos'],
                    ['label' => 'أخرى', 'value' => 'other'],
                ],
                'order' => 5,
            ],
            [
                'question_text' => 'كم من المحتمل أن توصي بنا لصديق أو زميل؟',
                'question_type' => 'nps',
                'is_required' => true,
                'settings' => [
                    'lowLabel' => 'غير محتمل على الإطلاق',
                    'highLabel' => 'محتمل جداً'
                ],
                'order' => 6,
            ],
            [
                'question_text' => 'أي ملاحظات إضافية لديك؟',
                'question_type' => 'textarea',
                'is_required' => false,
                'placeholder' => 'اكتب ملاحظاتك هنا...',
                'order' => 7,
            ],
        ];

        foreach ($questions as $question) {
            SurveyQuestion::create([
                'survey_id' => $survey->id,
                'question_text' => $question['question_text'],
                'question_type' => $question['question_type'],
                'is_required' => $question['is_required'],
                'placeholder' => $question['placeholder'] ?? null,
                'options' => $question['options'] ?? null,
                'settings' => $question['settings'] ?? null,
                'order' => $question['order'],
            ]);
        }
    }
}
