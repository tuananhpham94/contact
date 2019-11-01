<?php

use Illuminate\Database\Seeder;

class CompanyTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = array(
            array ('legal_name' => 'ANZ', 'email' => 'info@anz.co.nz'),
            array ('legal_name' => 'BNZ', 'email' => 'info@bnz.co.nz'),
            array ('legal_name' => 'ASB', 'email' => 'info@asb.co.nz'),
            array ('legal_name' => 'The Co-operative Bank', 'email' => 'info@co-ope.co.nz'),
            array ('legal_name' => 'Westpac', 'email' => 'info@Westpac.co.nz'),
            array ('legal_name' => 'Heartland', 'email' => 'info@Heartland.co.nz'),
            array ('legal_name' => 'NZCU', 'email' => 'info@NZCU.co.nz'),
            array ('legal_name' => 'Rabobank New Zealand', 'email' => 'info@rabobank.co.nz'),
            array ('legal_name' => 'PostBank', 'email' => 'info@postbank.co.nz'),
            array ('legal_name' => 'TSB', 'email' => 'info@TSB.co.nz'),
            array ('legal_name' => 'HSBC', 'email' => 'info@HSBC.co.nz'),
            array ('legal_name' => 'Citibank', 'email' => 'info@citibank.co.nz'),
            array ('legal_name' => 'Kiwibank', 'email' => 'info@Kiwibank.co.nz'),
            array ('legal_name' => 'IRD', 'email' => 'info@ird.govt.nz')
        );
        \Illuminate\Support\Facades\DB::table('companies')->insert($data);
    }
}
