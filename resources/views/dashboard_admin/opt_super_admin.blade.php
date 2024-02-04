<!-- resources/views/emails/otp.blade.php -->

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vaify Your Email</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f6f6f6; padding: 20px;">

    <table style="max-width: 600px; margin: 0 auto; background-color: #ffffff; border-radius: 10px; overflow: hidden;">
        <tr>
            <td style="padding: 30px; text-align: center; background-color: #f3f3f3;">
                <h1 style="color: #333333; margin-bottom: 20px;">One-Time Password</h1>
                <p style="color: #666666; font-size: 16px; line-height: 1.5;">Dear {{ $name }},</p>
                <p style="color: #666666; font-size: 16px; line-height: 1.5;">Please use the following OTP to verify your account:</p>
                <div style="background-color: #eeeeee; padding: 15px; font-size: 24px; margin-bottom: 20px;">
                    {{ $otp }}
                </div>
                <p style="color: #666666; font-size: 16px; line-height: 1.5;">This OTP is valid for {{ $expirationTime }} minutes.</p>
            </td>
        </tr>
    </table>

</body>
</html>
