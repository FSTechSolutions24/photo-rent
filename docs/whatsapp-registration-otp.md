# WhatsApp registration OTP

Registration now creates a short-lived `pending_registrations` record. A real
`users` row is created only after the six-digit WhatsApp code is confirmed.

## Local testing (free)

Keep `WHATSAPP_OTP_DRIVER=log`. The OTP is written to `storage/logs/laravel.log`
and is also displayed on the verification page in the `local` and `testing`
environments. The OTP is never displayed by the production application.

## Meta Cloud API setup

1. Create a Meta Business app and add the WhatsApp product.
2. Register a WhatsApp Business phone number and create a system-user access
   token with `whatsapp_business_messaging` permission.
3. In WhatsApp Manager, create an **Authentication** template named
   `registration_otp`, language `en_US`, with the security recommendation,
   a 10-minute expiration footer, and a **Copy Code** OTP button.
4. Wait for the template to be approved.
5. Configure production:

```dotenv
WHATSAPP_OTP_DRIVER=meta
WHATSAPP_GRAPH_VERSION=v25.0
WHATSAPP_ACCESS_TOKEN=your-system-user-token
WHATSAPP_PHONE_NUMBER_ID=your-phone-number-id
WHATSAPP_OTP_TEMPLATE_NAME=registration_otp
WHATSAPP_OTP_TEMPLATE_LANGUAGE=en_US
```

6. Reload cached configuration after changing environment values:

```shell
php artisan config:clear
```

Use Meta's test phone number and approved test recipients while developing.
Production authentication template deliveries are billable by Meta; the direct
Cloud API integration avoids adding a third-party provider fee.

## Security behavior

- Egyptian mobile numbers are normalized to E.164 (`+201...`) and protected by
  a unique database index.
- OTP values are stored only as password hashes.
- Codes expire after 10 minutes and are replaced when resent.
- Verification is limited to five incorrect attempts.
- Resending is rate-limited and capped at five sends per registration.
- Expired pending registrations are pruned hourly.
