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
    <!-- Loader (optional – keep if you’re using it globally) -->
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
           
        </div>
    </section>

    <!-- Result Check Form in Centered Card -->
    <section class="form-list">
        <div class="container py-4 py-md-5">
            <div class="row justify-content-center">
                <div class="col-sm-10 col-md-7 col-lg-5">
                    <div class="card shadow-sm border-0 rounded-3">
                        <div class="card-body p-4 p-md-5">
                            <h2 class="h2 mb-3 text-center">AP Aslam Holy Qur'an Award 2025 - Result</h2>
                            <p class="text-muted small text-center mb-4">
                                Please enter your Application ID and Date of Birth as used in the application to .
                            </p>

                            <form method="POST" action="{{ route('screening.result.check') }}">
                                @csrf

                                <!-- Application ID -->
                                <div class="form-area mb-3">
                                    <label for="application_id" class="form-label">
                                        Application ID <sup>*</sup>
                                    </label>
                                    <input
                                        type="text"
                                        class="form-control"
                                        id="application_id"
                                        name="application_id"
                                        value="{{ old('application_id') }}"
                                        required
                                    >
                                    <span class="error" role="alert">
                                        @error('application_id')
                                            {{ $message }}
                                        @enderror
                                    </span>
                                </div>

                                <!-- Date of Birth (just below Application ID) -->
                                <div class="form-area mb-3">
                                    <label for="dob" class="form-label">
                                        Date of Birth (DD/MM/YY) <sup>*</sup>
                                    </label>
                                    <div style="position: relative">
                                        <input
                                            type="text"
                                            class="form-control"
                                            id="dob"
                                            name="date_of_birth"
                                            value="{{ old('date_of_birth') }}"
                                            required
                                        >
                                        <img
                                            style="position: absolute; top: 12px; right: 10px;"
                                            src="{{ url('/images/calendar (2).svg') }}"
                                            alt="calendar"
                                        >
                                    </div>
                                    <span class="error" role="alert">
                                        @error('date_of_birth')
                                            {{ $message }}
                                        @enderror
                                    </span>
                                </div>

                                <!-- Submit Button -->
                                <div class="d-grid mt-3">
                                    <button type="submit" class="btn btn-std">
                                        Check Result
                                    </button>
                                </div>

                                <!-- Optional: Show result message -->
                                @if(session('status'))
                                    <div class="alert alert-info mt-3 mb-0">
                                        {{ session('status') }}
                                    </div>
                                @endif

                                @if(session('result'))
                                    {{-- Example result block – adjust as per your backend --}}
                                    <div class="alert alert-success mt-3 mb-0">
                                        {{ session('result') }}
                                    </div>
                                @endif
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

    <script>
        // Datepicker for DOB
        $("#dob").datepicker({
            dateFormat: "dd/mm/yy",
            yearRange: "-100:+0",
            changeMonth: true,
            changeYear: true
        });
    </script>
</body>

</html>
