<!DOCTYPE html>
<html lang="ml">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="format-detection" content="telephone=no, date=no, address=no, email=no">
<meta name="color-scheme" content="light">
<meta name="supported-color-schemes" content="light">
<title>പാസ്‌വേഡ് റീസെറ്റ് ചെയ്യുക - തിബ്യാൻ</title>
<style>
    /* Resets & Client Normalization */
    body, table, td, a { -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; }
    table, td { mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-collapse: collapse; }
    img { -ms-interpolation-mode: bicubic; border: 0; outline: none; text-decoration: none; }
    * { box-sizing: border-box; }

    /* Responsive Mobile Styles */
    @media only screen and (max-width: 600px) {
        body.email-outer-body {
            padding: 14px 8px !important;
        }
        table.email-card {
            width: 100% !important;
            max-width: 100% !important;
            border-radius: 14px !important;
        }
        td.email-header {
            padding: 18px 16px !important;
        }
        td.email-body {
            padding: 24px 18px 20px !important;
        }
        .email-greeting {
            font-size: 19px !important;
            margin-bottom: 12px !important;
        }
        .email-intro {
            font-size: 14.5px !important;
            line-height: 1.6 !important;
            margin-bottom: 18px !important;
        }
        .btn-action-container {
            margin: 22px 0 !important;
            width: 100% !important;
        }
        .btn-action {
            display: block !important;
            width: 100% !important;
            padding: 14px 18px !important;
            font-size: 15.5px !important;
            box-sizing: border-box !important;
            text-align: center !important;
        }
        .info-box {
            padding: 14px 16px !important;
            margin: 18px 0 !important;
        }
        .info-box-text {
            font-size: 13px !important;
            line-height: 1.55 !important;
        }
        td.email-footer {
            padding: 16px !important;
            font-size: 12px !important;
        }
    }
</style>
</head>
<body class="email-outer-body" style="margin: 0; padding: 28px 12px; background-color: #F7F0E3; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif; color: #17130F; -webkit-font-smoothing: antialiased; width: 100% !important;">
    <!-- Centering Wrapper -->
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="width: 100%; margin: 0 auto; border-collapse: collapse;">
        <tr>
            <td align="center" style="padding: 0;">
                <table role="presentation" class="email-card" width="100%" cellpadding="0" cellspacing="0" border="0" style="width: 100%; max-width: 580px; margin: 0 auto; background-color: #FFFFFF; border-radius: 20px; border: 1px solid #D9C8AC; overflow: hidden; box-shadow: 0 8px 24px rgba(89, 51, 32, 0.08); border-collapse: separate;">
                    <!-- Top Header -->
                    <tr>
                        <td class="email-header" style="background-color: #F6EEE1; padding: 22px 28px; text-align: center; border-bottom: 1px solid rgba(217, 200, 172, 0.6);">
                            <table role="presentation" cellpadding="0" cellspacing="0" border="0" align="center" style="margin: 0 auto;">
                                <tr>
                                    <td style="vertical-align: middle; padding-right: 12px;">
                                        <img src="{{ url('/images/quiz/logo_icon.svg') }}" alt="Tibyan" width="28" height="32" style="display: block; border: 0; outline: none; margin: 0 auto;" />
                                    </td>
                                    <td style="vertical-align: middle; border-left: 1px solid #CFCFCF; padding-left: 12px; padding-right: 4px;">
                                        <span style="font-family: 'Amiri', 'Traditional Arabic', serif; font-size: 26px; font-weight: bold; color: #593320; line-height: 1; display: block;">تبيان</span>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- Main Card Body -->
                    <tr>
                        <td class="email-body" style="padding: 34px 28px 28px; text-align: left;">
                            <h2 class="email-greeting" style="margin: 0 0 14px; font-size: 20px; font-weight: 700; color: #17130F;">
                                പ്രിയ {{ $user->name }},
                            </h2>

                            <p class="email-intro" style="margin: 0 0 20px; font-size: 15px; line-height: 1.6; color: #474747;">
                                നിങ്ങളുടെ തിബ്യാൻ ക്വിസ് അക്കൗണ്ട് പാസ്‌വേഡ് റീസെറ്റ് ചെയ്യാനുള്ള അപേക്ഷ ലഭിച്ചിട്ടുണ്ട്. താഴെ പറയുന്ന ബട്ടണിൽ ക്ലിക്ക് ചെയ്ത് നിങ്ങൾക്ക് പുതിയ പാസ്‌വേഡ് സെറ്റ് ചെയ്യാവുന്നതാണ്:
                            </p>

                            <!-- Action Button -->
                            <div class="btn-action-container" style="text-align: center; margin: 28px 0;">
                                <a href="{{ $resetUrl }}" class="btn-action" style="display: inline-block; background-color: #F6AC38; color: #593320; font-size: 16px; font-weight: 700; text-decoration: none; padding: 14px 34px; border-radius: 9999px; box-shadow: 0 4px 14px rgba(246, 172, 56, 0.35); text-align: center;">
                                    പാസ്‌വേഡ് റീസെറ്റ് ചെയ്യുക (Reset Password)
                                </a>
                            </div>

                            <div class="info-box" style="background-color: #FBF7EE; border: 1px solid #D9C8AC; border-radius: 10px; padding: 14px 18px; margin: 22px 0;">
                                <p class="info-box-text" style="margin: 0; font-size: 13px; line-height: 1.5; color: #6F5C44;">
                                    ഈ ലിങ്കിന്റെ കാലാവധി <strong>60 മിനിറ്റ്</strong> മാത്രമായിരിക്കും.<br>
                                    നിങ്ങൾ പാസ്‌വേഡ് റീസെറ്റ് ചെയ്യാൻ ആവശ്യപ്പെട്ടിട്ടില്ലെങ്കിൽ, ഈ ഇമെയിൽ അവഗണിക്കുക. നിങ്ങളുടെ അക്കൗണ്ട് പൂർണ്ണമായും സുരക്ഷിതമാണ്.
                                </p>
                            </div>

                            <hr style="border: none; border-top: 1px solid #D9C8AC; margin: 24px 0;">

                            <p style="margin: 0; font-size: 12px; line-height: 1.5; color: #6F5C44;">
                                ബട്ടൺ ക്ലിക്ക് ചെയ്യാൻ ബുദ്ധിമുട്ടുണ്ടെങ്കിൽ താഴെ കാണുന്ന ലിങ്ക് കോപ്പി ചെയ്ത് ബ്രൗസറിൽ പേസ്റ്റ് ചെയ്യുക:<br>
                                <a href="{{ $resetUrl }}" style="color: #854D3A; word-break: break-all;">{{ $resetUrl }}</a>
                            </p>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td class="email-footer" style="background-color: #854D3A; padding: 18px 24px; text-align: center; font-size: 13px; color: #F7F0E3;">
                            © 2026 AP Aslam Holy Qur’an Award. All rights reserved.
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
