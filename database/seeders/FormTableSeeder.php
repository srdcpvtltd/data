<?php

namespace Database\Seeders;

use App\Models\Form;
use Illuminate\Database\Seeder;

class FormTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Form::create([
            'name' => 'Default',
            'content' => '<div class="fb-textarea form-group field-textarea-1576265462046"><label for="textarea-1576265462046" class="fb-textarea-label">Information<br></label><textarea type="textarea" class="form-control" name="form_data[textarea-1576265462046]" id="textarea-1576265462046"></textarea></div>',
            'raw_content' => '[{"type":"textarea","label":"Information<br>","className":"form-control","name":"textarea-1576265462046","subtype":"textarea"}]'
        ]);
    }
}
