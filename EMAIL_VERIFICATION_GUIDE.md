# Email Verification Testing Guide

## Current Email Setup

Your application is currently using the **LOG driver** for emails. This means:

✅ **What happens:**
- All verification emails are written to `storage/logs/laravel.log`
- You can view the verification link in the log file
- Perfect for development/testing

❌ **What doesn't happen:**
- Emails are NOT sent to your actual email inbox
- You need to manually extract and click verification links from logs

---

## How to Test Email Verification

### Step 1: Register a New Account
1. Go to http://127.0.0.1:8000/register
2. Fill in the registration form with any test data
3. Password must be at least 8 characters
4. Click "Create Account"
5. You should be redirected to the email verification page

### Step 2: Find Your Verification Link
1. Open `storage/logs/laravel.log`
2. Look for the most recent entry (bottom of file)
3. Find the text that says something like:
   ```
   "Verification link:" or "url" or "email/verify"
   ```
4. The verification URL looks like:
   ```
   http://127.0.0.1:8000/email/verify/2/abcd1234...
   ```

### Step 3: Verify Your Email
1. Copy the verification link from the log file
2. Paste it in your browser address bar
3. Press Enter
4. You should see a success message
5. You'll now have access to the full application

---

## Using Laravel Tinker to Generate a Verification Link

If you can't find the link in the log, use this command:

```bash
php artisan tinker
```

Then run:
```php
$user = App\Models\User::where('email', 'your-email@example.com')->first();
$user->email_verified_at = null;
$user->save();
// Now access the /email/verify route with the user's ID and a hash
```

---

## For Production: Use a Real Email Service

When you're ready to deploy, update `.env` to use real email:

### Option 1: Mailtrap (Sandbox - Free)
```env
MAIL_MAILER=smtp
MAIL_HOST=sandbox.smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=YOUR_MAILTRAP_USERNAME
MAIL_PASSWORD=YOUR_MAILTRAP_PASSWORD
MAIL_ENCRYPTION=tls
```

[Get Mailtrap account](https://mailtrap.io/)

### Option 2: SendGrid (Production - Free tier)
```env
MAIL_MAILER=sendgrid
SENDGRID_API_KEY=YOUR_SENDGRID_KEY
```

### Option 3: Gmail SMTP
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-specific-password
MAIL_ENCRYPTION=tls
```

⚠️ **For Gmail:** You need to generate an app-specific password, not your regular password!

---

## Verifying Emails Are Being Sent

Check `storage/logs/laravel.log` - you'll see entries like:

```
[2026-01-29 14:25:38] local.DEBUG: Mailing mailable: App\Mail\VerifyEmail {"queue":false}
[2026-01-29 14:25:38] local.DEBUG: Message sent {"id":"<verification-link>","from":["noreply@cafemanagement.com"],"to":["user@example.com"]}
```

The complete email content including the verification link will also be visible.

---

## Troubleshooting

### "No recent verification email in logs"
- Make sure you're looking at the latest entries in the log file
- The log might be large - scroll to the bottom
- Try registering again and check immediately after

### "Verification link doesn't work"
- Make sure your APP_URL in .env matches your browser URL:
  - Browser: http://127.0.0.1:8000 → APP_URL=http://127.0.0.1:8000
  - Or Browser: http://localhost:8000 → APP_URL=http://localhost:8000

### "Can't find verification email anywhere"
- Check if MAIL_MAILER is set to "log" in .env
- Run: `php artisan config:clear`
- Register again and check logs

