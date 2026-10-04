<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Reset Your Password</title>
</head>
<body style="margin: 0; padding: 24px 0; background-color: #EEF2F7; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; color: #10233F;">
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="max-width: 560px; margin: 0 auto; background-color: #ffffff; border-radius: 18px; border: 1px solid #D9E1EC; overflow: hidden; box-shadow: 0 4px 12px rgba(16,35,63,0.06);">
        <!-- Header -->
        <tr>
            <td style="background-color: #10233F; padding: 28px 32px; text-align: center;">
                <div style="display: inline-block; background-color: #F2B31B; color: #10233F; font-weight: 800; font-size: 20px; width: 36px; height: 36px; line-height: 36px; border-radius: 10px; margin-bottom: 8px;">?</div>
                <h1 style="color: #ffffff; margin: 0; font-size: 22px; font-weight: 800; letter-spacing: -0.02em;">Friday Night Quiz</h1>
            </td>
        </tr>
        <!-- Content -->
        <tr>
            <td style="padding: 36px 32px;">
                <h2 style="margin: 0 0 16px; font-size: 20px; font-weight: 700; color: #10233F;">Hello {{ $user->name }},</h2>
                <p style="margin: 0 0 20px; font-size: 15px; line-height: 1.6; color: #5B6B82;">
                    You are receiving this email because we received a password reset request for your Friday Night Quiz participant account.
                </p>
                <div style="text-align: center; margin: 30px 0;">
                    <a href="{{ $resetUrl }}" style="display: inline-block; background-color: #F2B31B; color: #10233F; padding: 14px 32px; font-size: 16px; font-weight: 700; text-decoration: none; border-radius: 12px; box-shadow: 0 2px 8px rgba(242,179,27,0.3);">Reset Password</a>
                </div>
                <p style="margin: 0 0 12px; font-size: 14px; line-height: 1.5; color: #5B6B82;">
                    This password reset link will expire in <strong>60 minutes</strong>.
                </p>
                <p style="margin: 0 0 24px; font-size: 14px; line-height: 1.5; color: #5B6B82;">
                    If you did not request a password reset, no further action is required; your account remains completely secure.
                </p>
                <hr style="border: none; border-top: 1px solid #D9E1EC; margin: 24px 0;">
                <p style="margin: 0; font-size: 12px; line-height: 1.5; color: #9BADC5;">
                    If you're having trouble clicking the "Reset Password" button, copy and paste the URL below into your web browser:<br>
                    <a href="{{ $resetUrl }}" style="color: #10233F; word-break: break-all;">{{ $resetUrl }}</a>
                </p>
            </td>
        </tr>
        <!-- Footer -->
        <tr>
            <td style="background-color: #F8FAFC; padding: 20px 32px; text-align: center; font-size: 13px; color: #5B6B82; border-top: 1px solid #D9E1EC;">
                Friday Night Quiz · Quran Competition Online Award
            </td>
        </tr>
    </table>
</body>
</html>
