<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    @vite('resources/css/app.css')
    </head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen p-4">

    <div class="flex w-full max-w-4xl bg-white shadow-lg rounded-xl overflow-hidden">
        <div class="hidden md:flex w-1/2 p-12 items-center justify-center relative bg-gradient-to-br from-[#FDD179] via-[#8C52EE] to-[#603EBC] text-white">
            <div class="absolute inset-0">
                <div class="absolute top-1/4 left-0 w-48 h-48 bg-white opacity-10 rounded-full transform -translate-x-1/2 -translate-y-1/2 blur-3xl"></div>
                <div class="absolute bottom-1/4 right-0 w-64 h-64 bg-white opacity-10 rounded-full transform translate-x-1/2 translate-y-1/2 blur-3xl"></div>
            </div>

            <div class="relative z-10 text-center">
                <div class="absolute top-0 left-0 -mt-8 -ml-8">
                     <svg class="w-10 h-10 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10.894 2.553a1 1 0 00-1.788 0l-.622 1.059a1 1 0 01-.976.517L5.58 3.732a1 1 0 00-.923 1.343l.86 1.147a1 1 0 01-.157 1.488l-.86 1.147a1 1 0 00.923 1.343l2.05-.273a1 1 0 01.976.517l.622 1.059a1 1 0 001.788 0l.622-1.059a1 1 0 01.976-.517l2.05.273a1 1 0 00.923-1.343l-.86-1.147a1 1 0 01.157-1.488l.86-1.147a1 1 0 00-.923-1.343l-2.05.273a1 1 0 01-.976-.517l-.622-1.059z"></path>
                    </svg>
                </div>
                <h1 class="text-5xl font-extrabold tracking-tight">Welcome Back!</h1>
            </div>
        </div>

        <div class="w-full md:w-1/2 p-8 md:p-12">
            <h2 class="text-3xl font-bold text-gray-800 mb-2">Login</h2>
            <p class="text-gray-500 text-sm mb-8">Welcome back! Please login to your account.</p>

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium text-gray-700">User Name</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="email"
                           class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-purple-500 focus:border-purple-500 sm:text-sm @error('email') border-red-500 @enderror">
                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                    <input id="password" type="password" name="password" required autocomplete="current-password"
                           class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-purple-500 focus:border-purple-500 sm:text-sm @error('password') border-red-500 @enderror">
                    @error('password')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <button type="submit"
                            class="w-full mt-2 flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-lg font-medium text-white bg-purple-600 hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500">
                        Login
                    </button>
                </div>
            </form>
            </div>
    </div>

</body>
</html>