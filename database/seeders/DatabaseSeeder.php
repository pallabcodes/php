<?php

namespace Database\Seeders;

use App\Models\MyBlog;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database and when run php arisan db:seed (All seeders within this file will run and prevent duplicate seeding data too)
     * If some data i.e. already seeded; even if the same data ran again through seeder, it won't seed with same data again and will throw error 
     */
    public function run(): void
    {
        MyBlog::factory(10)->create(); // or diretly run from tinker: App\Models\MyBlog::factory(10)->create();

        // But if the said model has a factory then it can be used like below in this file 
        // User::factory(10)->create();


        // when doing manually or UserFactory not defined, then `UserSeeded needed and need to call like below`

        // $this->call(UserSeeder::class);
        // $this->call(BlogSeeder::class);
    }
}
