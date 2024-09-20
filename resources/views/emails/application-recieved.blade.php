<!DOCTYPE html>
<html>

<head>
    <title>ASLAM HOLY QUR'AN AWARD - Emailer</title>

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
                                        background="{{ $message->embed(public_path('images/header-bg.png')) }}"
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
                                    <h5
                                        style="font-size: 14px; margin-top: 0px;margin-bottom: 0px;font-weight: 600;color:#000000;padding-top:50px;">
                                        Dear <?= $mailData['application']->full_name ?? 'Applicant' ?>,
                                </td>
                            </tr>
                            <tr>
                                <td style="padding:0px 20px;">
                                    <h5
                                        style="font-size: 14px; margin-top: 0px;margin-bottom: 0px;font-weight: 400;color:#000000;">
                                        السلام عليكم ورحمة الله وبركات</h5>
                                </td>
                            </tr>
                            <tr>
                                <td style="padding:0px 20px;">
                                    <h5
                                        style="line-height:22px;font-size: 14px; margin-top: 0px;margin-bottom: 0px;font-weight: 400;color:#000000;">
                                        We are pleased to confirm that we have received your application for the primary
                                        round of the Holy Quran Competition.</h5>
                                </td>
                            </tr>
                            <tr>
                                <td style="padding:0px 20px;">
                                    <h5
                                        style="line-height:22px;font-size: 14px; margin-top: 0px;margin-bottom: 0px;font-weight: 400;color:#000000;">
                                        Your Application ID is:
                                        <b><?= $mailData['application']->application_id ?? 'Not Available' ?></b>
                                    </h5>
                                </td>
                            </tr>
                            <tr>
                                <td style="padding:0px 20px;">
                                    <h5
                                        style="line-height:22px;font-size: 14px; margin-top: 0px;margin-bottom: 0px;font-weight: 400;color:#000000;">
                                        Thank you for your interest and participation. We will review your submission
                                        and keep you updated on the status.</h5>
                                </td>
                            </tr>
                            <tr>
                                <td style="padding:0px 20px;">
                                    <h5
                                        style="font-size: 14px; margin-top: 0px;margin-bottom: 0px;font-weight: 400;color:#000000;">
                                        If you have any questions, feel free to contact us.</h5>
                                </td>
                            </tr>
                            <tr>
                                <td style="padding:0px 20px;">
                                    <h5
                                        style="padding-bottom:8px;font-size: 14px; margin-top: 0px;margin-bottom: 0px;font-weight: 400;color:#000000;">
                                        Best regards</h5>
                                    {{-- <h5
                                        style="font-size: 14px; margin-top: 0px;padding-top:0;padding-bottom:8px;margin-bottom: 0px;font-weight: 400;color:#000000;">
                                        Muhammed Musthafa</h5> --}}
                                    <h5
                                        style="font-size: 14px; margin-top: 0px;padding-top:0;padding-bottom:10px;margin-bottom: 0px;font-weight: 400;color:#000000;">
                                        Convener, AP Aslam Holy Qur'an Award 2024</h5>
                                </td>
                            </tr>
                            <tr>
                                <td style="padding:0px 20px;">
                                    <h5
                                        style="font-size: 14px; margin-top: 0px;margin-bottom: 0px;font-weight: 400;color:#000000;">
                                        Website: www.aslamquranaward.com</h5>
                                    <h5
                                        style="font-size: 14px; margin-top: 0px;margin-bottom: 0px;font-weight: 400;color:#000000;">
                                        Email: info@aslamquranaward.com | Phone: 9846310383</h5>
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
                                        <h5
                                            style="color:#000000;font-size: 8px;text-align:center; margin-top: 10px;margin-bottom: 0px;font-weight: 400;padding-bottom:0; background-color: transparent;">
                                            Copyright © 2024 Aslam Quran Award, All rights reserved.</h5>
                                    </a>
                                </td>
                            </tr>
                            <tr>
                                <td style="padding:0px 20px;">
                                    <h5
                                        style="padding-top:16px;font-size: 8px;color:#000000;text-align:center; margin-top: 0px;margin-bottom: 0px;font-weight: 400;color:#000000;background-color: transparent;">
                                        Follow us on</h5>
                                </td>
                            </tr>
                            <tr>
                                <td style="padding:0px 20px 30px 20px;text-align:center;">
                                    <a style="text-decoration: none;"
                                        href="https://www.instagram.com/apaslamquranaward/?utm_source=qr&igsh=YjZ4OXNieTRocXdy"
                                        target="_blank">
                                        <img src="{{ $message->embed(public_path('images/insta 2.png')) }}">
                                    </a>
                                    <a style="text-decoration: none;"
                                        href="https://youtube.com/@apaslamquranaward?si=hrE99ldkB2tm7fqP"
                                        target="_blank">
                                        <img src="{{ $message->embed(public_path('images/youtube 2.png')) }}">
                                    </a>
                                    <a style="text-decoration: none;" href="https://www.facebook.com/apaslamquranaward"
                                        target="_blank">
                                        <img src="{{ $message->embed(public_path('images/facebook 3.png')) }}">
                                    </a>
                                    <a style="text-decoration: none;" href="https://www.x.com/apaslamquranaward"
                                        target="_blank">
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