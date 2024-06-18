<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <title>ChargePanda Installer</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body>
<div class="w-full max-w-lg mx-auto mt-8">

    <?php if (!empty($reqErrors)) { ?>
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
        <strong class="font-bold">Requirement Error(s)!</strong><br>
        <span class="block sm:inline">Please fix the following errors to proceed the installation:</span>
        <ul class="mt-3 list-disc list-inside text-sm">
                <?php foreach ($reqErrors as $reqError) { ?>
            <li><?php echo $reqError ?></li>
            <?php } ?>
        </ul>
    </div>
    <?php } else { ?>
    @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
            <ul class="mt-3 list-disc list-inside text-sm">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <form class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4" method="post" action="{{route('ch_update_env')}}">
        @csrf
        <div class="mb-4">
            <label class="block text-gray-700 font-bold mb-2" for="DB_HOST">
                Database Host
            </label>
            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="DB_HOST" name="DB_HOST" type="text" placeholder="Enter database host"
                   value="localhost" required>
        </div>
        <div class="mb-4">
            <label class="block text-gray-700 font-bold mb-2" for="DB_USERNAME">
                Database Username
            </label>
            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="DB_USERNAME" name="DB_USERNAME" type="text" placeholder="Enter database username"
                   required value="{{old('DB_USERNAME')}}">
        </div>
        <div class="mb-4">
            <label class="block text-gray-700 font-bold mb-2" for="DB_PASSWORD">
                Database Password
            </label>
            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="DB_PASSWORD" name="DB_PASSWORD" type="text" placeholder="Enter database password">
        </div>
        <div class="mb-4">
            <label class="block text-gray-700 font-bold mb-2" for="DB_DATABASE">
                Database Name
            </label>
            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="DB_DATABASE" name="DB_DATABASE" type="text" placeholder="Enter database name"
                   required value="{{old('DB_DATABASE')}}">
        </div>

        <div class="flex items-center justify-between">
            <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit">
                Save Configuration
            </button>
        </div>
    </form>
    <?php } ?>
</div>

</body>
</html>
