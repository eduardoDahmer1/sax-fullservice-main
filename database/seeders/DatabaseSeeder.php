<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(Database\Seeders\AdminLanguagesTableSeeder::class);
        $this->call(Database\Seeders\AdminsTableSeeder::class);
        $this->call(Database\Seeders\CountriesTableSeeder::class);
        $this->call(Database\Seeders\StatesTableSeeder::class);
        $this->call(Database\Seeders\CitiesTableSeeder::class);
        $this->call(Database\Seeders\CountersTableSeeder::class);
        $this->call(Database\Seeders\CurrenciesTableSeeder::class);
        $this->call(Database\Seeders\EmailTemplatesTableSeeder::class);
        $this->call(Database\Seeders\EmailTemplateTranslationsTableSeeder::class);
        $this->call(Database\Seeders\GeneralsettingsTableSeeder::class);
        $this->call(Database\Seeders\GeneralsettingTranslationsTableSeeder::class);
        $this->call(Database\Seeders\LanguagesTableSeeder::class);
        $this->call(Database\Seeders\NotificationsTableSeeder::class);
        $this->call(Database\Seeders\PagesettingsTableSeeder::class);
        $this->call(Database\Seeders\PagesettingTranslationsTableSeeder::class);
        $this->call(Database\Seeders\PaymentGatewaysTableSeeder::class);
        $this->call(Database\Seeders\RolesTableSeeder::class);
        $this->call(Database\Seeders\RoleTranslationsTableSeeder::class);
        $this->call(Database\Seeders\SeotoolsTableSeeder::class);
        $this->call(Database\Seeders\SeotoolTranslationsTableSeeder::class);
        $this->call(Database\Seeders\ServicesTableSeeder::class);
        $this->call(Database\Seeders\ServiceTranslationsTableSeeder::class);
        $this->call(Database\Seeders\SocialProvidersTableSeeder::class);
        $this->call(Database\Seeders\SocialsettingsTableSeeder::class);
        $this->call(Database\Seeders\SubscriptionsTableSeeder::class);
        $this->call(Database\Seeders\SubscriptionTranslationsTableSeeder::class);
        $this->call(Database\Seeders\PackagesTableSeeder::class);
        $this->call(Database\Seeders\PackageTranslationsTableSeeder::class);
        $this->call(Database\Seeders\ShippingsTableSeeder::class);
        $this->call(Database\Seeders\ShippingsTranslationsTableSeeder::class);

        # Clear cache after run all Seeders. It prevents application to start with empty Generalsetting model.
        Artisan::call('cache:clear');
    }
}
