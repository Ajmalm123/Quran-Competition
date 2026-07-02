<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AP Aslam Holy Qur'an Award 2025 - Screening Result</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link rel="icon" type="image/svg" href="{{ asset('images/aqa_faviocn.svg') }}">
    <link href="{{ url('css/style.css') }}" rel="stylesheet" type="text/css" />
</head>

<body>
    <!-- Loader (optional) -->
    <div class="loader-container" style="display:none;">
        <div class="loader"></div>
    </div>

    <!-- Header -->
    <section class="header-area">
        <div class="spacer">
            <a href="https://event.aslamquranaward.com/">
                <img class="img-fluid" src="{{ url('/images/footer-mail-logo.png') }}" alt="logo">
            </a>
        </div>
    </section>

    <!-- Page Title -->
    <section class="form-area pb-0">
        <div class="spacer">
            <div class="form-title text-center">
                <div class="title-head">
                    <h1>AP Aslam Holy Qur'an Award 2025</h1>
                    <p class="pb-0">Screening Result</p>
                </div>
            </div>
        </div>
    </section>

    <!--@php-->
    <!--    $eligible = $candidate->marks >= $threshold;-->
    <!--@endphp-->
    
    <!-- Result Card (Centered & Responsive) -->
    <section class="form-list">
        <div class="container py-4 py-md-5">
            <div class="row justify-content-center">
                <div class="col-sm-11 col-md-8 col-lg-6">
                    <div class="card shadow-sm border-0 rounded-3">
                        <div class="card-body p-4 p-md-5">

                            <!-- Status Badge -->
                            <div class="text-center mb-3">
                                @if($eligible)
                                    <span class="badge bg-success px-3 py-2">
                                        Eligible for Final Round
                                    </span>
                                @else
                                    <span class="badge bg-danger px-3 py-2">
                                        Not Eligible for Final Round
                                    </span>
                                @endif
                            </div>

                            <h2 class="h4 text-center mb-1">
                                Screening Result Details
                            </h2>
                            <p class="text-muted small text-center mb-4">
                                Below are the details as per your application and screening score.
                            </p>

                            <!-- Candidate Details -->
                            <div class="mb-3">
                                <div class="d-flex justify-content-between flex-wrap 
                                            border-bottom py-2">
                                    <span class="fw-semibold">Application ID</span>
                                    <span>{{ $candidate->application_id }}</span>
                                </div>

                                <div class="d-flex justify-content-between flex-wrap 
                                            border-bottom py-2">
                                    <span class="fw-semibold">Name</span>
                                    <span>{{ $candidate->full_name }}</span>
                                </div>

                                <div class="d-flex justify-content-between flex-wrap 
                                            border-bottom py-2">
                                    <span class="fw-semibold">Date of Birth</span>
                                    <span>{{ $candidate->date_of_birth }}</span>
                                    {{-- format if needed: \Carbon\Carbon::parse($candidate->date_of_birth)->format('d/m/Y') --}}
                                </div>

                                <div class="d-flex justify-content-between flex-wrap 
                                            border-bottom py-2">
                                    <span class="fw-semibold">Zone</span>
                                    <span>{{ $candidate->zone_name ?? '-' }}</span>
                                </div>

                                <div class="d-flex justify-content-between flex-wrap 
                                            border-bottom py-2">
                                    <span class="fw-semibold">Contact Number</span>
                                    <span>{{ $candidate->contact_number }}</span>
                                </div>

                                <div class="d-flex justify-content-between flex-wrap 
                                            border-bottom py-2">
                                    <span class="fw-semibold">Screening Marks</span>
                                    <span>{{ $candidate->marks }}</span>
                                </div>
                            </div>

                            <!-- Eligibility Text -->
                            <div class="mt-3">
                                @if($eligible)
                                    <div class="alert alert-success mb-3">
                                        Congratulations! Your screening mark
                                        <strong>{{ $candidate->marks }}</strong>
                                        is above the required mark of
                                        <strong>{{ $threshold }}</strong>. You are
                                        <strong>eligible for the Grand Finale round</strong>.
                                    </div>
                                @else
                                    <div class="alert alert-warning mb-3">
                                        Your screening mark
                                        <strong>{{ $candidate->marks }}</strong>
                                        is below the required mark of
                                        <strong>{{ $threshold }}</strong>. You are
                                        <strong>not eligible for the Grand Finale round</strong>.
                                    </div>
                                @endif
                            </div>

                            <!-- Actions -->
                            <div class="d-flex flex-column flex-sm-row justify-content-between mt-3 gap-2">
                                <a href="{{ route('screening.result.form') }}" class="btn btn-outline-secondary w-100">
                                    Check Another Application
                                </a>
                                <a href="https://event.aslamquranaward.com/" class="btn btn-std w-100">
                                    Back to Website
                                </a>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Scripts (if needed) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

@php
    // TEMP PREVIEW DATA – safe even when real data comes from controller
    if (!isset($candidate)) {
        $candidate = (object)[
            'application_id'   => 'AQA12345',
            'full_name'        => 'Sample Candidate',
            'date_of_birth'    => '15/03/2005',
            'zone_name'        => 'Malappuram',
            'contact_number'   => '9876543210',
            'marks'            => 82,
        ];
    }

    if (!isset($threshold)) {
        $threshold = 70;
    }

    // IMPORTANT: define $eligible here for the whole view
    $eligible = $candidate->marks >= $threshold;
@endphp


</html>
