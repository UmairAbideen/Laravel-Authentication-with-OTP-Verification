<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify OTP</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-5">
        <h2>Verify OTP</h2>

        <!-- Success message -->
        @if (session('success'))
            <div class="alert alert-success" id="otp-message">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger">{{ $errors->first() }}</div>
        @endif

        <form action="{{ route('verify.otp') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="otp" class="form-label">Enter OTP</label>
                <input type="text" class="form-control" id="otp" name="otp" required>
            </div>
            <button type="submit" class="btn btn-primary">Verify OTP</button>
        </form>

        <form action="{{ route('resend.otp') }}" method="POST" class="mt-3">
            @csrf
            <input type="hidden" name="email" value="{{ session('email') }}">
            <button type="submit" class="btn btn-secondary">Resend OTP</button>
        </form>
    </div>

    <script>
        // Disappear the message after 5 seconds
        setTimeout(function() {
            let message = document.getElementById('otp-message');
            if (message) {
                message.style.display = 'none';
            }
        }, 5000); // 5000ms = 5 seconds
    </script>
</body>

</html>
