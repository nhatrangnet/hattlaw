<?php

use Illuminate\Database\Seeder;
use App\Model\Role;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	$superadmin = Role::create([
    		'name' => 'Super Admin',
    		'slug' => 'super_admin',
            'permissions' => [
                'super_admin' => true,
            ]
    	]);

        $admin = Role::create([
        	'name' => 'Admin',
        	'slug' => 'admin',
        	'permissions' => [
        		'user_update' => true,
                'user_update' => true,
                'news_update' => true,
        		'news_publish' => true,
        	]
        ]);

        $editor = Role::create([
            'name' => 'Editor',
            'slug' => 'editor',
            'permissions' => [
                'news_create' => true,
            ]
        ]);
        
        $user = Role::create([
            'name' => 'User',
            'slug' => 'user',
        ]);
    }
}
