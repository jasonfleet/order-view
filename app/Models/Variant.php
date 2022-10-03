<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;

class Variant extends Base
{
    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'price',
        'product_id',
        'size_name',
    ];

    /**
     * Columns headers on an import.
     *
     * @var string[]
     */
    public static $importColumns = [
        'product_price' => 'price',
        'product_size_name' => 'size_name',
    ];

    /**
     * The name of the database table.
     *
     * @var string
     */
    public static $dbTableName = 'variants';

    /**
     * Add the variants to the database.
     *
     * @param array $variants
     * @param integer $productId
     *
     * @return void
     */
    public static function addRows(int $productId, array $variants)
    {
        $datetime = (new \DateTime())->format('Y-m-d h:i:s');

        foreach ($variants as $sizeName => $variant) {
            $dbVariant = self::getBySizeName($sizeName, $productId);

            if (is_null($dbVariant)) {
                $variant['product_id'] = $productId;

                $variant['created_at'] = $datetime;
                $variant['updated_at'] = $datetime;

                $id = DB::table(self::$dbTableName)->insertGetId($variant);
            } else {
                $id = $dbVariant->id;

                if ($dbVariant->price != $variant['price']) {
                    DB::table(self::$dbTableName)->where('id', $id)
                        ->update(['price' => $variant['price'], 'updated_at' => $datetime]);
                }
            }
        }
    }

    /**
     * Returns an organization by the school-urn.
     *
     * @param string $sizeName
     *
     * @return stdClass|null
     */
    public static function getBySizeName(string $sizeName, int $productId): ?\stdClass
    {
        return self::getOneBy(self::$dbTableName, ['size_name' => $sizeName, 'product_id' => $productId]);
    }
}
