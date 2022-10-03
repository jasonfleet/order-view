<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use stdClass;

class Organization extends Base
{

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'email',
        'name',
        'school_urn',
        'telephone',
        'url',
    ];

    /**
     * Columns headers on an import.
     *
     * @var string[]
     */
    public static $importColumns = [
        'organization_name' => 'name',
        'organization_email' => 'email',
        'organization_telephone' => 'telephone',
        'organization_url' => 'url',
        'school_URN' => 'school_urn',
    ];

    /**
     * The name of the database table.
     *
     * @var string
     */
    public static $dbTableName = 'organizations';

    /**
     * Add the orders to the database.
     *
     * @param array $organizations
     *
     * @return void
     */
    public static function addRows(array $organizations)
    {
        foreach ($organizations as $schoolUrn => $organization) {
            $dbOrganization = DB::table(self::$dbTableName)
                ->where('school_URN', $schoolUrn)
                ->first();

            if (is_null($dbOrganization)) {
                $id = DB::table(self::$dbTableName)
                    ->insertGetId($organization);
            }
        }
    }

    /**
     * Returns an organization by the school-urn.
     *
     * @param string $schoolUrn
     * @return stdClass|null
     */
    public static function getBySchoolUrn(string $schoolUrn): ?\stdClass
    {
        return self::getOneBy(self::$dbTableName, ['school_urn' => $schoolUrn]);
    }

    /**
     * Import the orders in the array..
     *
     * @return array
     */
    public static function importRows(array $csvRows): array
    {
        $organizationHeaders = array_shift($csvRows);
        $organizationHeaders = array_combine($organizationHeaders, array_flip(str_replace(' ', '', $organizationHeaders)));

        $organizations = [];

        foreach ($csvRows as $row) {
            if (count($row) < count(self::$importColumns)) {
                break;
            }

            $schoolUrn = $row[$organizationHeaders['school_URN']];

            if (!isset($organizations[$schoolUrn])) {
                $organizations[$schoolUrn] = self::extract($organizationHeaders, self::$importColumns, $row);
            }
        }

        return $organizations;
    }
}
