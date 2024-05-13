<?php

use Illuminate\Database\Seeder;
use App\Model\Role;
use App\Model\Admin;

class AdminsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $superadmin = Role::where('slug','super_admin')->first();
        $admin = Role::where('slug','admin')->first();


        $user1 = Admin::create([
        	'name' => 'Super Admin',
        	'email' => 'cnttnt@gmail.com',
        	'password' => \Hash::make('abcd1234')
        ]);
        $user1->roles()->attach($superadmin);

        $user2 = Admin::create([
        	'name' => 'Admin',
        	'email' => 'admin@gmail.com',
        	'password' => \Hash::make('abcd1234')
        ]);
        $user2->roles()->attach($admin);

        $editor = Role::where('slug','editor')->first();
        $user3 = Admin::create([
        	'name' => 'Editor',
        	'email' => 'editor@gmail.com',
        	'password' => \Hash::make('abcd1234')
        ]);
        $user3->roles()->attach($editor);
    }
}
