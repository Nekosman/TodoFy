<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    @vite('resources/css/app.css')
    <link href="https://fonts.googleapis.com/css2?family=League+Spartan:wght@100..900&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'League Spartan', sans-serif;
            background: #F8F9FA;
        }
    </style>
</head>
<body class="flex items-center justify-center min-h-screen bg-gray-100">
    <div class="w-full max-w-md p-6 bg-white rounded-lg shadow-md mx-auto">
        <h2 class="text-2xl font-semibold text-center text-gray-700">Reset Password</h2>
        
        @if (Session::has('message'))
            <div class="p-3 my-4 text-sm text-green-800 bg-green-200 rounded">
                {{ Session::get('message') }}
            </div>
        @endif

        <form action="{{ route('reset.password.post') }}" method="POST" class="mt-4">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">
            
            <div class="mb-4">
                <label for="email_address" class="block text-sm font-medium text-gray-600">E-Mail Address</label>
                <input type="email" id="email_address" name="email" required autofocus 
                       class="w-full px-4 py-2 mt-2 border rounded-lg focus:ring focus:ring-blue-300">
                @if ($errors->has('email'))
                    <span class="text-sm text-red-500">{{ $errors->first('email') }}</span>
                @endif
            </div>
            
            <div class="mb-4">
                <label for="password" class="block text-sm font-medium text-gray-600">New Password</label>
                <input type="password" id="password" name="password" required 
                       class="w-full px-4 py-2 mt-2 border rounded-lg focus:ring focus:ring-blue-300">
                @if ($errors->has('password'))
                    <span class="text-sm text-red-500">{{ $errors->first('password') }}</span>
                @endif
            </div>
            
            <div class="mb-4">
                <label for="password-confirm" class="block text-sm font-medium text-gray-600">Confirm Password</label>
                <input type="password" id="password-confirm" name="password_confirmation" required 
                       class="w-full px-4 py-2 mt-2 border rounded-lg focus:ring focus:ring-blue-300">
                @if ($errors->has('password_confirmation'))
                    <span class="text-sm text-red-500">{{ $errors->first('password_confirmation') }}</span>
                @endif
            </div>
            
            <button type="submit" class="w-full px-4 py-2 text-white bg-orange-400 rounded-lg hover:bg-orange-600">
                Reset Password
            </button>
        </form>
    </div>
</body>
</html>
