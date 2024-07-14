<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
        }

        .container {
            margin: 0 auto;
            padding: 20px;
            max-width: 600px;
            background-color: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 8px;
        }

        .header {
            text-align: center;
            padding-bottom: 20px;
        }

        .content {
            margin-bottom: 20px;
        }

        .content p {
            margin: 10px 0;
        }

        .details-table {
            width: 100%;
            margin-bottom: 20px;
            border-collapse: collapse;
        }

        .details-table th,
        .details-table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        .details-table th {
            background-color: #f2f2f2;
        }

        .footer {
            text-align: center;
            font-size: 0.9em;
            color: #555;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h2>പ്രിയപ്പെട്ട {{ $mailData['application']->full_name }}</h2>
        </div>
        <div class="content">
            <p>السلام عليكم ورحمة الله</p>
            <p>'എ.പി. അസ്‌ലം ഹോളി ഖുർആൻ അവാർഡ് 2024’ ലേക്കുള്ള നിങ്ങളുടെ അപേക്ഷ അംഗീകരിച്ചിരിക്കുന്നു എന്ന് സന്തോഷപൂർവം
                അറിയിക്കുന്നു.</p>
            <p>താങ്കൾ പങ്കെടുക്കേണ്ട പ്രാഥമിക മത്സരത്തിൻ്റെ വിവരങ്ങൾ താഴെ കൊടുക്കുന്നു.</p>
        </div>
        <table class="details-table">
            <?php
            $date = $mailData['application']?->zone?->assignment?->date;
            $time = $mailData['application']?->zone?->assignment?->time;
            
            // Format the date and time using Carbon
            // $formattedDate = Carbon::parse($date)->translatedFormat('j F Y, l');
            // $formattedTime = Carbon::parse($time)->format('g:i A');
            ?>
            <tr>
                <th>Date</th>
                <td>{{ $date }}</td>
            </tr>
            <tr>
                <th>Time</th>
                <td>{{ $time }}</td>
            </tr>
            <tr>
                <th>Location</th>
                <td>{{ $mailData['application']?->zone?->assignment?->center_id }}</td>
            </tr>
        </table>
        <div class="content">
            <p>മത്സരത്തിന് മുന്നോടിയായി താഴെ പറയുന്ന കാര്യങ്ങൾ ശ്രദ്ധിക്കണമെന്ന് വിനീതമായി അഭ്യർത്ഥിക്കുന്നു.</p>
            <ul>
                <li>നിർദ്ദേശിച്ച റിപ്പോർട്ടിങ് സമയത്ത് തന്നെ സ്ഥലത്തെത്തി രജിസ്റ്റർ ചെയ്യേണ്ടതാണ്. മത്സരം തുടങ്ങിയ ശേഷം
                    എത്തുന്നവരെ മത്സരിക്കാൻ അനുവദിക്കുന്നതല്ല.</li>
                <li>ഹിഫ്ള്, പാരായണ നിയമങ്ങൾ, മഖാരിജുൽ ഹുറൂഫ്, ആശയം (ആദ്യത്തെ അഞ്ച് ജുസ്ഉകളിലെയും അവസാനത്തെ അഞ്ചു
                    ജുസ്ഉകളിലെയും) എന്നിവയുടെ അടിസ്ഥാനത്തിലായിരിക്കും മത്സരം നടക്കുക. ആ രംഗത്ത് സംഭവിക്കുന്ന ഓരോ
                    പിഴവുകളും മത്സരാർത്ഥികളുടെ മാർക്ക് കുറയ്ക്കുന്നതാണ്.</li>
                <li>മത്സരാർത്ഥികളോടുള്ള ചോദ്യങ്ങൾ മലയാളത്തിലായിരിക്കും; ആയത്തുകളുടെ ആശയങ്ങളുമായി ബന്ധപ്പെട്ട
                    ചോദ്യങ്ങൾക്ക് മറുപടി പറയേണ്ടതും മലയാളത്തിലാണ്.</li>
                <li>അപേക്ഷയോടൊപ്പം സമർപ്പിച്ച രേഖകളുടെ ഒറിജിനൽ കയ്യിൽ കരുതേണ്ടതാണ്.</li>
            </ul>
            <p>അല്ലാഹു അനുഗ്രഹിക്കട്ടെ, ആമീൻ.</p>
            <p>മറ്റു വിവരങ്ങൾ തുടർന്ന് അറിയിക്കുന്നതാണ്.</p>
        </div>
        <div class="footer">
            <p>💬 പ്രോഗ്രാമുമായി ബന്ധപ്പെട്ട വിവരങ്ങൾക്കും സംശയങ്ങൾക്കും ബന്ധപ്പെടുക.</p>
            <p>info@aslamquranaward.com</p>
            <p>കൺവീനർ, എ.പി. അസ്‌ലം ഹോളി ഖുർആൻ അവാർഡ് 2024</p>
        </div>
    </div>
</body>

</html>
