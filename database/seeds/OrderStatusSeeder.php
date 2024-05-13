<?php

use Illuminate\Database\Seeder;
use App\Model\OrderStatus;

class OrderStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $checking = OrderStatus::create([
    		'name' => 'checking',
    		'description' => 'On checking....',
    	]);

        $process = OrderStatus::create([
    		'name' => 'process',
    		'description' => 'On process....',
    	]);

		$pending = OrderStatus::create([
    		'name' => 'pending',
    		'description' => 'On pending....',
    	]);

		$fail = OrderStatus::create([
    		'name' => 'fail',
    		'description' => 'On fail....',
    	]);
    	$finish = OrderStatus::create([
    		'name' => 'finish',
    		'description' => 'On finish....',
    	]);

    }
}
