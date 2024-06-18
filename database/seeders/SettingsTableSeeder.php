<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;
use function config;

class SettingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $settings = [
            'app.name' => 'ChargePanda',
            'mail.from.address' => config('mail.from.address'),
            'currency' => 'USD',
            'currency_position' => 'left',
            'services.per_page' => '8',
            'email_verification' => 'off',
            'make_site_private' => 'off',
            'recaptcha.enabled' => 'off',
            'paypal.enabled' => 'no',
            'stripe.enabled' => 'no',
            'offline_payments.enabled' => 'no',
            'social_login.enabled' => 'no',
            'services.facebook.enabled' => 'no',
            'services.twitter.enabled' => 'no',
            'settings.taxes.enabled' => 'no',
            'cart.tax' => 0.00,
        ];

        Setting::updateSettings($settings);
    }
}
