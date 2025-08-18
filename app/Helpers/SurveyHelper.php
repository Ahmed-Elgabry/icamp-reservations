<?php

namespace App\Helpers;

class SurveyHelper
{
    /**
     * Generate HTML for a survey question
     *
     * @param \App\Models\SurveyQuestion $question
     * @return string
     */
    public static function generateQuestionHtml($question)
    {
        $type = $question['question_type'];
        $label = $question['question_text'];
        $placeholder = $question['placeholder'];
        $options = $question['options'];
        $settings = $question['settings'];

        switch($type) {
            case "text":
            case "email":
            case "tel":
            case "url":
            case "number":
                return '
                    <label class="form-label">' . $label . '</label>
                    <input type="' . $type . '" class="form-control" placeholder="' . $placeholder . '" disabled>
                ';
            case "textarea":
                return '
                    <label class="form-label">' . $label . '</label>
                    <textarea class="form-control" rows="3" placeholder="' . $placeholder . '" disabled></textarea>
                ';
            case "select":
                $selectOptions = '';
                if ($options) {
                    foreach ($options as $option) {
                        $selectOptions .= '<option value="' . $option['value'] . '">' . $option['label'] . '</option>';
                    }
                }
                return '
                    <label class="form-label">' . $label . '</label>
                    <select class="form-select" disabled>
                        <option value="">حدد خيارًا...</option>
                        ' . $selectOptions . '
                    </select>
                ';
            case "radio":
                $radioOptions = '';
                if ($options) {
                    foreach ($options as $index => $option) {
                        $radioOptions .= '
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="field_' . $question['id'] . '" id="field_' . $question['id'] . '_' . $index . '" value="' . $option['value'] . '"  disabled>
                                <label class="form-check-label" for="field_' . $question['id'] . '_' . $index . '">
                                    ' . $option['label'] . '
                                </label>
                            </div>
                        ';
                    }
                }
                return '
                    <label class="form-label">' . $label . '</label>
                    <div>
                        ' . $radioOptions . '
                    </div>
                ';
            case "checkbox":
                $checkboxOptions = '';
                if ($options) {
                    foreach ($options as $index => $option) {
                        $checkboxOptions .= '
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="field_' . $question['id'] . '_' . $index . '" value="' . $option['value'] . '" disabled>
                                <label class="form-check-label" for="field_' . $question['id'] . '_' . $index . '">
                                    ' . $option['label'] . '
                                </label>
                            </div>
                        ';
                    }
                }
                return '
                    <label class="form-label">' . $label . '</label>
                    <div>
                        ' . $checkboxOptions . '
                    </div>
                ';
            case "date":
                return '
                    <label class="form-label">' . $label . '</label>
                    <input type="date" class="form-control" disabled>
                ';
            case "datetime":
                return '
                    <label class="form-label">' . $label . '</label>
                    <input type="datetime-local" class="form-control" disabled>
                ';
            case "stars":
                $points = $settings['points'] ?? 5;
                $stars = '';
                for ($i = 1; $i <= $points; $i++) {
                    $stars .= '
                        <input type="radio" id="field_' . $question['id'] . '_' . $i . '" name="field_' . $question['id'] . '" value="' . $i . '"  disabled>
                        <label for="field_' . $question['id'] . '_' . $i . '" class="mdi mdi-star"></label>
                    ';
                }
                return '
                    <label class="form-label">' . $label . '</label>
                    <div class="star-rating">
                        ' . $stars . '
                    </div>
                    <div class="d-flex justify-content-between mt-1">
                        <span class="text-muted small">' . ($settings['lowLabel'] ?? 'سيء') . '</span>
                        <span class="text-muted small">' . ($settings['highLabel'] ?? 'ممتاز') . '</span>
                    </div>
                ';
            case "nps":
                $npsOptions = '';
                for ($i = 0; $i <= 10; $i++) {
                    $npsOptions .= '
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="field_' . $question['id'] . '" id="field_' . $question['id'] . '_' . $i . '" value="' . $i . '" disabled>
                            <label class="form-check-label" for="field_' . $question['id'] . '_' . $i . '">
                                ' . $i . '
                            </label>
                        </div>
                    ';
                }
                return '
                    <label class="form-label">' . $label . '</label>
                    <div class="nps-rating">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted small">' . ($settings['lowLabel'] ?? 'غير محتمل') . '</span>
                            <span class="text-muted small">' . ($settings['highLabel'] ?? 'محتمل جدًا') . '</span>
                        </div>
                        <div class="d-flex flex-wrap">
                            ' . $npsOptions . '
                        </div>
                    </div>
                ';
            case "rating":
                $points = $settings['points'] ?? 5;
                $ratingOptions = '';
                for ($i = 1; $i <= $points; $i++) {
                    $ratingOptions .= '
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="field_' . $question['id'] . '" id="field_' . $question['id'] . '_' . $i . '" value="' . $i . '"  disabled>
                            <label class="form-check-label" for="field_' . $question['id'] . '_' . $i . '">
                                ' . $i . '
                            </label>
                        </div>
                    ';
                }
                return '
                    <label class="form-label">' . $label . '</label>
                    <div class="rating-scale">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted small">' . ($settings['lowLabel'] ?? 'سيء') . '</span>
                            <span class="text-muted small">' . ($settings['highLabel'] ?? 'ممتاز') . '</span>
                        </div>
                        <div class="d-flex flex-wrap">
                            ' . $ratingOptions . '
                        </div>
                    </div>
                ';
            default:
                return '
                    <label class="form-label">' . $label . '</label>
                    <input type="text" class="form-control" placeholder="' . $placeholder . '" disabled>
                ';
        }
    }

