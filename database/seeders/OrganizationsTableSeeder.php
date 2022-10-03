<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class OrganizationsTableSeeder extends Seeder
{
    public $organizationsData = [
        ['114519', 'Pevensey and Westham CofE Primary School', '01323 762269', 'admin@pevensey-westham.e-sussex.sch.uk', 'http://www.pevenseyschool.org.uk'],
        ['110286', 'Elmhurst School', '01296 481380', 'office@elmhurst.bucks.sch.uk', 'http://www.elmhurst.bucks.sch.uk'],
        ['5230420', 'Riverbank School', '01224 483 217', 'cfowler@aberdeencity.gov.uk', 'http://'],
        ['901113196', 'Westcliff Primary Academy', '1626862444', 'admin@dawlish-westcliff-primary.devon.sch.uk ', 'http://www.dawlish-westcliff-primary.devon.sch.uk/'],
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $datetime = (new \DateTime())->format('Y-m-d h:i:s');

        foreach ($this->organizationsData as $row) {
            DB::table('organizations')->insert([
                'school_URN' => $row[0],
                'name' => $row[1],
                'telephone' => $row[2],
                'email' => $row[3],
                'url' => $row[4],
                'created_at' => $datetime,
                'updated_at' => $datetime,
            ]);
        }
    }
}
