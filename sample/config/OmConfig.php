<?php

// OM Paiment 
define("OM_PAIEMENT", "o_money");

// OM Environment 
define("OM_ENVIRONMENT", "production");

// OM Environment 
define("OM_ENDPOINTS_TOKEN", "https://api.orange.com/oauth/v3/token");

// OM REST API endpoints
define("OM_ENDPOINTS", array(
	"sandbox" => "https://api.orange.com/orange-money-webpay/dev",
	"production" => "https://api.orange.com/orange-money-webpay"
));

// OM REST App credentials
define("OM_CREDENTIALS", array(
	"sandbox" => [
		"client_secret" => ""
	],
	"production" => [
		"client_secret" => ""
	]
));

// OM Merchant Key App credentials
define("OM_MERCHANT", "");

// OM Merchant Reference
define("OM_REFERENCE", "");

// OM Transaction Prefix
define("OM_TRANSACTION_PREFIX", "");

// OM REST API version
define("OM_REST_VERSION", "v1");

// OM Currency for the API
define("OM_CURRENCY", "XOF");

// OM Country for the API
define("OM_COUNTRY", "ml");

// OM Langue for the API
define("OM_LANG", "fr");