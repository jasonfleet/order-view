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
     * Test for import.
     * Uses storage/TestOrder.csv
     *
     * @return void
     */
    public function importOrdersTest()
    {
        $csv = array_map('str_getcsv', (explode("\n", Storage::get('TestOrders.csv'))));

        $csvResult = Order::importRows($csv);

        $result = Order::addRows($csvResult);

        return response(
            json_encode([
                'db' => Order::getAll(Order::$dbTableName),
                'result' => $result,
                'test-csv' => $csvResult,
            ],  JSON_PRETTY_PRINT)
        )
            ->header('Content-Type', 'application/json');
    }

    /**
     * Test for import.
     * Uses storage/app/Organizations.csv
     *
     * @return void
     */
    public function importOrganizationsTest()
    {
        $csv = array_map('str_getcsv', (explode("\n", Storage::get('Organizations.csv'))));

        $csvResult = Organization::importRows($csv);

        $result = Organization::addRows($csvResult);

        return response(
            json_encode([
                'db' => Organization::getAll(Organization::$dbTableName),
                'result' => $result,
                'csv' => $csvResult,
            ],  JSON_PRETTY_PRINT)
        )
            ->header('Content-Type', 'application/json');
    }

    /**
     * Test for import.
     * Uses storage/app/Products.csv
     *
     * @return void
     */
    public function importProductsTest()
    {
        $csv = array_map('str_getcsv', (explode("\n", Storage::get('Products.csv'))));

        $csvResult = Product::importRows($csv);

        Product::addRows($csvResult);

        return response(
            json_encode([
                'db' => Product::getAll(Product::$dbTableName),
                'test-csv' => $csvResult,
            ],  JSON_PRETTY_PRINT)
        )
            ->header('Content-Type', 'application/json');
    }
}
