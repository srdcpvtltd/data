<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Complete Installation</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
<div class="flex items-center justify-center h-screen">
    <div class="bg-white rounded-lg shadow-lg p-8">
        <h1 class="text-2xl font-bold mb-4">Welcome!</h1>
        <p class="mb-4">Please create an Admin account to complete the installation process.</p>
        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <ul class="mt-3 list-disc list-inside text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <form method="post" action="{{route('ch_complete_installation')}}">
            @csrf
            <div class="mb-4">
                <label class="block text-gray-700 font-bold mb-2" for="ADMIN_EMAIL">
                    Admin Email
                </label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="ADMIN_EMAIL" name="ADMIN_EMAIL" type="text" placeholder="Admin Email" required>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 font-bold mb-2" for="ADMIN_PASSWORD">
                    Admin Password
                </label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="ADMIN_PASSWORD" name="ADMIN_PASSWORD" type="text" placeholder="Enter your password" required>
            </div>

            <div class="flex items-center justify-between">
                <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit">
                    Complete Installation
                </button>
            </div>
        </form>
    </div>
</div>
</body>
</html>
