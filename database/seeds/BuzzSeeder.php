<?php

use App\Buzz;
use Illuminate\Database\Seeder;

class BuzzSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Buzz::class, 200)->create();
    }
}
