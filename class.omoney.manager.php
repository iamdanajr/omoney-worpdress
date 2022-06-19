<?php

	class OmoneyManager
	{


		/*** Orange money configuration attributes */
		private $om_version = "v1";
		private $om_lang    = "fr";
		private $om_country = "ml";

		private $om_token_endpoint;
		private $om_dev_endpoint;
		private $om_prod_endpoint;

		private $om_token;
		private $om_http;
		
		public $om_reference;
		public $om_environnement;
		public $om_endpoint;
		public $om_merchant;
		public $om_secret;
		public $om_currency;
		public $om_prefix;


		/**
			*   Orange money constructor.
			*	
			*	@param int    $om_environnement : 0 for the test environement, 1 for the production environement.
			*	@param string $om_reference : The reference of user to be displayed in payment page.
			*	@param string $om_merchant : The merchant code to be used to identified the merchant.
			*	@param string $om_secret : The merchant secret to be used for the authorization.
			*	@param string $om_prefix : The prefix to add to the transaction id.
		*/

		public function __construct($om_environnement = 0, $om_reference, $om_merchant, $om_secret, $om_prefix = "OM"){

			$this->init();

			$this->om_environnement = $om_environnement;
			$this->om_reference = $om_reference;
			$this->om_merchant = $om_merchant;
			$this->om_secret = $om_secret;
			$this->om_prefix = substr(strtoupper($om_prefix),0,5);

			if ($this->om_environnement == 0) {
				$this->om_currency = "OUV" ;
			}else{
				$this->om_currency = "XOF" ;
			}

		}

		/**
			* 	Generate endpoint for the OmClass helper the OM REST endpoint url.
		*/
		public function init(){

			/**
			 * Endpoint configuration
			 */
			$this->om_token_endpoint = "https://api.orange.com/oauth/v3/token";
			$this->om_dev_endpoint   = "https://api.orange.com/orange-money-webpay/dev/".$this->om_version;
			$this->om_prod_endpoint  = "https://api.orange.com/orange-money-webpay/".$this->om_country."/".$this->om_version;
			
			/**
			 * HttpHelper configuration
			 */
			$this->om_http           = new OmoneyHttp();

		}

		/**
			* 	Call private webpayment  class function to forward curl request to helper.
			*	
			* 	Check for bearer token.
			*	Call internal REST create order function.
			*
			*	@param array data Url to be called using curl
			* 	@return array Formatted API response
		*/
		public function webPayment($id, $montant, $return_url, $notif_url, $cancel_url) {

			if($this->om_token === null) {
				$this->getToken();
			}

			$data = array(
				"merchant_key" => $this->om_merchant,
				"currency" => $this->om_currency,
				"order_id" => $this->om_prefix."-".$id,
				"amount" => $montant,
				"return_url" => $return_url,
				"cancel_url" => $cancel_url,
				"notif_url" => $notif_url,
				"lang" => $this->om_lang,
				"reference" => $this->om_reference
			);

			$data = json_encode($data);

			$this->om_http->resetHelper();
			$this->om_http->setUrl($this->createApiUrl("webpayment"));
			
			$this->om_http->addHeader("Authorization: Bearer ". $this->om_token);
			$this->om_http->addHeader("Accept: application/json");
			$this->om_http->addHeader("Content-Type: application/json");
			
			$this->om_http->setPostBody($data);
			return $this->om_http->sendRequest();
		}

		/**
			* 	Call private check transaction class function to forward curl request to helper.
			*	
			* 	Check for bearer token.
			*	Call internal REST create order function.
			*
			*	@param array data Url to be called using curl
			* 	@return array Formatted API response
		*/

		public function checkTransactionStatus($orderId, $amount, $pay_token) {

			if($this->om_token === null) {
				$this->getToken();
			}

			$data = array(
				"order_id" => $this->om_prefix."-".$orderId,
				"amount" => $amount,
				"pay_token" => $pay_token
			);

			$data = json_encode($data);

			$this->om_http->resetHelper();
			$this->om_http->addHeader("Authorization: Bearer " . $this->om_token);
			$this->om_http->addHeader("Content-Type: application/json");
			$this->om_http->addHeader("Accept: application/json");
			$this->om_http->setUrl($this->createApiUrl("transactionstatus"));
			$this->om_http->setPostBody($data);
			return $this->om_http->sendRequest(); 

		}


		/**
			* 	Create the OM REST endpoint url.
			*
			*	Use the configurations and combine resources to create the endpoint.
			*
			*	@param string $route Url to be called using curl
			* 	@return string REST API url depending on environment.
		*/
		private function createApiUrl($route) {

			if ($this->om_environnement == 0) {
				return $this->om_dev_endpoint.  "/" . $route;
			}else{
				return $this->om_prod_endpoint.  "/" . $route;
			}

		}
		


		/**
			* 	Request for OM REST oath bearer token.
			*	
			* 	Reset curl helper. 
			*	Set default Omoney headers.
			*	Set curl url.
			*	Set curl credentials.
			*	Set curl body.
			*	Set class token attribute with bearer token.
			*
			* 	@return void
		*/
		private function getToken() {

			$this->om_http->resetHelper();
			$this->om_http->setUrl($this->om_token_endpoint);
			$this->om_http->addHeader("Content-Type: application/x-www-form-urlencoded");
			$this->om_http->addHeader("Accept: application/json");
			$this->om_http->addHeader("Authorization: Basic ".$this->om_secret);
			$this->om_http->setPostBody("grant_type=client_credentials");
			$returnData = $this->om_http->sendRequest();
			$this->om_token = $returnData['access_token'];	

		}

	}

?>