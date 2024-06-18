<?php

namespace Database\Seeders;

use App;
use App\Models\Addon;
use App\Models\Form;
use App\Models\Product;
use App\Models\Task;
use Illuminate\Database\Seeder;

class ServiceTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $service = Product::create([
            'name' => 'Web Hosting',
            'description' => ' Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec nec consequat purus. Proin congue felis vitae neque dignissim varius. Mauris ullamcorper eleifend quam, a convallis ligula. Maecenas vulputate tortor non posuere ultricies. Mauris luctus sed neque vitae ornare. Curabitur sollicitudin hendrerit lorem, sit amet consequat ipsum. Maecenas at urna ac erat feugiat vulputate sit amet ac nisl. Vivamus eu nulla in purus finibus facilisis. Phasellus sit amet venenatis eros. Suspendisse vulputate, tortor quis mollis tempor, sem dui vulputate felis, vitae suscipit justo diam sit amet justo.',
            'user_id' => 1,
            'price' => 12.00,
            'status' => 1,
            'dynamic_pricing' => 1,
        ]);

        Addon::create([
            'name' => 'Fast Delivery',
            'description' => 'Fast Delivery',
            'price' => 10,
            'product_id' => $service->id,
        ]);

        App\Models\Plan::create([
            'name' => 'Basic',
            'Description' => '10GB Space + 30GB Bandwidth + 5 Domains',
            'price' => 10.00,
            'features' => json_encode([
                'Storage Space: 10 GB',
                'Bandwidth: 100 GB',
                'Domain Name: 1 free domain registration',
                'Email Accounts: 5 email accounts',
                'Website Builder: Included',
                'Database Support: MySQL databases included',
                'Security Features: SSL certificate, firewall protection',
                'Support: 24/7 live chat and ticket-based support',
                'Scalability: Limited scalability options',
            ]),
            'product_id' => $service->id,
        ]);

        App\Models\Plan::create([
            'name' => 'Professional',
            'Description' => '20GB Space + 60GB Bandwidth + 10 Domains',
            'price' => 20.00,
            'features' => json_encode([
                'Storage Space: 20 GB',
                'Bandwidth: 60 GB',
                'Domain Name: 3 free domain registration',
                'Email Accounts: 10 email accounts',
                'Website Builder: Included',
                'Database Support: MySQL databases included',
                'Security Features: SSL certificate, firewall protection',
                'Support: 24/7 live chat and ticket-based support',
                'Scalability: Limited scalability options',
            ]),
            'product_id' => $service->id,
        ]);

        $meta_array = [
            'guideline' => 'Vestibulum vulputate risus neque, eu ullamcorper ipsum iaculis eu. Fusce ullamcorper ut orci sed cursus. Morbi ullamcorper, sem fringilla laoreet laoreet, mauris erat dictum felis, id placerat nisi erat quis ante.',
            'demo_url' => 'http://www.chargepanda.com/',
            'delivery_time' => '3 Days',
            'revisions' => '4',
            'variable_pricing_enabled' => '0',
        ];

        foreach ($meta_array as $key => $value) {
            $newMeta = new App\Models\ProductMeta(['key' => $key]);
            $meta = $service->meta()->where('key', $key)->first() ?: $newMeta->product()->associate($service);

            if (is_array($value)) {
                $value = serialize($value);
            }

            $meta->value = $value;
            $meta->save();
        }

    }
}
