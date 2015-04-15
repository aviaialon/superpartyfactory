<?php
/**
 * This script exports the data into Elastic search
 */

header("Cache-Control: no-cache, must-revalidate");
header ("Pragma: no-cache");
header("Expires: Mon, 24 Sep 2012 04:00:00 GMT");

ob_start();
require_once 'listings_data_access.php';
$data = \core\api\Listings::getSearchData(false);
$esUrl = "http://192.168.2.15:9200/mls-premium/listings/%s?pretty";

ob_end_flush();
foreach ($data as $listing) {
	unset ($listing['distance_from_centerpoint']);
	// Lat / Lng points passed as array have to be array(lng, lat)
	// When passed as string, has to be lat, lng
	$listing['geo_point'] = array((float) $listing['longitude'], (float) $listing['latitude']);
	$listingInput = json_encode($listing);
	$ch = curl_init(sprintf($esUrl, (string) $listing['id']));
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");                                                                     
	curl_setopt($ch, CURLOPT_POSTFIELDS, $listingInput);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                                                                      
	curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
		'Content-Type: application/json',                                                                                
		'Content-Length: ' . strlen($listingInput))                                                                       
	);                                                                                                                   
	
	$result = curl_exec($ch);
	curl_close($ch);
	print_r($result);
	ob_end_flush();
    ob_flush();
}


/*
PUT http://192.168.2.15:9200/mls-premium/listings/_mappings?pretty

{
    "listings": {
        "properties": {
			"geo_point" : {
				"type": "geo_point",
				"fielddata": {
					"format": "compressed",
					"precision": "1cm"
				}
			},
            "latitude": {
				"type": "float"
			},
			"longitude": {
				"type": "float"
			},
			"acreage": {
				"type": "short"
			},
			"activeStatus": {
				"type": "byte"
			},
			"addressText": {
				"type": "string"
			},
			"airCondition": {
				"type": "short"
			},
			"bathrooms": {
				"type": "short"
			},
			"bedrooms": {
				"type": "short"
			},
			"centrisId": {
				"type": "string"
			},
			"date_added": {
				"type": "date",
				"format": "dateOptionalTime"
			},
			"description": {
				"type": "string"
			},
			"fireplace": {
				"type": "short"
			},
			"garage": {
				"type": "short"
			},
			"id": {
				"type": "integer"
			},
			"imagePosition1": {
				"type": "string"
			},
			"individualId": {
				"type": "integer"
			},
			"individual_full_name": {
				"type": "string"
			},
			"individual_last_name": {
				"type": "string"
			},
			"individual_name": {
				"type": "string"
			},
			"individual_photo": {
				"type": "string"
			},
			"individual_position": {
				"type": "string"
			},
			"individual_website": {
				"type": "string"
			},
			"isRentable": {
				"type": "byte"
			},
			"leaseRent": {
				"type": "float"
			},
			"link_url": {
				"type": "string"
			},
			"listingTypeText": {
				"type": "string"
			},
			"listingsBuildingTypeId": {
				"type": "integer"
			},
			"listingsBuildingTypeName": {
				"type": "string"
			},
			"listingsCategoryTypeId": {
				"type": "integer"
			},
			"mlsId": {
				"type": "string"
			},
			"org_address": {
				"type": "string"
			},
			"org_logo": {
				"type": "string"
			},
			"org_name": {
				"type": "string"
			},
			"org_website": {
				"type": "string"
			},
			"organizationId": {
				"type": "integer"
			},
			"ownershipTypeId": {
				"type": "integer"
			},
			"pool": {
				"type": "short"
			},
			"price": {
				"type": "float"
			},
			"sizeExterior": {
				"type": "string"
			},
			"sizeInterior": {
				"type": "string"
			},
			"stories": {
				"type": "string"
			},
			"views": {
				"type": "integer"
			},
			"waterfront": {
				"type": "string"
			}
        }
    }
}







QUERY BY BOUNDING BOX

GET http://192.168.2.15:9200/mls-premium/listings/_search?pretty


"top_left" : {
	"lat" : 45.565100672639424, = ne_lat
	"lon" : =-73.6835425817871 = ne_lng
},
"bottom_right" : {
	"lat" : 45.54646805374096, = sw_lat
	"lon" : -73.73778757690428 = sw_lng
}

RESULTS BY BOUNDING BOX - WORKING

{
    "query": {
        "match_all": {}
    },
    "filter": {
        "geo_bounding_box": {
            "listings.geo_point": {
                "top_left" : {
					"lat" : 45.565100672639424,
					"lon" : -73.6835425817871
				},
				"bottom_right" : {
					"lat" : 45.54646805374096,
					"lon" : -73.73778757690428
				}
            }
        }
    }
}


RESULTS BY DISTANCE (WILL RETURN THE DISTANCE IN RESULTS) - WORKING
WILL RETURN THE CLOSEST LISTINGS TO THE LAT LONG POINT
{
    "query": {
        "match_all": {}
    },
	"sort" : [
        {
            "_geo_distance" : {
                "listings.geo_point" : {
                    "lat" : 45.502885,
					"lon" : -73.702395	
				},
                "order" : "asc",
                "unit" : "km",
                "mode" : "min",
                "distance_type" : "sloppy_arc"
            }
        }
    ]
}


DISTANCE SEARCH WITH SORTING WITH SCRIPTED FIELDS
{
    "query": {
        "match_all": {}
    },
    "filter": {
    	"geo_distance" : {
                "distance" : "21km",
                "geo_point" : {
                    "lat" : 45.502885,
					"lon" : -73.702395
                }
            }
        }
    }
}






{
    "query": {
        "match_all": {}
    },
    "filter": {
			"geo_distance" : {
				"distance" : "21km",
				"geo_point" : {
					"lat" : 45.502885,
					"lon" : -73.702395
				}
			},
			
			"script" : {
				"script" : "doc[\u0027geo_point\u0027].empty ? null : doc[\u0027geo_point\u0027].arcDistanceInKm(lat, lon)"
        	}
        }
    },
	"sort" : [
        {
            "_geo_distance" : {
                "listings.geo_point" : {
                    "lat" : 45.502885,
					"lon" : -73.702395	
				},
                "order" : "asc",
                "unit" : "km",
                "mode" : "min",
                "distance_type" : "sloppy_arc"
            }
        }
    ]
}

SORTING WITH SCRIPTED FIELDS

{
    "query": {
        "match_all": {}
    },
    "filter": {
    "geo_distance" : {
                "distance" : "1km",
                "geo_point" : {
                    "lat" : 45.502885,
					"lon" : -73.702395
                }
            }
        
        }
    },
    "script_fields" : {
      "distance" : {
         "params" : {
            "lat" : 2.27,
            "lon" : 50.3
         },
         "script" : "doc[\u0027geo_point\u0027].distanceInKm(lat,lon)"
      }
   }
}


another version?

{
    "query": {
        "match_all": {}
    },
    "filter": {
    "geo_distance" : {
                "distance" : "200km",
                "geo_point" : {
                    "lat" : 45.565100672639424,
					"lon" : -73.6835425817871
                }
            }
        
        }
    }
}

*/
