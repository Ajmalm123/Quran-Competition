<!DOCTYPE html>
<html lang="ml">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Friday Night Quiz Login Credentials</title>
</head>
<body style="margin: 0; padding: 28px 12px; background-color: #F7F0E3; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif; color: #17130F; -webkit-font-smoothing: antialiased;">
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="max-width: 580px; margin: 0 auto; background-color: #FFFFFF; border-radius: 20px; border: 1px solid #D9C8AC; overflow: hidden; box-shadow: 0 8px 24px rgba(89, 51, 32, 0.08);">
        <!-- Top Website Header -->
        <tr>
            <td style="background-color: #F6EEE1; padding: 22px 28px; text-align: center; border-bottom: 1px solid rgba(217, 200, 172, 0.6);">
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
                <div style="margin-top: 6px; font-size: 13px; font-weight: 700; color: #854D3A; letter-spacing: 0.5px; text-transform: uppercase;">
                    Friday Night Quiz
                </div>
            </td>
        </tr>

        <!-- Main Card Body -->
        <tr>
            <td style="padding: 34px 28px 28px;">
                <h2 style="margin: 0 0 14px; font-size: 20px; font-weight: 700; color: #17130F;">
                    പ്രിയ {{ $user->name }},
                </h2>

                <p style="margin: 0 0 20px; font-size: 15px; line-height: 1.6; color: #474747;">
                    @if($reason === 'forgot_password')
                        നിങ്ങളുടെ Friday Night Quiz അക്കൗണ്ടിനായി പുതിയ പാസ്‌വേഡ് ജനറേറ്റ് ചെയ്തിരിക്കുന്നു. താഴെ പറയുന്ന വിവരങ്ങൾ ഉപയോഗിച്ച് നിങ്ങൾക്ക് ഉടൻ തന്നെ ക്വിസിൽ ലോഗിൻ ചെയ്യാവുന്നതാണ്:
                    @elseif($reason === 'admin_update')
                        നിങ്ങളുടെ Friday Night Quiz അക്കൗണ്ടിന്റെ ലോഗിൻ വിവരങ്ങൾ അഡ്മിനിസ്ട്രേറ്റർ അപ്‌ഡേറ്റ് ചെയ്തിരിക്കുന്നു. താഴെ പറയുന്ന പുതിയ വിവരങ്ങൾ ഉപയോഗിച്ച് നിങ്ങൾക്ക് ലോഗിൻ ചെയ്യാവുന്നതാണ്:
                    @else
                        Friday Night Quiz-ൽ ലോഗിൻ ചെയ്യാനുള്ള നിങ്ങളുടെ പാസ്‌വേഡ് വിവരങ്ങൾ താഴെ നൽകുന്നു:
                    @endif
                </p>

                <!-- Credentials Card -->
                <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color: #FBF7EE; border: 1px solid #D9C8AC; border-radius: 14px; margin: 20px 0 26px;">
                    <tr>
                        <td style="padding: 20px 22px;">
                            @if(!empty($user->whatsapp_number))
                            <div style="margin-bottom: 14px;">
                                <div style="font-size: 12px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; color: #6F5C44; margin-bottom: 3px;">
                                    📱 രജിസ്റ്റർ ചെയ്ത മൊബൈൽ (Mobile Number)
                                </div>
                                <div style="font-size: 16px; font-weight: 700; color: #17130F;">
                                    {{ $user->whatsapp_number }}
                                </div>
                            </div>
                            @endif

                            @if(!empty($user->email))
                            <div style="margin-bottom: 16px;">
                                <div style="font-size: 12px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; color: #6F5C44; margin-bottom: 3px;">
                                    ✉️ ഇമെയിൽ (Email Address)
                                </div>
                                <div style="font-size: 15px; font-weight: 600; color: #17130F;">
                                    {{ $user->email }}
                                </div>
                            </div>
                            @endif

                            <div>
                                <div style="font-size: 12px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; color: #854D3A; margin-bottom: 6px;">
                                    🔑 ലോഗിൻ പാസ്‌വേഡ് (Login Password)
                                </div>
                                <div style="background-color: #FFFFFF; border: 1.5px dashed #BDA585; border-radius: 10px; padding: 12px 18px; display: inline-block;">
                                    <span style="font-family: 'Courier New', Courier, monospace; font-size: 24px; font-weight: 800; letter-spacing: 2.5px; color: #593320;">
                                        {{ $plainPassword }}
                                    </span>
                                </div>
                            </div>
                        </td>
                    </tr>
                </table>

                <!-- Action Button -->
                <div style="text-align: center; margin: 28px 0 20px;">
                    <a href="{{ $loginUrl }}" style="display: inline-block; background-color: #F6AC38; color: #593320; font-size: 16px; font-weight: 700; text-decoration: none; padding: 14px 34px; border-radius: 9999px; box-shadow: 0 4px 14px rgba(246, 172, 56, 0.35); text-align: center;">
                        ലോഗിൻ ചെയ്ത് ക്വിസ് ആരംഭിക്കുക
                    </a>
                </div>

                <div style="background-color: #F7F0E3; border-radius: 10px; padding: 12px 16px; margin-top: 24px;">
                    <p style="margin: 0; font-size: 13px; line-height: 1.5; color: #6F5C44;">
                        💡 <strong>ശ്രദ്ധിക്കുക:</strong> ലോഗിൻ ചെയ്ത ശേഷം നിങ്ങൾക്ക് ആവശ്യമെങ്കിൽ അക്കൗണ്ടിൽ നിന്ന് പാസ്‌വേഡ് മാറ്റാവുന്നതാണ്. സുരക്ഷിതത്വത്തിനായി ഈ പാസ്‌വേഡ് മറ്റാരുമായും പങ്കുവെക്കാതിരിക്കുക.
                    </p>
                </div>
            </td>
        </tr>

        <!-- Footer -->
        <tr>
            <td style="background-color: #854D3A; padding: 18px 24px; text-align: center; font-size: 13px; color: #F7F0E3;">
                © 2026 AP Aslam Holy Qur’an Award. All rights reserved.
            </td>
        </tr>
    </table>
</body>
</html>
