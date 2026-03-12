<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Email Verification</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .container {
            background-color: #ffffff;
            border-radius: 10px;
            padding: 40px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .header h1 {
            color: #6f4e37;
            margin: 0;
            font-size: 28px;
        }
        .header p {
            color: #888;
            margin: 5px 0 0;
        }
        .code-box {
            background: linear-gradient(135deg, #6f4e37 0%, #8b6914 100%);
            border-radius: 10px;
            padding: 30px;
            text-align: center;
            margin: 30px 0;
        }
        .code {
            font-size: 42px;
            font-weight: bold;
            letter-spacing: 10px;
            color: #ffffff;
            font-family: 'Courier New', monospace;
        }
        .message {
            text-align: center;
            color: #666;
            margin-bottom: 20px;
        }
        .warning {
            background-color: #fff3cd;
            border: 1px solid #ffc107;
            border-radius: 5px;
            padding: 15px;
            margin-top: 20px;
            font-size: 14px;
            color: #856404;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #eee;
            color: #888;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>☕ Café Delight</h1>
            <p>{{ $purpose === 'password_reset' ? 'Password Reset' : 'Email Verification' }}</p>
        </div>
        
        <p class="message">
            Hello <strong>{{ $userName }}</strong>,<br>
            @if($purpose === 'password_reset')
                We received a request to reset your password. Please use the code below to continue:
            @else
                Thank you for registering! Please use the verification code below to complete your registration:
            @endif
        </p>
        
        <div class="code-box">
            <div class="code">{{ $code }}</div>
        </div>
        
        <p class="message">
            @if($purpose === 'password_reset')
                Enter this code on the reset page to set a new password.
            @else
                Enter this code on the verification page to activate your account.
            @endif
        </p>
        
        <div class="warning">
            <strong>⏰ This code expires in 10 minutes.</strong><br>
            @if($purpose === 'password_reset')
                If you did not request a password reset, please ignore this email.
            @else
                If you didn't create an account with Café Delight, please ignore this email.
            @endif
        </div>
        
        <div class="footer">
            <p>© {{ date('Y') }} Café Delight. All rights reserved.</p>
            <p>This is an automated message, please do not reply.</p>
        </div>
    </div>
</body>
</html>
