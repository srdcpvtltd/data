<?php

namespace Database\Seeders;

use App\Models\Term;
use DB;
use Illuminate;
use Illuminate\Database\Seeder;
use function public_path;

class TermTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Illuminate\Support\Facades\File::deleteDirectory(public_path() . '/uploads/');
        DB::table('terms')->delete();

        Term::create([
            'id' => 1,
            'name' => 'Design',
        ]);

        Term::create([
            'id' => 2,
            'name' => 'Logo Design',
            'parent' => 1
        ]);

        Term::create([
            'id' => 3,
            'name' => 'Web Design',
            'parent' => 1
        ]);

        Term::create([
            'id' => 4,
            'name' => 'Customization',
        ]);

        Term::create([
            'id' => 5,
            'name' => 'Logo Customization',
            'parent' => 4
        ]);

        Term::create([
            'id' => 6,
            'name' => 'Web Customization',
            'parent' => 4
        ]);

        Term::create([
            'id' => 7,
            'name' => '3D Modeling',
        ]);

        Term::create([
            'id' => 8,
            'name' => 'Personal',
            'taxonomy' => 'tag'
        ]);

        Term::create([
            'id' => 9,
            'name' => 'Wordpress Customization',
            'taxonomy' => 'tag'
        ]);

        Term::create([
            'id' => 10,
            'name' => 'Fashion Designing',
            'taxonomy' => 'tag'
        ]);

        Term::create([
            'id' => 11,
            'name' => 'Simple Customization',
            'parent' => 6
        ]);

        Term::create([
            'id' => 12,
            'name' => 'Page Customization',
            'parent' => 11
        ]);
    }
}
