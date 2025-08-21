@section('pageTitle', __('dashboard.survey_statistics'))
@extends('dashboard.layouts.app')
@section('content')
<!--begin::Post-->
<div class="post d-flex flex-column-fluid" id="kt_post">
    <!--begin::Container-->
    <div id="kt_content_container" class="container-xxl">
        <!--begin::Statistics-->
        <div class="row g-5 g-xl-8">
            <!--begin::Col-->
            <div class="col-xl-6">
                <!--begin::Statistics Widget 1-->
                <div class="card card-xl-stretch mb-xl-8">
                    <!--begin::Body-->
                    <div class="card-body">
                        <!--begin::Statistics-->
                        <div class="statistics d-flex align-items-center">
                            <!--begin::Statistics-->
                            <div class="statistics-info">
                                <!--begin::Title-->
                                <h5 class="text-gray-800 fw-bold">@lang('dashboard.total_surveys')</h5>
                                <!--end::Title-->
                                <!--begin::Number-->
                                <div class="fs-2 fw-bolder">1</div>
                                <!--end::Number-->
                            </div>
                            <!--end::Statistics-->
                            <!--begin::Symbol-->
                            <div class="symbol symbol-50px symbol-light ms-auto">
                                <span class="symbol-label">
                                    <span class="svg-icon svg-icon-2x svg-icon-primary">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                            <path opacity="0.3" d="M20 22H4C3.4 22 3 21.6 3 21V3C3 2.4 3.4 2 4 2H20C20.6 2 21 2.4 21 3V21C21 21.6 20.6 22 20 22Z" fill="currentColor"/>
                                            <path d="M7 12C7 11.4 7.4 11 8 11H16C16.6 11 17 11.4 17 12C17 12.6 16.6 13 16 13H8C7.4 13 7 12.6 7 12ZM7 16C7 15.4 7.4 15 8 15H16C16.6 15 17 15.4 17 16C17 16.6 16.6 17 16 17H8C7.4 17 7 16.6 7 16ZM7 8C7 7.4 7.4 7 8 7H16C16.6 7 17 7.4 17 8C17 8.6 16.6 9 16 9H8C7.4 9 7 8.6 7 8Z" fill="currentColor"/>
                                        </svg>
                                    </span>
                                </span>
                            </div>
                            <!--end::Symbol-->
                        </div>
                        <!--end::Statistics-->
                    </div>
                    <!--end::Body-->
                </div>
                <!--end::Statistics Widget 1-->
            </div>
            <!--end::Col-->
            <!--begin::Col-->
            <div class="col-xl-6">
                <!--begin::Statistics Widget 2-->
                <div class="card card-xl-stretch mb-xl-8">
                    <!--begin::Body-->
                    <div class="card-body">
                        <!--begin::Statistics-->
                        <div class="statistics d-flex align-items-center">
                            <!--begin::Statistics-->
                            <div class="statistics-info">
                                <!--begin::Title-->
                                <h5 class="text-gray-800 fw-bold">@lang('dashboard.total_answers')</h5>
                                <!--end::Title-->
                                <!--begin::Number-->
                                <div class="fs-2 fw-bolder">{{ $totalResponses }}</div>
                                <!--end::Number-->
                            </div>
                            <!--end::Statistics-->
                            <!--begin::Symbol-->
                            <div class="symbol symbol-50px symbol-light ms-auto">
                                <span class="symbol-label">
                                    <span class="svg-icon svg-icon-2x svg-icon-success">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                            <path opacity="0.3" d="M20 22H4C3.4 22 3 21.6 3 21V3C3 2.4 3.4 2 4 2H20C20.6 2 21 2.4 21 3V21C21 21.6 20.6 22 20 22Z" fill="currentColor"/>
                                            <path d="M7 12C7 11.4 7.4 11 8 11H16C16.6 11 17 11.4 17 12C17 12.6 16.6 13 16 13H8C7.4 13 7 12.6 7 12ZM7 16C7 15.4 7.4 15 8 15H16C16.6 15 17 15.4 17 16C17 16.6 16.6 17 16 17H8C7.4 17 7 16.6 7 16ZM7 8C7 7.4 7.4 7 8 7H16C16.6 7 17 7.4 17 8C17 8.6 16.6 9 16 9H8C7.4 9 7 8.6 7 8Z" fill="currentColor"/>
                                        </svg>
                                    </span>
                                </span>
                            </div>
                            <!--end::Symbol-->
                        </div>
                        <!--end::Statistics-->
                    </div>
                    <!--end::Body-->
                </div>
                <!--end::Statistics Widget 2-->
            </div>
            <!--end::Col-->
            <!--begin::Col-->
            {{-- <div class="col-xl-4">
                <!--begin::Statistics Widget 3-->
                <div class="card card-xl-stretch mb-xl-8">
                    <!--begin::Body-->
                    <div class="card-body">
                        <!--begin::Statistics-->
                        <div class="statistics d-flex align-items-center">
                            <!--begin::Statistics-->
                            <div class="statistics-info">
                                <!--begin::Title-->
                                <h5 class="text-gray-800 fw-bold">@lang('dashboard.average_rating')</h5>
                                <!--end::Title-->
                                <!--begin::Number-->
                                <div class="fs-2 fw-bolder">{{ $averageRating }}</div>
                                <!--end::Number-->
                            </div>
                            <!--end::Statistics-->
                            <!--begin::Symbol-->
                            <div class="symbol symbol-50px symbol-light ms-auto">
                                <span class="symbol-label">
                                    <span class="svg-icon svg-icon-2x svg-icon-warning">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                            <path opacity="0.3" d="M20 22H4C3.4 22 3 21.6 3 21V3C3 2.4 3.4 2 4 2H20C20.6 2 21 2.4 21 3V21C21 21.6 20.6 22 20 22Z" fill="currentColor"/>
                                            <path d="M7 12C7 11.4 7.4 11 8 11H16C16.6 11 17 11.4 17 12C17 12.6 16.6 13 16 13H8C7.4 13 7 12.6 7 12ZM7 16C7 15.4 7.4 15 8 15H16C16.6 15 17 15.4 17 16C17 16.6 16.6 17 16 17H8C7.4 17 7 16.6 7 16ZM7 8C7 7.4 7.4 7 8 7H16C16.6 7 17 7.4 17 8C17 8.6 16.6 9 16 9H8C7.4 9 7 8.6 7 8Z" fill="currentColor"/>
                                        </svg>
                                    </span>
                                </span>
                            </div>
                            <!--end::Symbol-->
                        </div>
                        <!--end::Statistics-->
                    </div>
                    <!--end::Body-->
                </div>
                <!--end::Statistics Widget 3-->
            </div> --}}
            <!--end::Col-->
        </div>
        <!--end::Statistics-->

        <!--begin::Charts Row 2-->
        <div class="row g-5 g-xl-8">
            <!--begin::Col-->
            <div class="col-xl-6">
                <!--begin::Chart Widget 3-->
                <div class="card card-xl-stretch mb-xl-8">
                    <!--begin::Header-->
                    <div class="card-header border-0 pt-5">
                        <h3 class="card-title align-items-start flex-column">
                            <span class="card-label fw-bold fs-3 mb-1">@lang('dashboard.surveys_by_date')</span>
                            <span class="text-muted fw-semibold fs-7">@lang('dashboard.surveys_by_date_desc')</span>
                        </h3>
                    </div>
                    <!--end::Header-->
                    <!--begin::Body-->
                    <div class="card-body">
                        <!--begin::Chart-->
                        <canvas id="timelineChart" style="height: 300px;"></canvas>
                        <!--end::Chart-->
                    </div>
                    <!--end::Body-->
                </div>
                <!--end::Chart Widget 3-->
            </div>
            <!--end::Col-->
            <!--begin::Col-->
            <div class="col-xl-6">
                <!--begin::Chart Widget 4-->
                <div class="card card-xl-stretch mb-xl-8">
                    <!--begin::Header-->
                    <div class="card-header border-0 pt-5 d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center">
                            <h3 class="card-title align-items-start flex-column">
                                <span class="card-label fw-bold fs-3 mb-1">@lang('dashboard.question_options')</span>
                                <span class="text-muted fw-semibold fs-7">@lang('dashboard.question_options_desc')</span>
                            </h3>
                        </div>
                        <div class="d-flex align-items-center">
                            <select class="form-select form-select-sm w-auto" id="questionSelect">
                                <option value="" selected disabled>@lang('dashboard.select_question')</option>
                                @foreach($questionsWithOptions as $question)
                                    <option value="{{ $question['id'] }}">{{ $question['title'] }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <!--end::Header-->
                    <!--begin::Body-->
                    <div class="card-body">
                        <!--begin::Chart-->
                        <canvas id="questionOptionsChart" style="height: 300px;"></canvas>
                        <!--end::Chart-->
                    </div>
                    <!--end::Body-->
                </div>
                <!--end::Chart Widget 4-->
            </div>
            <!--end::Col-->
        </div>
        <!--end::Charts Row 2-->
        <!--begin::Charts Row-->
        <div class="row g-5 g-xl-8">
            <!--begin::Col-->
            <div class="col-xl-6">
                <!--begin::Chart Widget 1-->
                <div class="card card-xl-stretch mb-xl-8">
                    <!--begin::Header-->
                    <div class="card-header border-0 pt-5">
                        <h3 class="card-title align-items-start flex-column">
                            <span class="card-label fw-bold fs-3 mb-1">@lang('dashboard.question_types')</span>
                            <span class="text-muted fw-semibold fs-7">@lang('dashboard.question_types_desc')</span>
                        </h3>
                    </div>
                    <!--end::Header-->
                    <!--begin::Body-->
                    <div class="card-body">
                        <!--begin::Chart-->
                        <canvas id="questionsChart" style="height: 300px;"></canvas>
                        <!--end::Chart-->
                    </div>
                    <!--end::Body-->
                </div>
                <!--end::Chart Widget 1-->
            </div>
            <!--end::Col-->
        </div>
        <!--end::Charts Row-->
        <!--begin::Questions Table-->
        <div class="row g-5 g-xl-8">
            <div class="col-xl-12">
                <div class="card card-xl-stretch mb-5 mb-xl-8">
                    <!--begin::Header-->
                    <div class="card-header border-0 pt-5 d-flex justify-content-between">
                        <div>
                            <h3 class="card-title align-items-start flex-column">
                                <span class="card-label fw-bold fs-3 mb-1">@lang('dashboard.all_questions')</span>
                                <span class="text-muted fw-semibold fs-7">@lang('dashboard.all_questions_desc')</span>
                            </h3>
                        </div>
                        <div>
                            <!--begin::Export-->
                            <button type="button" class="btn btn-flex btn-light-primary" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                                <span class="svg-icon svg-icon-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                        <rect opacity="0.3" x="12.75" y="4.25" width="12" height="2" rx="1" transform="rotate(90 12.75 4.25)" fill="currentColor" />
                                        <path d="M12.0573 6.11875L13.5203 7.87435C13.9121 8.34457 14.6232 8.37683 15.056 7.94401C15.4457 7.5543 15.4641 6.92836 15.0979 6.51643L12.4974 3.59084C12.0996 3.14332 11.4004 3.14332 11.0026 3.59084L8.40206 6.51643C8.0359 6.92836 8.0543 7.5543 8.44401 7.94401C8.87683 8.37683 9.58785 8.34458 9.9797 7.87435L11.4427 6.11875C11.6026 5.92684 11.8974 5.92684 12.0573 6.11875Z" fill="currentColor" />
                                        <path d="M18.75 8.25H17.25C16.8358 8.25 16.5 8.58579 16.5 9C16.5 9.41421 16.8358 9.75 17.25 9.75H18C18.4142 9.75 18.75 10.0858 18.75 10.5V16.5C18.75 16.9142 18.4142 17.25 18 17.25H6C5.58579 17.25 5.25 16.9142 5.25 16.5V10.5C5.25 10.0858 5.58579 9.75 6 9.75H6.75C7.16421 9.75 7.5 9.41421 7.5 9C7.5 8.58579 7.16421 8.25 6.75 8.25H5.25C4.42157 8.25 3.75 8.92157 3.75 9.75V17.25C3.75 18.0784 4.42157 18.75 5.25 18.75H18.75C19.5784 18.75 20.25 18.0784 20.25 17.25V9.75C20.25 8.92157 19.5784 8.25 18.75 8.25Z" fill="currentColor" />
                                    </svg>
                                </span>
                                @lang('dashboard.export')
                            </button>
                            <!--begin::Menu-->
                            <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-bold fs-7 w-200px py-4" data-kt-menu="true">
                                <!--begin::Menu item-->
                                <div class="menu-item px-3">
                                    <a href="{{ route('surveys.statistics.export.excel', $survey->id) }}" class="menu-link px-3">
                                        @lang('dashboard.export_as_excel')
                                    </a>
                                </div>
                                <!--end::Menu item-->
                                <!--begin::Menu item-->
                                <div class="menu-item px-3">
                                    <a href="{{ route('surveys.statistics.export.pdf', $survey->id) }}" class="menu-link px-3">
                                        @lang('dashboard.export_as_pdf')
                                    </a>
                                </div>
                                <!--end::Menu item-->
                            </div>
                            <!--end::Menu-->
                            <!--end::Export-->
                        </div>
                    </div>
                    <!--end::Header-->
                    <!--begin::Body-->
                    <div class="card-body">
                        <!--begin::Table-->
                        <div class="table-responsive">
                            <table class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-4">
                                <!--begin::Table head-->
                                <thead>
                                    <tr class="fw-bold text-muted">
                                        <th class="min-w-150px">@lang('dashboard.question_id')</th>
                                        <th class="min-w-300px">@lang('dashboard.question_text')</th>
                                        <th class="min-w-150px">@lang('dashboard.question_type')</th>
                                        <th class="min-w-150px text-end">@lang('dashboard.answers_count')</th>
                                    </tr>
                                </thead>
                                <!--end::Table head-->
                                <!--begin::Table body-->
                                <tbody>
                                    @foreach($allQuestionsData as $question)
                                    <tr>
                                        <td>
                                            <span class="text-gray-800 fw-bold">#{{ $question['id'] }}</span>
                                        </td>
                                        <td>
                                            <span class="text-gray-800 fw-bold d-block mb-1">{{ $question['title'] }}</span>
                                        </td>
                                        <td>
                                            <span class="badge badge-light-{{ $question['type'] === 'text' ? 'info' : ($question['type'] === 'textarea' ? 'primary' : ($question['type'] === 'radio' ? 'success' : ($question['type'] === 'checkbox' ? 'warning' : 'danger'))) }}">
                                                {{ $question['type'] }}
                                            </span>
                                        </td>
                                        <td class="text-end">
                                            <span class="badge badge-light-primary">{{ $question['answers_count'] }}</span>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <!--end::Table body-->
                            </table>
                        </div>
                        <!--end::Table-->
                    </div>
                    <!--end::Body-->
                </div>
            </div>
        </div>
        <!--end::Questions Table-->
    </div>
    <!--end::Container-->
</div>
<!--end::Post-->
{{-- @push('scripts') --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Questions Types Chart
        const questionsCtx = document.getElementById('questionsChart').getContext('2d');
        const questionsChart = new Chart(questionsCtx, {
            type: 'doughnut',
            data: {
                labels: [
                    '@lang('dashboard.short_text')',
                    '@lang('dashboard.long_text')',
                    '@lang('dashboard.single_choice')',
                    '@lang('dashboard.multiple_choice')',
                    '@lang('dashboard.dropdown_list')',
                    '@lang('dashboard.star_rating')',
                    '@lang('dashboard.rating_scale')'
                ],
                datasets: [{
                    data: [
                        {{ $questionTypes['text'] ?? 0 }},
                        {{ $questionTypes['textarea'] ?? 0 }},
                        {{ $questionTypes['radio'] ?? 0 }},
                        {{ $questionTypes['checkbox'] ?? 0 }},
                        {{ $questionTypes['select'] ?? 0 }},
                        {{ $questionTypes['stars'] ?? 0 }},
                        {{ $questionTypes['rating'] ?? 0 }}
                    ],
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.7)',
                        'rgba(54, 162, 235, 0.7)',
                        'rgba(255, 206, 86, 0.7)',
                        'rgba(75, 192, 192, 0.7)',
                        'rgba(153, 102, 255, 0.7)',
                        'rgba(255, 159, 64, 0.7)',
                        'rgba(75, 192, 192, 0.7)'
                    ],
                    borderColor: [
                        'rgb(255, 99, 132)',
                        'rgb(54, 162, 235)',
                        'rgb(255, 206, 86)',
                        'rgb(75, 192, 192)',
                        'rgb(153, 102, 255)',
                        'rgb(255, 159, 64)',
                        'rgb(75, 192, 192)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'right'
                    }
                }
            }
        });

        // Timeline Chart
        const timelineCtx = document.getElementById('timelineChart').getContext('2d');
        const timelineChart = new Chart(timelineCtx, {
            type: 'line',
            data: {
                labels: [
                    @foreach($timelineData as $date => $count)
                        '{{ $date }}',
                    @endforeach
                ],
                datasets: [{
                    label: '@lang('dashboard.answers_count')',
                    data: [
                        @foreach($timelineData as $count)
                            {{ $count }},
                        @endforeach
                    ],
                    fill: false,
                    borderColor: 'rgb(75, 192, 192)',
                    tension: 0.1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0
                        }
                    }
                }
            }
        });

        // Question Options Chart
        const questionOptionsCtx = document.getElementById('questionOptionsChart').getContext('2d');
        const questionOptionsChart = new Chart(questionOptionsCtx, {
            type: 'doughnut',
            data: {
                labels: [],
                datasets: [{
                    data: [],
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.7)',
                        'rgba(54, 162, 235, 0.7)',
                        'rgba(255, 206, 86, 0.7)',
                        'rgba(75, 192, 192, 0.7)',
                        'rgba(153, 102, 255, 0.7)',
                        'rgba(255, 159, 64, 0.7)',
                        'rgba(75, 192, 192, 0.7)'
                    ],
                    borderColor: [
                        'rgb(255, 99, 132)',
                        'rgb(54, 162, 235)',
                        'rgb(255, 206, 86)',
                        'rgb(75, 192, 192)',
                        'rgb(153, 102, 255)',
                        'rgb(255, 159, 64)',
                        'rgb(75, 192, 192)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '60%',
                plugins: {
                    legend: {
                        position: 'right',
                        labels: {
                            font: {
                                size: 12
                            },
                            padding: 15
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const label = context.label || '';
                                const value = context.raw || 0;
                                const data = context.dataset.data;
                                const total = data.reduce((a, b) => a + b, 0);

                                console.log('Tooltip data:', {
                                    label,
                                    value,
                                    total,
                                    data
                                });

                                const percentage = total > 0 ? Math.round((value / total) * 100) : 0;
                                return `${label}: ${value} (${percentage}%)`;
                            }
                        }
                    }
                }
            }
        });

        // Store questions with options data
        const questionsWithOptions = [
            @foreach($questionsWithOptions as $question)
                {
                    id: {{ $question['id'] }},
                    title: '{{ $question['title'] }}',
                    type: '{{ $question['type'] }}',
                    options: {!! json_encode($question['options']) !!}
                },
            @endforeach
        ];

        // Store survey responses data
        const responses = [
            @foreach($responses as $response)
                {
                    id: {{ $response->id }},
                    answers: [
                        @foreach($response->answers as $answer)
                            {
                                question_id: {{ $answer->survey_question_id }},
                                answer_text: '{{ addslashes($answer->answer_text) }}',
                                answer_option: {!! is_array($answer->answer_option) ? json_encode($answer->answer_option) : "'".addslashes($answer->answer_option)."'" !!}
                            },
                        @endforeach
                    ]
                },
            @endforeach
        ];

        // Handle question selection change
        // document.getElementById('questionSelect').addEventListener('change', function(e) {
        //     const questionId = parseInt(e.target.value);
        //     console.log('Selected question ID:', questionId);

        //     if (!questionId) {
        //         console.log('No question selected, resetting chart');
        //         questionOptionsChart.data.labels = ['No data'];
        //         questionOptionsChart.data.datasets[0].data = [1];
        //         questionOptionsChart.update();
        //         return;
        //     }

        //     const selectedQuestion = questionsWithOptions.find(q => q.id === questionId);
        //     console.log('Selected question:', selectedQuestion);

        //     if (!selectedQuestion) {
        //         console.log('Question not found in questionsWithOptions');
        //         questionOptionsChart.data.labels = ['Question not found'];
        //         questionOptionsChart.data.datasets[0].data = [1];
        //         questionOptionsChart.update();
        //         return;
        //     }

        //     if (!selectedQuestion.options || selectedQuestion.options.length === 0) {
        //         console.log('Question has no options');
        //         questionOptionsChart.data.labels = ['No options'];
        //         questionOptionsChart.data.datasets[0].data = [1];
        //         questionOptionsChart.update();
        //         return;
        //     }

        //     console.log('Question options:', selectedQuestion.options);

        //     // Create a mapping from value to label
        //     const valueToLabel = {};
        //     const optionCounts = {};

        //     // Create a mapping for different option formats
        //     selectedQuestion.options.forEach((option, index) => {
        //         let value, label;

        //         if (typeof option === 'object' && option !== null) {
        //             // If option is an object, get value and label separately
        //             value = String(option.value || option.label || '');
        //             label = option.label || option.value || '';
        //         } else {
        //             // If option is a simple value, use it as both value and label
        //             value = String(option);
        //             label = option;

        //             // Also create potential mappings for different formats
        //             // For example, map "الخيار 1" to "option1"
        //             const optionNumber = index + 1;
        //             const potentialValue = `option${optionNumber}`;
        //             valueToLabel[potentialValue] = label;
        //         }

        //         valueToLabel[value] = label;
        //         optionCounts[value] = 0;
        //     });

        //     console.log('Value to label mapping:', valueToLabel);
        //     console.log('Initial option counts:', optionCounts);

        //     // Log responses structure
        //     console.log('Total responses:', responses.length);
        //     console.log('First response sample:', responses[0]);

        //     responses.forEach((response, responseIndex) => {
        //         console.log(`Processing response ${responseIndex}:`, response);

        //         const answer = response.answers.find(a => a.question_id === questionId);
        //         console.log(`Answer for question ${questionId}:`, answer);

        //         if (!answer) {
        //             console.log(`No answer found for question ${questionId} in response ${responseIndex}`);
        //             return;
        //         }

        //         if (selectedQuestion.type === 'checkbox') {
        //             console.log('Processing checkbox answer:', answer.answer_option);

        //             let options = [];
        //             if (Array.isArray(answer.answer_option)) {
        //                 options = answer.answer_option;
        //                 console.log('Options is array:', options);
        //             } else if (answer.answer_option) {
        //                 try {
        //                     options = JSON.parse(answer.answer_option);
        //                     console.log('Parsed options from JSON:', options);
        //                 } catch (e) {
        //                     console.log('Failed to parse JSON, using as string:', answer.answer_option);
        //                     options = [answer.answer_option];
        //                 }
        //             }

        //             options.forEach(option => {
        //                 const value = String(option);
        //                 console.log(`Processing option value: ${value}`);

        //                 // Try to find a matching key in our mapping
        //                 let matchedKey = null;
        //                 if (optionCounts.hasOwnProperty(value)) {
        //                     matchedKey = value;
        //                 } else if (valueToLabel.hasOwnProperty(value)) {
        //                     // This value maps to a label, so we need to find the original key
        //                     // This is a reverse mapping scenario
        //                     for (const key in optionCounts) {
        //                         if (valueToLabel[key] === valueToLabel[value]) {
        //                             matchedKey = key;
        //                             break;
        //                         }
        //                     }
        //                 } else {
        //                     // Try to extract a number from the value and match with index
        //                     const match = value.match(/option(\d+)/i);
        //                     if (match) {
        //                         const optionNumber = parseInt(match[1]);
        //                         const optionIndex = optionNumber - 1;
        //                         if (optionIndex >= 0 && optionIndex < selectedQuestion.options.length) {
        //                             // Find the key for this option
        //                             for (const key in optionCounts) {
        //                                 if (valueToLabel[key] === selectedQuestion.options[optionIndex]) {
        //                                     matchedKey = key;
        //                                     break;
        //                                 }
        //                             }
        //                         }
        //                     }
        //                 }

        //                 if (matchedKey !== null && optionCounts.hasOwnProperty(matchedKey)) {
        //                     optionCounts[matchedKey]++;
        //                     console.log(`Incremented count for ${matchedKey}: ${optionCounts[matchedKey]}`);
        //                 } else {
        //                     console.log(`Option value ${value} not found in question options`);
        //                 }
        //             });
        //         } else {
        //             console.log('Processing non-checkbox answer:', answer.answer_text);
        //             const value = String(answer.answer_text || '');
        //             console.log(`Processing value: ${value}`);

        //             // Try to find a matching key in our mapping
        //             let matchedKey = null;
        //             if (optionCounts.hasOwnProperty(value)) {
        //                 matchedKey = value;
        //             } else if (valueToLabel.hasOwnProperty(value)) {
        //                 // This value maps to a label, so we need to find the original key
        //                 // This is a reverse mapping scenario
        //                 for (const key in optionCounts) {
        //                     if (valueToLabel[key] === valueToLabel[value]) {
        //                         matchedKey = key;
        //                         break;
        //                     }
        //                 }
        //             } else {
        //                 // Try to extract a number from the value and match with index
        //                 const match = value.match(/option(\d+)/i);
        //                 if (match) {
        //                     const optionNumber = parseInt(match[1]);
        //                     const optionIndex = optionNumber - 1;
        //                     if (optionIndex >= 0 && optionIndex < selectedQuestion.options.length) {
        //                         // Find the key for this option
        //                         for (const key in optionCounts) {
        //                             if (valueToLabel[key] === selectedQuestion.options[optionIndex]) {
        //                                 matchedKey = key;
        //                                 break;
        //                             }
        //                         }
        //                     }
        //                 }
        //             }

        //             if (matchedKey !== null && optionCounts.hasOwnProperty(matchedKey)) {
        //                 optionCounts[matchedKey]++;
        //                 console.log(`Incremented count for ${matchedKey}: ${optionCounts[matchedKey]}`);
        //             } else {
        //                 console.log(`Value ${value} not found in question options`);
        //             }
        //         }
        //     });

        //     console.log('Final option counts:', optionCounts);

        //     // Convert value counts to label counts for display
        //     const labels = [];
        //     const data = [];

        //     for (const value in optionCounts) {
        //         labels.push(valueToLabel[value]);
        //         data.push(optionCounts[value]);
        //     }

        //     const total = data.reduce((a, b) => a + b, 0);
        //     console.log('Total responses for this question:', total);
        //     console.log('Chart labels:', labels);
        //     console.log('Chart data:', data);

        //     if (total === 0) {
        //         console.log('No responses found, showing fallback');
        //         questionOptionsChart.data.labels = ['No responses'];
        //         questionOptionsChart.data.datasets[0].data = [1];
        //     } else {
        //         questionOptionsChart.data.labels = labels;
        //         questionOptionsChart.data.datasets[0].data = data;
        //         console.log('Updating chart with actual data');
        //     }

        //     questionOptionsChart.update('none');
        //     setTimeout(() => {
        //         questionOptionsChart.resize();
        //     }, 100);
        // });
document.getElementById('questionSelect').addEventListener('change', function(e) {
    const questionId = parseInt(e.target.value);
    console.log('Selected question ID:', questionId);

    if (!questionId) {
        console.log('No question selected, resetting chart');
        questionOptionsChart.data.labels = ['No data'];
        questionOptionsChart.data.datasets[0].data = [1];
        questionOptionsChart.update();
        return;
    }

    const selectedQuestion = questionsWithOptions.find(q => q.id === questionId);
    console.log('Selected question:', selectedQuestion);

    if (!selectedQuestion) {
        console.log('Question not found in questionsWithOptions');
        questionOptionsChart.data.labels = ['Question not found'];
        questionOptionsChart.data.datasets[0].data = [1];
        questionOptionsChart.update();
        return;
    }

    if (!selectedQuestion.options || selectedQuestion.options.length === 0) {
        console.log('Question has no options');
        questionOptionsChart.data.labels = ['No options'];
        questionOptionsChart.data.datasets[0].data = [1];
        questionOptionsChart.update();
        return;
    }

    console.log('Question options:', selectedQuestion.options);

    // Create a mapping from value to label
    const valueToLabel = {};
    const optionCounts = {};

    // Handle different option formats
    selectedQuestion.options.forEach((option, index) => {
        let value, label;

        if (selectedQuestion.type === 'stars') {
            // For stars, value is the number of stars (1, 2, 3, etc.)
            value = String(index + 1);
            label = option; // The formatted star string (★, ★★, etc.)
        } else if (selectedQuestion.type === 'rating') {
            // For rating, value is the rating number (1, 2, 3, etc.)
            value = String(index + 1);
            label = option; // The rating number as string
        } else if (typeof option === 'object' && option !== null) {
            // If option is an object, get value and label separately
            value = String(option.value || option.label || '');
            label = option.label || option.value || '';
        } else {
            // If option is a simple value, use it as both value and label
            value = String(option);
            label = option;

            // Also create potential mappings for different formats
            // For example, map "الخيار 1" to "option1"
            const optionNumber = index + 1;
            const potentialValue = `option${optionNumber}`;
            valueToLabel[potentialValue] = label;
        }

        valueToLabel[value] = label;
        optionCounts[value] = 0;
    });

    console.log('Value to label mapping:', valueToLabel);
    console.log('Initial option counts:', optionCounts);

    // Log responses structure
    console.log('Total responses:', responses.length);
    console.log('First response sample:', responses[0]);

    responses.forEach((response, responseIndex) => {
        console.log(`Processing response ${responseIndex}:`, response);

        const answer = response.answers.find(a => a.question_id === questionId);
        console.log(`Answer for question ${questionId}:`, answer);

        if (!answer) {
            console.log(`No answer found for question ${questionId} in response ${responseIndex}`);
            return;
        }

        if (selectedQuestion.type === 'checkbox') {
            console.log('Processing checkbox answer:', answer.answer_option);

            let options = [];
            if (Array.isArray(answer.answer_option)) {
                options = answer.answer_option;
                console.log('Options is array:', options);
            } else if (answer.answer_option) {
                try {
                    options = JSON.parse(answer.answer_option);
                    console.log('Parsed options from JSON:', options);
                } catch (e) {
                    console.log('Failed to parse JSON, using as string:', answer.answer_option);
                    options = [answer.answer_option];
                }
            }

            options.forEach(option => {
                const value = String(option);
                console.log(`Processing option value: ${value}`);

                // Try to find a matching key in our mapping
                let matchedKey = null;
                if (optionCounts.hasOwnProperty(value)) {
                    matchedKey = value;
                } else if (valueToLabel.hasOwnProperty(value)) {
                    // This value maps to a label, so we need to find the original key
                    for (const key in optionCounts) {
                        if (valueToLabel[key] === valueToLabel[value]) {
                            matchedKey = key;
                            break;
                        }
                    }
                } else {
                    // Try to extract a number from the value and match with index
                    const match = value.match(/option(\d+)/i);
                    if (match) {
                        const optionNumber = parseInt(match[1]);
                        const optionIndex = optionNumber - 1;
                        if (optionIndex >= 0 && optionIndex < selectedQuestion.options.length) {
                            // Find the key for this option
                            for (const key in optionCounts) {
                                if (valueToLabel[key] === selectedQuestion.options[optionIndex]) {
                                    matchedKey = key;
                                    break;
                                }
                            }
                        }
                    }
                }

                if (matchedKey !== null && optionCounts.hasOwnProperty(matchedKey)) {
                    optionCounts[matchedKey]++;
                    console.log(`Incremented count for ${matchedKey}: ${optionCounts[matchedKey]}`);
                } else {
                    console.log(`Option value ${value} not found in question options`);
                }
            });
        } else {
            console.log('Processing non-checkbox answer:', answer.answer_text);
            const value = String(answer.answer_text || '');
            console.log(`Processing value: ${value}`);

            // For stars and rating, ensure the value is within 1-5 range
            let processedValue = value;
            if (selectedQuestion.type === 'stars' || selectedQuestion.type === 'rating') {
                const numValue = parseInt(value);
                if (!isNaN(numValue)) {
                    // Ensure the value is between 1 and 5
                    processedValue = String(Math.max(1, Math.min(5, numValue)));
                }
            }

            // Try to find a matching key in our mapping
            let matchedKey = null;
            if (optionCounts.hasOwnProperty(processedValue)) {
                matchedKey = processedValue;
            } else if (valueToLabel.hasOwnProperty(processedValue)) {
                // This value maps to a label, so we need to find the original key
                for (const key in optionCounts) {
                    if (valueToLabel[key] === valueToLabel[processedValue]) {
                        matchedKey = key;
                        break;
                    }
                }
            } else {
                // Try to extract a number from the value and match with index
                const match = processedValue.match(/option(\d+)/i);
                if (match) {
                    const optionNumber = parseInt(match[1]);
                    const optionIndex = optionNumber - 1;
                    if (optionIndex >= 0 && optionIndex < selectedQuestion.options.length) {
                        // Find the key for this option
                        for (const key in optionCounts) {
                            if (valueToLabel[key] === selectedQuestion.options[optionIndex]) {
                                matchedKey = key;
                                break;
                            }
                        }
                    }
                }
            }

            if (matchedKey !== null && optionCounts.hasOwnProperty(matchedKey)) {
                optionCounts[matchedKey]++;
                console.log(`Incremented count for ${matchedKey}: ${optionCounts[matchedKey]}`);
            } else {
                console.log(`Value ${processedValue} not found in question options`);
            }
        }
    });

    console.log('Final option counts:', optionCounts);

    // Convert value counts to label counts for display
    const labels = [];
    const data = [];

    for (const value in optionCounts) {
        labels.push(valueToLabel[value]);
        data.push(optionCounts[value]);
    }

    const total = data.reduce((a, b) => a + b, 0);
    console.log('Total responses for this question:', total);
    console.log('Chart labels:', labels);
    console.log('Chart data:', data);

    if (total === 0) {
        console.log('No responses found, showing fallback');
        questionOptionsChart.data.labels = ['No responses'];
        questionOptionsChart.data.datasets[0].data = [1];
    } else {
        questionOptionsChart.data.labels = labels;
        questionOptionsChart.data.datasets[0].data = data;
        console.log('Updating chart with actual data');
    }

    questionOptionsChart.update('none');
    setTimeout(() => {
        questionOptionsChart.resize();
    }, 100);
});    });
</script>
{{-- @endpush --}}
@endsection
