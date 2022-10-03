<?php

namespace App\Models;

use stdClass;
use Illuminate\Support\Facades\DB;

/**
 * Base class
 *
 * Convenience functions.
 *
 */
class Base
{
    /**
     * Extract the order data from the csv row.
     *
     * @param array $headers
     * @param array $columns
     * @param array $row
     *
     * @return array
     */
    public static function extract(array $headers, array $columns, array $row): array
    {
        $product = [];

        foreach ($columns as $importColumn => $column) {
            $product[$column] = $row[$headers[$importColumn]];
        }

        return $product;
    }

    /**
     * Returns an array of entities in the database.
     *
     * @param string $dbTableName
     * @return array
     */
    public static function getAll(string $dbTableName): array
    {
        return DB::table($dbTableName)
            ->get()->all();
    }

    /**
     * Returns an array of entities that have tha $column value of $value.
     *
     * @param string $dbTableName
     * @param string $column
     * @param string $value
     *
     * @return stdClass|null
     */
    public static function getOneBy(string $dbTableName, array $columnValues): ?\stdClass
    {
        $q = DB::table($dbTableName);

        foreach ($columnValues as $column => $value) {
            $q->where($column, $value);
        }
        return $q->first();
    }
}
