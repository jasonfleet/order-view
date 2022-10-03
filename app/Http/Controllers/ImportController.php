<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Organization;
use App\Models\Product;
use Illuminate\Support\Facades\Storage;

class ImportController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Import all from a single file.
     *
     * storage/app/TestOrders.csv must exist for this to work.
     *
     * @return void
     */
    public function all()
    {
        return response(
            json_encode([
                'organizations' => $this->importOrganizations('TestOrders.csv'),
                'products' => $this->importProducts('TestOrders.csv'),
                'orders' => $this->importOrders('TestOrders.csv'),
            ],  JSON_PRETTY_PRINT)
        )
            ->header('Content-Type', 'application/json');
    }
    /**
     * Import orders.
     *
     * @param string $filename
     * @return void
     */
    public function importOrders(string $filename)
    {
        $csv = array_map('str_getcsv', (explode("\n", Storage::get($filename))));

        $csvResult = Order::importRows($csv);

        $result = null;

        if (!isset($csvResult['error'])) {
            $result = Order::addRows($csvResult);
        }

        return [
            'db' => Order::getAll(Order::$dbTableName),
            'result' => $result,
            'csv' => $csvResult,
        ];
    }

    /**
     * Import organizations.
     *
     * @param string $filename
     * @return void
     */
    public function importOrganizations(string $filename)
    {
        $csv = array_map('str_getcsv', (explode("\n", Storage::get($filename))));

        $csvResult = Organization::importRows($csv);

        $result = null;

        if (!isset($csvResult['error'])) {
            $result = Organization::addRows($csvResult);
        }

        return [
            'db' => Organization::getAll(Organization::$dbTableName),
            'result' => $result,
            'csv' => $csvResult,
        ];
    }

    /**
     * Import products
     *
     * @param string $filename
     * @return void
     */
    public function importProducts(string $filename)
    {
        $csv = array_map('str_getcsv', (explode("\n", Storage::get($filename))));

        $csvResult = Product::importRows($csv);

        $result = null;

        if (!isset($csvResult['error'])) {
            $result = Product::addRows($csvResult);
        }

        return [
            'db' => Product::getAll(Product::$dbTableName),
            'result' => $result,
            'csv' => $csvResult,
        ];
    }

    /**
     * Orders
     *
     * @return void
     */
    public function orders()
    {
        return response(
            json_encode($this->importOrders('TestOrders.csv'),  JSON_PRETTY_PRINT)
        )
            ->header('Content-Type', 'application/json');
    }

    /**
     * Organizations
     *
     * @return void
     */
    public function organizations()
    {
        return response(
            json_encode($this->importOrganizations('Organizations.csv'),  JSON_PRETTY_PRINT)
        )
            ->header('Content-Type', 'application/json');;
    }

    /**
     * Organizations
     *
     * @return void
     */
    public function products()
    {
        return response(
            json_encode($this->importProducts('Products.csv'),  JSON_PRETTY_PRINT)
        )
            ->header('Content-Type', 'application/json');;
    }
}
