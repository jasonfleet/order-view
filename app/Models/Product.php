<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;

class Product extends Base
{
    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'colour_image_url',
        'colour_name',
        'colour_style_ref',
        'ean',
        'name',
    ];

    /**
     * Columns headers on an import.
     *
     * @var string[]
     */
    public static $importColumns = [
        'product_colour_image_url' => 'colour_image_url',
        'product_colour_name' => 'colour_name',
        'product_colour_style_ref' => 'colour_style_ref',
        'product_ean' => 'ean',
        'product_name' => 'name',
    ];

    /**
     * The name of the database table.
     *
     * @var string
     */
    public static $dbTableName = 'products';

    /**
     * Add the products to the database.
     *
     * @param array $products
     *
     * @return void
     */
    public static function addRows(array $products)
    {
        $datetime = (new \DateTime())->format('Y-m-d h:i:s');

        foreach ($products as $colourStyleRef => $product) {
            if (isset($product['variants'])) {
                $variants = $product['variants'];
                unset($product['variants']);
            } else {
                $variants = null;
            }

            $dbProduct = DB::table(self::$dbTableName)
                ->where('colour_style_ref', $colourStyleRef)
                ->first();

            if (is_null($dbProduct)) {
                $product['created_at'] = $datetime;
                $product['updated_at'] = $datetime;

                $id = DB::table(self::$dbTableName)->insertGetId($product);
            } else {
                $id = $dbProduct->id;

                if (
                    strcmp($dbProduct->ean, $product['ean']) != 0
                    || strcmp($dbProduct->name, $product['name']) != 0
                ) {
                    DB::table(self::$dbTableName)->where('id', $id)
                        ->update(['ean' => $product['ean'], 'name' => $product['name'], 'updated_at' => $datetime]);
                }
            }

            if (!is_null($variants)) {
                Variant::addRows($id, $variants);
            }
        }
    }

    /**
     * Undocumented function
     *
     * @param string $colourStyleRef
     *
     * @return stdClass|null
     */
    public static function getOneColourStyleRef(string $colourStyleRef): ?\stdClass
    {
        return self::getOneBy(self::$dbTableName, ['colour_style_ref' => $colourStyleRef]);
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    public static function getProducts()
    {

        return DB::table(self::$dbTableName)
            ->leftJoin('variants', 'products.id', '=', 'variants.product_id')
            ->get();
    }

    /**
     * Import the products in the array.
     *
     * @param array $csvRows
     *
     * @return array
     */
    public static function importRows(array $csvRows): array
    {
        $productHeaders = str_replace(' ', '', array_shift($csvRows));

        $missingColumns = array_diff(
            array_keys(
                array_merge(self::$importColumns, Variant::$importColumns)
            ),
            $productHeaders
        );

        if (!empty($missingColumns)) {
            return [
                'error' => [
                    'missing columns' => $missingColumns,
                ]
            ];
        }

        $productHeaders = array_combine($productHeaders, array_flip($productHeaders));
        $products = [];

        foreach ($csvRows as $row) {
            if (count($row) < count(self::$importColumns)) {
                break;
            }

            $colourStyleRef = $row[$productHeaders['product_colour_style_ref']];

            if (!isset($products[$colourStyleRef])) {
                $products[$colourStyleRef] = self::extract($productHeaders, self::$importColumns, $row);

                $products[$colourStyleRef]['variants'] = [];
            }

            $productSizeName = $row[$productHeaders['product_size_name']];

            if (!isset($products[$colourStyleRef]['variants'][$productSizeName])) {
                $products[$colourStyleRef]['variants'][$productSizeName] =
                    self::extract($productHeaders, Variant::$importColumns, $row);
            }
        }

        return $products;
    }
}
