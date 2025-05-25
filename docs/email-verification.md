# Email Verification Setup - Laravel 12

## Overview

This document describes the email verification system implemented for the Desiree project. The system ensures that users verify their email addresses before accessing the application and sends copies of verification emails to administrators.

## Features

- ✅ Email verification required for new user registrations
- ✅ Custom email templates with Flux UI and Tailwind CSS styling
- ✅ Admin notifications for new user registrations
- ✅ Copy emails sent to administrators and contact email
- ✅ Responsive email design
- ✅ Professional verification flow
- ✅ Queue support for email sending

## Configuration

### Environment Variables

#### Local Development (.env)
```env
MAIL_MAILER=log
MAIL_FROM_ADDRESS="noreply@swingcuritiba.com.br"
MAIL_FROM_NAME="${APP_NAME}"
```

#### Production (.env.production)
```env
MAIL_MAILER=smtp
MAIL_SCHEME=tls
MAIL_HOST=smtp.kinghost.net
MAIL_PORT=587
MAIL_USERNAME=noreply@swingcuritiba.com.br
MAIL_PASSWORD=your_email_password_here
MAIL_FROM_ADDRESS="noreply@swingcuritiba.com.br"
MAIL_FROM_NAME="${APP_NAME}"
MAIL_ENCRYPTION=tls
```

### KingHost SMTP Configuration

For production deployment on KingHost, you'll need to:

1. Create an email account: `noreply@swingcuritiba.com.br`
2. Use the following SMTP settings:
   - **Host**: smtp.kinghost.net
   - **Port**: 587
   - **Encryption**: TLS
   - **Username**: noreply@swingcuritiba.com.br
   - **Password**: [Your email password]

## Implementation Details

### Files Modified/Created

1. **User Model** (`app/Models/User.php`)
   - Implements `MustVerifyEmail` contract
   - Custom `sendEmailVerificationNotification()` method

2. **Custom Notifications**
   - `app/Notifications/CustomVerifyEmail.php` - Main verification email
   - `app/Notifications/AdminEmailVerificationCopy.php` - Admin copy notification

3. **Email Templates**
   - `resources/views/emails/verify-email.blade.php` - User verification email
   - `resources/views/emails/admin-verification-copy.blade.php` - Admin copy email

4. **Registration Process** (`resources/views/livewire/auth/register.blade.php`)
   - Updated to redirect to verification page after registration

5. **Verification UI** (`resources/views/livewire/auth/verify-email.blade.php`)
   - Enhanced with better UX and success messages

6. **Verification Controller** (`app/Http/Controllers/Auth/VerifyEmailController.php`)
   - Added success/error messages

## Admin Notifications

The system automatically sends copies of verification emails to:

1. **All users with 'admin' role** in the database
2. **Contact email**: contato@swingcuritiba.com.br

Admin emails include:
- User registration details
- Verification link
- Registration timestamp
- User role and status

## Testing

### Test Command

Use the custom Artisan command to test email verification:

```bash
php artisan test:email-verification user@example.com
```

### Manual Testing

1. Register a new user account
2. Check that the user is redirected to verification page
3. Verify that emails are sent (check logs in development)
4. Click verification link to complete the process
5. Confirm admin emails are received

## User Flow

1. **Registration**: User fills out registration form
2. **Account Creation**: User account is created but not verified
3. **Auto-Login**: User is logged in to access verification page
4. **Email Sent**: Verification email is sent to user and admins
5. **Verification Page**: User sees verification instructions
6. **Email Verification**: User clicks link in email
7. **Success**: User is verified and redirected to dashboard

## Security Features

- Signed URLs with 60-minute expiration
- Throttled verification attempts (6 per minute)
- CSRF protection on all forms
- Secure email templates with no external resources

## Troubleshooting

### Common Issues

1. **Emails not sending in production**
   - Check SMTP credentials in .env.production
   - Verify email account exists on KingHost
   - Check Laravel logs for SMTP errors

2. **Admin emails not received**
   - Ensure admin users have role='admin' in database
   - Check contact email address is correct
   - Verify queue workers are running (if using queues)

3. **Verification links not working**
   - Check APP_URL is correct in environment
   - Verify signed route middleware is working
   - Check link hasn't expired (60 minutes)

### Logs

Check the following logs for debugging:
- `storage/logs/laravel.log` - General application logs
- Email driver logs (if using log driver)
- Queue logs (if using database queues)

## Deployment Notes

### KingHost Deployment

1. Update `.env.production` with correct SMTP settings
2. Create email account on KingHost control panel
3. Test email sending after deployment
4. Monitor logs for any SMTP connection issues

### Email Configuration

The system uses synchronous email sending for maximum compatibility:

```env
QUEUE_CONNECTION=sync
```

### Email Sending

✅ **Immediate Sending**: Emails are sent instantly when requested
✅ **No Queue Dependencies**: Works on any hosting provider
✅ **Simple Configuration**: No additional setup required
✅ **Reliable Delivery**: Direct SMTP connection ensures delivery

## Maintenance

### Regular Tasks

1. Monitor email delivery rates
2. Check admin notification delivery
3. Clean up old verification tokens (handled automatically)
4. Update email templates as needed

### Updates

When updating the system:
1. Test email functionality after updates
2. Verify admin notifications still work
3. Check email template rendering
4. Confirm SMTP settings are preserved
