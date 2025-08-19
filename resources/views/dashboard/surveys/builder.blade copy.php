{{-- resources/views/admin/surveys/builder.blade.php --}}
{{-- @extends('layouts.app')

@section('title', 'Survey Builder')
@section('content') --}}

{{-- @section('styles') --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap-utilities.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/@mdi/font@6.5.95/css/materialdesignicons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

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
    </style>
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
                    <button class="btn btn-sm btn-outline-secondary" id="previewBtn">
                        <i class="mdi mdi-open-in-new me-1"></i> مشاهدة
                    </button>
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
                            <div dir="rtl" class="form-field {{ $question['settings']['width'] ?? 'col-12' }}" id="field_{{ $question['id'] }}" data-field-id="{{ $question['id'] }}">
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
        <div class="col-md-3 p-3">
            <div class="properties-panel">
                <div class="p-3 border-bottom">
                    <h5 class="mb-1">خصائص السؤال</h5>
                    <p class="text-muted small mb-0" id="fieldTypeLabel">حدد سؤالاً لتحرير خصائصه</p>
                </div>

                <div class="p-3">
                    <div class="mb-3">
                        <label class="form-label">نص السؤال</label>
                        <input type="text" class="form-control" id="fieldLabel" placeholder="أدخل نص السؤال">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">النص البديل</label>
                        <input type="text" class="form-control" id="fieldPlaceholder" placeholder="أدخل النص البديل">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">نص المساعدة</label>
                        <textarea class="form-control" id="fieldHelpText" rows="2" placeholder="نص مساعدة إضافي"></textarea>
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

<!-- Preview Button -->
<button class="btn btn-primary preview-btn" id="previewFormBtn">
    <i class="mdi mdi-open-in-new mdi-24px"></i>
</button>

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
                    label: getDefaultLabel(type),
                    placeholder: "",
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
                    settings: {}
                };

                // Add options for fields that support them
                if (type === 'select' || type === 'radio' || type === 'checkbox') {
                    fieldData.options = [
                        { label: "الخيار 1", value: "option1" },
                        { label: "الخيار 2", value: "option2" },
                    ];
                }

                // Add settings for rating fields
                if (type === 'stars' || type === 'rating') {
                    fieldData.settings = {
                        points: 5,
                    };
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

            // Get default label based on field type
            function getDefaultLabel(type) {
                const labels = {
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
                };
                return labels[type] || "سؤال جديد";
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

                switch(type) {
                    case "text":
                    case "email":
                    case "tel":
                    case "url":
                    case "number":
                        return `
                            <label class="form-label">${label} </label>
                            <input type="${type}" class="form-control" placeholder="${placeholder}" disabled>
                        `;
                    case "textarea":
                        return `
                            <label class="form-label">${label} </label>
                            <textarea class="form-control" rows="3" placeholder="${placeholder}" disabled></textarea>
                        `;
                    case "select":
                        let selectOptions = options.map(opt =>
                            `<option value="${opt.value}">${opt.label}</option>`
                        ).join('');
                        return `
                            <label class="form-label">${label} </label>
                            <select class="form-select" disabled>
                                <option value="">حدد خيارًا...</option>
                                ${selectOptions}
                            </select>
                        `;
                    case "radio":
                        let radioOptions = options.map((opt, index) => `
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="${fieldData.id}" id="${fieldData.id}_${index}" value="${opt.value}" disabled>
                                <label class="form-check-label" for="${fieldData.id}_${index}">
                                    ${opt.label}
                                </label>
                            </div>
                        `).join('');
                        return `
                            <label class="form-label">${label} </label>
                            <div>
                                ${radioOptions}
                            </div>
                        `;
                    case "checkbox":
                        let checkboxOptions = options.map((opt, index) => `
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="${fieldData.id}_${index}" value="${opt.value}" disabled>
                                <label class="form-check-label" for="${fieldData.id}_${index}">
                                    ${opt.label}
                                </label>
                            </div>
                        `).join('');
                        return `
                            <label class="form-label">${label} </label>
                            <div>
                                ${checkboxOptions}
                            </div>
                        `;
                    case "date":
                        return `
                            <label class="form-label">${label} </label>
                            <input type="date" class="form-control" disabled>
                        `;
                    case "datetime":
                        return `
                            <label class="form-label">${label} </label>
                            <input type="datetime-local" class="form-control" disabled>
                        `;

                    case "stars":
                        const ratingPoints = settings.points || 5;
                        let ratingStars = '';
                        for (let i = ratingPoints; i >= 1; i--) {
                            ratingStars += `
                                <input type="radio" id="${fieldData.id}_${i}" name="${fieldData.id}" value="${i}" disabled>
                                <label for="${fieldData.id}_${i}">★</label>
                            `;
                        }
                        return `
                            <label class="form-label">${label} </label>
                            <div class="rating-form-stars">
                                ${ratingStars}
                            </div>
                        `;
                    case "rating":
                        const ratingValues = Array.from({length: settings.points || 5}, (_, i) => i + 1);
                        let ratingOptions = ratingValues.map(value => `
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="${fieldData.id}" id="${fieldData.id}_${value}" value="${value}" disabled>
                                <label class="form-check-label" for="${fieldData.id}_${value}">
                                    ${value}
                                </label>
                            </div>
                        `).join('');
                        return `
                            <label class="form-label">${label} </label>
                            <div class="rating-scale {{ app()->getLocale() == "ar" ? "rating-scale-ar" : "" }}">
                                <div class="row">
                                    ${ratingOptions}
                                </div>
                            </div>
                        `;
                    default:
                        return `
                            <label class="form-label">${label} </label>
                            <input type="text" class="form-control" placeholder="${placeholder}" disabled>
                        `;
                }
            }

            // Get field type name in Arabic
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
                $("#fieldLabel").val(fieldData.label);
                $("#fieldPlaceholder").val(fieldData.placeholder);
                $("#fieldHelpText").val(fieldData.helpText || "");

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
                            <input type="text" class="form-control form-control-sm" placeholder="تسمية الخيار" value="${option.label}">
                            <button class="btn btn-sm btn-link text-danger p-0 delete-option">
                                <i class="mdi mdi-delete"></i>
                            </button>
                        </div>
                    `;
                    optionsList.append(optionHtml);
                });

                // Add event listeners to option inputs
                optionsList.find("input").on("input", function() {
                    const index = $(this).closest(".option-item").data("index");
                    const newLabel = $(this).val();
                    updateOptionLabel(fieldData.id, index, newLabel);
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
            function updateOptionLabel(fieldId, optionIndex, newLabel) {
                const fieldData = surveyData.fields.find(field => field.id === fieldId);
                if (!fieldData || !fieldData.options || !fieldData.options[optionIndex]) return;

                fieldData.options[optionIndex].label = newLabel;
                fieldData.options[optionIndex].value = generateOptionValue(newLabel);

                // Update field HTML
                $(`#${fieldId} .field-content`).html(generateFieldHtml(fieldData));

                // Mark as unsaved
                $(".unsaved-indicator").addClass("bg-warning").removeClass("bg-success");
            }

            // Generate option value from label
            function generateOptionValue(label) {
                return label.toLowerCase()
                    .replace(/\s+/g, '_')
                    .replace(/[^\w-]+/g, '')
                    .replace(/-+/g, '_');
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
                    label: `الخيار ${fieldData.options.length + 1}`,
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
                if (!fieldData || !fieldData.settings) return;

                fieldData.settings.points = parseInt($("#ratingPoints").val()) || 5;

                // Update field HTML
                $(`#${selectedField} .field-content`).html(generateFieldHtml(fieldData));

                // Mark as unsaved
                $(".unsaved-indicator").addClass("bg-warning").removeClass("bg-success");
            });

            // Update field when properties change
            $("#fieldLabel, #fieldPlaceholder, #fieldHelpText").on("input", function() {
                if (!selectedField) return;

                const fieldData = surveyData.fields.find(field => field.id === selectedField);
                if (!fieldData) return;

                fieldData.label = $("#fieldLabel").val();
                fieldData.placeholder = $("#fieldPlaceholder").val();
                fieldData.helpText = $("#fieldHelpText").val();

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

            // Move field up or down
            function moveField(fieldId, direction) {
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
            }

            // Delete field
            function deleteField(fieldId) {
                if (confirm("هل أنت متأكد من حذف هذا السؤال؟")) {
                    // Remove from DOM
                    $(`#${fieldId}`).remove();

                    // Remove from survey data
                    surveyData.fields = surveyData.fields.filter(field => field.id !== fieldId);

                    // Clear selection if deleted field was selected
                    if (selectedField === fieldId) {
                        selectedField = null;
                        $("#fieldTypeLabel").text("حدد سؤالاً لتحرير خصائصه");
                        $("#fieldLabel").val("");
                        $("#fieldPlaceholder").val("");
                        $("#fieldHelpText").val("");
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

            // Update field order in survey data
            function updateFieldOrder() {
                const fieldOrder = [];
                $("#formFields .form-field").each(function() {
                    fieldOrder.push($(this).data("field-id"));
                });

                // Reorder fields in survey data
                const orderedFields = [];
                fieldOrder.forEach(fieldId => {
                    const field = surveyData.fields.find(f => f.id === fieldId);
                    if (field) {
                        orderedFields.push(field);
                    }
                });

                surveyData.fields = orderedFields;

                // Mark as unsaved
                $(".unsaved-indicator").addClass("bg-warning").removeClass("bg-success");
            }

            // Save survey
            $("#saveBtn, #saveFormBtn").on("click", function() {
                // Get survey ID from hidden field
                const surveyId = $("#surveyId").val();

                // Prepare data for saving
                const saveData = {
                    title: surveyData.title,
                    description: surveyData.description || "",
                    questions: surveyData.fields.map((field, index) => ({
                        question_text: field.label,
                        question_type: field.type,
                        placeholder: field.placeholder,
                        help_text: field.helpText,
                        error_message: field.errorMessage,
                        options: field.options || null,
                        settings: field.settings || null,
                        order: index
                    }))
                };

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
                        let response = JSON.parse(xhr.responseText); // تحويل النص إلى JSON
                        if (response.errors) {
                            // عرض أول خطأ فقط
                            let firstError = Object.values(response.errors)[0][0];
                            alert('Error: ' + firstError);
                        } else {
                            alert('Unexpected error occurred.');
                        }
                    }

                });
            });

            // Preview survey
            $("#previewBtn, #previewFormBtn").on("click", function() {
                // Generate preview HTML
                let previewHtml = `
                    <div class="container mt-4">
                        <h2 class="mb-4">${surveyData.title}</h2>
                        <form id="previewForm">
                `;

                surveyData.fields.forEach(field => {
                    previewHtml += `
                        <div class="mb-3 ${field.width}">
                            ${generatePreviewFieldHtml(field)}
                        </div>
                    `;
                });

                previewHtml += `
                        </form>
                    </div>
                `;

                // Open preview in new window
                const previewWindow = window.open("", "_blank");
                previewWindow.document.open();
                previewWindow.document.write(`
                    <!DOCTYPE html>
                    <html lang="ar" dir="rtl">
                        <head>
                            <meta charset="UTF-8">
                            <meta name="viewport" content="width=device-width, initial-scale=1.0">
                            <title>معاينة الاستبيان</title>
                            <link rel="preconnect" href="https://fonts.googleapis.com">
                            <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
                            <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@200..1000&display=swap" rel="stylesheet">
                            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
                            <link href="https://cdn.jsdelivr.net/npm/@mdi/font@6.5.95/css/materialdesignicons.min.css" rel="stylesheet">
                            <style>
                                body {
                                    font-family: "Cairo", sans-serif;
                                    background-color: #f2f2f2;
                                    margin: 0;
                                    padding: 0;
                                }
                                .rating-form-container {
                                    max-width: 600px;
                                    margin: 80px auto;
                                    padding: 20px;
                                    background-color: #ffffff;
                                    border-radius: 8px;
                                    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
                                }
                                .order-details {
                                    padding: 20px;
                                    background-color: #f9f9f9;
                                    border-radius: 8px;
                                    margin-bottom: 20px;
                                }
                                .order-details h2 {
                                    margin-top: 0;
                                    color: #333;
                                }
                                .order-details p {
                                    margin: 5px 0;
                                    color: #666;
                                }
                                .rating-form-header {
                                    background-color: #007bff;
                                    color: #fff;
                                    padding: 15px;
                                    border-radius: 8px 8px 0 0;
                                    text-align: center;
                                    font-size: 24px;
                                }
                                .rating-form-body {
                                    padding: 20px;
                                }
                                .rating-form-footer {
                                    text-align: center;
                                    padding: 10px;
                                }
                                .rating-form-group {
                                    margin-bottom: 15px;
                                }
                                .rating-form-label {
                                    display: block;
                                    font-weight: bold;
                                    margin-bottom: 5px;
                                    font-size: 18px;
                                }
                                .rating-form-input,
                                .rating-form-textarea {
                                    width: 100%;
                                    padding: 10px;
                                    border: 1px solid #ced4da;
                                    border-radius: 4px;
                                    box-sizing: border-box;
                                    font-size: 16px;
                                }
                                .rating-form-textarea {
                                    height: 100px;
                                    resize: vertical;
                                }
                                .rating-form-submit {
                                    background-color: #28a745;
                                    color: #fff;
                                    border: none;
                                    padding: 10px 20px;
                                    border-radius: 4px;
                                    cursor: pointer;
                                    font-size: 16px;
                                    transition: background-color 0.3s ease;
                                }
                                .rating-form-submit:hover {
                                    background-color: #218838;
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
                                .alert {
                                    padding: 10px;
                                    margin-bottom: 20px;
                                    border-radius: 4px;
                                    color: #fff;
                                }
                                .alert-success {
                                    background-color: #28a745;
                                }
                                .alert-error {
                                    background-color: #dc3545;
                                }
                                .rating-scale.rating-scale-ar input[type="radio"] {
                                    float: right;
                                    margin-left: 10px;
                                }
                            </style>
                        </head>
                        <body>
                            <div class="rating-form-container">
                                <!-- Switch lang to ar and en -->
                                <div>
                                    <a href="#">English</a>
                                </div>

                                <div class="order-details">
                                    <h2>تفاصيل الطلب</h2>
                                    <p>رقم الطلب: 12345</p>
                                    <p>تاريخ الطلب: 2023-06-15</p>
                                    <p>العميل: أحمد محمد</p>
                                </div>

                                <div class="rating-form-header">قيم الطلب</div>

                                <div class="rating-form-body">
                                    <form action="#" method="POST">

                                        <div>
                                            ${previewHtml}
                                        </div>

                                        <div class="rating-form-footer">
                                            <button type="submit" class="rating-form-submit">إرسال التقييم ⭐</button>
                                        </div>
                                    </form>
                                </div>
                            </div>

                            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"><\/script>
                        </body>
                    </html>
                `);
                previewWindow.document.close();
            });

            // Generate preview field HTML (without disabled attribute)
            function generatePreviewFieldHtml(fieldData) {
                const { type, label, placeholder, options, settings } = fieldData;


                switch(type) {
                    case "text":
                    case "email":
                    case "tel":
                    case "url":
                    case "number":
                        return `
                        <div class="rating-form-group">
                            <label class="rating-form-label">${label}</label>
                            <input type="${type}" class="rating-form-input" placeholder="${placeholder}"  >
                        </div>
                        `;
                        break;
                    case "textarea":
                        return `
                        <div class="rating-form-group">
                            <label class="rating-form-label">${label}</label>
                            <textarea class="rating-form-textarea" rows="3" placeholder="${placeholder}" ></textarea>
                        </div>
                        `;
                        break;
                    case "select":
                        let selectOptions = '';
                        if (options) {
                            options.forEach(option => {
                                selectOptions += `<option value="${option.value}">${option.label}</option>`;
                            });
                        }
                        return `
                        <div class="rating-form-group">
                            <label class="rating-form-label">${label}</label>
                            <select class="form-select" >
                                <option value="">حدد خيارًا...</option>
                                ${selectOptions}
                            </select>
                        </div>
                        `;
                        break;
                    case "radio":
                        let radioOptions = '';
                        if (options) {
                            options.forEach((option, index) => {
                                radioOptions += `
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio"  id="question_${index}" value="${option.value}" >
                                        <label class="form-check-label" for="question_${index}">
                                            ${option.label}
                                        </label>
                                    </div>
                                `;
                            });
                        }
                        return `
                        <div class="rating-form-group">
                            <label class="rating-form-label">${label}</label>
                            <div>
                                ${radioOptions}
                            </div>
                        </div>
                        `;
                        break;
                    case "checkbox":
                        let checkboxOptions = '';
                        if (options) {
                            options.forEach((option, index) => {
                                checkboxOptions += `
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="question[]" id="question_${index}" value="${option.value}">
                                        <label class="form-check-label" for="question_${index}">
                                            ${option.label}
                                        </label>
                                    </div>
                                `;
                            });
                        }
                        return `
                        <div class="rating-form-group">
                            <label class="rating-form-label">${label}</label>
                            <div>
                                ${checkboxOptions}
                            </div>
                        </div>
                        `;
                        break;
                    case "date":
                        return `
                        <div class="rating-form-group">
                            <label class="rating-form-label">${label}</label>
                            <input type="date" class="rating-form-input"  >
                        </div>
                        `;
                        break;
                    case "datetime":
                        return `
                        <div class="rating-form-group">
                            <label class="rating-form-label">${label}</label>
                            <input type="datetime-local" class="rating-form-input"  >
                        </div>
                        `;
                        break;
                        case "stars":
                            const points = settings.points || 5;
                            let stars = '';
                            for (let i = points; i >= 1; i--) {
                                stars += `
                                    <input type="radio" id="${fieldData.id}_star${i}" value="${i}" name="${fieldData.id}">
                                    <label for="${fieldData.id}_star${i}">★</label>
                                `;
                            }
                            return `
                            <div class="rating-form-group">
                                <label class="rating-form-label">${label}</label>
                                <div class="rating-form-stars">
                                    ${stars}
                                </div>
                            </div>
                            `;
                        break;
                    case "rating":
                        const ratingPoints = settings.points || 5;
                        let ratingOptions = '';
                        for (let i = 1; i <= ratingPoints; i++) {
                            ratingOptions += `
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" id="question_${i}" value="${i}" >
                                    <label class="form-check-label" for="question_${i}">
                                        ${i}
                                    </label>
                                </div>
                            `;
                        }
                        return `
                        <div class="rating-form-group">
                            <label class="rating-form-label">${label}</label>
                            <div class="rating-scale {{ app()->getLocale() == "ar" ? "rating-scale-ar" : "" }}">
                                <div class="row">
                                    ${ratingOptions}
                                </div>
                            </div>
                        </div>
                        `;
                        break;
                    default:
                        return `
                        <div class="rating-form-group">
                            <label class="rating-form-label">${label}</label>
                            <input type="text" class="rating-form-input" placeholder="${placeholder}" >
                        </div>
                        `;
                }
            }

            // Initialize with existing survey data if available
            @if(isset($survey))
                // Initialize survey data
                surveyData = {
                    id: {{ $survey['id'] }},
                    title: "{{ $survey['title'] }}",
                    description: "{{ $survey['description'] }}",
                    fields: [
                        @foreach($survey['fields'] as $question)
                            {
                                id: "field_{{ $question['id'] }}",
                                type: "{{ $question['question_type'] }}",
                                label: "{{ $question['question_text'] }}",
                                placeholder: "{{ $question['placeholder'] }}",
                                helpText: "{{ $question['help_text'] }}",
                                width: "{{ $question['settings']['width'] ?? 'col-12' }}",
                                errorMessage: "{{ $question['error_message'] }}",
                                options: {!! $question['options'] ? json_encode($question['options']) : 'null' !!},
                                settings: {!! $question['settings'] ? json_encode($question['settings']) : '{}' !!}
                            }@if(!$loop->last),@endif
                        @endforeach
                    ]
                };

                // Set survey title
                $("#surveyTitle").val(surveyData.title);

                // Remove empty state
                $("#formFields .text-center.text-muted").remove();

                surveyData.fields.forEach(function(field) {
                    attachFieldEvents(field.id);
                });
            @endif
        });
    </script>
{{-- @endsection --}}
