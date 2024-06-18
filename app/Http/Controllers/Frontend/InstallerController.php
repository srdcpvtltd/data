<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use PDO;
use PDOException;

class InstallerController extends Controller
{
    public function index()
    {
        if (file_exists(base_path('storage/app/installed'))) {
            return redirect()->to('/');
        }

        $reqErrors = $this->checkRequirements();

        return view('installer.index', compact('reqErrors'));
    }

    private function checkRequirements(): array
    {
        $requirements = [
            'PHP version >= 8.1' => version_compare(PHP_VERSION, '8.1.0', '>='),
            'OpenSSL PHP Extension is not installed or enabled.' => extension_loaded('openssl'),
            'PDO PHP Extension is not installed or enabled.' => extension_loaded('pdo'),
            'Mbstring PHP Extension is not installed or enabled.' => extension_loaded('mbstring'),
            'Tokenizer PHP Extension is not installed or enabled.' => extension_loaded('tokenizer'),
            'XML PHP Extension is not installed or enabled.' => extension_loaded('xml'),
            'Ctype PHP Extension is not installed or enabled.' => extension_loaded('ctype'),
            'JSON PHP Extension is not installed or enabled.' => extension_loaded('json'),
            'BCMath PHP Extension is not installed or enabled.' => extension_loaded('bcmath'),
            'FileInfo PHP Extension is not installed or enabled.' => extension_loaded('fileinfo'),
            'storage/framework is not writable.' => is_writable(base_path('storage/framework')),
            'Storage/logs directory is not writable.' => is_writable(base_path('storage/logs')),
            'Writable bootstrap/cache is not writable.' => is_writable(base_path('bootstrap/cache')),
        ];

        $errors = [];

        foreach ($requirements as $key => $requirement) {
            if (!$requirement) {
                $errors[] = $key;
            }
        }

        return $errors;
    }

    public function updateEnv(Request $request)
    {
        $request->validate([
            'DB_HOST' => 'required',
            'DB_USERNAME' => 'required',
            'DB_DATABASE' => 'required'
        ]);

        $envData = $request->except('_token');

        try {
            new PDO(
                'mysql:host=' . $envData['DB_HOST'] . ';dbname=' . $envData['DB_DATABASE'],
                $envData['DB_USERNAME'],
                $envData['DB_PASSWORD']
            );
        } catch (PDOException $e) {
            return back()->withInput()->withErrors($e->getMessage());
        }

        try {
            $envFilePath = base_path('.env');
            $contents = file_get_contents($envFilePath);

            foreach ($envData as $key => $value) {
                $contents = preg_replace('/^' . $key . '=.*$/m', $key . '=' . $value, $contents);
            }

            file_put_contents($envFilePath, $contents);
        } catch (\Exception $exception) {
            return back()->withInput()->withErrors($exception->getMessage());
        }

        return redirect()->route('ch_install_last_step');
    }

    public function lastStep()
    {
        if (file_exists(storage_path('app/installed'))) {
            return redirect('/');
        }

        Artisan::call('migrate:fresh', [
            '--seed' => true,
            '--force' => true,
        ]);
        // Storage link
        Artisan::call('storage:link');

        return view('installer.complete_installation');
    }

    public function completeInstallation(Request $request)
    {
        $request->validate([
            'ADMIN_EMAIL' => 'required|email',
            'ADMIN_PASSWORD' => 'required',
        ]);

        touch(storage_path('app/installed'));

        $user = User::create([
            'first_name' => 'Site',
            'last_name' => 'Admin',
            'email' => request()->post('ADMIN_EMAIL'),
            'password' => bcrypt(request()->post('ADMIN_PASSWORD')),
            'email_verified_at' => Carbon::now(),
        ]);

        $user->roles()->sync([1]);

        Auth::login($user);

        return redirect()->route('ch-admin.ch_admin_dashboard');
    }
}
