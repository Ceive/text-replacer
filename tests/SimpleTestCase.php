<?php
/**
 * @created Alexey Kutuzov <lexus27.khv@gmail.com>
 * @Project: ceive.text-replacer
 */

namespace Ceive\Text\Replacer\Tests;


use Ceive\Text\Replacer;

/**
 * @Author: Alexey Kutuzov <lexus27.khv@gmail.com>
 * Class SimpleTestCase
 * @package Ceive\Util\Text\Replacer\Tests
 */
class SimpleTestCase extends \PHPUnit_Framework_TestCase{
	
	/** @var  Replacer */
	protected $replacer;
	
	public function initialize(){
		$this->replacer = new Replacer('{{','}}','\w+');
	}
	
	public function replacingTest(){
		$data = [
			'title' => 'Happens',
			'for' => 'Peoples'
		];
		
		$template = 'My text a {{title}}, for {{for}}, and {{unknown-key}}';
		
		$result = $this->replacer->replace($template, function($key) use($data){
			return isset($data[$key])?$data[$key]:'...unknown-value...';
		});
		
		$this->assertEquals($result, 'My text a Happens, for Peoples, and ...unknown-value...');
	}
	
	
	public function escapingTest(){
		$data = [
			'title' => 'Happens',
			'for' => 'Peoples'
		];
		// use escaped placeholder {{title}} as plain text
		$template = 'My text a \\\\{{title}}, for {{for}}, and {{unknown-key}}';
		
		$result = $this->replacer->replace($template, function($key) use($data){
			return isset($data[$key])?$data[$key]:'...unknown-value...';
		});
		
		$this->assertEquals($result, 'My text a {{title}}, for Peoples, and ...unknown-value...');
	}
	
	public function staticReplacerTest(){
		//equals instance
		$this->assertEquals(
			Replacer::getStaticReplacer('{{','}}','\w+'),
			Replacer::getStaticReplacer('{{','}}','\w+')
		);
		
		$this->assertNotEquals(
			Replacer::getStaticReplacer('{{','}}','\w+'),
			Replacer::getStaticReplacer('{{','}}','\w+\d+')
		);
	}
}


