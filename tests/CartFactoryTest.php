<?php
/**
 * @package   Pro2Store
 * @author    Ray Lawlor - pro2.store
 * @copyright Copyright (C) 2021 Ray Lawlor - pro2.store
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 *
 */
define('_JEXEC', 1);

if (file_exists(__DIR__ . '/defines.php'))
{
	include_once __DIR__ . '/defines.php';
}

if (!defined('_JDEFINES'))
{
	define('JPATH_BASE', dirname(__DIR__,1));
	require_once JPATH_BASE . '/includes/defines.php';
}

require_once JPATH_BASE . '/includes/framework.php';

use Protostore\Cart\CartFactory;
use PHPUnit\Framework\TestCase;

class CartFactoryTest extends TestCase
{

	public function testGet()
	{


		$cart = CartFactory::get();

		$this->assertInstanceOf(\Protostore\Cart\Cart::class, $cart);

	}
//
//	public function testGetCurrentCartId()
//	{
//
//	}
//
//	public function testGetCount()
//	{
//
//	}
//
//	public function testGetSelectedVariant()
//	{
//
//	}
//
//	public function testInit()
//	{
//
//	}
//
//	public function testAddNonVariantToCart()
//	{
//
//	}
//
//	public function testRemoveAll()
//	{
//
//	}
//
//	public function testAddVariantToCart()
//	{
//
//	}
//
//	public function testGetCartItems()
//	{
//
//	}
//
//	public function testGetCartitem()
//	{
//
//	}
//
//	public function testGetSelectedOptions()
//	{
//
//	}
//
//	public function testChangeCount()
//	{
//
//	}
//
//	public function testAddToCart()
//	{
//
//	}
//
//	public function testGetVariantLabels()
//	{
//
//	}
}
