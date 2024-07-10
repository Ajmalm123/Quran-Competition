<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>ASLAM HOLY QUR'AN AWARD - Emailer</title>
    <style>
        @page { margin: 10px; }
        body {
            font-family: "DejaVu Sans", sans-serif;
            font-size: 10px;
            line-height: 1.2;
            margin: 0;
            padding: 0;
        }
        h1, h5 { margin: 5px 0; }
        h1 { font-size: 14px; }
        h5 { font-size: 10px; padding: 2px 0; }
        .header img { width: 100%; height: auto; }
        .admit-card {
            border: 1px solid #DDDDDD;
            margin-top: 10px;
        }
        .admit-card-header {
            height: 30px;
            background-color: #F4F4F4;
            padding: 0 10px;
        }
        .candidate-photo {
            width: 60px;
            height: 80px;
            margin-right: 10px;
        }
        .candidate-info td {
            font-size: 10px;
            padding: 2px 0;
        }
        table { border-collapse: collapse; }
    </style>
</head>
<body>
    <table style="width: 100%;">
        <tr>
            <td style="background-image: url('{{ url('/images/email_head.png') }}'); background-size: cover; background-position: center; height: 80px;">
            </td>
        </tr>
        <tr>
            <td style="padding: 5px 20px;">
                <h5 style="font-weight: 600;">Dear <?= $mailData['application']->full_name ?? 'Applicant' ?>,</h5>
                <h5>السلام عليكم ورحمة الله وبركات</h5>
                <h5>
                    എ.പി. അസ്ലം ഹോളി ഖുർആൻ അവാർഡ് 2024' ന്േവണ്ടിയുള്ള പ്രാഥമിക മത്സരത്തിന്താങ്കെള
                    െതരഞ്ഞെടുത്തതായി അറിയിക്കുന്നതിൽ സന്തോഷമുണ
                    ്. താങ്കൾ തന്നെ െതെരഞ്ഞെടുത്തത്പ്രകാരം
                    <b><?= $mailData['application']->application_id ?? 'Not Available' ?></b>…ൽ െവച്ച്നടക്കുന്ന േമഖലാ തല
                    മത്സരത്തിലാണ്താങ്കൾ പങ്കെ
                    ്<b><?= $mailData['application']->application_id ?? 'Not Available' ?></b>തി)……ന്രാവിെല 9 മണി മുതൽ
                    ൈവകുന്നേരം 4 മണി വെരയാണ്മത്സരം
                    നടക്കുക. താെഴ പറയുന്ന കാര്യങ്ങൾ ശ്രദ്ധിക്കണെമന്ന്വിനീതമായ
                </h5>
                <h5>
                    1) കൃത്യം ഒമ്പത്മണിക്ക്തന്നെ സ്ഥലത്തെത്തി രജിസ്റ്റർ െചയ്യേണ്ടതാണ്. മത്സരം തുടങ്ങിയ േശഷം
                    എത്തുന്നവെര മത്സരിക്കാൻ അനുവദിക്കുനനതല്ല.
                    2) ഇതോടൊപ്പമുള്ള അഡ്മിഷൻ കാർഡ്പ്രിന്റ്എടുത്ത്കയ്യിൽ െവക്കേണ്ടതാണ്. അഡ്മിഷൻ കാർഡ്
                    ൈകവശമുള്ളവർക്ക് മാത്രയിരിക്കും ഹാളിൽ മത്സരാർത്ഥികൾക്ക്േവണ്ടി നിശ്ചയിക്കപ്പെട്ട സ്ഥലത്തേക്ക്
                    പ്രേവശനം ലഭിക്കുക.
                    3) ഹഫ്സ്ഖിറാഅത്തിൽ ഖുർആൻ പൂർണ്ണമായും മനഃപാഠമുണ്ടോ എന്നായിരിക്കും പ്രാഥമികമായി
                    പരിശോധിക്കുക; മറ്റ്ഖിറാഅത്തുകൾ പരിഗണിക്കുന്നതല്ല.
                    4) തജ്വീദ്നിയമങ്ങൾ ശരിയായ രീതിയിൽ പാലിച്ചുകൊണ്ടാണോ പാരായണം െചയ്യുന്നെതന്ന
                    പരിശോധനയും അതോടൊപ്പം തന്നെ നടക്കും . ആ രംഗത്ത്സംഭവിക്കുന്ന ഓരോ പാളിച്ചകളും
                    മത്സരാർത്ഥികളുെട മാർക്ക്കുറയ്ക്കുെമന്ന്ഓർക്കുക.
                    5) ആദ്യത്തെ അഞ്ച്ജുസ്ഉകളിെലയും (ഫാതിഹ, ബഖറ, ആലുഇമ്രാൻ, നിസാഅ സൂറത്തുകൾ),
                    അവസാനത്തെ അഞ്ചു ജുസ്ഉകളിെലയും (അഹ്ഖാഫ്മുതൽ നാസ്വെര സൂറത്തുകൾ) ആയത്തുകളിൽ
                    പ്രതിപാദിക്കപ്പെട്ട കാര്യങ്ങൾ മത്സരാർത്ഥിക്ക്മനസ്സിലായിട്ടുണ്ടോ എന്ന പരിധോധനയാണ്പിന്നീട്
                    നടക്കുക. േനർക്ക്േനെരയുള്ള അർത്ഥം അറിയുമോെയന്നല്ല, സാരം മനസ്സിലായിട്ടുണ്ടോ എന്നാണ്
                    പരിശോധിക്കപ്പെടുക.
                    6) മത്സരാർത്ഥികളോടുള്ള ചോദ്യങ്ങൾ മലയാളത്തിലായിരിക്കും; ആയത്തുകളുെട ആശയങ്ങളുമായി
                    ബന്ധപ്പെട്ട ചോദ്യങ്ങൾക്ക്മറുപടി പറേയണ്ടതും മലയാളത്തിലാണ്.
                </h5>
                <h5>
                    കൃത്യസമയത്ത്തന്നെ മത്സരസ്ഥലത്ത്എത്തിച്ചേരണെമന്ന്ഒരിക്കൽ കൂടി അഭ്യർത്ഥിക്കുന്നു. അല്ലാഹു
                    അനുഗ്രഹിക്കട്ടെ, ആമീൻ.
                </h5>
                <h5>Convener, AP Aslam Holy Qur'an Award 2024</h5>
            </td>
        </tr>
        <tr>
            <td>
                <table class="admit-card" style="width: 100%;">
                    <tr>
                        <td class="admit-card-header">
                            <h1 style="float: left;">ADMIT CARD</h1>
                            <img src="{{ url('/images/logo_admitcard.png') }}" alt="Logo" style="float: right; width: 120px; height: 20px;">
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 10px;">
                            <table style="width: 100%;">
                                <tr>
                                    <td style="width: 70px;">
                                        <div class="candidate-photo" style="background-color: #ddd;">
                                            <img src="candidate-photo.jpg" alt="Candidate Photo" style="width: 100%; height: 100%;">
                                        </div>
                                    </td>
                                    <td>
                                        <table class="candidate-info" style="width: 100%;">
                                            <tr>
                                                <td style="width: 100px;">Application ID:</td>
                                                <td style="font-weight: 600;">XXXXXXX</td>
                                            </tr>
                                            <tr>
                                                <td>Full Name:</td>
                                                <td style="font-weight: 600;">John Doe dasdasd sdas dasd</td>
                                            </tr>
                                            <tr>
                                                <td>Center Name:</td>
                                                <td style="font-weight: 600;">Center ABC</td>
                                            </tr>
                                            <tr>
                                                <td>Date:</td>
                                                <td style="font-weight: 600;">YYYY-MM-DD</td>
                                            </tr>
                                        </table>
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