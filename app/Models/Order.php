<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;

class Order extends Base
{
    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'bulk_total',
        'contact_name',
        'date',
        'delivery_address_1',
        'delivery_address_2',
        'delivery_address_3',
        'delivery_town',
        'delivery_county',
        'delivery_postcode',
        'email_address',
        'name',
        'order_id',
        'telephone',
    ];

    /**
     * Columns headers on an import.
     *
     * @var string[]
     */
    public static $importColumns = [
        'organization_bulk_order_total' => 'bulk_total',
        'order_id' => 'order_id',
        'order_contact_name' => 'contact_name',
        'order_date' => 'date',
        'order_delivery_address_1' => 'delivery_address_1',
        'order_delivery_address_2' => 'delivery_address_2',
        'order_delivery_address_3' => 'delivery_address_3',
        'order_delivery_town' => 'delivery_town',
        'order_delivery_county' => 'delivery_county',
        'order_delivery_postcode' => 'delivery_postcode',
        'order_email_address' => 'email_address',
        'order_name' => 'name',
        'school_URN' => 'school_URN',
        'order_telephone' => 'telephone',
    ];

    /**
     * The name of the database table.
     *
     * @var string
     */
    public static $dbTableName = 'orders';

    /**
     * Add the orders to the database.
     *
     * TODO: add a cache for the organizations
     *
     * @param array $orders
     *
     * @return array
     */
    public static function addRows(array $orders): array
    {
        $datetime = (new \DateTime())->format('Y-m-d h:i:s');

        $result = [
            'errors' => []
        ];

        foreach ($orders as $orderId => $order) {
            $lines = $order['lines'];
            unset($order['lines']);

            $dbOrder = DB::table(self::$dbTableName)
                ->where('order_id', $orderId)
                ->first();

            if (is_null($dbOrder)) {
                $org = Organization::getBySchoolUrn($order['school_URN']);

                if (is_null($org)) {
                    $result['errors'][] = [
                        'reason' => 'Organization [' . $order['school_URN'] . '] does not exist',
                        'row' => $order,
                    ];
                } else {
                    $orderLineValidation = OrderLine::validate($order['bulk_total'], $lines);

                    if (count($orderLineValidation['errors']) != 0) {
                        $result['errors'][] = $orderLineValidation['errors'];
                    } else {
                        $order['organization_id'] = $org->id;
                        unset($order['school_URN']);

                        $order['created_at'] = $datetime;
                        $order['updated_at'] = $datetime;

                        $id = DB::table(self::$dbTableName)
                            ->insertGetId($order);

                        OrderLine::addRows($id, $lines);
                    }
                }
            }
        }

        return $result;
    }

    /**
     * Import the orders in the array..
     *
     * @return array
     */
    public static function importRows(array $csvRows): array
    {
        $orderHeaders = array_shift($csvRows);
        $orderHeaders = array_combine($orderHeaders, array_flip(str_replace(' ', '', $orderHeaders)));

        $orders = [];

        foreach ($csvRows as $row) {

            if (count($row) < count(self::$importColumns)) {
                break;
            }

            $orderId = $row[$orderHeaders['order_id']];

            if (!isset($orders[$orderId])) {
                $orders[$orderId] = self::extract($orderHeaders, self::$importColumns, $row);

                $orders[$orderId]['lines'] = [];
            }

            $orders[$orderId]['lines'][] = self::extract($orderHeaders, OrderLine::$importColumns, $row);
        }

        return $orders;
    }
}
