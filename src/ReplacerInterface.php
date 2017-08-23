<?php
/**
 * @created Alexey Kutuzov <lexus27.khv@gmail.com>
 * @Project: ceive.text-replacer
 */

namespace Ceive\Text;


interface ReplacerInterface{
	
	public function replace($text, callable $evaluator);
	
}

