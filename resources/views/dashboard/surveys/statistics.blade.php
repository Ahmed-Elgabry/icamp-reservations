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
        <!--begin::Charts Row-->
        <div class="row g-5 g-xl-8">
            <!--begin::Col-->
            <div class="col-xl-6">
                <!--begin::Chart Widget 1-->
                <div class="card card-xl-stretch mb-xl-8">
                    <!--begin::Header-->
                    <div class="card-header border-0 pt-5">
                        <h3 class="card-title align-items-start flex-column">
                            <span class="card-label fw-bold fs-3 mb-1">@lang('dashboard.ratings_distribution')</span>
                            <span class="text-muted fw-semibold fs-7">@lang('dashboard.ratings_distribution_desc')</span>
                        </h3>
                    </div>
                    <!--end::Header-->
                    <!--begin::Body-->
                    <div class="card-body">
                        <!--begin::Chart-->
                        <canvas id="ratingsChart" style="height: 300px;"></canvas>
                        <!--end::Chart-->
                    </div>
                    <!--end::Body-->
                </div>
                <!--end::Chart Widget 1-->
            </div>
            <!--end::Col-->
            <!--begin::Col-->
            <div class="col-xl-6">
                <!--begin::Chart Widget 2-->
                <div class="card card-xl-stretch mb-xl-8">
                    <!--begin::Header-->
                    <div class="card-header border-0 pt-5">
                        <h3 class="card-title align-items-start flex-column">
                            <span class="card-label fw-bold fs-3 mb-1">@lang('dashboard.question_answers')</span>
                            <span class="text-muted fw-semibold fs-7">@lang('dashboard.question_answers_desc')</span>
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
                <!--end::Chart Widget 2-->
            </div>
            <!--end::Col-->
        </div>
        <!--end::Charts Row-->
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
                    <div class="card-header border-0 pt-5">
                        <h3 class="card-title align-items-start flex-column">
                            <span class="card-label fw-bold fs-3 mb-1">@lang('dashboard.popular_questions')</span>
                            <span class="text-muted fw-semibold fs-7">@lang('dashboard.popular_questions_desc')</span>
                        </h3>
                    </div>
                    <!--end::Header-->
                    <!--begin::Body-->
                    <div class="card-body">
                        <!--begin::Chart-->
                        <canvas id="popularQuestionsChart" style="height: 300px;"></canvas>
                        <!--end::Chart-->
                    </div>
                    <!--end::Body-->
                </div>
                <!--end::Chart Widget 4-->
            </div>
            <!--end::Col-->
        </div>
        <!--end::Charts Row 2-->

        <!--begin::Questions Table-->
        <div class="row g-5 g-xl-8">
            <div class="col-xl-12">
                <div class="card card-xl-stretch mb-5 mb-xl-8">
                    <!--begin::Header-->
                    <div class="card-header border-0 pt-5">
                        <h3 class="card-title align-items-start flex-column">
                            <span class="card-label fw-bold fs-3 mb-1">@lang('dashboard.all_questions')</span>
                            <span class="text-muted fw-semibold fs-7">@lang('dashboard.all_questions_desc')</span>
                        </h3>
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
        // Ratings Distribution Chart
        const ratingsCtx = document.getElementById('ratingsChart').getContext('2d');
        const ratingsChart = new Chart(ratingsCtx, {
            type: 'bar',
            data: {
                labels: [
                    '@lang('dashboard.one_star')',
                    '@lang('dashboard.two_stars')',
                    '@lang('dashboard.three_stars')',
                    '@lang('dashboard.four_stars')',
                    '@lang('dashboard.five_stars')'
                ],
                datasets: [{
                    label: '@lang('dashboard.ratings_count')',
                    data: [
                        {{ $ratingsDistribution[1] ?? 0 }},
                        {{ $ratingsDistribution[2] ?? 0 }},
                        {{ $ratingsDistribution[3] ?? 0 }},
                        {{ $ratingsDistribution[4] ?? 0 }},
                        {{ $ratingsDistribution[5] ?? 0 }}
                    ],
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.7)',
                        'rgba(255, 159, 64, 0.7)',
                        'rgba(255, 205, 86, 0.7)',
                        'rgba(75, 192, 192, 0.7)',
                        'rgba(54, 162, 235, 0.7)'
                    ],
                    borderColor: [
                        'rgb(255, 99, 132)',
                        'rgb(255, 159, 64)',
                        'rgb(255, 205, 86)',
                        'rgb(75, 192, 192)',
                        'rgb(54, 162, 235)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    title: {
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

        // Popular Questions Chart
        const popularQuestionsCtx = document.getElementById('popularQuestionsChart').getContext('2d');
        const popularQuestionsChart = new Chart(popularQuestionsCtx, {
            type: 'bar',
            data: {
                labels: [
                    @foreach($popularQuestionsData as $question)
                        '{{ $question['title'] }}',
                    @endforeach
                ],
                datasets: [{
                    label: '@lang('dashboard.answers_count')',
                    data: [
                        @foreach($popularQuestionsData as $question)
                            {{ $question['answers_count'] }},
                        @endforeach
                    ],
                    backgroundColor: 'rgba(153, 102, 255, 0.7)',
                    borderColor: 'rgb(153, 102, 255)',
                    borderWidth: 1
                }]
            },
            options: {
                indexAxis: 'y',
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    x: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0
                        }
                    }
                }
            }
        });
    });
</script>
{{-- @endpush --}}
@endsection
