<?php
require_once 'listings_data_access.php';
/*
"geo": {
        "accuracy": 4,
        "district": null,
        "city": "Laval",
        "state": "Quebec",
        "country": "Canada",
        "country_code": "CA",
        "state_short": "QC",
        "natural_feature": null,
        "result_type": "locality",
        "colloquial_area": null,
        "success?": true,
        "market": "Montreal"
    }
    */


$listingOutput = \core\api\Listings::getListingsCollection();
header('Content-Type: application/json; charset=utf-8');
echo \core\api\Listings::jsonEncode($listingOutput);