<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>icamp - بناء الاستبيان</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap-utilities.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/@mdi/font@6.5.95/css/materialdesignicons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
	<link rel="canonical" href="{{ Request::fullUrl() }}" />
    <style>
        :root {
            --bs-primary: #2563eb;
            --bs-secondary: #6b7280;
        }
        body {
            background-color: #f9fafb;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .sidebar-field {
            cursor: pointer;
            transition: all 0.2s;
            border-radius: 0.5rem;
            padding: 0.75rem;
            text-align: center;
            margin-bottom: 0.5rem;
        }
        .sidebar-field:hover {
            background-color: #dbeafe;
            transform: translateY(-2px);
        }
        .form-field {
            border: 2px dashed #d1d5db;
            border-radius: 0.5rem;
            padding: 1rem;
            margin-bottom: 1rem;
            position: relative;
            transition: all 0.2s;
            cursor: move;
        }
        .form-field:hover {
            border-color: #9ca3af;
            background-color: #f9fafb;
        }
        .form-field.selected {
            border-color: #2563eb;
            background-color: #eff6ff;
        }
        .field-actions {
            position: absolute;
            top: 0.5rem;
            left: 0.5rem;
            opacity: 0;
            transition: opacity 0.2s;
        }
        .form-field:hover .field-actions {
            opacity: 1;
        }
        .properties-panel {
            background-color: white;
            border-radius: 0.5rem;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            /* height: calc(100vh - 120px); */
            overflow-y: auto;
        }
        .preview-btn {
            position: fixed;
            bottom: 1.5rem;
            left: 1.5rem;
            z-index: 100;
            width: 3rem;
            height: 3rem;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .ui-sortable-helper {
            transform: rotate(5deg);
            box-shadow: 0 10px 25px rgba(0,0,0,0.15);
        }
        .field-type-label {
            font-size: 0.75rem;
            color: #6b7280;
            margin-top: 0.25rem;
        }
        .unsaved-indicator {
            width: 0.5rem;
            height: 0.5rem;
            background-color: #f97316;
            border-radius: 50%;
            display: inline-block;
        }
        .option-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 0.5rem;
        }
        .option-item input[type="text"] {
            flex: 1;
        }
        .option-item button {
            padding: 0.25rem;
            border: none;
            background: none;
            color: #dc2626;
            cursor: pointer;
        }
        .option-item button:hover {
            color: #b91c1c;
        }
        .rating-form-stars {
            display: flex;
            justify-content: space-between;
            max-width: 200px;
            margin: 0 auto 20px;
        }
        .rating-form-stars input {
            display: none;
        }
        .rating-form-stars label {
            font-size: 30px;
            color: #ccc;
            cursor: pointer;
            transition: color 0.2s ease;
        }
        .rating-form-stars input:checked ~ label,
        .rating-form-stars input:hover ~ label,
        .rating-form-stars label:hover ~ label , .checked {
            color: #f5c518  !important
        }
        .rating-scale.rating-scale-ar input[type="radio"] {
            float: right;
            margin-left: 10px;
        }
        .lang-inputs {
            display: block;
            gap: 10px;
        }
        .lang-inputs .form-control {
            flex: 1;
        }
        .lang-label {
            font-size: 0.75rem;
            color: #6b7280;
            margin-bottom: 0.25rem;
        }
    </style>

    @if (app()->getLocale() == 'ar')
        <style>
            .rating-form-stars {
                direction: ltr;
            }
        </style>
    @else
        <style>
            .rating-form-stars {
                direction: rtl;
            }
        </style>
    @endif
</head>
<body>

