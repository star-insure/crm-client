<?php

return [
    /**
     * URL to the API, e.g. "https://crm.starinsure.co.nz"
     * No trailing slash, no "/api" suffix – we add those later.
     */
    'api_url' => env('CRM_API_URL', 'https://crm.starinsure.co.nz'),

    /**
     * A pre-generated long lifen access token
     * Required if "auth_strategy" is "app"
     */
    'token' => env('CRM_API_TOKEN', ''),

    /**
     * Which group are we acting within? ("administrator" is 2)
     * Required if "auth_strategy" is "app"
     */
    'group_id' => env('CRM_API_GROUP_ID', '2'),

    /**
     * The API version to use, e.g. "v1"
     */
    'version' => env('CRM_API_VERSION', 'v1'),
];
