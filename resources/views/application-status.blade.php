<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aslam Holy Qur'an Award</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            font-family: Raleway, sans-serif;
            background: url('pattern.svg');
            background-size: cover;
            background-color: #f4f4f4;
        }
        
        h2 {
            font-size: 1.3rem;
        }
        
        .container {
            background: white;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
            text-align: center;
        }
        
        .image_container img{
        
        }
        .container img {
            max-width: 400px;
            margin-bottom: 2rem;
        }
        
        .container h2 {
            margin-bottom: 1.5rem;
            color: #333;
        }
        .container label {
            display: block;
            margin-bottom: 0.5rem;
            color: #555;
            text-align: left;
        }
        .container input {
            width: 100%;
            padding: 0.75rem;
            margin-bottom: 1rem;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .container button {
            width: 100%;
            padding: 0.75rem;
            background: #C19D5C;
            border: none;
            border-radius: 5px;
            color: white;
            font-size: 1rem;
            cursor: pointer;
            margin-top: .75rem;
        }
        .container button:hover {
            background: #D0AA67;
            color: white;
        }
        @media (max-width: 400px) {
            .container {
                padding: 1.2rem;
                margin: 1.2rem;
            }
            .container h2 {
                font-size: 1.5rem;
            }
            .container button {
                font-size: 0.875rem;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <form>
                            <img src="{{ url('/images/footer-mail-logo.svg') }}" alt="Logo">

            <div class="form-area">
                <label for="application-id" class="form-label">Application ID</label>
                    <input type="text" class="form-control" id="application-id" name="application-id"
                        value="{{ old('application-id') }}" aria-describedby="nameHelp" maxlength="10">
                        
                        <span class="error" role="alert">
                        @error('application-id')
                        {{ $message }}
                        @enderror
                        </span>
                <label for="dob" class="form-label">Date of Birth (DD/MM/YY)</label>
                    <div style="position: relative">
                        <input type="text" class="form-control" id="dob" name="date_of_birth"
                            value="" aria-describedby="emailHelp" contenteditable="true">
                        <img style="position: absolute;top: 12px; right: 10px;"
                            src="{{ url('/images/calendar (2).svg') }}" alt="calendar">
                    </div>
                    <span class="error" role="alert">
                        @error('date_of_birth')
                            {{ $message }}
                        @enderror
                    </span>
            </div>
            <button type="submit" id="myButton" class="btn btn-std">Submit</button>
        </form>
    </div>
     <!-- The Modal -->
    <div id="statusModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <div id="result"></div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
    <script>
        $(function() {
            $("#dob").datepicker({
                dateFormat: "dd/mm/yy",
                yearRange: "-100:+0",
                changeMonth: true,
                changeYear: true
            });
        });
    </script>
   

    <script>
        document.getElementById('statusForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const application_id = document.getElementById('application_id').value;
            const date_of_birth = document.getElementById('date_of_birth').value;

            fetch('/check_status', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    application_id: application_id,
                    date_of_birth: date_of_birth
                })
            })
            .then(response => response.json())
            .then(data => {
                let result = document.getElementById('result');
                if (data.error) {
                    result.innerHTML = `<p>${data.error}</p>`;
                } else {
                    result.innerHTML = `
                        <p>Application ID: ${data.application_id}</p>
                        <p>Applicant Name: ${data.applicant_name}</p>
                        <p>Status: ${data.status}</p>
                        <p>Details: ${data.other_details}</p>
                    `;
                }
                let modal = document.getElementById('statusModal');
                modal.style.display = "block";
            })
            .catch(error => {
                console.error('Error:', error);
            });
        });

        // Get the modal
        var modal = document.getElementById("statusModal");

        // Get the <span> element that closes the modal
        var span = document.getElementsByClassName("close")[0];

        // When the user clicks on <span> (x), close the modal
        span.onclick = function() {
            modal.style.display = "none";
        }

        // When the user clicks anywhere outside of the modal, close it
        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }
    </script>
</body>
</html>
