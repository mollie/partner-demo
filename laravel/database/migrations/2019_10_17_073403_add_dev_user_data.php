<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class AddDevUserData extends Migration
{
    /**
     * Run the migrations.
     * @return void
     */
    public function up()
    {
        $password = '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'; // password

        DB::table('users')->insert([
            'id' => 1,
            'company_name' => 'Mollie B.V',
            'website' => 'https://www.mollie.com/en/',
            'email' => 'info@mollie.com',
            'password' => $password,
        ]);
    }

    /**
     * Reverse the migrations.
     * @return void
     */
    public function down()
    {
        DB::table('users')->delete(1);
    }
}
