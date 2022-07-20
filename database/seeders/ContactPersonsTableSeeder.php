<?php

namespace Database\Seeders;

use App\Models\ContactPerson;
use Illuminate\Database\Seeder;

class ContactPersonsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ContactPerson::factory(60)->create();
    }
}
