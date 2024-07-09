<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aslam Holy Qur'an Award</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link href="{{ url('css/style.css') }}" rel="stylesheet" type="text/css" />
</head>

<body>
    {{-- @if (session()->has('success')) --}}
    <div class="modal fade" id="successmodal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true"
        style="display: none;">
        <div class="modal-dialog modal-dialog-centered guideline-modal ">
            <div class="modal-content">
                <div class="modal-body pb-0">
                    <div class="modal-detail-area success-modal">
                        <img class="img-fluid" src="{{ url('/images/success.gif') }}" alt="successgif">
                        <h5>Application Submitted
                            Successfully !</h5>
                        <p>This form takes on a more boxy appearance, and works well as a modal. Also, note its dual
                            purpose as a sign-up or sign-in form, toggled at the top.</p>
                        <button type="submit" class="btn btn-std-modal" data-bs-dismiss="modal"
                            aria-label="Close">Okay</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- @endif --}}
    {{-- 
    @if (session()->has('error'))
        <div class="alert alert-danger">
            {{ session()->get('error') }}
        </div>
    @endif --}}

    <section class="header-area">
        <div class="spacer">
            <img class="img-fluid" src="{{ url('/images/logo.svg') }}" alt="logo">
        </div>
    </section>
    <section class="form-area pb-0">
        <div class="spacer">
            <div class="form-title">
                <div class="title-head">
                    <h1>Application For Competition</h1>
                    <p class="pb-0">This form takes on a more boxy appearance, and works well as a modal. Also, note
                        its dual purpose as a sign-up or sign-in form, toggled at the top.</p>
                </div>
                <p class="pb-0" data-bs-toggle="modal" data-bs-target="#exampleModal" style="cursor:pointer;"><u>See
                        Guidelines</u></p>
            </div>
        </div>
    </section>
    <section class="form-list">
        <form method="POST" action="{{ route('application.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="spacer mt-0">
                <h2>Personal Details</h2>
                <div class="col-md-8">
                    <div class="form-area">
                        <label for="exampleInputEmail1" class="form-label">Full Name - പേര്
                            <sup>*</sup></label>
                        <input type="text" class="form-control" id="name" name="full_name"
                            aria-describedby="contactNumberHelp" required value="{{ old('full_name') }}">
                        <span class="error" role="alert">
                            @error('full_name')
                                {{ $message }}</span>
                        @enderror
                        </span>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-area">
                            <label for="exampleInputEmail1" class="form-label">Gender - ലിംഗം <sup>*</sup></label>
                            <div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="gender" id="inlineRadio19"
                                        value="Male"checked>
                                    <label class="form-check-label" for="inlineRadio1">Male</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="gender" id="inlineRadio29"
                                        value="Female" {{ old('gender') == 'Female' ? 'checked' : '' }} disabled>
                                    <label class="form-check-label" for="inlineRadio2">Female</label>
                                </div>

                            </div>
                            <span class="error" role="alert">
                                @error('gender')
                                    {{ $message }}</br>
                                @enderror
                            </span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-area">
                            <label for="dob" class="form-label">Date of Birth - ജനന തിയ്യതി (DD/MM/YY)
                                <sup>*</sup></label>
                            <div style="position: relative">
                                <input type="text" class="form-control" id="dob" name="date_of_birth"
                                    value="" aria-describedby="emailHelp" contenteditable="true">
                                <img style="position: absolute;top: 12px;
                                right: 10px;"
                                    src="{{ url('/images/calendar (2).svg') }}" alt="calender">
                            </div>
                            <span class="error" role="alert">
                                @error('date_of_birth')
                                    {{ $message }}
                                @enderror
                            </span>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-area">
                            <label for="exampleInputEmail1" class="form-label">Mother Tongue - മാതൃ
                                ഭാഷ<sup>*</sup></label>
                            <select class="form-select" aria-label="Default select example" name="mother_tongue">
                                <option value="0" {{ old('mother_tongue') == '0' ? 'selected' : '' }}>Please
                                    Select</option>
                                <option value="Malayalam" {{ old('mother_tongue') == 'Malayalam' ? 'selected' : '' }}>
                                    Malayalam</option>
                                <option value="Other" {{ old('mother_tongue') == 'Other' ? 'selected' : '' }}>Other
                                </option>
                            </select>
                            <span class="error" role="alert">
                                @error('mother_tongue')
                                    {{ $message }}</br>
                                @enderror
                            </span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-area">
                            <label for="exampleInputEmail1" class="form-label">Educational Qualification - വിദ്യാഭ്യാസ
                                യോഗ്യത <sup>*</sup></label>
                            <select class="form-select" aria-label="Default select example"
                                name="educational_qualification">
                                <option value="0"
                                    {{ old('educational_qualification') == '0' ? 'selected' : '' }}>
                                    Please Select</option>
                                <option value="SSLC"
                                    {{ old('educational_qualification') == 'SSLC' ? 'selected' : '' }}>SSLC</option>
                                <option value="Plus Two"
                                    {{ old('educational_qualification') == 'Plus Two' ? 'selected' : '' }}>Plus Two
                                </option>
                                <option value="Degree"
                                    {{ old('educational_qualification') == 'Degree' ? 'selected' : '' }}>Degree
                                </option>
                                <option value="Above Degree"
                                    {{ old('educational_qualification') == 'Above Degree' ? 'selected' : '' }}>Above
                                    Degree</option>
                            </select>
                            <span class="error" role="alert">
                                @error('educational_qualification')
                                    {{ $message }}</br>
                                @enderror
                            </span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-area">
                            <label for="exampleInputEmail1" class="form-label">Aadhar Number - ആധാർ നമ്പർ
                                <sup>*</sup></label>
                            <input type="text" class="form-control" id="aadharNumber" name="aadhar_number"
                                value="{{ old('aadhar_number') }}" aria-describedby="nameHelp" maxlength="12">
                            <span class="error" role="alert">
                                @error('aadhar_number')
                                    {{ $message }}
                                @enderror
                            </span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-area">
                            <label for="exampleInputEmail1" class="form-label">Job - ജോലി</label>
                            <input type="text" class="form-control" id="name" name="job"
                                value="{{ old('job') }}" aria-describedby="nameHelp">
                            <span class="error" role="alert">
                                @error('job')
                                    {{ $message }}
                                @enderror
                            </span>
                        </div>
                    </div>
                </div>

            </div>
            <div class="spacer mt-0">
                <h2>Contact Information</h2>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-area">
                            <label for="exampleInputEmail1" class="form-label">Contact Number - ബന്ധപ്പെടാനുള്ള നമ്പർ
                                <sup>*</sup></label>
                            <input type="text" class="form-control" id="contactNumber" name="contact_number"
                                aria-describedby="nameHelp" value="{{ old('contact_number') }}" maxlength="10">
                            <span class="error" role="alert">
                                @error('contact_number')
                                    {{ $message }}
                                @enderror
                            </span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-area">
                            <label for="exampleInputEmail1" class="form-label">Whatsapp - വാട്സ്ആപ് നമ്പർ
                            </label>
                            <input type="text" class="form-control" id="whatsappNumber" name="whatsapp"
                                aria-describedby="nameHelp" value="{{ old('whatsapp') }}" maxlength="10">
                            <span class="error" role="alert">
                                @error('whatsapp')
                                    {{ $message }}
                                @enderror
                            </span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-area">
                            <label for="exampleInputEmail1" class="form-label">Email - ഇമെയിൽ <sup>*</sup></label>
                            <input type="email" class="form-control" id="name" name="email"
                                aria-describedby="nameHelp" value="{{ old('email') }}">
                            <span class="error" role="alert">
                                @error('email')
                                    {{ $message }}
                                @enderror
                            </span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-area">
                            <label for="current_address" class="form-label">Current Address - വിലാസം
                                <sup>*</sup></label>
                            <textarea class="form-control" style="height:auto;resize:none;" rows="4" id="current_address"
                                name="c_address">{{ old('c_address') }}</textarea>
                            <span class="error" role="alert">
                                @error('c_address')
                                    {{ $message }}
                                @enderror
                            </span>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="use_permanent_address">
                            <label class="form-check-label" for="use_permanent_address">
                                Use permanent address as current address
                            </label>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-area">
                            <label for="permanent_address" class="form-label">Permanent Address - വിലാസം
                                <sup>*</sup></label>
                            <textarea class="form-control" style="height:auto;resize:none;" rows="4" id="permanent_address"
                                name="pr_address">{{ old('pr_address') }}</textarea>
                            <span class="error" role="alert">
                                @error('pr_address')
                                    {{ $message }}
                                @enderror
                            </span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-area">
                            <label for="exampleInputEmail1" class="form-label">District - ജില്ല <sup>*</sup></label>
                            <select class="form-select" aria-label="Default select example" name="district">
                                <option value="0" {{ old('district') == '0' ? 'selected' : '' }}>Please Select
                                </option>
                                <option value="Kasaragod" {{ old('district') == 'Kasaragod' ? 'selected' : '' }}>
                                    Kasaragod</option>
                                <option value="Kannur" {{ old('district') == 'Kannur' ? 'selected' : '' }}>Kannur
                                </option>
                                <option value="Wayanad" {{ old('district') == 'Wayanad' ? 'selected' : '' }}>Wayanad
                                </option>
                                <option value="Kozhikode" {{ old('district') == 'Kozhikode' ? 'selected' : '' }}>
                                    Kozhikode</option>
                                <option value="Malappuram" {{ old('district') == 'Malappuram' ? 'selected' : '' }}>
                                    Malappuram</option>
                                <option value="Palakkad" {{ old('district') == 'Palakkad' ? 'selected' : '' }}>
                                    Palakkad</option>
                                <option value="Thrissur" {{ old('district') == 'Thrissur' ? 'selected' : '' }}>
                                    Thrissur</option>
                                <option value="Ernakulam" {{ old('district') == 'Ernakulam' ? 'selected' : '' }}>
                                    Ernakulam</option>
                                <option value="Idukki" {{ old('district') == 'Idukki' ? 'selected' : '' }}>Idukki
                                </option>
                                <option value="Kottayam" {{ old('district') == 'Kottayam' ? 'selected' : '' }}>
                                    Kottayam</option>
                                <option value="Alappuzha" {{ old('district') == 'Alappuzha' ? 'selected' : '' }}>
                                    Alappuzha</option>
                                <option value="Pathanamthitta"
                                    {{ old('district') == 'Pathanamthitta' ? 'selected' : '' }}>Pathanamthitta</option>
                                <option value="Kollam" {{ old('district') == 'Kollam' ? 'selected' : '' }}>Kollam
                                </option>
                                <option value="Thiruvananthapuram"
                                    {{ old('district') == 'Thiruvananthapuram' ? 'selected' : '' }}>Thiruvananthapuram
                                </option>
                            </select>
                            <span class="error" role="alert">
                                @error('district')
                                    {{ $message }}</br>
                                @enderror
                            </span>
                        </div>
                    </div>
                </div>

            </div>
            <div class="spacer mt-0">
                <h2>Contact Information</h2>
                <div class="col-md-8">
                    <div class="form-area">
                        <label for="institution_name" class="form-label">Name and place of the institution where hifz
                            completed<br>ഹിഫ്സ് പൂർത്തിയാക്കിയ സ്ഥാപനത്തിന്റെ പേരും സ്ഥലവും</label>
                        <input type="text" class="form-control" id="institution_name" aria-describedby="nameHelp"
                            name="institution_name" value="{{ old('institution_name') }}">
                        <span class="error" role="alert">
                            @error('institution_name')
                                {{ $message }}
                            @enderror
                        </span>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-area">
                            <label class="form-label">Did you earn ijazah in any qira'th? <sup>*</sup><br>ഏതെങ്കിലും
                                ഖിറാഅതിൽ ഇജാസ നേടിയിട്ടുണ്ടോ?</label>
                            <div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="is_completed_ijazah"
                                        id="ijazahYes" value="Yes"
                                        {{ old('is_completed_ijazah') == 'Yes' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="ijazahYes">Yes</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="is_completed_ijazah"
                                        id="ijazahNo" value="No"
                                        {{ old('is_completed_ijazah') == 'No' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="ijazahNo">No</label>
                                </div>
                            </div>
                            <span class="error" role="alert">
                                @error('is_completed_ijazah')
                                    {{ $message }}
                                @enderror
                            </span>
                        </div>
                    </div>
                    <div class="col-md-8" id="qirathIjazahSection"
                        style="{{ old('is_completed_ijazah') == 'Yes' ? '' : 'display:none;' }}">
                        <div class="form-area">
                            <label for="qirath_with_ijazah" class="form-label">Qira'th/ Qiraths which earned
                                ijazah<sup>*</sup><br>ഇജാസ നേടിയ ഖിറാഅത് / ഖിറാഅത്തുകൾ</label>
                            <input type="text" class="form-control" id="qirath_with_ijazah"
                                name="qirath_with_ijazah" aria-describedby="nameHelp"
                                value="{{ old('qirath_with_ijazah') }}">
                            <span class="error" role="alert">
                                @error('qirath_with_ijazah')
                                    {{ $message }}
                                @enderror
                            </span>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-area">
                            <label class="form-label">From where you participate in primary competition
                                <sup>*</sup><br>പ്രാഥമിക മത്സരത്തിൽ പങ്കെടുക്കുന്നത് എവിടെ നിന്ന് ?</label>
                            <div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio"
                                        name="primary_competition_participation" id="participationAbroad"
                                        value="Abroad"
                                        {{ old('primary_competition_participation') == 'Abroad' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="participationAbroad">വിദേശത്ത് നിന്ന</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio"
                                        name="primary_competition_participation" id="participationNative"
                                        value="Native"
                                        {{ old('primary_competition_participation') == 'Native' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="participationNative">സ്വദേശത്ത് നിന്ന</label>
                                </div>
                            </div>
                            <span class="error" role="alert">
                                @error('primary_competition_participation')
                                    {{ $message }}
                                @enderror
                            </span>
                        </div>
                    </div>
                    <div class="col-md-4" id="nativeZoneSection"
                        style="{{ old('primary_competition_participation') == 'Native' ? '' : 'display:none;' }}">
                        <div class="form-area">
                            <label for="native_zone" class="form-label">From which zone in
                                native?<sup>*</sup><br>സ്വദേശത്ത് ഏത് മേഖലയിൽ നിന്ന് ?</label>
                            <select class="form-select" aria-label="Default select example" name="zone"
                                id="native_zone">
                                <option value="">Please Select</option>
                                <option value="Kollam" {{ old('native_zone') == 'Kollam' ? 'selected' : '' }}>Kollam
                                </option>
                                <option value="Ernakulam" {{ old('native_zone') == 'Ernakulam' ? 'selected' : '' }}>
                                    Ernakulam</option>
                                <option value="Malappuram" {{ old('native_zone') == 'Malappuram' ? 'selected' : '' }}>
                                    Malappuram</option>
                                <option value="Kannur" {{ old('native_zone') == 'Kannur' ? 'selected' : '' }}>Kannur
                                </option>
                            </select>
                            <span class="error" role="alert">
                                @error('zone')
                                    {{ $message }}
                                @enderror
                            </span>
                        </div>
                    </div>
                    <div class="col-md-4" id="abroadZoneSection"
                        style="{{ old('primary_competition_participation') == 'Abroad' ? '' : 'display:none;' }}">
                        <div class="form-area">
                            <label for="abroad_zone" class="form-label">From which zone in
                                abroad?<sup>*</sup><br>വിദേശത്ത് നിന്നാണെങ്കിൽ</label>
                            <select class="form-select" aria-label="Default select example" name="abroad_zone"
                                id="abroad_zone">
                                <option value="">Please Select</option>
                                <option value="Jeddah" {{ old('abroad_zone') == 'Jeddah' ? 'selected' : '' }}>Jeddah
                                </option>
                                <option value="Dubai" {{ old('abroad_zone') == 'Dubai' ? 'selected' : '' }}>Dubai
                                </option>
                                <option value="Doha" {{ old('abroad_zone') == 'Doha' ? 'selected' : '' }}>Doha
                                </option>
                                <option value="Bahrain" {{ old('abroad_zone') == 'Bahrain' ? 'selected' : '' }}>
                                    Bahrain</option>
                                <option value="Muscat" {{ old('abroad_zone') == 'Muscat' ? 'selected' : '' }}>Muscat
                                </option>
                                <option value="Kuwait" {{ old('abroad_zone') == 'Kuwait' ? 'selected' : '' }}>Kuwait
                                </option>
                            </select>
                            <span class="error" role="alert">
                                @error('zone')
                                    {{ $message }}
                                @enderror
                            </span>
                        </div>
                    </div>
                </div>
            </div>


            <div class="spacer mt-0">
                <h2>Upload Documents</h2>
                <div class="points-area">
                    <svg width="24" height="25" viewBox="0 0 24 25" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path d=" M12 2.29517C6.48 2.29517 2 6.77517 2 12.2952C2 17.8152 6.48 22.2952 12 22.2952C17.52
                                    22.2952 22 17.8152 22 12.2952C22 6.77517 17.52 2.29517 12 2.29517ZM13
                                    17.2952H11V11.2952H13V17.2952ZM13 9.29517H11V7.29517H13V9.29517Z"
                            fill="#9B7858" />
                    </svg>
                    <div class="points">
                        <ul class="mb-0">
                            <li>Photo should be in JPG format, with dimensions of 300px width by 400px
                                height, and must
                                not exceed 100 KB in size.</li>
                            <li>Birth certificate and Letter of recommendation should be submitted in either
                                PDF or
                                image format (JPG), and must be clear and legible.</li>
                            <li>പഠിക്കുന്നതോ പഠിച്ചിറങ്ങിയതിയോ ആയ സ്ഥാപനത്തിന്റെ /മഹല്ല്
                                കമ്മറ്റിയുടെ/ഇസ്ലാഹി
                                സെന്ററിന്റെ/മറ്റു ഇസ്ലാമിക സംഘടനകളുടെയോ റെക്കമെൻഡേഷൻ ലെറ്റർ ആണ് അപ്‌ലോഡ്
                                ചെയ്യേണ്ടത്
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="form-area">
                    <label for="exampleInputEmail1" class="form-label">Please attach the following
                        documents - താഴെ
                        പറഞ്ഞ രേഖകൾ ഉൾപെടുത്തുക </label>
                </div>
                <div class="col-md-4">
                    <div class="form-area">
                        <p>1. Photo (Passport size) - ഫോട്ടോ(പാസ്പോർട്ട് സൈസ്)<sup>*</sup></p>
                        <div class="upload-area" id="passport">
                            <input accept="image/*" type='file' name="passport_size_photo" id="imgInp" />
                            <div class="upload-text">
                                <svg width="24" height="25" viewBox="0 0 24 25" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M18 15.2952V18.2952H6V15.2952H4V18.2952C4 19.3952 4.9 20.2952 6 20.2952H18C19.1 20.2952 20 19.3952 20 18.2952V15.2952H18ZM7 9.29517L8.41 10.7052L11 8.12517V16.2952H13V8.12517L15.59 10.7052L17 9.29517L12 4.29517L7 9.29517Z"
                                        fill="#2964FA" />
                                </svg>
                                <span>Upload File</span>
                            </div>
                        </div>
                        <div id="showimage">
                            <div class="preview-area">
                                <div class="primary-area">
                                    <img id="blah" src="#" alt="your image" />
                                    <div id="filename">{{ old('passport_size_photo') }}</div>
                                </div>
                                <svg style="cursor:pointer;" onclick="showupload()" width="24" height="25"
                                    viewBox="0 0 24 25" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M19 6.70517L17.59 5.29517L12 10.8852L6.41 5.29517L5 6.70517L10.59 12.2952L5 17.8852L6.41 19.2952L12 13.7052L17.59 19.2952L19 17.8852L13.41 12.2952L19 6.70517Z"
                                        fill="black" />
                                </svg>
                            </div>
                        </div>
                        <span class="error" role="alert">
                            @error('passport_size_photo')
                                {{ $message }}
                            @enderror
                        </span>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-area">
                        <p>2. Birth certificate - ജനന സർട്ടിഫിക്കേറ്റ് <sup>*</sup></p>
                        <div class="upload-area" id="birthcertificate">
                            <input accept="application/pdf, image/*" type='file' id="imgInpbirth"
                                name="birth_certificate" />
                            <div class="upload-text">
                                <svg width="24" height="25" viewBox="0 0 24 25" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M18 15.2952V18.2952H6V15.2952H4V18.2952C4 19.3952 4.9 20.2952 6 20.2952H18C19.1 20.2952 20 19.3952 20 18.2952V15.2952H18ZM7 9.29517L8.41 10.7052L11 8.12517V16.2952H13V8.12517L15.59 10.7052L17 9.29517L12 4.29517L7 9.29517Z"
                                        fill="#2964FA" />
                                </svg>
                                <span>Upload File</span>
                            </div>
                        </div>
                        <div id="showimage-birth">
                            <div class="preview-area">
                                <div class="primary-area">
                                    <img id="blahbirth" src="#" alt="your image" />
                                    <div id="filename-birth">{{ old('birth_certificate') }}</div>
                                </div>
                                <svg style="cursor:pointer;" onclick="showuploadbirth()" width="24"
                                    height="25" viewBox="0 0 24 25" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M19 6.70517L17.59 5.29517L12 10.8852L6.41 5.29517L5 6.70517L10.59 12.2952L5 17.8852L6.41 19.2952L12 13.7052L17.59 19.2952L19 17.8852L13.41 12.2952L19 6.70517Z"
                                        fill="black" />
                                </svg>
                            </div>
                        </div>
                        <span class="error" role="alert">
                            @error('birth_certificate')
                                {{ $message }}
                            @enderror
                        </span>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-area">
                        <p>3. Letter of Recommendation - റെക്കമെൻഡേഷൻ ലെറ്റർ <sup>*</sup></p>
                        <div class="upload-area" id="recommendation">
                            <input accept="application/pdf, image/*" type='file' id="imgInpletter"
                                name="letter_of_recommendation" />
                            <div class="upload-text">
                                <svg width="24" height="25" viewBox="0 0 24 25" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M18 15.2952V18.2952H6V15.2952H4V18.2952C4 19.3952 4.9 20.2952 6 20.2952H18C19.1 20.2952 20 19.3952 20 18.2952V15.2952H18ZM7 9.29517L8.41 10.7052L11 8.12517V16.2952H13V8.12517L15.59 10.7052L17 9.29517L12 4.29517L7 9.29517Z"
                                        fill="#2964FA" />
                                </svg>
                                <span>Upload File</span>
                            </div>
                        </div>
                        <div id="showimage-letter">
                            <div class="preview-area">
                                <div class="primary-area">
                                    <img id="blahletter" src="#" alt="your image" />
                                    <div id="filename-letter">{{ old('letter_of_recommendation') }}</div>
                                </div>
                                <svg style="cursor:pointer;" onclick="showuploadletter()" width="24"
                                    height="25" viewBox="0 0 24 25" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M19 6.70517L17.59 5.29517L12 10.8852L6.41 5.29517L5 6.70517L10.59 12.2952L5 17.8852L6.41 19.2952L12 13.7052L17.59 19.2952L19 17.8852L13.41 12.2952L19 6.70517Z"
                                        fill="black" />
                                </svg>
                            </div>
                        </div>
                        <span class="error" role="alert">
                            @error('letter_of_recommendation')
                                {{ $message }}
                            @enderror
                        </span>
                    </div>
                </div>
            </div>
            <div class="spacer mt-0">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                    <label class="form-check-label" for="flexCheckDefault" style="font-size: 14px;">
                        I hereby declare that the information provided in this application is true and
                        correct to the
                        best
                        of my knowledge and belief.
                    </label>
                </div>
                <button type="submit" id="myButton" disabled class="btn btn-std">Submit Application</button>
            </div>
        </form>
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered guideline-modal">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Guidelines</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body pb-0">
                        <div class="modal-detail-area">
                            <h4>Hifz and Participation Details</h4>
                            <ul>
                                <li>Photo should be in JPG format, with dimensions of 300px width by 400px height, and
                                    must not exceed 100 KB in size.</l1>
                                <li>Photo should be in JPG format, with dimensions of 300px width by 400px height, and
                                    must not exceed 100 KB in size.</l1>
                                <li>Photo should be in JPG format, with dimensions of 300px width by 400px height, and
                                    must not exceed 100 KB in size.</l1>
                                <li>Photo should be in JPG format, with dimensions of 300px width by 400px height, and
                                    must not exceed 100 KB in size.</l1>
                                <li>Photo should be in JPG format, with dimensions of 300px width by 400px height, and
                                    must not exceed 100 KB in size.</l1>
                            </ul>
                            <h4>Hifz and Participation Details</h4>
                            <ul>
                                <li>Photo should be in JPG format, with dimensions of 300px width by 400px height, and
                                    must not exceed 100 KB in size.</l1>
                                <li>Photo should be in JPG format, with dimensions of 300px width by 400px height, and
                                    must not exceed 100 KB in size.</l1>
                                <li>Photo should be in JPG format, with dimensions of 300px width by 400px height, and
                                    must not exceed 100 KB in size.</l1>
                                <li>Photo should be in JPG format, with dimensions of 300px width by 400px height, and
                                    must not exceed 100 KB in size.</l1>
                                <li>Photo should be in JPG format, with dimensions of 300px width by 400px height, and
                                    must not exceed 100 KB in size.</l1>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{-- <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#successmodal">
            Launch static backdrop modal
        </button> --}}

    </section>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js" type="99a4cc3df5457cbbea90be86-text/javascript"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    @if (session()->has('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                var successModal = new bootstrap.Modal(document.getElementById('successmodal'));
                successModal.show();
            });
        </script>
    @endif
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            const currentAddress = document.getElementById('current_address');
            const permanentAddress = document.getElementById('permanent_address');
            const checkbox = document.getElementById('use_permanent_address');

            checkbox.addEventListener('change', function() {
                if (this.checked) {
                    permanentAddress.value = currentAddress.value;
                } else {
                    permanentAddress.value = '';
                }
            });
            const ijazahRadios = document.querySelectorAll('input[name="is_completed_ijazah"]');
            const qirathIjazahSection = document.getElementById('qirathIjazahSection');
            const qirathIjazahInput = document.getElementById('qirath_with_ijazah');

            const participationRadios = document.querySelectorAll(
                'input[name="primary_competition_participation"]');
            const nativeZoneSection = document.getElementById('nativeZoneSection');
            const abroadZoneSection = document.getElementById('abroadZoneSection');
            const nativeZoneSelect = document.getElementById('native_zone');
            const abroadZoneSelect = document.getElementById('abroad_zone');

            function toggleIjazahSection() {
                qirathIjazahSection.style.display = this.value === 'Yes' ? '' : 'none';
                if (this.value === 'No') {
                    qirathIjazahInput.value = '';
                }
            }

            function toggleZones() {
                if (this.value === 'Native') {
                    nativeZoneSection.style.display = '';
                    abroadZoneSection.style.display = 'none';
                    abroadZoneSelect.value = '';
                } else if (this.value === 'Abroad') {
                    nativeZoneSection.style.display = 'none';
                    abroadZoneSection.style.display = '';
                    nativeZoneSelect.value = '';
                }
            }

            ijazahRadios.forEach(radio => {
                radio.addEventListener('change', toggleIjazahSection);
            });

            participationRadios.forEach(radio => {
                radio.addEventListener('change', toggleZones);
            });

            // Initialize on page load
            const selectedIjazah = document.querySelector('input[name="is_completed_ijazah"]:checked');
            if (selectedIjazah) {
                toggleIjazahSection.call(selectedIjazah);
            }

            const selectedParticipation = document.querySelector(
                'input[name="primary_competition_participation"]:checked');
            if (selectedParticipation) {
                toggleZones.call(selectedParticipation);
            }
        });
        // function showDiv() {
        //     document.getElementById('displaythis').style.display = 'block';
        // }

        // function hideDiv() {
        //     document.getElementById('displaythis').style.display = 'none';
        // }

        // function showDiv1() {
        //     document.getElementById('showthis').style.display = 'block';
        //     document.getElementById('hidenative').style.display = 'none';
        // }

        function showupload() {
            document.getElementById('showimage').style.display = 'none';
            document.getElementById('passport').style.display = 'block';
            // Reset the file input
            document.getElementById('imgInp').value = '';
        }

        function showuploadbirth() {
            document.getElementById('showimage-birth').style.display = 'none';
            document.getElementById('birthcertificate').style.display = 'block';
            document.getElementById('imgInpbirth').value = '';
        }

        function showuploadletter() {
            document.getElementById('showimage-letter').style.display = 'none';
            document.getElementById('recommendation').style.display = 'block';
            document.getElementById('imgInpletter').value = '';
        }

        function hideDiv1() {
            document.getElementById('showthis').style.display = 'none';
            document.getElementById('hidenative').style.display = 'block';
        }
        const imgInp = document.getElementById('imgInp');

        imgInp.onchange = evt => {
            const [file] = imgInp.files;
            if (file) {
                document.getElementById('showimage').style.display = 'block';
                document.getElementById('passport').style.display = 'none';
                blah.src = URL.createObjectURL(file);
                document.getElementById('filename').textContent = ` ${file.name}`;
            } else {
                // If no file is selected, hide the preview
                document.getElementById('showimage').style.display = 'none';
                document.getElementById('passport').style.display = 'block';
            }
        }

        imgInpbirth.onchange = evt => {
            const [file] = imgInpbirth.files
            if (file) {
                document.getElementById('showimage-birth').style.display = 'block';
                document.getElementById('birthcertificate').style.display = 'none';
                blahbirth.src = URL.createObjectURL(file);
                document.getElementById('filename-birth').textContent = ` ${file.name}`;
            } else {
                // If no file is selected, hide the preview
                document.getElementById('showimage-birth').style.display = 'none';
                document.getElementById('birthcertificate').style.display = 'block';
            }

        }
        imgInpletter.onchange = evt => {
            const [file] = imgInpletter.files
            if (file) {

                document.getElementById('showimage-letter').style.display = 'block';
                document.getElementById('recommendation').style.display = 'none';

                blahletter.src = URL.createObjectURL(file);
                document.getElementById('filename-letter').textContent = ` ${file.name}`;
            } else {
                // If no file is selected, hide the preview
                document.getElementById('showimage-letter').style.display = 'none';
                document.getElementById('recommendation').style.display = 'block';
            }

        }

        function allowOnlyNumbers(inputElement) {
            inputElement.addEventListener('input', function(event) {
                const allowedChars = '0123456789';
                let inputValue = event.target.value;

                // Filter out any disallowed characters
                let filteredValue = '';
                for (let char of inputValue) {
                    if (allowedChars.includes(char)) {
                        filteredValue += char;
                    }
                }
                // Update the input's value with the filtered value
                if (inputValue !== filteredValue) {
                    event.target.value = filteredValue;
                }
            });
        }

        const contactNumberInput = document.getElementById('contactNumber');
        const aadharNumberInput = document.getElementById('aadharNumber');
        const whatsappNumberInput = document.getElementById('whatsappNumber');


        allowOnlyNumbers(contactNumberInput);
        allowOnlyNumbers(aadharNumberInput);
        allowOnlyNumbers(
            whatsappNumberInput);



        $("#dob").datepicker({
            dateFormat: "dd/mm/yy",
            yearRange: "-100:+0",
            changeMonth: true,
            changeYear: true
        });

        $(document).ready(function() {
            $('form').submit(function(event) {
                event.preventDefault();
                var formData = new FormData(this);

                // Clear previous error messages
                $('.error').empty();

                $.ajax({
                    url: "{{ route('application.store') }}",
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        if (response.success) {
                            // Show the success modal
                            $('#successmodal').modal('show');
                        } else {
                            alert('An error occurred. Please check the form for errors.');
                        }
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        if (jqXHR.status === 422) {
                            // Validation errors
                            var errors = jqXHR.responseJSON.errors;
                            console.log(errors);
                            $.each(errors, function(field, messages) {
                                var errorSpan = $('[name="' + field + '"]')
                                    .siblings(
                                        '.error');
                                if (errorSpan.length === 0) {
                                    errorSpan = $('[name="' + field + '"]').closest(
                                        '.form-area').find('.error');
                                }
                                errorSpan.html(messages.join('<br>'));
                            });
                        } else {
                            alert(
                                'An error occurred while processing your application. Please try again later.'
                            );
                        }
                    }
                });
            });
        });
        document.getElementById('flexCheckDefault').addEventListener('change', function() {
            var myButton = document.getElementById('myButton');
            if (this.checked) {
                myButton.disabled = false;
                myButton.classList.add('enabled');
            } else {
                myButton.disabled = true;
                myButton.classList.remove('enabled');
            }
        });
    </script>


    </script>
</body>

</html>