{{-- @endsection --}}
<div class="container-fluid" style="height: 100vh;">
    <div class="row">
        <!-- Left Sidebar - Question Types -->
        <div class="col-md-1 bg-white p-3 border-end" style="height: 100vh; overflow-y: auto;">
            <div class="d-flex flex-column">
                <div class="sidebar-field" data-type="text" title="حقل نصي">
                    <i class="mdi mdi-format-size mdi-24px text-primary"></i>
                    <div class="field-type-label">نص</div>
                </div>
                <div class="sidebar-field" data-type="textarea" title="منطقة نص">
                    <i class="mdi mdi-format-align-left mdi-24px text-primary"></i>
                    <div class="field-type-label">منطقة نص</div>
                </div>
                <div class="sidebar-field" data-type="select" title="قائمة منسدلة">
                    <i class="mdi mdi-chevron-down mdi-24px text-primary"></i>
                    <div class="field-type-label">قائمة</div>
                </div>
                <div class="sidebar-field" data-type="radio" title="أزرار اختيار">
                    <i class="mdi mdi-circle-outline mdi-24px text-primary"></i>
                    <div class="field-type-label">اختيار</div>
                </div>
                <div class="sidebar-field" data-type="checkbox" title="خانات اختيار">
                    <i class="mdi mdi-checkbox-outline mdi-24px text-primary"></i>
                    <div class="field-type-label">خانات</div>
                </div>
                <div class="sidebar-field" data-type="date" title="اختيار التاريخ">
                    <i class="mdi mdi-calendar mdi-24px text-primary"></i>
                    <div class="field-type-label">تاريخ</div>
                </div>
                <div class="sidebar-field" data-type="datetime" title="التاريخ والوقت">
                    <i class="mdi mdi-clock mdi-24px text-primary"></i>
                    <div class="field-type-label">وقت</div>
                </div>
                <div class="sidebar-field" data-type="number" title="رقم">
                    <i class="mdi mdi-pound mdi-24px text-primary"></i>
                    <div class="field-type-label">رقم</div>
                </div>
                <div class="sidebar-field" data-type="email" title="البريد الإلكتروني">
                    <i class="mdi mdi-email mdi-24px text-primary"></i>
                    <div class="field-type-label">بريد</div>
                </div>
                <div class="sidebar-field" data-type="tel" title="رقم الهاتف">
                    <i class="mdi mdi-phone mdi-24px text-primary"></i>
                    <div class="field-type-label">هاتف</div>
                </div>
                <div class="sidebar-field" data-type="url" title="رابط إلكتروني">
                    <i class="mdi mdi-link mdi-24px text-primary"></i>
                    <div class="field-type-label">رابط</div>
                </div>
                <div class="sidebar-field" data-type="stars" title="تقييم بالنجوم">
                    <i class="mdi mdi-star mdi-24px text-primary"></i>
                    <div class="field-type-label">نجوم</div>
                </div>
                <div class="sidebar-field" data-type="rating" title="مقياس تقييم">
                    <i class="mdi mdi-thumb-up mdi-24px text-primary"></i>
                    <div class="field-type-label">تقييم</div>
                </div>
            </div>
        </div>
        <!-- Main Content - Survey Builder -->
        <div class="col-md-8 p-2" style="height: 100vh; overflow-y: auto;">
            <div class="bg-white rounded shadow-sm mb-4 p-3 d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center">
                    <span class="unsaved-indicator me-2" title="تغييرات غير محفوظة"></span>
                    <input type="text" class="form-control form-control-lg border-0 fw-bold" id="surveyTitle" value="{{ $survey['title'] ?? 'استبيان جديد' }}">
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('surveys.demo') }}" target="_blank" class="btn btn-sm btn-outline-secondary">
                        <i class="mdi mdi-open-in-new me-1"></i> مشاهدة
                    </a>
                    <a href="{{ url('/') }}" class="btn btn-sm btn-outline-secondary">
                        <i class="mdi mdi-home-outline me-1"></i> الرئيسية
                    </a>
                    <div class="vr mx-2"></div>
                    <button class="btn btn-sm btn-primary" id="saveBtn">
                        <i class="mdi mdi-content-save-outline me-1"></i> حفظ
                    </button>
                </div>
            </div>
            <div class="bg-white rounded shadow-sm p-4" style="min-height: 70vh;">
                <div id="formFields" class="sortable-container">
                    <!-- Survey questions will be added here dynamically -->
                    @if(isset($survey) && isset($survey['fields']) && count($survey['fields']) > 0)
                        @foreach($survey['fields'] as $question)
                            <div dir="rtl" class="form-field {{ $question['settings']['width'] ?? 'col-12' }}" id="field_{{ $question['id'] }}" data-field-id="field_{{ $question['id'] }}">
                                <div class="field-actions">
                                    <button class="btn btn-sm btn-light me-1 duplicate-field" title="نسخ">
                                        <i class="mdi mdi-content-copy"></i>
                                    </button>
                                    <button class="btn btn-sm btn-light me-1 move-up" title="تحريك للأعلى">
                                        <i class="mdi mdi-arrow-up"></i>
                                    </button>
                                    <button class="btn btn-sm btn-light me-1 move-down" title="تحريك للأسفل">
                                        <i class="mdi mdi-arrow-down"></i>
                                    </button>
                                    <button class="btn btn-sm btn-light delete-field" title="حذف">
                                        <i class="mdi mdi-delete-outline text-danger"></i>
                                    </button>
                                </div>
                                <div class="field-content">
                                    {!! SurveyHelper::generateQuestionHtml($question) !!}
                                </div>
                                <div class="field-type-label text-start">{{ SurveyHelper::getQuestionTypeName($question["question_type"]) }}</div>
                            </div>
                        @endforeach
                    @else
                        <div class="text-center text-muted py-5">
                            <i class="mdi mdi-drag-variant mdi-48px d-block mb-3"></i>
                            <p>اسحب أسئلة من الشريط الجانبي وأفلتها هنا لبناء الاستبيان</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        <!-- Right Sidebar - Question Properties -->
        <div class="col-md-3 p-3" style="height: 100vh; overflow-y: auto;">
            <div class="properties-panel">
                <div class="p-3 border-bottom">
                    <h5 class="mb-1">خصائص السؤال</h5>
                    <p class="text-muted small mb-0" id="fieldTypeLabel">حدد سؤالاً لتحرير خصائصه</p>
                </div>
                <div class="p-3">
                    <div class="mb-3">
                        <label class="form-label">نص السؤال</label>
                        <div class="lang-inputs">
                            <div class="flex-1">
                                <div class="lang-label">العربية</div>
                                <input type="text" class="form-control" id="fieldLabelAr" placeholder="أدخل نص السؤال بالعربية">
                            </div>
                            <div class="flex-1">
                                <div class="lang-label">English</div>
                                <input type="text" class="form-control" id="fieldLabelEn" placeholder="Enter question text in English">
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">النص البديل</label>
                        <div class="lang-inputs">
                            <div class="flex-1">
                                <div class="lang-label">العربية</div>
                                <input type="text" class="form-control" id="fieldPlaceholderAr" placeholder="أدخل النص البديل بالعربية">
                            </div>
                            <div class="flex-1">
                                <div class="lang-label">English</div>
                                <input type="text" class="form-control" id="fieldPlaceholderEn" placeholder="Enter placeholder text in English">
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">نص المساعدة</label>
                        <div class="lang-inputs">
                            <div class="flex-1">
                                <div class="lang-label">العربية</div>
                                <textarea class="form-control" id="fieldHelpTextAr" rows="2" placeholder="نص مساعدة إضافي بالعربية"></textarea>
                            </div>
                            <div class="flex-1">
                                <div class="lang-label">English</div>
                                <textarea class="form-control" id="fieldHelpTextEn" rows="2" placeholder="Additional help text in English"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">هل الحقل مطلوب؟</label>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="fieldRequired">
                            <label class="form-check-label" for="fieldRequired">
                                مطلوب
                            </label>
                        </div>
                    </div>
                    <!-- Options Editor (for select, radio, checkbox) -->
                    <div id="optionsEditor" class="mb-3" style="display: none;">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <label class="form-label">الخيارات</label>
                            <button class="btn btn-sm btn-link text-primary p-0" id="addOptionBtn">
                                <i class="mdi mdi-plus me-1"></i> إضافة
                            </button>
                        </div>
                        <div id="optionsList" class="space-y-2">
                            <!-- Options will be added here dynamically -->
                        </div>
                    </div>
                    <!-- Rating Settings (for stars, rating) -->
                    <div id="ratingSettings" class="mb-3" style="display: none;">
                        <div class="mb-3">
                            <label class="form-label">عدد النقاط</label>
                            <input type="number" class="form-control" id="ratingPoints" min="2" max="10" value="5">
                        </div>
                    </div>
                    <div class="mb-3 d-none" >
                        <label class="form-label">معرف السؤال</label>
                        <input type="text" class="form-control" id="fieldId" placeholder="question_id">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Hidden fields for survey data -->
