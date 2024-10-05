<!DOCTYPE html>
<html>

<head>
    <title>EP Aslam Holy Quran Award 2024 - Emailer</title>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Raleway:ital,wght@0,100..900;1,100..900&display=swap');

        body {
            font-family: "Raleway", sans-serif;
        }

        h1 {
            font-size: 16px;
            margin-bottom: 10px;
            margin-top: 10px;
        }

        h5 {
            margin: 0 20px;
            padding: 15px 20px;
            background-color: #fff;
        }
    </style>
</head>

<body>
    <table width="600" height="100" align="center" style="margin-top:20px;">
        <tbody>
            <tr>
                <td width="600" align="center">
                    <table width="600" style="border: 1px solid #E9EAEC; border-spacing: 0;background-color: #F2F2F2;">
                        <tbody>
                            <tr>
                                <td style="padding:0;">
                                    <table width="600" cellpadding="0" cellspacing="0" border="0"
                                        background="https://aslamquranaward.com/images/header-bg.png"
                                        style="background-color: #f2f2f2; background-repeat: no-repeat; background-size: cover; border-spacing: 0; margin:40px 18px 0 18px; padding:20px;">
                                        <tr>
                                            <td width="300" style="padding-left:16px;">
                                                <h1 style="font-size:14px;color:#fff;margin-bottom:0;">AP Aslam Holy Qur'an</h1>
                                                <h1 style="font-size:14px;color:#fff;margin-top:5px;">Award 2024</h1>
                                            </td>
                                            <td width="300" style="padding-left:20px;text-align:right;">
                                                <img style="width:15em" src="{{ $message->embed(public_path('images/logo-email.png')) }}">
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <td style="padding:0px 20px;">
                                    <h5 style="font-size: 14px; margin-top: 0px;margin-bottom: 0px;font-weight: 600;color:#000000;padding-top:50px;">
                                        السلام عليكم ورحمة الله وبركاته
                                    </h5>
                                </td>
                            </tr>
                            <tr>
                                <td style="padding:0px 20px;">
                                    <h5 style="font-size: 14px; margin-top: 0px;margin-bottom: 0px;font-weight: 700;color:#000000;">
                                        <strong>പ്രിയപ്പെട്ട ഹാഫിസ് {{ $mailData['applicant_name'] }},</strong>
                                    </h5>
                                </td>
                            </tr>
                            <tr>
                                <td style="padding:0px 20px;">
                                    <h5 style="line-height:22px;font-size: 14px; margin-top: 0px;margin-bottom: 0px;font-weight: 400;color:#000000;">
                                        എപി അസ്ലം ഹോളി ഖുർആൻ അവാർഡ് 2024 ന്റെ പ്രാഥമിക റൗണ്ട് മത്സരത്തിൽ താങ്കൾ തിരഞ്ഞെടുത്ത മേഖലയിലെ മത്സരത്തിന്റെ സമയക്രമം താഴെ കൊടുക്കുന്നു.
                                    </h5>
                                </td>
                            </tr>
                            <tr>
                                <td style="padding:0px 20px;">
                                    <h5 style="line-height:22px;font-size: 14px; margin-top: 0px;margin-bottom: 0px;font-weight: 400;color:#000000;">
                                        മേഖല: {{ $mailData['zone'] }}<br>
                                         സെന്റർ നെയിം: {{ $mailData['center_name'] }}<br>
                                        {{-- സെന്റർ കോഡ്: {{ $mailData['center_code'] }}<br>  --}}
                                        സ്ഥലം: {{ $mailData['location'] }}<br>
                                        തിയ്യതി: {{ $mailData['date'] !== 'N/A' ? \Carbon\Carbon::parse($mailData['date'])->format('F j, Y') : 'N/A' }}<br>
                                        റിപ്പോർട്ടിംഗ് ടൈം: {{ $mailData['reporting_time'] !== 'N/A' ? \Carbon\Carbon::parse($mailData['reporting_time'])->format('h:i A') : 'N/A' }}
                                    </h5>
                                </td>
                            </tr>
                            <tr>
                                <td style="padding:0px 20px;">
                                    <h5 style="line-height:22px;font-size: 14px; margin-top: 0px;margin-bottom: 0px;font-weight: 700;color:#000000;">
                                        മത്സരവുമായി ബന്ധപ്പെട്ട നിർദ്ദേശങ്ങൾ
                                    </h5>
                                </td>
                            </tr>
                            <tr>
                                <td style="padding:0px 20px;">
                                    <h5 style="line-height:22px;font-size: 14px; margin-top: 0px;margin-bottom: 0px;font-weight: 400;color:#000000;">
                                        <ul>
                                            <li>മത്സരാർത്ഥി റിപ്പോർട്ടിംഗ് ടൈം കൃത്യമായി പാലിക്കേണ്ടതാണ്. റിപ്പോർട്ടിംഗ് കഴിഞ്ഞ് അരമണിക്കൂർ കൊണ്ട് മത്സരങ്ങൾ ആരംഭിക്കുന്നതായിരിക്കും.</li>
                                            <li>അപേക്ഷയോടൊപ്പം നൽകിയ ശുപാർശ കത്തും (Recommendation Letter) ഏതെങ്കിലും ഒരു ഐഡി കാർഡും റിപ്പോർട്ടിങ് സമയത്ത് ഹാജരാക്കേണ്ടതാണ്.</li>
                                            <li>Participant id card എന്ന പേരിൽ മത്സരത്തിൽ പങ്കെടുക്കുന്നതിന് ഹാജരാക്കേണ്ട admit card എത്രയും പെട്ടെന്ന് തന്നെ ഇ മെയിൽ വഴി അയച്ചു തരുന്നതായിരിക്കും. അതിന്റെ സോഫ്റ്റ്‌ കോപ്പിയോ ഹാർഡ് കോപ്പിയോ ഹാജരാക്കേണ്ടതാണ്.</li>
                                            <li>മത്സരത്തിന്റെ എല്ലാ ഘട്ടങ്ങളിലും വിധികർത്താക്കളുടെ തീരുമാനങ്ങൾ അന്തിമമായിരിക്കും.</li>
                                            <li>മത്സരത്തിന്റെ ആദ്യാവസാനം സദസ്സിൽ സാന്നിധ്യം ഉണ്ടായിരിക്കണം. മത്സരങ്ങൾക്ക് ശേഷമുള്ള സർട്ടിഫിക്കറ്റ് വിതരണം കഴിഞ്ഞതിനുശേഷം മാത്രമേ പിരിഞ്ഞു പോകാവൂ.</li>
                                            <li>മത്സരാർത്ഥിക്കുള്ള അന്നേ ദിവസത്തെ ഭക്ഷണം ഉണ്ടായിരിക്കും.</li>
                                        </ul>
                                    </h5>
                                </td>
                            </tr>
                            <tr>
                                <td style="padding:0px 20px;">
                                    <h5 style="padding-bottom:8px;font-size: 14px; margin-top: 0px;margin-bottom: 0px;font-weight: 400;color:#000000;">
                                        വിശ്വസ്ഥതയോടെ,
                                    </h5>
                                    <h5 style="font-size: 14px; margin-top: 0px;padding-top:0;padding-bottom:10px;margin-bottom: 0px;font-weight: 400;color:#000000;">
                                        കോർഡിനേറ്റർ<br>
                                        എപി അസ്‌ലം ഹോളി ഖുർആൻ അവാർഡ് കമ്മിറ്റി
                                    </h5>
                                </td>
                            </tr>
                            <tr>
                                <td style="padding:0px 20px;">
                                    <h5 style="font-size: 14px; margin-top: 0px;margin-bottom: 0px;font-weight: 400;color:#000000;">
                                        9846310383
                                    </h5>
                                    <h5 style="font-size: 14px; margin-top: 0px;margin-bottom: 0px;font-weight: 400;color:#000000;">
                                        info@aslamquranaward.com
                                    </h5>
                                </td>
                            </tr>
                            <tr>
                                <td style="text-align:center;padding-top:40px;">
                                    <img src="{{ $message->embed(public_path('images/footer-mail-logo.png')) }}">
                                </td>
                            </tr>
                            <tr>
                                <td style="padding:0px 20px;">
                                    <a href="https://aslamquranaward.com/" target="_blank">
                                        <h5 style="color:#000000;font-size: 8px;text-align:center; margin-top: 10px;margin-bottom: 0px;font-weight: 400;padding-bottom:0; background-color: transparent;">
                                            Copyright © 2024 Aslam Quran Award, All rights reserved.
                                        </h5>
                                    </a>
                                </td>
                            </tr>
                            <tr>
                                <td style="padding:0px 20px;">
                                    <h5 style="padding-top:16px;font-size: 8px;color:#000000;text-align:center; margin-top: 0px;margin-bottom: 0px;font-weight: 400;color:#000000;background-color: transparent;">
                                        Follow us on
                                    </h5>
                                </td>
                            </tr>
                            <tr>
                                <td style="padding:0px 20px 30px 20px;text-align:center;">
                                    <a style="text-decoration: none;" href="https://www.instagram.com/apaslamquranaward/?utm_source=qr&igsh=YjZ4OXNieTRocXdy" target="_blank">
                                        <img src="{{ $message->embed(public_path('images/insta 2.png')) }}">
                                    </a>
                                    <a style="text-decoration: none;" href="https://youtube.com/@apaslamquranaward?si=hrE99ldkB2tm7fqP" target="_blank">
                                        <img src="{{ $message->embed(public_path('images/youtube 2.png')) }}">
                                    </a>
                                    <a style="text-decoration: none;" href="https://www.facebook.com/apaslamquranaward" target="_blank">
                                        <img src="{{ $message->embed(public_path('images/facebook 3.png')) }}">
                                    </a>
                                    <a style="text-decoration: none;" href="https://www.x.com/apaslamquranaward" target="_blank">
                                        <img src="{{ $message->embed(public_path('images/twitter 3.png')) }}">
                                    </a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>
</body>

</html>