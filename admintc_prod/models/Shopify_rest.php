<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Shopify_rest extends CI_Model {

	/**
	 *
	 * Shopify_rest Model
	 *
	 * Model responsible by all products system to shopify
	 *
	 */

	//private $hostname = "terracotta-new.myshopify.com";
	//private $key = "05726802f8b8ae78af5cedfa7cff2aca";
	//private $password = "bae5ca655036f2bb2431113c890a4bdc";
	private $hostname = "terracotta.myshopify.com";
	private $key = "65553f21afeae9d2bdd0f593f7a679af";
	private $password = "9f0032ea537c09ceae6907c797e45c4a";

	//POST /admin/products.json
	public function post($products)
	{
		// Come from Stock Controller stock_in

		$url_base = "https://".$this->key.":".$this->password."@".$this->hostname;

		$body = array(
			'product' => array(
				'title' => $products['title'],
				'product_type' => $products['type'],
				'published' => false,
				'options' => array(
					'0' => array(
						'name' => $products['model'] )
					),
				'variants' => array(
					'0' => array(
						'option1' => $products['variant'],
						'price' => $products['newPrice'],
						'sku' => $products['sku'],
						'inventory_management' => "shopify",
						'inventory_policy' => "deny"
					)

				)

			)
		);

		$data_string = json_encode($body);

		// Old Shopify url updated to versioned API
		//$ch = curl_init($url_base.'/admin/products.json');

		$ch = curl_init($url_base.'/admin/api/2021-01/products.json');

		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			'Content-Type: application/json',
			'Content-Length: ' . strlen($data_string))
		);

		return curl_exec($ch);

	}

	//PUT /admin/variants/#{id}.json
	public function updt_qty_new($idInventory,$qty)
	{
		
		$url_base = "https://".$this->key.":".$this->password."@".$this->hostname;

		//$data_string = '{"variant":{"id":"'.$idVar.'","inventory_quantity":"'.$qty.'"}}';

		//{"location_id": 6884556842,"inventory_item_id": 12250274365496,"available": 1}

		$data_string = '{"location_id": 11469453,"inventory_item_id": '.$idInventory.',"available": '.$qty.'}';

		//$url = $url_base."/admin/variants/".$idVar.".json";
		$url = $url_base."/admin/api/2021-01/inventory_levels/set.json";
		$ch = curl_init($url);

		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		//curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			'Content-Type: application/json',
			'Content-Length: ' . strlen($data_string))
		);

		return curl_exec($ch);

	}

	//PUT /admin/variants/#{id}.json
	public function addsku($ids)
	{
		// Come from ??? - Not in use

		$url_base = "https://".$this->key.":".$this->password."@".$this->hostname;

		$data_string = '{"variant":{"id":"'.$ids['idVar'].'","sku":"'.$ids['sku'].'"}}';

		$url = $url_base."/admin/variants/".$ids['idVar'].".json";
		$ch = curl_init($url);

        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
		    'Content-Type: application/json',
		    'Content-Length: ' . strlen($data_string))
		);

		curl_exec($ch);

	}

	//POST /admin/products/#{id}/variants.json
	public function vpost($variant)
	{

		$url_base = "https://".$this->key.":".$this->password."@".$this->hostname;

		$body = array(
			'variant' => array(
				'option1' => $variant['variant'],
				'price' => $variant['newPrice'],
				'sku' => $variant['sku'],
				'inventory_management' => "shopify",
//				'inventory_policy' => "deny",
				'inventory_policy' => "deny"
//				'inventory_quantity' => $variant['quantity']
			)
		);

		$data_string = json_encode($body);

		// Old Shopify url updated to versioned API
		$ch = curl_init($url_base.'/admin/products/'.$variant['idShopify'].'/variants.json');

		//$ch = curl_init($url_base.'/admin/api/2021-01/products/'.$variant['idShopify'].'/variants.json');

		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_ENCODING, 'UTF-8');
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
		    'Content-Type: application/json',
		    'Content-Length: ' . strlen($data_string))
		);
 
		return curl_exec($ch);

	}

	//PUT /admin/products/#{id}.json
	public function put($editInfo)
	{

		$url_base = "https://".$this->key.":".$this->password."@".$this->hostname;

		$data_string = '{"product":{"id":"'.$editInfo['idShopify'].'","product_type":"'.$editInfo['type'].'","title":"'.$editInfo['title'].'","vendor":"'.$editInfo['vendor'].'","options":[{"id":"'.$editInfo['idOptions'].'","name":"'.$editInfo['model'].'"}]}}';

		$url = $url_base."/admin/products/".$editInfo['idShopify'].".json";
		$ch = curl_init($url);

		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			'Content-Type: application/json',
			'Content-Length: ' . strlen($data_string))
		);
 
		curl_exec($ch);

	}

	//PUT /admin/variants/#{id}.json
	public function vput($editInfo)
	{
		
		$url_base = "https://".$this->key.":".$this->password."@".$this->hostname;

		//Old inventory fields
		//$data_string = '{"variant":{"id":"'.$editInfo['idVar'].'","option1":"'.$editInfo['variant'].'","price":"'.$editInfo['salesPrice'].'","inventory_quantity":"'.$editInfo['stockQty'].'","sku":"'.$editInfo['sku'].'"}}';
		$data_string = '{"variant":{"id":"'.$editInfo['idVar'].'","option1":"'.$editInfo['variant'].'","price":"'.$editInfo['salesPrice'].'","sku":"'.$editInfo['sku'].'"}}';

		$url = $url_base."/admin/variants/".$editInfo['idVar'].".json";
		$ch = curl_init($url);

		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			'Content-Type: application/json',
			'Content-Length: ' . strlen($data_string))
		);

		curl_exec($ch);

		//New inventory fields
		$this->updt_qty_new($editInfo['idInventory'],$editInfo['stockQty']);

	}

	//GET /admin/products/632910392.json
	public function get_product($idShopify)
	{
		
		$url_base = "https://".$this->key.":".$this->password."@".$this->hostname;

		$url = $url_base."/admin/products/".$idShopify.".json";

		$ch = curl_init($url);

		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

		$output = curl_exec($ch);

		echo $output;

	}

	//Retrieve the item's inventory levels
	public function get_inventoryLevel($idInventory)
	{
		//https://{shop}.myshopify.com/admin/api/2021-01/inventory_levels.json?inventory_item_ids={inventory_item_id}
		
		$url_base = "https://".$this->key.":".$this->password."@".$this->hostname;

		$url = $url_base."/admin/api/2021-01/inventory_levels.json?inventory_item_ids=".$idInventory;
//		$url = $url_base."/admin/products/".$idShopify."/variants/".$idVar.".json";

		$ch = curl_init($url);

		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

		$output = curl_exec($ch);

		return $output;

	}

}