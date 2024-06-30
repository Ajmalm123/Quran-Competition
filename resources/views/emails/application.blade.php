<!DOCTYPE html>
<html>

<head>
    <title>Application Approved</title>
</head>

<body>
    <h1>Dear {{ $mailData['application']->full_name }},</h1>
    <p>{{ $mailData['message'] }}</p>
</body>

</html>
