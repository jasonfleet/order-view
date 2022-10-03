<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OrderLine extends Base
{
    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'order_id',
        'line_price',
        'price',
        'quantity',
        'variant_id',
    ];

    /**
     * Columns headers on an import.
     *
     * @var string[]
     */
    public static $importColumns = [
        'product_colour_style_ref' => 'product_colour_style_ref',
        'product_line_price' => 'line_price',
        'product_price' => 'price',
        'product_quantity' => 'quantity',
        'product_size_name' => 'size_name',
    ];

    /**
     * The name of the database table.
     *
     * @var string
     */
    public static $dbTableName = 'order_lines';

    /**
     * Add the variants to the database.
     *
     * @param array $variants
     * @param integer $orderId
     *
     * @return void
     */
    public static function addRows(int $orderId, array $lines)
    {
        $datetime = (new \DateTime())->format('Y-m-d h:i:s');

        foreach ($lines as $line) {
            // left over from import validation
            unset($line['size_name']);
            unset($line['product_colour_style_ref']);

            $line['order_id'] = $orderId;
            $line['variant_id'] = $orderId;

            $line['created_at'] = $datetime;
            $line['updated_at'] = $datetime;

            $id = DB::table(self::$dbTableName)->insert($line);
        }
    }

    /**
     * Validate the order-lines for an order.
     *
     * Returns the errors if there are any.
     *
     * @param float $bulkTotal
     * @param array $lines
     *
     * @return array
     */
    public static function validate(float $bulkTotal, array $lines): array
    {
        $result = [
            'errors' => []
        ];

        $total = 0;

        foreach ($lines as $line) {
            $total += $line['line_price'];

            $product = Product::getOneColourStyleRef($line['product_colour_style_ref']);
            $variant = Variant::getBySizeName($line['size_name'], $product->id);

            if (is_null(($variant))) {
                $result['errors'][] = [
                    'reason' => 'Variant [' . $line['size_name'] . '] does not not exist on product [' . $line['product_colour_style_ref'] . ']',
                    'rows' => $line,
                ];
            }
        }

        if ($total != $bulkTotal) {
            $result['errors'][] = [
                'reason' => 'Bulk total [' . $bulkTotal . '] does not match order-lines total [' . $total . ']',
                'rows' => $lines,
            ];
        }

        return $result;
    }
}
