<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Your Gallery Is Ready</title>
</head>

<body style="margin:0; padding:0; background:#f4f4f4; font-family:Arial, Helvetica, sans-serif;">

<table width="100%" cellpadding="0" cellspacing="0" style="background:#f4f4f4; padding:40px 0;">
    <tr>
        <td align="center">

            <table width="600" cellpadding="0" cellspacing="0"
                   style="background:#ffffff; border-radius:8px; overflow:hidden;">

                <!-- Header -->
                <tr>
                    <td style="padding:30px; text-align:center; background:#111111; color:#ffffff;">
                        <h1 style="margin:0; font-size:28px;">
                            Your Gallery Is Ready
                        </h1>
                    </td>
                </tr>

                <!-- Content -->
                <tr>
                    <td style="padding:40px; color:#333333;">

                        <h2 style="margin-top:0;">
                            Your photos are ready!
                        </h2>

                        <p style="font-size:16px; line-height:1.6;">
                            Your gallery has been prepared and is now available
                            to view and download.
                        </p>

                        <p style="text-align:center; margin:35px 0;">

                            <a href="{{ $galleryUrl }}"
                               style="
                                   display:inline-block;
                                   padding:14px 30px;
                                   background:#111111;
                                   color:#ffffff;
                                   text-decoration:none;
                                   border-radius:5px;
                                   font-size:16px;
                               ">
                                View Your Gallery
                            </a>

                        </p>

                        <p style="font-size:14px; color:#777777; line-height:1.6;">
                            If the button above doesn't work, you can copy and paste
                            the following link into your browser:
                        </p>

                        <p style="font-size:14px; word-break:break-all;">
                            <a href="{{ $galleryUrl }}">
                                {{ $galleryUrl }}
                            </a>
                        </p>

                    </td>
                </tr>

                <!-- Footer -->
                <tr>
                    <td style="padding:20px 40px; background:#f8f8f8; text-align:center; color:#888888; font-size:12px;">

                        <p style="margin:0;">
                            Thank you for using our gallery service.
                        </p>

                    </td>
                </tr>

            </table>

        </td>
    </tr>
</table>

</body>
</html>