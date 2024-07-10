<!DOCTYPE html>
<html>

<head>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Malayalam:wght@100..900&display=swap" rel="stylesheet">
    <title>ASLAM HOLY QUR’AN AWARD - Emailer</title>

    <style>
        /* @import url('https://fonts.googleapis.com/css2?family=Raleway:ital,wght@0,100..900;1,100..900&display=swap'); */

        body {
            font-family: "Noto Sans Malayalam", sans-serif;
            width: 210mm;
            height: 297mm;
            margin: 0;
            padding: 0;
        }

        h1 {
            font-size: 16px;
            margin-bottom: 10px;
            margin-top: 10px;
        }

        h5 {
            margin: 0 20px;
            padding: 10px 0px;
            background-color: #fff;
        }

        .header {
            background-color: #f4f4f4;
            text-align: center;
        }

        .header img {
            width: 100%;
            display: block;
            border: none;
        }

        .message-body {
            padding: 80px;
        }

        .admit-card {
            border: 1px solid #DDDDDD;
            /* Add stroke here */
            border-radius: 0px;
            /* Optional: Add rounded corners */
            margin-top: 80px;
        }

        .admit-card-header {
            height: 60px;
            background-color: #F4F4F4;
            padding: 0 32px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .admit-card-header h1 {
            font-size: 16px;
            margin: 0;
        }

        .admit-card-header img {
            width: 160px;
            height: 28px;
        }

        .candidate-details {
            padding: 32px;
            display: flex;
            align-items: center;
        }

        .candidate-photo {
            width: 84px;
            height: 108px;
            background-color: #ddd;
            margin-right: 32px;
        }

        .candidate-info table {
            width: 100%;
            border-collapse: collapse;
            /* Ensure cells don't have spaces between them */
        }

        .candidate-info td {
            font-size: 14px;
            padding: 8px 0;
            color: #000000;
        }

        .candidate-info .title {
            font-weight: bold;
            padding-right: 10px;
            /* Adjust spacing between title and value */
        }

        /*@media print {
                    .header {
                        background: #f4f4f4 !important;
                    }
                    .admit-card-header {
                        background: #F4F4F4 !important;
                    }
                    .header img {
                        width: 100%;
                    }
                }*/
    </style>
</head>


<body>
    <table align="center" style="width: 100%;">

        <tr>
            <td
                style="background-image: url('{{ url('/images/email_head.png') }}'); background-size: cover; background-position: center; height: 140px;">
                <!-- Content inside the header cell -->
            </td>
        </tr>


        <tr>
            <td style="padding: 0px 80px;">
                <h5 style="font-size: 14px; margin: 0; font-weight: 600; color: #000000; padding-top: 20px;">
                    Dear <?= $mailData['application']->full_name ?? 'Applicant' ?>,
                </h5>
            </td>
        </tr>

        <tr>
            <td style="padding: 0px 80px;">
                <h5 style="font-size: 14px; margin: 0; font-weight: 400; color: #000000;">
                    السلام عليكم ورحمة الله وبركات
                </h5>
            </td>
        </tr>

        <tr>
            <td style="padding: 0px 80px;">
                <h5 style="font-size: 14px; margin: 0; line-height: 22px; font-weight: 400; color: #000000;">
                    എ.പി. അസ്ലം ഹോളി ഖുർആൻ അവാർഡ് 2024’ ന്േവണ്ടിയുള്ള പ്രാഥമിക മത്സരത്തിന്താങ്കെള
                    െതരഞ്ഞെടുത്തതായി അറിയിക്കുന്നതിൽ സന്തോഷമുണ
                </h5>
            </td>
        </tr>
        <tr>
            <td style="padding: 0px 80px;">
                <h5 style="font-size: 14px; margin: 0; line-height: 22px; font-weight: 400; color: #000000;">
                    ്. താങ്കൾ തന്നെ െതെരഞ്ഞെടുത്തത്പ്രകാരം
                    <b><?= $mailData['application']->application_id ?? 'Not Available' ?></b>…ൽ െവച്ച്നടക്കുന്ന േമഖലാ തല
                    മത്സരത്തിലാണ്താങ്കൾ പങ്കെ
                    ്<b><?= $mailData['application']->application_id ?? 'Not Available' ?></b>തി)……ന്രാവിെല 9 മണി മുതൽ
                    ൈവകുന്നേരം 4 മണി വെരയാണ്മത്സരം
                    നടക്കുക. താെഴ പറയുന്ന കാര്യങ്ങൾ ശ്രദ്ധിക്കണെമന്ന്വിനീതമായ
                </h5>
            </td>
        </tr>
        <tr>
            <td style="padding: 0px 80px;">
                <h5 style="font-size: 14px; margin: 0; line-height: 22px; font-weight: 400; color: #000000;">
                    1) കൃത്യം ഒമ്പത്മണിക്ക്തന്നെ സ്ഥലത്തെത്തി രജിസ്റ്റർ െചയ്യേണ്ടതാണ്. മത്സരം തുടങ്ങിയ േശഷം
                    എത്തുന്നവെര മത്സരിക്കാൻ അനുവദിക്കുന<br>നതല്ല.
                    2) ഇതോടൊപ്പമുള്ള അഡ്മിഷൻ കാർഡ്പ്രിന്റ്എടുത്ത്കയ്യിൽ െവക്കേണ്ടതാണ്. അഡ്മിഷൻ കാർഡ്
                    ൈകവശമുള്ളവർക്ക് മാത്രയിരിക്കും ഹാളിൽ മത്സരാർത്ഥികൾക്ക്േവണ്ടി നിശ്ചയിക്കപ്പെട്ട സ്ഥലത്തേക്ക്
                    പ്രേവശനം ലഭിക്കുക.<br>
                    3) ഹഫ്സ്ഖിറാഅത്തിൽ ഖുർആൻ പൂർണ്ണമായും മനഃപാഠമുണ്ടോ എന്നായിരിക്കും പ്രാഥമികമായി
                    പരിശോധിക്കുക; മറ്റ്ഖിറാഅത്തുകൾ പരിഗണിക്കുന്ന<br>തല്ല.
                    4) തജ്വീദ്നിയമങ്ങൾ ശരിയായ രീതിയിൽ പാലിച്ചുകൊണ്ടാണോ പാരായണം െചയ്യുന്നെതന്ന
                    പരിശോധനയും അതോടൊപ്പം തന്നെ നടക്കും . ആ രംഗത്ത്സംഭവിക്കുന്ന ഓരോ പാളിച്ചകളും
                    മത്സരാർത്ഥികളുെട മാർക്ക്കുറയ്ക്കുെമന്ന്ഓർക്കു<br>ക.
                    5) ആദ്യത്തെ അഞ്ച്ജുസ്ഉകളിെലയും (ഫാതിഹ, ബഖറ, ആലുഇമ്രാൻ, നിസാഅ സൂറത്തുകൾ),
                    അവസാനത്തെ അഞ്ചു ജുസ്ഉകളിെലയും (അഹ്ഖാഫ്മുതൽ നാസ്വെര സൂറത്തുകൾ) ആയത്തുകളിൽ
                    പ്രതിപാദിക്കപ്പെട്ട കാര്യങ്ങൾ മത്സരാർത്ഥിക്ക്മനസ്സിലായിട്ടുണ്ടോ എന്ന പരിധോധനയാണ്പിന്നീട്
                    നടക്കുക. േനർക്ക്േനെരയുള്ള അർത്ഥം അറിയുമോെയന്നല്ല, സാരം മനസ്സിലായിട്ടുണ്ടോ എന്നാണ്
                    പരിശോധിക്കപ്പെടുക.<br>
                    5) മത്സരാർത്ഥികളോടുള്ള ചോദ്യങ്ങൾ മലയാളത്തിലായിരിക്കും; ആയത്തുകളുെട ആശയങ്ങളുമായി
                    ബന്ധപ്പെട്ട ചോദ്യങ്ങൾക്ക്മറുപടി പറേയണ്ടതും മലയാളത്തിലാണ്.
                </h5>
            </td>
        </tr>
        <tr>
            <td style="padding: 0px 80px;">
                <h5 style="font-size: 14px; margin: 0; font-weight: 400; color: #000000;">
                    കൃത്യസമയത്ത്തന്നെ മത്സരസ്ഥലത്ത്എത്തിച്ചേരണെമന്ന്ഒരിക്കൽ കൂടി അഭ്യർത്ഥിക്കുന്നു. അല്ലാഹു
                    അനുഗ്രഹിക്കട്ടെ, ആമീൻ.
                </h5>
            </td>
        </tr>

        <tr>
            <td style="padding: 0px 80px;">
                <h5
                    style="font-size: 14px; margin: 0; padding-top: 0; padding-bottom: 10px; font-weight: 400; color: #000000;">
                    Convener, AP Aslam Holy Qur’an Award 2024
                </h5>
            </td>
        </tr>
        <tr style= "padding:40px 40px">
            <td>
                <table class="admit-card" style="width: 100%; border: 1px solid #DDDDDD; margin-top: 20px;">
                    <tr>
                        <td>
                            <table style="width: 100%; border-collapse: collapse;">
                                <tr>
                                    <td
                                        style="height: 60px; background-color: #F4F4F4; padding: 0 32px; display: flex; justify-content: space-between; align-items: center;">
                                        <h1 style="font-size: 16px; margin: 0;">ADMIT CARD</h1>
                                        <img src="{{ url('/images/logo_admitcard.png') }}" alt="Logo"
                                            style="width: 160px; height: 28px;">
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding: 32px; display: flex; align-items: center;">
                                        <div class="candidate-photo"
                                            style="width: 98px; height: 126px; background-color: #ddd; margin-right: 32px;">
                                            <img src="candidate-photo.jpg" alt="Candidate Photo"
                                                style="width: 100%; height: 100%;">
                                        </div>
                                        <div class="candidate-info">
                                            <table style="width: 100%; border-collapse: collapse;">
                                                <tr>
                                                    <td
                                                        style="font-size: 12px; font-weight: regular; color: #000000; padding-right: 10px;">
                                                        Application ID:</td>
                                                    <td
                                                        style="font-size: 15px; font-weight: 600; padding-right: 25px; color: #000000;">
                                                        XXXXXXX</td>
                                                </tr>
                                                <tr>
                                                    <td
                                                        style="font-size: 12px; font-weight: regular; color: #000000; padding-right: 10px;">
                                                        Full Name:</td>
                                                    <td style="font-size: 15px; font-weight: 600; color: #000000;">John
                                                        Doe dasdasd sdas dasd</td>
                                                </tr>
                                                <tr>
                                                    <td
                                                        style="font-size: 12px; font-weight: regular; color: #000000; padding-right: 10px;">
                                                        Center Name:</td>
                                                    <td style="font-size: 15px; font-weight: 600; color: #000000;">
                                                        Center ABC</td>
                                                </tr>
                                                <tr>
                                                    <td
                                                        style="font-size: 12px; font-weight: regular; color: #000000; padding-right: 10px;">
                                                        Date:</td>
                                                    <td style="font-size: 15px; font-weight: 600; color: #000000;">
                                                        YYYY-MM-DD</td>
                                                </tr>
                                            </table>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>

</html>
