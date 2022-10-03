<?php

namespace App\Http\Controllers;

use App\Models\Order;

class OrderController extends Controller
{
    /**
     * Undocumented function
     *
     * @return void
     */
    public function get()
    {
        return Order::getOrders();
    }
}