    /**
     * Generate HTML for a survey question (for preview/actual form, interactive)
     *
     * @param array $question
     * @return string
     */
    public static function generatePreviewHtml($question)
    {
        $type = $question['question_type'];
        $label = $question['question_text'];
        $placeholder = $question['placeholder'] ?? '';
        $options = $question['options'] ?? [];
        $settings = $question['settings'] ?? [];

        $html = '<div class="mb-4 ' . ($settings['width'] ?? 'col-12') . '">';

        switch($type) {
            case "text":
            case "email":
            case "tel":
            case "url":
            case "number":
                $html .= '
                <div class="rating-form-group">
                    <label class="rating-form-label">' . $label . '</label>
                    <input type="' . $type . '" class="rating-form-input" placeholder="' . $placeholder . '" name="question_' . $question['id'] . '" >
                </div>
                    ';
                break;

            case "textarea":
                $html .= '
                <div class="rating-form-group">
                    <label class="rating-form-label">' . $label . '</label>
                    <textarea class="rating-form-textarea" rows="3" placeholder="' . $placeholder . '" name="question_' . $question['id'] . '" ></textarea>
                </div>
                ';
                break;

            case "select":
                $selectOptions = '';
                if ($options) {
                    foreach ($options as $option) {
                        $selectOptions .= '<option value="' . $option['value'] . '">' . $option['label'] . '</option>';
                    }
                }
                $html .= '
                <div class="rating-form-group">
                    <label class="rating-form-label">' . $label . '</label>
                    <select class="form-select" name="question_' . $question['id'] . '" >
                        <option value="">حدد خيارًا...</option>
                        ' . $selectOptions . '
                    </select>
                </div>
                ';
                break;

            case "radio":
                $radioOptions = '';
                if ($options) {
                    foreach ($options as $index => $option) {
                        $radioOptions .= '
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="question_' . $question['id'] . '" id="question_' . $question['id'] . '_' . $index . '" value="' . $option['value'] . '" >
                                <label class="form-check-label" for="question_' . $question['id'] . '_' . $index . '">
                                    ' . $option['label'] . '
                                </label>
                            </div>
                        ';
                    }
                }
                $html .= '
                <div class="rating-form-group">
                    <label class="rating-form-label">' . $label . '</label>
                    <div>
                        ' . $radioOptions . '
                    </div>
                </div>
                ';
                break;

            case "checkbox":
                $checkboxOptions = '';
                if ($options) {
                    foreach ($options as $index => $option) {
                        $checkboxOptions .= '
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="question_' . $question['id'] . '[]" id="question_' . $question['id'] . '_' . $index . '" value="' . $option['value'] . '">
                                <label class="form-check-label" for="question_' . $question['id'] . '_' . $index . '">
                                    ' . $option['label'] . '
                                </label>
                            </div>
                        ';
                    }
                }
                $html .= '
                <div class="rating-form-group">
                    <label class="rating-form-label">' . $label . '</label>
                    <div>
                        ' . $checkboxOptions . '
                    </div>
                </div>
                ';
                break;

            case "date":
                $html .= '
                <div class="rating-form-group">
                    <label class="rating-form-label">' . $label . '</label>
                    <input type="date" class="form-control" name="question_' . $question['id'] . '" >
                </div>
                ';
                break;

            case "datetime":
                $html .= '
                <div class="rating-form-group">
                    <label class="rating-form-label">' . $label . '</label>
                    <input type="datetime-local" class="form-control" name="question_' . $question['id'] . '" >
                </div>
                ';
                break;

            case "stars":
                $points = $settings['points'] ?? 5;
                $stars = '';
                for ($i = $points; $i >= 1; $i--) {
                    $stars .= '
                    <div class="rating-form-group">
                        <input type="radio" id="question_' . $question['id'] . '_' . $i . '" name="question_' . $question['id'] . '" value="' . $i . '" >
                        <label for="question_' . $question['id'] . '_' . $i . '" class="mdi mdi-star"></label>
                    </div>
                    ';
                }
                $html .= '
                <div class="rating-form-group">
                    <label class="rating-form-label">' . $label . '</label>
                    <div class="star-rating">
                        ' . $stars . '
                    </div>
                    <div class="d-flex justify-content-between mt-1">
                        <span class="text-muted small">' . ($settings['lowLabel'] ?? 'سيء') . '</span>
                        <span class="text-muted small">' . ($settings['highLabel'] ?? 'ممتاز') . '</span>
                    </div>
                </div>
                ';
                break;

            case "nps":
                $npsOptions = '';
                for ($i = 0; $i <= 10; $i++) {
                    $npsOptions .= '
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="question_' . $question['id'] . '" id="question_' . $question['id'] . '_' . $i . '" value="' . $i . '" >
                            <label class="form-check-label" for="question_' . $question['id'] . '_' . $i . '">
                                ' . $i . '
                            </label>
                        </div>
                    ';
                }
                $html .= '
                <div class="rating-form-group">
                    <label class="form-label">' . $label . '</label>
                    <div class="nps-rating">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted small">' . ($settings['lowLabel'] ?? 'غير محتمل') . '</span>
                            <span class="text-muted small">' . ($settings['highLabel'] ?? 'محتمل جدًا') . '</span>
                        </div>
                        <div class="d-flex flex-wrap">
                            ' . $npsOptions . '
                        </div>
                    </div>
                </div>
                ';
                break;

            case "rating":
                $points = $settings['points'] ?? 5;
                $ratingOptions = '';
                for ($i = 1; $i <= $points; $i++) {
                    $ratingOptions .= '
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="question_' . $question['id'] . '" id="question_' . $question['id'] . '_' . $i . '" value="' . $i . '" >
                            <label class="form-check-label" for="question_' . $question['id'] . '_' . $i . '">
                                ' . $i . '
                            </label>
                        </div>
                    ';
                }
                $html .= '
                <div class="rating-form-group">
                    <label class="rating-form-label">' . $label . '</label>
                    <div class="rating-scale">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted small">' . ($settings['lowLabel'] ?? 'سيء') . '</span>
                            <span class="text-muted small">' . ($settings['highLabel'] ?? 'ممتاز') . '</span>
                        </div>
                        <div class="d-flex flex-wrap">
                            ' . $ratingOptions . '
                        </div>
                    </div>
                </div>
                ';
                break;

            default:
                $html .= '
                <div class="rating-form-group">
                    <label class="rating-form-label">' . $label . '</label>
                    <input type="text" class="form-control" placeholder="' . $placeholder . '" name="question_' . $question['id'] . '" >
                </div>
                ';
        }

        $html .= '</div>';
        return $html;
    }

    /**
     * Get question type name in Arabic
     *
     * @param string $type
     * @return string
     */
    public static function getQuestionTypeName($type)
    {
        $names = [
            'text' => 'Text',
            'textarea' => 'Textarea',
            'select' => 'Dropdown',
            'radio' => 'Radio',
            'checkbox' => 'Checkbox',
            'date' => 'Date',
            'datetime' => 'Datetime',
            'number' => 'Number',
            'email' => 'Email',
            'tel' => 'Tel',
            'url' => 'Url',
            'stars' => 'Stars',
            'nps' => 'NPS',
            'rating' => 'Rating',
        ];

        return $names[$type] ?? 'Field';
    }
}