<input type="hidden" id="surveyId" value="{{ $survey['id'] ?? '' }}">
<input type="hidden" id="surveyData" value='{!! isset($survey) ? json_encode($survey) : '{}' !!}'>
<!-- Toast for save confirmation -->
<div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
    <div id="saveToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-header">
            <i class="mdi mdi-check-circle text-success me-2"></i>
            <strong class="me-auto">تم الحفظ</strong>
            <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body">
            تم حفظ الاستبيان بنجاح.
        </div>
    </div>
</div>
{{-- @endsection --}}
{{-- @section('scripts') --}}
    <script>
        $(document).ready(function() {
            let selectedField = null;
            let fieldIdCounter = {{ isset($survey['fields']) ? collect($survey['fields'])->max('id') ?? 0 : 0 }} + 1;
            let surveyData = {!! isset($survey) ? json_encode($survey) : '{}' !!};

            // Initialize sortable for form fields
            $("#formFields").sortable({
                placeholder: "ui-state-highlight",
                tolerance: "pointer",
                cursor: "move",
                update: function(event, ui) {
                    updateFieldOrder();
                }
            });

            // Add field when sidebar field is clicked
            $(".sidebar-field").on("click", function() {
                const fieldType = $(this).data("type");
                addField(fieldType);
            });

            // Add field function
            function addField(type) {
                const fieldId = `field_${fieldIdCounter++}`;
                const fieldData = {
                    id: fieldId,
                    type: type,
                    label: { ar: getDefaultLabel(type, 'ar'), en: getDefaultLabel(type, 'en') },
                    placeholder: { ar: "", en: "" },
                    width: "col-12",
                    errorMessage: "",
                    bgColor: "#ffffff",
                    textColor: "#000000",
                    fontSize: "medium",
                    bold: false,
                    customId: "",
                    customClass: "",
                    attributes: "",
                    disabled: false,
                    hidden: false,
                    settings: {
                        required: false // Always include required default
                    }
                };

                // Add options for fields that support them
                if (type === 'select' || type === 'radio' || type === 'checkbox') {
                    fieldData.options = [
                        { label: { ar: "الخيار 1", en: "Option 1" }, value: "option1" },
                        { label: { ar: "الخيار 2", en: "Option 2" }, value: "option2" },
                    ];
                }

                // Add settings for rating fields - FIXED: Include default points
                if (type === 'stars' || type === 'rating') {
                    fieldData.settings.points = 5; // Set default points
                    console.log("Created new rating field with default points:", fieldData.settings);
                }

                // Add to survey data
                if (!surveyData.fields) {
                    surveyData.fields = [];
                }
                surveyData.fields.push(fieldData);
                renderField(fieldData);

                // Remove empty state message if it exists
                $("#formFields .text-center.text-muted").remove();

                // Select the newly added field
                selectField(fieldId);
            }

            // Get default label based on field type and language
            function getDefaultLabel(type, lang) {
                const labels = {
                    ar: {
                        text: "سؤال نصي",
                        textarea: "منطقة نص",
                        select: "قائمة منسدلة",
                        radio: "مجموعة خيارات",
                        checkbox: "مجموعة خانات اختيار",
                        date: "التاريخ",
                        datetime: "التاريخ والوقت",
                        number: "رقم",
                        email: "البريد الإلكتروني",
                        tel: "رقم الهاتف",
                        url: "رابط الموقع",
                        stars: "تقييم بالنجوم",
                        rating: "مقياس التقييم",
                    },
                    en: {
                        text: "Text Question",
                        textarea: "Textarea Question",
                        select: "Dropdown Question",
                        radio: "Radio Group",
                        checkbox: "Checkbox Group",
                        date: "Date",
                        datetime: "Date and Time",
                        number: "Number",
                        email: "Email",
                        tel: "Phone",
                        url: "URL",
                        stars: "Star Rating",
                        rating: "Rating Scale",
                    }
                };
                return labels[lang][type] || (lang === 'ar' ? "سؤال جديد" : "New Question");
            }

            // Render field in the survey builder
function renderField(fieldData) {
    let fieldHtml = `
        <div dir="rtl" class="form-field ${fieldData.width}" id="${fieldData.id}" data-field-id="${fieldData.id}">
            <div class="field-actions">
                <button class="btn btn-sm btn-light me-1 duplicate-field" title="نسخ">
                    <i class="mdi mdi-content-copy"></i>
                </button>
                <button class="btn btn-sm btn-light me-1 move-up" title="تحريك للأعلى">
                    <i class="mdi mdi-arrow-up"></i>
                </button>
                <button class="btn btn-sm btn-light me-1 move-down" title="تحريك للأسفل">
                    <i class="mdi mdi-arrow-down"></i>
                </button>
                <button class="btn btn-sm btn-light delete-field" title="حذف">
                    <i class="mdi mdi-delete text-danger"></i>
                </button>
            </div>
            <div class="field-content">
                ${generateFieldHtml(fieldData)}
            </div>
            <div class="field-type-label text-start">${getFieldTypeName(fieldData.type)}</div>
        </div>
    `;
    $("#formFields").append(fieldHtml);

    // Add event listeners to the new field
    attachFieldEvents(fieldData.id);
}

            // Generate HTML for specific field type
            function generateFieldHtml(fieldData) {
                const { type, label, placeholder, options, settings } = fieldData;
                const currentLocale = '{{ app()->getLocale() }}';
                const currentLang = currentLocale === 'ar' ? 'ar' : 'en';
                const required = settings.required ? ' required' : '';
                const requiredLabel = settings.required ? ' *' : '';

                switch(type) {
                    case "text":
                    case "email":
                    case "tel":
                    case "url":
                    case "number":
                        return `
                            <label class="form-label">${label[currentLang]}${requiredLabel}</label>
                            <input type="${type}" class="form-control" placeholder="${placeholder[currentLang]}" disabled${required}>
                        `;
                    case "textarea":
                        return `
                            <label class="form-label">${label[currentLang]}${requiredLabel}</label>
                            <textarea class="form-control" rows="3" placeholder="${placeholder[currentLang]}" disabled${required}></textarea>
                        `;
                    case "select":
                        let selectOptions = options.map(opt =>
                            `<option value="${opt.value}">${opt.label[currentLang]}</option>`
                        ).join('');
                        return `
                            <label class="form-label">${label[currentLang]}${requiredLabel}</label>
                            <select class="form-select" disabled${required}>
                                <option value="">${currentLang === 'ar' ? 'حدد خيارًا...' : 'Select an option...'}</option>
                                ${selectOptions}
                            </select>
                        `;
                    case "radio":
                        let radioOptions = options.map((opt, index) => {
                            const isRequired = (index === 0 && required) ? ' required' : '';
                            return `
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="${fieldData.id}" id="${fieldData.id}_${index}" value="${opt.value}" disabled ${isRequired}>
                                    <label class="form-check-label" for="${fieldData.id}_${index}">
                                        ${opt.label[currentLang]}
                                    </label>
                                </div>
                            `;
                        }).join('');
                        return `
                            <label class="form-label">${label[currentLang]}${requiredLabel}</label>
                            <div>
                                ${radioOptions}
                            </div>
                        `;
                    case "checkbox":
                        let checkboxOptions = options.map((opt, index) => `
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="${fieldData.id}_${index}" value="${opt.value}" disabled>
                                <label class="form-check-label" for="${fieldData.id}_${index}">
                                    ${opt.label[currentLang]}
                                </label>
                            </div>
                        `).join('');
                        return `
                            <label class="form-label">${label[currentLang]}${requiredLabel}</label>
                            <div>
                                ${checkboxOptions}
                            </div>
                        `;
                    case "date":
                        return `
                            <label class="form-label">${label[currentLang]}${requiredLabel}</label>
                            <input type="date" class="form-control" disabled${required}>
                        `;
                    case "datetime":
                        return `
                            <label class="form-label">${label[currentLang]}${requiredLabel}</label>
                            <input type="datetime-local" class="form-control" disabled${required}>
                        `;
                    case "stars":
                        const ratingPoints = settings.points || 5;
                        let ratingStars = '';
                        for (let i = ratingPoints; i >= 1; i--) {
                            const isRequired = (i === ratingPoints && required) ? ' required' : '';
                            ratingStars += `
                                <input type="radio" id="${fieldData.id}_${i}" name="${fieldData.id}" value="${i}" disabled${isRequired}>
                                <label for="${fieldData.id}_${i}">★</label>
                            `;
                        }
                        return `
                            <label class="form-label">${label[currentLang]}${requiredLabel}</label>
                            <div class="rating-form-stars">
                                ${ratingStars}
                            </div>
                        `;
                    case "rating":
                        const ratingValues = Array.from({length: settings.points || 5}, (_, i) => i + 1);
                        let ratingOptions = ratingValues.map(value => {
                            const isRequired = (value === 1 && required) ? ' required' : '';
                            return `
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="${fieldData.id}" id="${fieldData.id}_${value}" value="${value}" disabled ${isRequired}>
                                    <label class="form-check-label" for="${fieldData.id}_${value}">
                                        ${value}
                                    </label>
                                </div>
                            `;
                        }).join('');
                        return `
                            <label class="form-label">${label[currentLang]}${requiredLabel}</label>
                            <div class="rating-scale {{ app()->getLocale() == "ar" ? "rating-scale-ar" : "" }}">
                                <div class="row">
                                    ${ratingOptions}
                                </div>
                            </div>
                        `;
                    default:
                        return `
                            <label class="form-label">${label[currentLang]}${requiredLabel}</label>
                            <input type="text" class="form-control" placeholder="${placeholder[currentLang]}" disabled${required}>
                        `;
                }
            }

            // Get field type name
            function getFieldTypeName(type) {
                const names = {
                    text: "Text",
                    textarea: "Textarea",
                    select: "Dropdown",
                    radio: "Radio",
                    checkbox: "Checkbox",
                    date: "Date",
                    datetime: "Datetime",
                    number: "Number",
                    email: "Email",
                    tel: "Tel",
                    url: "Url",
                    stars: "Stars",
                    rating: "Rating",
                };
                return names[type] || "Field";
            }

            // Attach event listeners to field actions
            function attachFieldEvents(fieldId) {
                const fieldElement = $(`#${fieldId}`);

                // Select field when clicked
                fieldElement.on("click", function(e) {
                    if (!$(e.target).closest(".field-actions").length) {
                        selectField(fieldId);
                    }
                });

                // Duplicate field
                fieldElement.find(".duplicate-field").on("click", function() {
                    duplicateField(fieldId);
                });

                // Move field up
                fieldElement.find(".move-up").on("click", function() {
                    moveField(fieldId, "up");
                });

                // Move field down
                fieldElement.find(".move-down").on("click", function() {
                    moveField(fieldId, "down");
                });

                // Delete field
                fieldElement.find(".delete-field").on("click", function() {
                    deleteField(fieldId);
                });
            }

            // Select a field and show its properties
            function selectField(fieldId) {
                // Remove previous selection
                $(".form-field").removeClass("selected");

                // Add selection to current field
                $(`#${fieldId}`).addClass("selected");

                // Find field data
                const fieldData = surveyData.fields.find(field => field.id === fieldId);
                if (!fieldData) return;

                selectedField = fieldId;

                // Update properties panel
                $("#fieldTypeLabel").text(`${getFieldTypeName(fieldData.type)} سؤال`);
                $("#fieldLabelAr").val(fieldData.label.ar || "");
                $("#fieldLabelEn").val(fieldData.label.en || "");
                $("#fieldPlaceholderAr").val(fieldData.placeholder.ar || "");
                $("#fieldPlaceholderEn").val(fieldData.placeholder.en || "");
                $("#fieldHelpTextAr").val(fieldData.helpText ? fieldData.helpText.ar || "" : "");
                $("#fieldHelpTextEn").val(fieldData.helpText ? fieldData.helpText.en || "" : "");
                $("#fieldRequired").prop('checked', fieldData.settings.required || false);

                // Show/hide options editor based on field type
                if (fieldData.type === 'select' || fieldData.type === 'radio' || fieldData.type === 'checkbox') {
                    $("#optionsEditor").show();
                    $("#ratingSettings").hide();
                    renderOptionsEditor(fieldData);
                } else if (fieldData.type === 'stars' || fieldData.type === 'rating') {
                    $("#optionsEditor").hide();
                    $("#ratingSettings").show();
                    renderRatingSettings(fieldData);
                } else {
                    $("#optionsEditor").hide();
                    $("#ratingSettings").hide();
                }

                // Advanced tab
                $("#fieldId").val(fieldData.customId);
            }

            // Render options editor
            function renderOptionsEditor(fieldData) {
                const optionsList = $("#optionsList");
                optionsList.empty();

                fieldData.options.forEach((option, index) => {
                    const optionHtml = `
                        <div class="option-item" data-index="${index}">
                            <div class="lang-inputs" style="width: 100%">
                                <div class="flex-1">
                                    <div class="lang-label">العربية</div>
                                    <input type="text" class="form-control form-control-sm option-label-ar" placeholder="تسمية الخيار بالعربية" value="${option.label.ar || ''}">
                                </div>
                                <div class="flex-1">
                                    <div class="lang-label">English</div>
                                    <input type="text" class="form-control form-control-sm option-label-en" placeholder="Option label in English" value="${option.label.en || ''}">
                                </div>
                            </div>
                            <button class="btn btn-sm btn-link text-danger p-0 delete-option">
                                <i class="mdi mdi-delete"></i>
                            </button>
                        </div>
                    `;
                    optionsList.append(optionHtml);
                });

                // Add event listeners to option inputs
                optionsList.find(".option-label-ar").on("input", function() {
                    const index = $(this).closest(".option-item").data("index");
                    const newLabel = $(this).val();
                    updateOptionLabel(fieldData.id, index, newLabel, 'ar');
                });

                optionsList.find(".option-label-en").on("input", function() {
                    const index = $(this).closest(".option-item").data("index");
                    const newLabel = $(this).val();
                    updateOptionLabel(fieldData.id, index, newLabel, 'en');
                });

                // Add event listeners to delete buttons
                optionsList.find(".delete-option").on("click", function() {
                    const index = $(this).closest(".option-item").data("index");
                    deleteOption(fieldData.id, index);
                });
            }

            // Render rating settings
            function renderRatingSettings(fieldData) {
                $("#ratingPoints").val(fieldData.settings.points || 5);
            }

            // Update option label
            function updateOptionLabel(fieldId, optionIndex, newLabel, lang) {
                const fieldData = surveyData.fields.find(field => field.id === fieldId);
                if (!fieldData || !fieldData.options || !fieldData.options[optionIndex]) return;

                // Initialize label object if it doesn't exist
                if (!fieldData.options[optionIndex].label) {
                    fieldData.options[optionIndex].label = { ar: '', en: '' };
                }

                fieldData.options[optionIndex].label[lang] = newLabel;

                // Update field HTML
                $(`#${fieldId} .field-content`).html(generateFieldHtml(fieldData));

                // Mark as unsaved
                $(".unsaved-indicator").addClass("bg-warning").removeClass("bg-success");
            }

            // Delete option
            function deleteOption(fieldId, optionIndex) {
                const fieldData = surveyData.fields.find(field => field.id === fieldId);
                if (!fieldData || !fieldData.options || fieldData.options.length <= 1) return;

                fieldData.options.splice(optionIndex, 1);

                // Re-render options editor
                renderOptionsEditor(fieldData);

                // Update field HTML
                $(`#${fieldId} .field-content`).html(generateFieldHtml(fieldData));

                // Mark as unsaved
                $(".unsaved-indicator").addClass("bg-warning").removeClass("bg-success");
            }

            // Add new option
            $("#addOptionBtn").on("click", function() {
                if (!selectedField) return;

                const fieldData = surveyData.fields.find(field => field.id === selectedField);
                if (!fieldData || !fieldData.options) return;

                const newOption = {
                    label: { ar: `الخيار ${fieldData.options.length + 1}`, en: `Option ${fieldData.options.length + 1}` },
                    value: `option${fieldData.options.length + 1}`
                };

                fieldData.options.push(newOption);

                // Re-render options editor
                renderOptionsEditor(fieldData);

                // Update field HTML
                $(`#${selectedField} .field-content`).html(generateFieldHtml(fieldData));

                // Mark as unsaved
                $(".unsaved-indicator").addClass("bg-warning").removeClass("bg-success");
            });

            // Update rating settings
            $("#ratingPoints").on("input", function() {
                if (!selectedField) return;

                const fieldData = surveyData.fields.find(field => field.id === selectedField);
                if (!fieldData) return;

                // Initialize settings if it doesn't exist
                if (!fieldData.settings) {
                    fieldData.settings = {};
                }

                // Make sure to save the points value
                const pointsValue = parseInt($("#ratingPoints").val()) || 5;
                fieldData.settings.points = pointsValue;

                console.log("Updated points for field", selectedField, "to", pointsValue);
                console.log("Field settings:", fieldData.settings);

                // Update field HTML
                $(`#${selectedField} .field-content`).html(generateFieldHtml(fieldData));

                // Mark as unsaved
                $(".unsaved-indicator").addClass("bg-warning").removeClass("bg-success");
            });

            $("#fieldRequired").on("change", function() {
                if (!selectedField) return;
                const fieldData = surveyData.fields.find(field => field.id === selectedField);
                if (!fieldData) return;

                // Initialize settings if it doesn't exist
                if (!fieldData.settings) {
                    fieldData.settings = {};
                }

                fieldData.settings.required = $(this).prop('checked');

                // Update field HTML
                $(`#${selectedField} .field-content`).html(generateFieldHtml(fieldData));

                // Mark as unsaved
                $(".unsaved-indicator").addClass("bg-warning").removeClass("bg-success");
            });

            // Update field when properties change
            $("#fieldLabelAr, #fieldLabelEn").on("input", function() {
                if (!selectedField) return;

                const fieldData = surveyData.fields.find(field => field.id === selectedField);
                if (!fieldData) return;

                // Initialize label object if it doesn't exist
                if (!fieldData.label) {
                    fieldData.label = { ar: '', en: '' };
                }

                fieldData.label.ar = $("#fieldLabelAr").val();
                fieldData.label.en = $("#fieldLabelEn").val();

                // Update field HTML
                $(`#${selectedField} .field-content`).html(generateFieldHtml(fieldData));

                // Mark as unsaved
                $(".unsaved-indicator").addClass("bg-warning").removeClass("bg-success");
            });

            $("#fieldPlaceholderAr, #fieldPlaceholderEn").on("input", function() {
                if (!selectedField) return;

                const fieldData = surveyData.fields.find(field => field.id === selectedField);
                if (!fieldData) return;

                // Initialize placeholder object if it doesn't exist
                if (!fieldData.placeholder) {
                    fieldData.placeholder = { ar: '', en: '' };
                }

                fieldData.placeholder.ar = $("#fieldPlaceholderAr").val();
                fieldData.placeholder.en = $("#fieldPlaceholderEn").val();

                // Update field HTML
                $(`#${selectedField} .field-content`).html(generateFieldHtml(fieldData));

                // Mark as unsaved
                $(".unsaved-indicator").addClass("bg-warning").removeClass("bg-success");
            });

            $("#fieldHelpTextAr, #fieldHelpTextEn").on("input", function() {
                if (!selectedField) return;

                const fieldData = surveyData.fields.find(field => field.id === selectedField);
                if (!fieldData) return;

                // Initialize helpText object if it doesn't exist
                if (!fieldData.helpText) {
                    fieldData.helpText = { ar: '', en: '' };
                }

                fieldData.helpText.ar = $("#fieldHelpTextAr").val();
                fieldData.helpText.en = $("#fieldHelpTextEn").val();

                // Update field HTML
                $(`#${selectedField} .field-content`).html(generateFieldHtml(fieldData));

                // Mark as unsaved
                $(".unsaved-indicator").addClass("bg-warning").removeClass("bg-success");
            });

            $("#fieldId").on("input", function() {
                if (!selectedField) return;

                const fieldData = surveyData.fields.find(field => field.id === selectedField);
                if (!fieldData) return;

                fieldData.customId = $("#fieldId").val();

                // Mark as unsaved
                $(".unsaved-indicator").addClass("bg-warning").removeClass("bg-success");
            });

            // Update survey title
            $("#surveyTitle").on("input", function() {
                surveyData.title = $(this).val();
                $(".unsaved-indicator").addClass("bg-warning").removeClass("bg-success");
            });

            // Duplicate field
            function duplicateField(fieldId) {
                const fieldData = surveyData.fields.find(field => field.id === fieldId);
                if (!fieldData) return;

                // Create a copy of the field data
                const newFieldData = JSON.parse(JSON.stringify(fieldData));
                newFieldData.id = `field_${fieldIdCounter++}`;

                // Add to survey data
                surveyData.fields.push(newFieldData);

                // Render the new field
                renderField(newFieldData);

                // Select the new field
                selectField(newFieldData.id);

                // Mark as unsaved
                $(".unsaved-indicator").addClass("bg-warning").removeClass("bg-success");
            }

            function validateDataConsistency() {
                const domFields = $("#formFields .form-field").length;
                const dataFields = surveyData.fields.length;

                if (domFields !== dataFields) {
                    console.error("Data inconsistency: DOM has", domFields, "fields but data has", dataFields);
                    return false;
                }

                // Check if all fields in DOM exist in data
                let allFieldsExist = true;
                $("#formFields .form-field").each(function() {
                    const fieldId = $(this).data("field-id");
                    if (!surveyData.fields.find(f => f.id === fieldId)) {
                        console.error("Field in DOM not found in data:", fieldId);
                        allFieldsExist = false;
                    }
                });

                return allFieldsExist;
            }
            // Move field up or down
            function moveField(fieldId, direction) {
                // Check if the field exists in surveyData
                const fieldExists = surveyData.fields.some(field => field.id === fieldId);
                if (!fieldExists) {
                    console.error("Cannot move field because it's not in survey data:", fieldId);
                    return;
                }

                const fieldElement = $(`#${fieldId}`);
                if (direction === "up") {
                    const prevElement = fieldElement.prev();
                    if (prevElement.length) {
                        fieldElement.insertBefore(prevElement);
                    }
                } else if (direction === "down") {
                    const nextElement = fieldElement.next();
                    if (nextElement.length) {
                        fieldElement.insertAfter(nextElement);
                    }
                }

                updateFieldOrder();
                validateDataConsistency(); // Validate after moving
                debugDataState(); // Debug after moving
            }
            // Delete field
            function deleteField(fieldId) {
                if (confirm("هل أنت متأكد من حذف هذا السؤال؟")) {
                    console.log("Deleting field with ID:", fieldId);
                    console.log("Fields before deletion:", surveyData.fields.map(f => f.id));

                    // Remove from DOM
                    $(`#${fieldId}`).remove();

                    // Find the field index in the array
                    const fieldIndex = surveyData.fields.findIndex(field => field.id === fieldId);
                    if (fieldIndex !== -1) {
                        console.log("Found field at index:", fieldIndex);
                        // Remove the field from the array
                        surveyData.fields.splice(fieldIndex, 1);
                        console.log("Fields after deletion:", surveyData.fields.map(f => f.id));
                    } else {
                        console.error("Field not found in survey data!");
                    }

                    // Clear selection if deleted field was selected
                    if (selectedField === fieldId) {
                        selectedField = null;
                        $("#fieldTypeLabel").text("حدد سؤالاً لتحرير خصائصه");
                        $("#fieldLabelAr").val("");
                        $("#fieldLabelEn").val("");
                        $("#fieldPlaceholderAr").val("");
                        $("#fieldPlaceholderEn").val("");
                        $("#fieldHelpTextAr").val("");
                        $("#fieldHelpTextEn").val("");
                        $("#optionsEditor").hide();
                        $("#ratingSettings").hide();
                    }

                    // Show empty state if no fields
                    if (surveyData.fields.length === 0) {
                        $("#formFields").html(`
                            <div class="text-center text-muted py-5">
                                <i class="mdi mdi-drag-variant mdi-48px d-block mb-3"></i>
                                <p>اسحب أسئلة من الشريط الجانبي وأفلتها هنا لبناء الاستبيان</p>
                            </div>
                        `);
                    }

                    // Mark as unsaved
                    $(".unsaved-indicator").addClass("bg-warning").removeClass("bg-success");
                }
            }
            function debugDataState() {
                console.log("=== DEBUG: Current Data State ===");
                console.log("surveyData.fields:", surveyData.fields.map(f => f.id));
                console.log("DOM fields:", $("#formFields .form-field").map(function() {
                    return $(this).data("field-id");
                }).get());
                console.log("================================");
            }

            function updateFieldOrder() {
                const fieldOrder = [];
                $("#formFields .form-field").each(function() {
                    fieldOrder.push($(this).data("field-id"));
                });

                console.log("DOM field order:", fieldOrder);
                console.log("Current survey fields:", surveyData.fields.map(f => f.id));

                // Create a map of field IDs to their data for quick lookup
                const fieldMap = {};
                surveyData.fields.forEach(field => {
                    fieldMap[field.id] = field;
                });

                // Rebuild the fields array in the new order
                const orderedFields = [];
                fieldOrder.forEach(fieldId => {
                    if (fieldMap[fieldId]) {
                        orderedFields.push(fieldMap[fieldId]);
                    } else {
                        console.error("Field not found in survey data:", fieldId);
                        console.log("Available field IDs:", Object.keys(fieldMap));
                    }
                });

                // Only update if we found all fields
                if (orderedFields.length === fieldOrder.length) {
                    surveyData.fields = orderedFields;
                    console.log("Successfully updated field order");
                } else {
                    console.error("Not all fields found in survey data. Aborting order update.");
                    console.log("Expected fields:", fieldOrder);
                    console.log("Found fields:", orderedFields.map(f => f.id));
                }

                // Mark as unsaved
                $(".unsaved-indicator").addClass("bg-warning").removeClass("bg-success");
            }

            // Save survey
// Save survey
$("#saveBtn, #saveFormBtn").on("click", function() {
    // Get survey ID from hidden field
    const surveyId = $("#surveyId").val();

    // Prepare data for saving
    const saveData = {
        title: surveyData.title,
        description: surveyData.description || "",
        questions: surveyData.fields.map((field, index) => {
            const questionData = {
                id: field.id,
                question_text: field.label,
                question_type: field.type,
                placeholder: field.placeholder,
                help_text: field.helpText,
                error_message: field.errorMessage,
                options: field.options || null,
                settings: field.settings || {},
                order: index
            };

            // Debug each field being saved
            console.log(`Field ${field.id}:`, {
                type: field.type,
                settings: field.settings,
                hasPoints: field.settings && field.settings.points !== undefined
            });

            return questionData;
        })
    };

    // Debug the complete save data
    console.log("Complete save data:", saveData);

    // Show which fields have points
    saveData.questions.forEach(q => {
        if (q.question_type === 'stars' || q.question_type === 'rating') {
            console.log(`${q.question_type} field ${q.id} points:`, q.settings.points);
        }
    });

    // Send data to server
    $.ajax({
        url: `/surveys/${surveyId}`,
        type: 'PUT',
        data: {
            _token: '{{ csrf_token() }}',
            _method: 'PUT',
            survey: saveData
        },
        success: function(response) {
            // Update survey ID if this was a new survey
            if (!surveyId && response.id) {
                $("#surveyId").val(response.id);
            }

            // Show success message
            const toast = new bootstrap.Toast(document.getElementById('saveToast'));
            toast.show();

            // Mark as saved
            $(".unsaved-indicator").addClass("bg-success").removeClass("bg-warning");
        },
        error: function(xhr) {
            console.error("Save error:", xhr.responseText);
            let response = JSON.parse(xhr.responseText);
            if (response.errors) {
                // Display first error only
                let firstError = Object.values(response.errors)[0][0];
                alert('Error: ' + firstError);
            } else {
                alert('Unexpected error occurred.');
            }
        }
    });
});


            // Generate preview field HTML (without disabled attribute)
            function generatePreviewFieldHtml(fieldData) {
                const { type, label, placeholder, options, settings } = fieldData;
                const currentLocale = '{{ app()->getLocale() }}';
                const currentLang = currentLocale === 'ar' ? 'ar' : 'en';
                const required = settings.required ? ' required' : '';
                const requiredLabel = settings.required ? ' *' : '';

                switch(type) {
                    case "text":
                    case "email":
                    case "tel":
                    case "url":
                    case "number":
                        return `
                        <div class="rating-form-group">
                            <label class="rating-form-label">${label[currentLang]}${requiredLabel}</label>
                            <input type="${type}" class="rating-form-input" placeholder="${placeholder[currentLang]}" name="question_${fieldData.id.replace('field_', '')}"${required}>
                        </div>
                        `;
                    case "textarea":
                        return `
                        <div class="rating-form-group">
                            <label class="rating-form-label">${label[currentLang]}${requiredLabel}</label>
                            <textarea class="rating-form-textarea" rows="3" placeholder="${placeholder[currentLang]}" name="question_${fieldData.id.replace('field_', '')}"${required}></textarea>
                        </div>
                        `;
                    case "select":
                        let selectOptions = '';
                        if (options) {
                            options.forEach(option => {
                                selectOptions += `<option value="${option.value}">${option.label[currentLang]}</option>`;
                            });
                        }
                        return `
                        <div class="rating-form-group">
                            <label class="rating-form-label">${label[currentLang]}${requiredLabel}</label>
                            <select class="form-select" name="question_${fieldData.id.replace('field_', '')}"${required}>
                                <option value="">${currentLang === 'ar' ? 'حدد خيارًا...' : 'Select an option...'}</option>
                                ${selectOptions}
                            </select>
                        </div>
                        `;
                    case "radio":
                        let radioOptions = '';
                        if (options) {
                            options.forEach((option, index) => {
                                const isRequired = (index === 0 && required) ? ' required' : '';
                                radioOptions += `
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" id="question_${index}" name="question_${fieldData.id.replace('field_', '')}" value="${option.value}" ${isRequired}>
                                        <label class="form-check-label" for="question_${index}">
                                            ${option.label[currentLang]}
                                        </label>
                                    </div>
                                `;
                            });
                        }
                        return `
                        <div class="rating-form-group">
                            <label class="rating-form-label">${label[currentLang]}${requiredLabel}</label>
                            <div>
                                ${radioOptions}
                            </div>
                        </div>
                        `;
                    case "checkbox":
                        let checkboxOptions = '';
                        if (options) {
                            options.forEach((option, index) => {
                                checkboxOptions += `
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="question_${fieldData.id.replace('field_', '')}[]" id="question_${index}" value="${option.value}">
                                        <label class="form-check-label" for="question_${index}">
                                            ${option.label[currentLang]}
                                        </label>
                                    </div>
                                `;
                            });
                        }
                        return `
                        <div class="rating-form-group">
                            <label class="rating-form-label">${label[currentLang]}${requiredLabel}</label>
                            <div>
                                ${checkboxOptions}
                            </div>
                        </div>
                        `;
                    case "date":
                        return `
                        <div class="rating-form-group">
                            <label class="rating-form-label">${label[currentLang]}${requiredLabel}</label>
                            <input type="date" class="rating-form-input" name="question_${fieldData.id.replace('field_', '')}"${required}>
                        </div>
                        `;
                    case "datetime":
                        return `
                        <div class="rating-form-group">
                            <label class="rating-form-label">${label[currentLang]}${requiredLabel}</label>
                            <input type="datetime-local" class="rating-form-input" name="question_${fieldData.id.replace('field_', '')}"${required}>
                        </div>
                        `;
                    case "stars":
                        const points = settings.points || 5;
                        let stars = '';
                        for (let i = points; i >= 1; i--) {
                            const isRequired = (i === points && required) ? ' required' : '';
                            stars += `
                                <input type="radio" id="${fieldData.id}_star${i}" value="${i}" name="question_${fieldData.id.replace('field_', '')}" ${isRequired}>
                                <label for="${fieldData.id}_star${i}">★</label>
                            `;
                        }
                        return `
                        <div class="rating-form-group">
                            <label class="rating-form-label">${label[currentLang]}${requiredLabel}</label>
                            <div class="rating-form-stars">
                                ${stars}
                            </div>
                        </div>
                        `;
                    case "rating":
                        const ratingPoints = settings.points || 5;
                        let ratingOptions = '';
                        for (let i = 1; i <= ratingPoints; i++) {
                            const isRequired = (i === 1 && required) ? ' required' : '';
                            ratingOptions += `
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" id="question_${i}" name="question_${fieldData.id.replace('field_', '')}" value="${i}" ${isRequired}>
                                    <label class="form-check-label" for="question_${i}">
                                        ${i}
                                    </label>
                                </div>
                            `;
                        }
                        return `
                        <div class="rating-form-group">
                            <label class="rating-form-label">${label[currentLang]}${requiredLabel}</label>
                            <div class="rating-scale {{ app()->getLocale() == "ar" ? "rating-scale-ar" : "" }}">
                                <div class="row">
                                    ${ratingOptions}
                                </div>
                            </div>
                        </div>
                        `;
                    default:
                        return `
                        <div class="rating-form-group">
                            <label class="rating-form-label">${label[currentLang]}${requiredLabel}</label>
                            <input type="text" class="rating-form-input" placeholder="${placeholder[currentLang]}" name="question_${fieldData.id.replace('field_', '')}"${required}>
                        </div>
                        `;
                }
            }

            // Initialize with existing survey data if available
            @if(isset($survey))
                // Initialize survey data with consistent field IDs
                surveyData = {
                    id: {{ $survey['id'] }},
                    title: "{{ $survey['title'] }}",
                    description: "{{ $survey['description'] }}",
                    fields: [
                        @foreach($survey['fields'] as $question)
                    {
                            id: "field_{{ $question['id'] }}", // Ensure this matches the DOM data-field-id
                            type: "{{ $question['question_type'] }}",
                            label: {!! json_encode(is_array($question['question_text']) ? $question['question_text'] : ['ar' => $question['question_text'], 'en' => $question['question_text']]) !!},
                            placeholder: {!! json_encode(is_array($question['placeholder']) ? $question['placeholder'] : ['ar' => $question['placeholder'], 'en' => $question['placeholder']]) !!},
                            helpText: {!! json_encode(is_array($question['help_text']) ? $question['help_text'] : ['ar' => $question['help_text'], 'en' => $question['help_text']]) !!},
                            width: "{{ $question['settings']['width'] ?? 'col-12' }}",
                            errorMessage: "{{ $question['error_message'] }}",
                            options: {!! $question['options'] ? json_encode($question['options']) : 'null' !!},
                            settings: {!! $question['settings'] ? json_encode($question['settings']) : '{}' !!}
                        }
                        @if(!$loop->last),@endif
                        @endforeach
                    ]
                };

                // Set survey title
                $("#surveyTitle").val(surveyData.title);

                // Remove empty state
                $("#formFields .text-center.text-muted").remove();

                // Ensure all existing DOM elements have consistent data-field-id attributes
                $("#formFields .form-field").each(function() {
                    const domId = $(this).attr('id');
                    $(this).attr('data-field-id', domId);
                });

                surveyData.fields.forEach(function(field) {
                    attachFieldEvents(field.id);
                });
            @endif

            function checkFieldConsistency() {
                console.log("=== FIELD CONSISTENCY CHECK ===");

                const domFields = [];
                $("#formFields .form-field").each(function() {
                    domFields.push({
                        id: $(this).attr('id'),
                        dataFieldId: $(this).data('field-id')
                    });
                });

                const dataFields = surveyData.fields.map(f => f.id);

                console.log("DOM fields:", domFields);
                console.log("Data fields:", dataFields);

                // Check if all DOM fields have matching data
                domFields.forEach(domField => {
                    if (!dataFields.includes(domField.id)) {
                        console.error(`DOM field ${domField.id} not found in data`);
                    }
                    if (domField.id !== domField.dataFieldId) {
                        console.error(`Inconsistent field ID: DOM id=${domField.id}, data-field-id=${domField.dataFieldId}`);
                    }
                });

                // Check if all data fields have matching DOM
                dataFields.forEach(dataField => {
                    if (!$(`#${dataField}`).length) {
                        console.error(`Data field ${dataField} not found in DOM`);
                    }
                });

                console.log("===============================");
            }

        });
    </script>
</body>
</html>
