<?php

class IPv4Test extends PHPUnit_Framework_TestCase
{
	// see http://www.miniwebtool.com/ip-address-to-binary-converter/
	// and http://www.miniwebtool.com/ip-address-to-hex-converter
	public function validAddresses()
	{
		return array(
			array('127.0.0.1', '127.0.0.1', '2130706433', '01111111000000000000000000000001', '7f000001'),
			array('10.0.0.1', '10.0.0.1', '167772161','00001010000000000000000000000001', 'a000001'),
			array('0.0.0.0', '0.0.0.0', '0', '00000000000000000000000000000000', '00000000'),
			array('0.0.0.1', '0.0.0.1', '1', '00000000000000000000000000000001', '00000001'),
			array('255.255.255.254', '255.255.255.254', '4294967294', '11111111111111111111111111111110', 'fffffffe'),
			array('255.255.255.255', '255.255.255.255', '4294967295', '11111111111111111111111111111111', 'ffffffff'),
			array(ip2long('10.0.0.1'), '10.0.0.1', '167772161', '00001010000000000000000000000001', 'a000001'),
			array(ip2long('255.255.255.255'), '255.255.255.255', '4294967295', '11111111111111111111111111111111', 'ffffffff'),
			array(-1, '255.255.255.255', '4294967295', '11111111111111111111111111111111', 'ffffffff'),
			array('1', '0.0.0.1', '1', '00000000000000000000000000000001', '00000001'),
			array('4294967295', '255.255.255.255', '4294967295', '11111111111111111111111111111111', 'ffffffff'),
			array(inet_pton('10.0.0.1'), '10.0.0.1', '167772161', '00001010000000000000000000000001', 'a000001'),
			array(inet_pton('255.255.255.255'), '255.255.255.255', '4294967295', '11111111111111111111111111111111', 'ffffffff'),
		);
	}

	public function invalidAddresses()
	{
		return array(
			array("\t"),
			array("abc"),
			array(12.3),
			array(-12.3),
			array('-1'),
			array('4294967296'),
			array('2a01:8200::'),
			array('::1')
		);
	}

	/**
	 * @dataProvider validAddresses
	 */
	public function testConstructValid($ip, $string)
	{
		$instance = new IPv4($ip);
		$this->assertEquals($string, (string) $instance);
	}

	/**
	 * @dataProvider invalidAddresses
	 * @expectedException InvalidArgumentException
	 */
	public function testConstructInvalid($ip)
	{
		$instance = new IPv4($ip);
	}

	/**
	 * @dataProvider validAddresses
	 */
	public function testConvertToNumeric($ip, $string, $dec, $bin, $hex)
	{
		$instance = new IPv4($ip);
		$this->assertEquals($dec, $instance->numeric(), "Base 10 convertion of $string");
		$this->assertEquals($bin, $instance->numeric(2), "Base 2 (bin) convertion of $string");
		$this->assertEquals($hex, $instance->numeric(16), "Base 16 (hex) convertion of $string");
	}

	/**
	 * @expectedException OutOfBoundsException
	 */
	public function testPlusOob()
	{
		$ip = new IPv4('255.255.255.255');
		$ip->plus(1);
	}

	/**
	 * @expectedException OutOfBoundsException
	 */
	public function testMinusOob()
	{
		$ip = new IPv4('0.0.0.0');
		$ip->minus(1);
	}

	/**
	 * @expectedException OutOfBoundsException
	 */
	public function testPlusMinusOob()
	{
		$ip = new IPv4('0.0.0.0');
		$ip->plus(-1);
	}
}