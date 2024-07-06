<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Application Details</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 10px;
            line-height: 1.3;
            color: #333;
            margin: 0;
            padding: 10px;
        }

        .header {
            text-align: center;
            margin-bottom: 10px;
        }

        .header h1 {
            color: #2c3e50;
            border-bottom: 1px solid #3498db;
            padding-bottom: 5px;
            margin: 0 0 10px 0;
            font-size: 16px;
        }

        .section {
            margin-bottom: 10px;
            border: 1px solid #e0e0e0;
            border-radius: 3px;
            padding: 5px;
        }

        .section-title {
            font-size: 12px;
            font-weight: bold;
            margin-bottom: 5px;
            color: #2980b9;
            border-bottom: 1px solid #bdc3c7;
            padding-bottom: 2px;
        }

        .grid {
            display: table;
            width: 100%;
        }

        .grid-item {
            display: table-cell;
            width: 50%;
            vertical-align: top;
            padding: 0 5px;
        }

        .label {
            font-weight: bold;
            color: #34495e;
        }

        .value {
            margin-left: 2px;
        }

        img {
            max-width: 100px;
            max-height: 100px;
            border: 1px solid #bdc3c7;
            border-radius: 3px;
            margin-left: 5px;
        }

        p {
            margin: 2px 0;
        }

        .photo-container {
            float: right;
            width: 100px;
            height: 100px;
            margin-left: 5px;
            overflow: hidden;
        }

        .photo-container img {
            width: 100%;
            height: auto;
            object-fit: cover;
            border: 1px solid #bdc3c7;
            border-radius: 3px;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>Application Details</h1>
    </div>

    <div class="section">
        <div class="section-title">Personal Information</div>
        {{-- @if ($record->passport_size_photo)
            <div class="photo-container">
                <img src="{{ storage_path('app/public/' . $record->passport_size_photo) }}" alt="Passport Photo">
            </div>
        @endif --}}
        <div class="grid">
            <div class="grid-item">
                <p><span class="label">Full Name:</span> <span class="value">{{ $record->full_name }}</span></p>
                <p><span class="label">Age:</span> <span class="value">{{ \Carbon\Carbon::parse($record->date_of_birth)->age }}</span></p>
                <p><span class="label">Date of Birth:</span> <span class="value">{{ $record->date_of_birth }}</span></p>
                <p><span class="label">Gender:</span> <span class="value">{{ $record->gender }}</span></p>
            </div>
            <div class="grid-item">
                <p><span class="label">Educational Qualification:</span> <span class="value">{{ $record->educational_qualification }}</span></p>
                <p><span class="label">Job:</span> <span class="value">{{ $record->job }}</span></p>
                <p><span class="label">Mother Tongue:</span> <span class="value">{{ $record->mother_tongue }}</span></p>
                <p><span class="label">Aadhar Number:</span> <span class="value">{{ $record->aadhar_number }}</span></p>
            </div>
        </div>
    </div>

    <div class="section">
        <div class="section-title">Contact Information</div>
        <div class="grid">
            <div class="grid-item">
                <p><span class="label">Contact Number:</span> <span class="value">{{ $record->contact_number }}</span></p>
                <p><span class="label">WhatsApp:</span> <span class="value">{{ $record->whatsapp }}</span></p>
                <p><span class="label">Email:</span> <span class="value">{{ $record->email }}</span></p>
            </div>
            <div class="grid-item">
                <p><span class="label">Current Address:</span> <span class="value">{{ $record->c_address }}</span></p>
                <p><span class="label">Permanent Address:</span> <span class="value">{{ $record->pr_address }}</span></p>
                <p><span class="label">District:</span> <span class="value">{{ $record->district }}</span></p>
            </div>
        </div>
    </div>

    <div class="section">
        <div class="section-title">Hifz and Participation Details</div>
        <div class="grid">
            <div class="grid-item">
                <p><span class="label">Institution Name:</span> <span class="value">{{ $record->institution_name }}</span></p>
                <p><span class="label">Completed Ijazah:</span> <span class="value">{{ $record->is_completed_ijazah }}</span></p>
                <p><span class="label">Qirath with Ijazah:</span> <span class="value">{{ $record->qirath_with_ijazah }}</span></p>
            </div>
            <div class="grid-item">
                <p><span class="label">Primary Competition Participation:</span> <span class="value">{{ $record->primary_competition_participation }}</span></p>
                <p><span class="label">Zone:</span> <span class="value">{{ $record->zone }}</span></p>
            </div>
        </div>
    </div>
</body>

</html>
