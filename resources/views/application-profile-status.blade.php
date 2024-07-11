<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Application Status</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link href="{{ url('css/style.css') }}" rel="stylesheet" type="text/css" />
    <style>
    
    body {
            font-family: Arial, sans-serif;
            margin: 0;
            background-color: #f4f4f4;
        }
     h1 {
        color: #9B7858;
        padding-bottom: 24px;
        padding-top: 0px;
        font-size: 24px;
        font-weight: 600;
        line-height: 28px;
     }   
h2 {
    color: #9B7858;
    padding-bottom: 24px;
    padding-top: 0px;
    font-size: 16px;
    font-weight: 600;
    line-height: 28px;
}
        .container {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            max-width: 800px;
            margin: 20px auto;
        }
        .applicant-details img {
            width: 140px;
            height: 180px;
            object-fit: cover;
            border: 1px solid #ddd;
            border-radius: 8px;
        }
        .applicant-details {
            display: flex;
            align-items: center;
            gap: 20px;
        }
        .applicant-info {
            flex: 1;
        }
        .status-box {
            font-size: 28px;
            color: green;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 8px;
            display: inline-block;
            background-color: #e9ffe9;
        }
        .download-section {
            margin-top: 20px;
        }
        .download-button {
            display: inline-flex;
            align-items: center;
            padding: 10px 20px;
            font-size: 16px;
            color: white;
            background-color: #4CAF50;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
        }
        .download-button:hover {
            background-color: #45a049;
        }
        .download-button .icon {
            margin-right: 8px;
        }
    </style>
</head>
<body>
    <section class="header-area">
        <div class="spacer">
            <img class="img-fluid" src="{{ url('/images/footer-mail-logo.png') }}" alt="logo">
        </div>
    </section>
    <div class="container">
        <div class"header">
        <h1>AP Aslam Holy Qur'an Award Application Status</h1>
        <p>Welcome to the application status page. Here you can find the details of your application and download your invitation and admit card if your application is approved.</p>    
        </div>
        

        <h2>Personal Details</h2>
        <div class="applicant-details">
            <img src="https://via.placeholder.com/70x90" alt="Applicant Photo">
            <div class="applicant-info">
                <p><strong>Application ID:</strong> APQ20230001</p>
                <p><strong>Full Name:</strong> John Doe</p>
                <p><strong>Date of Birth:</strong> 1990-01-01</p>
                <p><strong>Email:</strong> john.doe@example.com</p>
                <p><strong>Contact Number:</strong> +1 234 567 890</p>
            </div>
        </div>

        <h2 class="subheading">Application Status</h2>
        <div class="status-box">
            Approved
        </div>

        <h2 class="subheading">Download Invitation and Admit Card</h2>
        <div class="download-section">
            <a href="#" class="download-button">
                <span class="icon">📥</span> Download
            </a>
        </div>
    </div>
</body>
</html>
