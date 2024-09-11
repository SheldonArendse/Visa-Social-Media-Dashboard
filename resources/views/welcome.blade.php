<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visa Social Media Dashboard</title>

    <!-- favicon in tab -->
    <link rel="icon" href="{{ asset('images/sami-logo(1).png') }}" type="image/png">
    <!-- style links -->
    <link rel="stylesheet" href="{{ asset('css/welcome-style.css') }}">
</head>

<body>
    <div class="container">
        <nav>
            <div class="logo">Visa Social Media Dashboard</div>
            <div class="nav-buttons">
                @auth
                <a href="{{ url('/dashboard') }}" class="nav-btn">Dashboard</a>
                <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                    @csrf
                    <button type="submit" class="nav-btn">Log out</button>
                </form>
                @else
                <a href="{{ route('login') }}" class="nav-btn">Log in</a>
                @if (Route::has('register'))
                <a href="{{ route('register') }}" class="nav-btn">Register</a>
                @endif
                @endauth
            </div>
        </nav>

        <div class="welcome-content">
            <h1>Embark on Your Journey with South African Migration International</h1>
            <p>Our innovative dashboard allows you to post articles across multiple social media platforms—Facebook, Twitter, Instagram, and LinkedIn—from a single, unified interface. Manage and share your visa-related insights efficiently and effectively.</p>
            <button class="btn">Start Your Journey</button>
        </div>

        <div class="visual-section">
            <div class="globe">
                <img src="{{ asset('images/sami-logo(1).png') }}" alt="SAMI Logo" class="globe-logo">
                <div class="meridians"></div>
                <div class="parallels"></div>
            </div>
            <svg class="paper-plane" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path d="M21.9977 2.00208C22.0015 1.99033 22 1.9785 21.9937 1.97225C21.9875 1.966 21.9756 1.96449 21.9639 1.96833L2.17747 9.79667C2.15543 9.80395 2.13977 9.82303 2.13715 9.84599C2.13453 9.86895 2.14534 9.89105 2.16559 9.90315L10.0122 14.9902C10.027 15.0001 10.045 15.0045 10.063 15.0026C10.081 15.0007 10.0979 14.9926 10.1105 14.98L21.8315 3.25903C21.8591 3.23143 21.8443 3.18677 21.8054 3.17954C21.7665 3.17231 21.7337 3.20716 21.7465 3.24498L15.2164 20.7992C15.2085 20.8211 15.2109 20.8456 15.2228 20.8652C15.2347 20.8847 15.2547 20.8972 15.2774 20.8991C15.3 20.901 15.3218 20.892 15.3365 20.8748L21.9977 2.00208Z" />
            </svg>
        </div>

    </div>
</body>

</html>