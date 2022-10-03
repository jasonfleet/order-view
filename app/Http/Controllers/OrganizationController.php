<?php

namespace App\Http\Controllers;

use App\Models\Organization;

class OrganizationController extends Controller
{
    /**
     * Undocumented function
     *
     * @return void
     */
    public function get()
    {
        return Organization::getOrganizations();
    }
}
