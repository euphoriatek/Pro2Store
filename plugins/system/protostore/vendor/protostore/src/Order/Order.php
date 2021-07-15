<?php

/**
 * @package   Pro2Store - Helper
 * @author    Ray Lawlor - pro2.store
 * @copyright Copyright (C) 2020 Ray Lawlor - pro2.store
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

// no direct access
namespace Protostore\Order;

defined('_JEXEC') or die('Restricted access');


class Order
{

	public int $id;
	public int $customer;
	public string $order_date;
	public string $order_number;
	public int $order_paid;
	public int $order_subtotal;
	public string $order_status;
	public string $order_status_formatted;
	public int $order_total;
	public ?int $shipping_total;
	public ?int $tax_total;
	public string $currency;
	public ?string $payment_method;
	public ?string $customer_notes;
	public ?string $vendor_token;
	public ?string $guest_pin;
	public ?int $billing_address_id;
	public ?int $shipping_address_id;
	public int $published;
	public ?string $discount_code;
	public ?int $discount_total;
	public ?int $donation_total;

	// price as formatted Strings:

	public ?string $order_total_formatted;
	public ?string $order_subtotal_formatted;
	public ?string $shipping_total_formatted;
	public ?string $tax_total_formatted;
	public ?string $discount_total_formatted;
	public ?string $donation_total_formatted;

	// products

	public ?array $ordered_products;
	public ?int $product_count;


	// Tracking

	public ?string $tracking_code;
	public ?string $tracking_link;
	public ?string $tracking_created;

	public function __construct($data)
	{

		if ($data)
		{
			$this->hydrate($data);
			$this->init();
		}

	}

	/**
	 *
	 * Function to simply "hydrate" the database values directly to the class parameters.
	 *
	 * @param $data
	 *
	 *
	 * @since 1.6
	 */

	private function hydrate($data)
	{
		foreach ($data as $key => $value)
		{

			if (property_exists($this, $key))
			{
				$this->{$key} = $value;
			}

		}
	}

	/**
	 *
	 * Function to "hydrate" all non-database values.
	 *
	 * @throws \Brick\Money\Exception\UnknownCurrencyException
	 * @since 1.6
	 */

	private function init()
	{

		// set all the formats for the money values.
		$this->order_status_formatted = OrderFactory::getStatusFormatted($this->order_status);
		$this->order_total_formatted  = OrderFactory::intToFormat($this->order_total, $this->currency);

		$this->order_subtotal_formatted = OrderFactory::intToFormat($this->order_subtotal, $this->currency);


		// get the £0.00 value for use on all formatted values if they are set to 0.
		$zeroFormatted = OrderFactory::intToFormat(0, $this->currency);


		$this->shipping_total_formatted = $zeroFormatted;
		if ($this->shipping_total)
		{
			$this->shipping_total_formatted = OrderFactory::intToFormat($this->shipping_total, $this->currency);
		}

		$this->tax_total_formatted = $zeroFormatted;
		if ($this->tax_total)
		{
			$this->tax_total_formatted = OrderFactory::intToFormat($this->tax_total, $this->currency);

		}

		$this->discount_total_formatted = $zeroFormatted;
		if ($this->discount_total)
		{
			$this->discount_total_formatted = OrderFactory::intToFormat($this->discount_total, $this->currency);
		}


		$this->donation_total_formatted = $zeroFormatted;
		if ($this->donation_total)
		{
			$this->donation_total_formatted = OrderFactory::intToFormat($this->donation_total, $this->currency);
		}

		// get the products ordered
		$this->ordered_products = OrderFactory::getOrderedProducts($this->id);
		if ($this->ordered_products)
		{
			$this->product_count = count($this->ordered_products);
		}


		$this->tracking_code = '';
		$this->tracking_link = '';
		$this->tracking_created = '';

		if($tracking = OrderFactory::getTracking($this->id)) {

			$this->tracking_code = $tracking->tracking_code;
			$this->tracking_link = $tracking->tracking_link;
			$this->tracking_created = $tracking->created;

		}

	}


}
