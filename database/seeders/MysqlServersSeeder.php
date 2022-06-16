<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MysqlServersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::connection('mysql')->table('mysql_servers')->insert(
           [
               [
                   'mysql_connect_name' => 'mysql_first',
                   'mysql_host_ip' => '127.0.0.1',
                   'mysql_db_name' => 'DB1',
                   'mysql_port' => '3306',
                   'mysql_user' => 'root',
                   'mysql_password' => 'password',
                   'open' => 1,
               ],

               [
                   'mysql_connect_name' => 'mysql_second',
                   'mysql_host_ip' => '127.0.0.1',
                   'mysql_db_name' => 'DB2',
                   'mysql_port' => '3306',
                   'mysql_user' => 'root',
                   'mysql_password' => 'password',
                   'open' => 1,
               ],

               [
                   'mysql_connect_name' => 'mysql_third',
                   'mysql_host_ip' => '127.0.0.1',
                   'mysql_db_name' => 'DB3',
                   'mysql_port' => '3306',
                   'mysql_user' => 'root',
                   'mysql_password' => 'password',
                   'open' => 1,
               ],
           ]
        );
    }
}
