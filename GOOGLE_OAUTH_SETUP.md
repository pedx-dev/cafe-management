# Google OAuth Setup Guide

## Issue
You're getting: `Error 401: invalid_client - The OAuth client was not found`

This happens because the Google OAuth credentials in your `.env` file are placeholders and haven't been set up.

## Steps to Fix Google OAuth

### 1. Create Google OAuth Credentials

1. Go to [Google Cloud Console](https://console.cloud.google.com/)
2. Sign in with your Google account
3. Create a new project:
   - Click "Select a Project" → "New Project"
   - Name: "Café Management"
   - Click "Create"

4. Enable the Google+ API:
   - In the search bar, type "Google+ API"
   - Click on "Google+ API"
   - Click "Enable"

5. Create OAuth Credentials:
   - Click "Create Credentials" → "OAuth Client ID"
   - If prompted, first create an OAuth consent screen:
     - Choose "External" user type
     - Fill in the app name: "Café Management"
     - Add your email
     - Skip optional fields and save
   - Return to Create Credentials
   - Select "Web Application"
   - Name: "Café Management Web"
   - Add Authorized JavaScript origins:
     - `http://localhost:8000`
     - `http://127.0.0.1:8000`
   - Add Authorized redirect URIs:
     - `http://localhost:8000/auth/google/callback`
     - `http://127.0.0.1:8000/auth/google/callback`
   - Click "Create"

6. Copy your credentials:
   - You'll see your Client ID and Client Secret
   - Keep this window open

### 2. Update Your .env File

1. Open `.env` file in the root directory
2. Find these lines:
   ```
   GOOGLE_CLIENT_ID=your-google-client-id
   GOOGLE_CLIENT_SECRET=your-google-client-secret
   GOOGLE_REDIRECT_URI=${APP_URL}/auth/google/callback
   ```

3. Replace with your actual credentials:
   ```
   GOOGLE_CLIENT_ID=YOUR_CLIENT_ID_HERE.apps.googleusercontent.com
   GOOGLE_CLIENT_SECRET=YOUR_CLIENT_SECRET_HERE
   GOOGLE_REDIRECT_URI=http://127.0.0.1:8000/auth/google/callback
   ```

### 3. Clear Laravel Cache

Run in terminal:
```bash
php artisan config:clear
php artisan cache:clear
```

### 4. Test Google Login

1. Go to http://127.0.0.1:8000/register
2. Click "Sign up with Google"
3. You should now be able to authenticate

## Email Verification Issue

After regular registration, users should see the email verification page. The verification is being sent to the log file (`storage/logs/laravel.log`).

### To verify emails were sent:

1. Register with email
2. Check `storage/logs/laravel.log`
3. You'll see the full verification email content including the verification link
4. The verification link format: `http://127.0.0.1:8000/email/verify/{id}/{hash}`

### For Production:

When deploying to production, change `MAIL_MAILER` in `.env` from `log` to an actual email service:
- Mailtrap (sandbox testing)
- SendGrid
- Mailgun
- AWS SES
- Or your own SMTP server

Example for Mailtrap:
```
MAIL_MAILER=smtp
MAIL_HOST=sandbox.smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_mailtrap_username
MAIL_PASSWORD=your_mailtrap_password
```

## Common Issues & Solutions

### Issue: "The redirect_uri does not match"
- Make sure your callback URL in Google Cloud Console exactly matches the one in your app
- Check for trailing slashes and protocol (http vs https)

### Issue: "Access blocked"
- Check if you're using the right email address for Google sign-up
- Make sure Google+ API is enabled in Cloud Console

### Issue: Email verification not appearing
- Check `storage/logs/laravel.log` to see the verification link
- Users must click the link to verify their account
- You can test by copying the verification link from the log file

