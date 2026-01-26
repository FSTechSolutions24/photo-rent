<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Gallery Access</title>
<style>
    /* Reset */
    * {
        box-sizing: border-box;
        margin: 0;
        padding: 0;
    }

    body, html {
        height: 100%;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    /* Split screen container */
    .split-screen {
        display: flex;
        height: 100vh;
        width: 100%;
    }

    /* Left side - Form */
    .left-side {
        flex: 1;
        display: flex;
        justify-content: center;
        align-items: center;
        background-color: #ffffff;
        position: relative;
        z-index: 2;
        padding: 40px;
        clip-path: polygon(0 0, 100% 0, 85% 100%, 0% 100%); /* Modern diagonal */
    }

    /* Right side - Background image */
    .right-side {
        flex: 2;
        background-image: url('/images/password_screen7.jpg');
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        position: relative;
        z-index: 1;
    }

    /* Optional gray overlay on image */
    .right-side::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(128, 128, 128, 0.5);
        z-index: 1;
    }

    /* Card inside left side */
    .gallery-card {
        width: 100%;
        max-width: 400px;
        text-align: center;
        z-index: 2; /* Above overlay */
    }

    .gallery-card input[type="password"] {
        width: 100%;
        padding: 14px 20px;
        border-radius: 20px;
        border: 1px solid #ccc;
        font-size: 16px;
        margin: 20px 0;
        outline: none;
    }

    .gallery-card input[type="password"]:focus {
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.2);
    }

    .gallery-card button {
        width: 100%;
        padding: 14px;
        background-color: #2563eb;
        color: #fff;
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

    .gallery-card h2 {
        font-size: 28px;
        margin-bottom: 25px;
        color: #333;
    }

    .gallery-card h2 .highlight {
        color: #2563eb;
    }

    .error-message {
        background-color: #fee2e2;
        color: #b91c1c;
        padding: 10px 15px;
        border-radius: 12px;
        margin-bottom: 20px;
        font-weight: 500;
    }

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

    /* Responsive tweak */
    @media (max-width: 768px) {
        .split-screen {
            flex-direction: column;
        }

        .left-side, .right-side {
            clip-path: none; /* Remove diagonal on small screens */
            flex: none;
            height: 50vh;
        }

        .right-side::before {
            background-color: rgba(128, 128, 128, 0.3); /* lighter overlay on mobile */
        }
    }

</style>
</head>
<body>

<div class="split-screen">
    
   
    <!-- Left side: form -->
    <div class="left-side">
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
    </div>

    <!-- Right side: background image -->
    <div class="right-side"></div>

    

</div>

</body>
</html>
