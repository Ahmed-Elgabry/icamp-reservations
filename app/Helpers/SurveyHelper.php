<?php

namespace App\Helpers;

use Illuminate\Support\Facades\App;

class SurveyHelper
{
    /**
     * Get the appropriate text based on current locale
     *
     * @param array|string $text
     * @return string
     */
    public static function getLocalizedText($text)
    {
        if (is_array($text)) {
            $locale = App::getLocale();
            return $text[$locale] ?? $text['ar'] ?? (is_string($text[array_key_first($text)]) ? $text[array_key_first($text)] : '');
        }
        return $text;
    }


    /**
     * Generate HTML for a survey question
     *
     * @param \App\Models\SurveyQuestion $question
     * @return string
     */
    public static function generateQuestionHtml($question)
    {
        if (isset($question['hidden']) && $question['hidden']) {
            return '';
        }
        $type = $question['question_type'];
        // $label = $question['question_text'];
        // $placeholder = $question['placeholder'];
        $options = $question['options'];
        $settings = $question['settings'];
        $type = $question['question_type'];
        $label = self::getLocalizedText($question['question_text']);
        $placeholder = self::getLocalizedText($question['placeholder']);
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
                        $optionLabel = self::getLocalizedText($option['label'] ?? $option);
                        $selectOptions .= '<option value="' . $option['value'] . '">' . $optionLabel . '</option>';
                    }
                }
                return '
                    <label class="form-label">' . $label . '</label>
                    <select class="form-select" disabled>
                        <option value="">' . (App::getLocale() === 'ar' ? 'حدد خيارًا...' : 'Select an option...') . '</option>
                        ' . $selectOptions . '
                    </select>
                ';
            case "radio":
                $radioOptions = '';
                if ($options) {
                    foreach ($options as $index => $option) {
                        $optionLabel = self::getLocalizedText($option['label'] ?? $option);
                        $radioOptions .= '
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="field_' . $question['id'] . '" id="field_' . $question['id'] . '_' . $index . '" value="' . $option['value'] . '"  disabled>
                                <label class="form-check-label" for="field_' . $question['id'] . '_' . $index . '">
                                    ' . $optionLabel . '
                                </label>
                            </div>
                        ';
                    }
                }
                return '
                    <label class="form-label">' . $label . '</label>
                    <div class="' . (App::getLocale() == 'ar' ? 'rating-scale-ar' : '') . '">
                        ' . $radioOptions . '
                    </div>
                ';
            case "checkbox":
                $checkboxOptions = '';
                if ($options) {
                    foreach ($options as $index => $option) {
                        $optionLabel = self::getLocalizedText($option['label'] ?? $option);
                        $checkboxOptions .= '
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="field_' . $question['id'] . '_' . $index . '" value="' . $option['value'] . '" disabled>
                                <label class="form-check-label" for="field_' . $question['id'] . '_' . $index . '">
                                    ' . $optionLabel . '
                                </label>
                            </div>
                        ';
                    }
                }
                return '
                    <label class="form-label">' . $label . '</label>
                    <div class="' . (App::getLocale() == 'ar' ? 'rating-scale-ar' : '') . '">
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
                // Generate stars in reverse order (5 to 1) for proper RTL display
                for ($i = $points; $i >= 1; $i--) {
                    $stars .= '
                        <input type="radio" id="field_' . $question['id'] . '_' . $i . '" name="field_' . $question['id'] . '" value="' . $i . '" disabled>
                        <label for="field_' . $question['id'] . '_' . $i . '">★</label>
                    ';
                }
                return '
                    <div class="rating-form-group">
                        <label class="rating-form-label">' . $label . '</label>
                        <div class="rating-form-stars">
                            ' . $stars . '
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
                    <div class="rating-scale ' . (App::getLocale() == 'ar' ? 'rating-scale-ar' : '') . '">
                        <div class="row">
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
        // $label = $question['question_text'];
        // $placeholder = $question['placeholder'] ?? '';
        $options = $question['options'] ?? [];
        $settings = $question['settings'] ?? [];
        $label = self::getLocalizedText($question['question_text']);
        $placeholder = self::getLocalizedText($question['placeholder'] ?? '');
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
                        $optionLabel = self::getLocalizedText($option['label'] ?? $option);
                        $selectOptions .= '<option value="' . $option['value'] . '">' . $optionLabel . '</option>';
                    }
                }
                $html .= '
                <div class="rating-form-group">
                    <label class="rating-form-label">' . $label . '</label>
                    <select class="form-select" name="question_' . $question['id'] . '" >
                        <option value="">' . (App::getLocale() === 'ar' ? 'حدد خيارًا...' : 'Select an option...') . '</option>
                        ' . $selectOptions . '
                    </select>
                </div>
                ';
                break;

            case "radio":
                $radioOptions = '';
                if ($options) {
                    foreach ($options as $index => $option) {
                        $optionLabel = self::getLocalizedText($option['label'] ?? $option);
                        $radioOptions .= '
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="question_' . $question['id'] . '" id="question_' . $question['id'] . '_' . $index . '" value="' . $option['value'] . '" >
                                <label class="form-check-label" for="question_' . $question['id'] . '_' . $index . '">
                                    ' . $optionLabel . '
                                </label>
                            </div>
                        ';
                    }
                }
                $html .= '
                <div class="rating-form-group">
                    <label class="rating-form-label">' . $label . '</label>
                    <div class="' . (App::getLocale() == 'ar' ? 'rating-scale-ar' : '') . '">
                        ' . $radioOptions . '
                    </div>
                </div>
                ';
                break;

            case "checkbox":
                $checkboxOptions = '';
                if ($options) {
                    foreach ($options as $index => $option) {
                        $optionLabel = self::getLocalizedText($option['label'] ?? $option);
                        $checkboxOptions .= '
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="question_' . $question['id'] . '[]" id="question_' . $question['id'] . '_' . $index . '" value="' . $option['value'] . '">
                                <label class="form-check-label" for="question_' . $question['id'] . '_' . $index . '">
                                    ' . $optionLabel . '
                                </label>
                            </div>
                        ';
                    }
                }
                $html .= '
                <div class="rating-form-group">
                    <label class="rating-form-label">' . $label . '</label>
                    <div class="' . (App::getLocale() == 'ar' ? 'rating-scale-ar' : '') . '">
                        ' . $checkboxOptions . '
                    </div>
                </div>
                ';
                break;

            case "date":
                $html .= '
                <div class="rating-form-group">
                    <label class="rating-form-label">' . $label . '</label>
                    <input type="date" class="rating-form-input" name="question_' . $question['id'] . '" >
                </div>
                ';
                break;

            case "datetime":
                $html .= '
                <div class="rating-form-group">
                    <label class="rating-form-label">' . $label . '</label>
                    <input type="datetime-local" class="rating-form-input" name="question_' . $question['id'] . '" >
                </div>
                ';
                break;

            case "stars":
                $points = $settings['points'] ?? 5;
                $stars = '';
                // Generate stars in reverse order (5 to 1) for proper RTL display
                for ($i = $points; $i >= 1; $i--) {
                    $stars .= '
                        <input type="radio" id="field_' . $question['id'] . '_' . $i . '" name="field_' . $question['id'] . '" value="' . $i . '">
                        <label for="field_' . $question['id'] . '_' . $i . '">★</label>
                    ';
                }
                return '
                    <div class="rating-form-group">
                        <label class="rating-form-label">' . $label . '</label>
                        <div class="rating-form-stars">
                            ' . $stars . '
                        </div>
                    </div>
                ';

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
                    <div class="rating-scale ' . (App::getLocale() == 'ar' ? 'rating-scale-ar' : '') . '">
                        <div class="row">
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
            'rating' => 'Rating',
        ];

        return $names[$type] ?? 'Field';
    }


    /**
     * Generate HTML for a survey answer response
     *
     * @param \App\Models\SurveyAnswer $answer
     * @return string
     */
    public static function getResponseHtml($answer)
    {
        $question = $answer->question;
        $type = $question->question_type;
        $value = $answer->answer_text;
        $options = $question->options;

        // For checkbox questions, get the value from answer_option instead of answer_text
        if ($type === "checkbox" && $answer->answer_option) {
            $value = $answer->answer_option;
        }

        switch($type) {
            case "text":
            case "email":
            case "tel":
            case "url":
            case "number":
                return '<input type="' . $type . '" class="rating-form-input" value="' . $value . '" disabled>';
            case "textarea":
                return '<textarea class="rating-form-textarea" rows="3" disabled>' . $value . '</textarea>';
            case "select":
                $selectOptions = '';
                if ($options) {
                    // Check if options is a JSON string and decode it if needed
                    if (is_string($options)) {
                        $options = json_decode($options, true);
                    }
                    foreach ($options as $option) {
                        // Handle both simple array and object format
                        $optionValue = is_array($option) ? ($option['value'] ?? $option) : $option;
                        $optionLabel = self::getLocalizedText($option['label'] ?? $option);
                        $selected = ($optionValue == $value) ? 'selected' : '';
                        $selectOptions .= '<option value="' . $optionValue . '" ' . $selected . '>' . $optionLabel . '</option>';
                    }
                }
                return '<select class="form-select" disabled>
                    <option value="">' . (App::getLocale() === 'ar' ? 'حدد خيارًا...' : 'Select an option...') . '</option>
                    ' . $selectOptions . '
                </select>';
            case "radio":
                $radioOptions = '';
                if ($options) {
                    // Check if options is a JSON string and decode it if needed
                    if (is_string($options)) {
                        $options = json_decode($options, true);
                    }
                    foreach ($options as $option) {
                        // Handle both simple array and object format
                        $optionValue = is_array($option) ? ($option['value'] ?? $option) : $option;
                        $optionLabel = self::getLocalizedText($option['label'] ?? $option);
                        $checked = ($optionValue == $value) ? 'checked' : '';
                        $radioOptions .= '
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="question_' . $question->id . '" value="' . $optionValue . '" ' . $checked . ' disabled>
                                <label class="form-check-label">' . $optionLabel . '</label>
                            </div>
                        ';
                    }
                }
                return '<div class="' . (app()->getLocale() == 'ar' ? 'rating-scale-ar' : '') . '">' . $radioOptions . '</div>';
            case "checkbox":
                $checkboxOptions = '';
                // Check if $value is already an array (from answer_option) or needs to be decoded
                $selectedOptions = is_array($value) ? $value : json_decode($value, true);
                if ($options) {
                    // Check if options is a JSON string and decode it if needed
                    if (is_string($options)) {
                        $options = json_decode($options, true);
                    }
                    foreach ($options as $option) {
                        // Handle both simple array and object format
                        $optionValue = is_array($option) ? ($option['value'] ?? $option) : $option;
                        $optionLabel = self::getLocalizedText($option['label'] ?? $option);
                        $checked = in_array($optionValue, $selectedOptions) ? 'checked' : '';
                        $checkboxOptions .= '
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="' . $optionValue . '" ' . $checked . ' disabled>
                                <label class="form-check-label">' . $optionLabel . '</label>
                            </div>
                        ';
                    }
                }
                return '<div class="' . (app()->getLocale() == 'ar' ? 'rating-scale-ar' : '') . '">' . $checkboxOptions . '</div>';
            case "date":
                return '<input type="date" class="rating-form-input" value="' . $value . '" disabled>';
            case "datetime":
                return '<input type="datetime-local" class="rating-form-input" value="' . $value . '" disabled>';
            case "stars":
                $points = 5;
                $stars = '';
                // Generate stars in reverse order (5 to 1) for proper RTL display
                for ($i = $points; $i >= 1; $i--) {
                    $checked = ($i == $value) ? 'checked' : '';
                    $stars .= '
                        <input type="radio" id="field_' . $question->id . '_' . $i . '" name="field_' . $question->id . '" value="' . $i . '" ' . $checked . ' disabled>
                        <label for="field_' . $question->id . '_' . $i . '">★</label>
                    ';
                }
                return '
                    <div class="rating-form-stars">
                        ' . $stars . '
                    </div>
                ';
            case "rating":
                $points = 5;
                $ratingOptions = '';
                for ($i = 1; $i <= $points; $i++) {
                    $checked = ($i == $value) ? 'checked' : '';
                    $ratingOptions .= '
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="field_' . $question->id . '" value="' . $i . '" ' . $checked . ' disabled>
                            <label class="form-check-label">' . $i . '</label>
                        </div>
                    ';
                }
                return '
                    <div class="rating-scale' . (app()->getLocale() == 'ar' ? ' rating-scale-ar' : '') . '">
                        <div class="row">
                            ' . $ratingOptions . '
                        </div>
                    </div>
                ';
            default:
                return '<input type="text" class="rating-form-input" value="' . $value . '" disabled>';
        }
    }
}
