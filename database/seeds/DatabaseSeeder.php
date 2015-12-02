<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use App\User;
use App\Models\AppointmentRequest;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $user_a = User::create([
            'id_card'    => '206560973',
            'first_name' => 'Carlos',
            'last_name'  => 'Rojas',
            'email'      => 'crojas@go-labs.net',
            'password'   => Hash::make('12345'),
            'birthday'   => '1989-01-31'
        ]);
        $user_b = User::create([
            'id_card'    => '207030692',
            'first_name' => 'Jorge',
            'last_name'  => 'Zavala',
            'email'      => 'jzavala@go-labs.net',
            'password'   => Hash::make('12345'),
            'birthday'   => '1992-09-05'
        ]);

        AppointmentRequest::create([
            'from_user_id' => $user_b->id,
            'to_user_id' => $user_a->id,
            'start_date' => '2015-12-01 8:00',
            'end_date' => '2015-12-01 10:00',
        ]);

        Model::reguard();
    }
}
