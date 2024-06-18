<?php

namespace App\Http\Controllers\Admin;

use Doctrine\DBAL\Version;
use Laracasts\Flash\Flash;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Chumper\Zipper\Facades\Zipper;
use phpDocumentor\Reflection\DocBlock\Tags\See;
use Storage;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Session;
use RachidLaasri\LaravelInstaller\Helpers\DatabaseManager;
use Illuminate\Support\Facades\Artisan;
use Symfony\Component\Console\Output\BufferedOutput;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File as SFile;
use App\Models\Seeder;

class AdminUpdateController extends Controller
{
    public function index()
    {
        $title = 'ChargePanda Updates';
        return view('admin.updates', compact('title'));
    }


    public function update(Request $request)
    {

        ini_set('max_execution_time', 0);
        ini_set('memory_limit', '512M');

        $path = Storage::disk('local')->getAdapter()->getPathPrefix();

        if ($request->input('check_updates')) {
            if (ch_check_for_updates(NOTIFIER_CACHE_INTERVAL, true)) {
                return redirect()->back();
            }

        } elseif ($request->input('download_now')) {

            $download_url = UPDATE_URL . '?purchase_code=' . setting('purchase_code') . '&cc_token=' . setting('cc_token') . '&current='.VERSION;
            //Get the header response for the file in question.
            $headers = get_headers($download_url, 1);

            //Convert the array keys to lower case for the sake
            //of consistency.
            $headers = array_change_key_case($headers);

            //Set to -1 by default.
            $size = -1;

            //Check to see if the content-length key actually exists in
            //the array before attempting to access it.
            if(isset($headers['content-length'])){
                $size = $headers['content-length'];
            }

            if ($size == 'unknown' || $size == -1) {
                Flash::error('Unable to download the latest files.');
                return redirect()->back();
            }

            try {
                $remote = fopen($download_url, 'r');
                $local = fopen($path . 'chargepanda.zip', 'w');
            } catch (\Exception $ex) {
                Flash::error($ex->getMessage());
                return redirect()->back();
            }


            $filesize = $size;
            $read_bytes = 0;
            while (!feof($remote)) {

                try {
                    $buffer = fread($remote, 2048);
                    fwrite($local, $buffer);
                } catch (\Exception $ex) {
                    die($ex->getMessage());
                }

                $read_bytes += 2048;

                //Use $filesize as calculated earlier to get the progress percentage
                $progress = (min(100, 100 * $read_bytes / $filesize));
                //you'll need some way to send $progress to the browser.
                //maybe save it to a file and then let an Ajax call check it?
            }
            fclose($remote);
            fclose($local);

            Session::put('update_status', 'downloaded');
            Session::save();
            Flash::success('Download files has been prepared. You can the update ChargePanda now.');
            return redirect()->back();

        } elseif ($request->input('update_now')) {

            $downloaded_package = $path . 'chargepanda.zip';

            try {
                $zip = new \ZipArchive();
                $res = $zip->open($downloaded_package);

                if ($res === TRUE) {
                    $zip->extractTo(base_path());
                    $zip->close();
                }

            } catch (\Exception $ex) {
                Flash::error($ex->getMessage());
                return redirect()->back();
            }

            unlink($downloaded_package);
            sleep(6);
            $migrations = $this->getMigrations();
            $dbMigrations = $this->getExecutedMigrations();

            if (count($migrations) - count($dbMigrations) > 0) {
                return redirect(url('update'));
            }

            $request->session()->forget('update_status');
            $request->session()->forget('isUpToDate');

            Flash::success('ChargePanda has been successfully updated.');
            return redirect()->route('ch-admin.ch_admin_dashboard', ['update_check' => true]);

        }

    }

    /**
     * Get the migrations in /database/migrations
     *
     * @return array Array of migrations name, empty if no migrations are existing
     */
    public function getMigrations()
    {
        $migrations = glob(database_path() . DIRECTORY_SEPARATOR . 'migrations' . DIRECTORY_SEPARATOR . '*.php');
        return str_replace('.php', '', $migrations);
    }

    /**
     * Get the migrations that have already been ran.
     *
     * @return Illuminate\Support\Collection List of migrations
     */
    public function getExecutedMigrations()
    {
        // migrations table should exist, if not, user will receive an error.
        return DB::table('migrations')->get()->pluck('migration');
    }

}
