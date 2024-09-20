<!DOCTYPE html>
<html>

<head>
    <title>ASLAM HOLY QUR'AN AWARD - Emailer</title>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Raleway:ital,wght@0,100..900;1,100..900&display=swap');

        body {
            font-family: "Raleway", sans-serif;
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
                    Dear Hafiz <?= $mailData['application']->full_name ?? 'Applicant' ?>,
                </h5>
            </td>
        </tr>

        <tr>
            <td style="padding: 0px 80px;">
                <h5 style="font-size: 14px; margin: 0; font-weight: 400; color: #000000;">
                    ‏اَلسَلامُ عَلَيْكُم وَرَحْمَةُ اَللهِ وَبَرَكاتُهُ‎,
                </h5>
            </td>
        </tr>

        <tr>
            <td style="padding: 0px 80px;">
                <h5 style="font-size: 14px; margin: 0; line-height: 22px; font-weight: 400; color: #000000;">
                    We are pleased to inform you that your application for the AP Aslam Holy Qur'an Award 2024 has been
                    successfully reviewed and approved. Congratulations!
                </h5>
            </td>
        </tr>
        <tr>
            <td style="padding: 0px 80px;">
                <h5 style="font-size: 14px; margin: 0; line-height: 22px; font-weight: 400; color: #000000;">
                    As you know, the competition carries an impressive prize pool of over 20 lakhs rupees, with
                    significant rewards for the winners. We encourage you to continue preparing with dedication.
                </h5>
            </td>
        </tr>
        <tr>
            <td style="padding: 0px 80px;">
                <h5 style="font-size: 14px; margin: 0; line-height: 22px; font-weight: 400; color: #000000;">
                    The competition will cover Thajweed, Qirath, Hifz, and also comprehension of the first five and last
                    five Juz of the Qur'an. Preparing across all these areas will help you perform your best.
                </h5>
            </td>
        </tr>
        <tr>
            <td style="padding: 0px 80px;">
                <h5 style="font-size: 14px; margin: 0; line-height: 22px; font-weight: 400; color: #000000;">
                    The area-wise competitions are scheduled to begin in the first week of November, and successful
                    participants will move on to the final competition on December 24, 2024, in Malappuram.
                </h5>
            </td>
        </tr>
        <tr>
            <td style="padding: 0px 80px;">
                <h5 style="font-size: 14px; margin: 0; line-height: 22px; font-weight: 400; color: #000000;">
                    Further details about your competition center and exact dates will be communicated via email. Please
                    keep an eye on your inbox for updates and instructions.
                </h5>
            </td>
        </tr>
        <tr>
            <td style="padding: 0px 80px;">
                <h5 style="font-size: 14px; margin: 0; line-height: 22px; font-weight: 400; color: #000000;">
                    We wish you the very best in your preparation. May Allah guide you in your efforts.
                </h5>
            </td>
        </tr>
        <tr>
            <td style="padding: 0px 80px;">
                <h5 style="font-size: 14px; margin: 0; line-height: 22px; font-weight: 400; color: #000000;">
                    If you have any questions, feel free to reach out.
                </h5>
            </td>
        </tr>

        <tr>
            <td style="padding: 0px 80px;">
                <h5
                    style="font-size: 14px; margin: 0; padding-top: 0; padding-bottom: 10px; font-weight: 400; color: #000000;">
                    Best regards,<br>
                    Coordinator<br>
                    AP Aslam Holy Qur'an Award Committee<br>
                    9846 310 383 | info@aslamquranaward.com
                </h5>
            </td>
        </tr>
        <tr style="padding:40px 40px">
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
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>

</html>