<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>TODOFY | Register</title>
    @vite('resources/css/app.css')
    <link href="https://fonts.googleapis.com/css2?family=League+Spartan:wght@100..900&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'League Spartan', sans-serif;
            background: #F8F9FA;
        }
    </style>
</head>

<body>
    <div class="flex h-screen">
        <!-- Bagian Kiri -->
        <div class="w-1/2 bg-orange-400 flex items-center justify-center p-10">
            <div class="text-white text-center">
                <h1 class="text-4xl font-bold">Welcome to <span class="text-black">TodoFy!</span></h1>
                <p class="text-lg">A place to make notes with ease!</p>
            </div>
        </div>

        <!-- Bagian Kanan -->
        <div class="w-1/2 flex items-center justify-center">
            <div class="w-2/3 mx-auto">
                <h2 class="text-3xl font-bold">Register</h2>
                <p class="text-gray-500">Create an account for the first time, if you already have an account <a
                        href="{{ route('login') }}" class="text-blue-500">Login</a> here.
                </p>

                <!-- Form -->
                <form action="{{ route('register.post') }}" method="POST">
                    @csrf

                    @session('error')
                        <div class="flex items-center p-4 mb-4 text-sm text-red-800 border border-red-300 rounded-lg bg-red-50 dark:text-red-400 dark:border-red-800"
                            role="alert">
                            <svg class="shrink-0 inline w-4 h-4 me-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                fill="currentColor" viewBox="0 0 20 20">
                                <path
                                    d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z" />
                            </svg>
                            <span class="sr-only">Info</span>
                            <div>
                                <span class="font-medium">Danger alert!</span>
                                {{ $value }}
                            </div>
                        </div>
                    @endsession
                    
                    <div class="mt-5">
                        <!-- Email -->
                        <label for="name" class="block text-gray-700">Name</label>
                        <input v-model="name" type="text" name="name" id="name"
                            class="w-full border-b-2 p-2 focus:outline-none focus:border-blue-400">

                        <label for="email" class="mt-4 block text-gray-700">Email</label>
                        <input v-model="email" type="email" name="email" id="email"
                            class="w-full border-b-2 p-2 focus:outline-none focus:border-blue-400">

                        <!-- Password -->
                        <label for="password" class="block mt-4 text-gray-700">Password</label>
                        <input v-model="password" type="password" name="password" id="password"
                            class="w-full border-b-2 p-2 focus:outline-none focus:border-blue-400">

                        <label for="password_confirmation" class="block mt-4 text-gray-700">Password Confirmation</label>
                        <input v-model="password_confirmation" type="password" name="password_confirmation" id="password_confirmation"
                            class="w-full border-b-2 p-2 focus:outline-none focus:border-blue-400">
                    </div>

                    <!-- Tombol Login -->
                    <button type="submit"
                        class="mt-5 w-full bg-orange-400 text-white p-3 rounded-lg font-bold hover:bg-orange-500 transition">
                        Enter Here
                    </button>
                </form>
            </div>
        </div>
    </div>
</body>

</html>
