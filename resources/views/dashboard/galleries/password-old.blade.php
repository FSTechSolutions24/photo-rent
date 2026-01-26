<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Gallery Access</title>
<style>
    /* Background */
    body {
        margin: 0;
        padding: 0;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 100vh;

        /* Background image with gray overlay */
        background-image: 
            linear-gradient(rgba(128, 128, 128, 0.5), rgba(128, 128, 128, 0.5)), /* Gray overlay */
            url('/images/password_screen2.jpg'); /* Your image */
        background-repeat: no-repeat;
        background-position: center center;
        background-size: cover;
        background-attachment: fixed;
    }



    /* Card */
    .gallery-card {
        width: 100%;
        max-width: 400px;
        background: #ffffff;
        padding: 40px 30px;
        border-radius: 30px; /* Card radius */
        box-shadow: 0 20px 40px rgba(0,0,0,0.1);
        text-align: center;
    }

    .gallery-card input[type="password"] {
        width: 100%;
        padding: 14px 20px;
        border-radius: 20px; /* Slightly smaller than card to fit nicely */
        border: 1px solid #ccc;
        font-size: 16px;
        margin-top: 20px;
        margin-bottom: 20px;
        outline: none;
        box-sizing: border-box; /* Important so padding doesn’t break width */
    }

    /* Heading */
    .gallery-card h2 {
        font-size: 28px;
        margin-bottom: 25px;
        color: #333333;
    }

    .gallery-card h2 .highlight {
        color: #2563eb; /* Custom blue */
    }

    /* Error message */
    .error-message {
        background-color: #fee2e2;
        color: #b91c1c;
        padding: 10px 15px;
        border-radius: 12px;
        margin-bottom: 20px;
        font-weight: 500;
    }


    .gallery-card input[type="password"]:focus {
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.2);
    }

    /* Button */
    .gallery-card button {
        width: 100%;
        padding: 14px;
        background-color: #2563eb;
        color: #ffffff;
        font-size: 16px;
        font-weight: 600;
        border: none;
        border-radius: 20px;
        cursor: pointer;
        transition: background-color 0.3s, transform 0.2s;
    }

    .gallery-card button:hover {
        background-color: #1d4ed8;
        transform: translateY(-2px);
    }

    /* Footer text */
    .gallery-card p {
        margin-top: 20px;
        font-size: 14px;
        color: #6b7280;
    }

    .gallery-card p a {
        color: #2563eb;
        text-decoration: none;
        font-weight: 500;
        transition: text-decoration 0.3s;
    }

    .gallery-card p a:hover {
        text-decoration: underline;
    }
</style>
</head>
<body>

<div class="gallery-card">
    <h2>🔒 Access Gallery: <span class="highlight">{{ $gallery->title }}</span></h2>

    @if ($errors->any())
        <div class="error-message">
            {{ $errors->first('password') }}
        </div>
    @endif

    <form method="POST" action="{{ route('gallery.show', [$photographer->subdomain, $gallery->slug]) }}">
        @csrf
        <input type="password" name="password" placeholder="Enter gallery password" required>
        <button type="submit">View Gallery</button>
    </form>

</div>

</body>
</html>
