<?php
/**
 * @created Alexey Kutuzov <lexus27.khv@gmail.com>
 * @Project: ceive.text-replacer
 */

namespace Ceive\Text;

/**
 * Class Replacer
 * @package Ceive\Util\Text
 */
class Replacer implements ReplacerInterface{
	
	/** @var ReplacerInterface[] */
	protected static $static_replacers = [];
	
	/** @var  string */
	protected $open;
	
	/** @var  string */
	protected $close;
	
	/** @var string */
	protected $pattern;
	
	/**
	 * Replacer constructor.
	 * @param null $open
	 * @param null $close
	 * @param string $pattern
	 */
	public function __construct($open = null, $close = null, $pattern = null){
		$this->pattern = $pattern?:'\w+';
		//@TODO implemented choice of bracers open close arrays choices by index up
		$this->open = $open?:'{{';
		$this->close = $close?:'}}';
	}
	
	
	/**
	 * 19/07/2017 implemented escaping as in ceive.text-replacer.js OK
	 * @TODO implemented choice of bracers as in ceive.text-replacer.js
	 * @param $text
	 * @param callable $evaluator
	 * @param boolean $safe_type @TODO safe_type if template contains only placeholder without other string chars
	 * @return mixed
	 */
	public function replace($text, callable $evaluator, $safe_type = false){
		$escaped = '\\\\\\\\|\\\\'.preg_quote($this->open);
		$regexp = '@'. $escaped .'|('.preg_quote($this->open).')('.$this->pattern.')('.preg_quote($this->close).')@S';
		
		if($safe_type){
			
		}
		$lastPh = null;
		$lastValue = null;
		$result = preg_replace_callback($regexp, function($m) use($evaluator, &$lastPh, & $lastValue){
			if($m[0] === '\\\\'){
				return '\\';
			}else if(substr($m[0],0,1) === '\\'.$this->open){
				return $this->open;
			}else{
				// $placeholder_name, $bracket_open, $bracket_close, $placeholder_full
				$value = call_user_func($evaluator, $m[2], $m[1], $m[3], $m[0]);
				$lastPh = $m[0];
				$lastValue = $value;
				return $value;
			}
		}, $text);
		
		if($safe_type && $lastPh === $text){
			return $lastValue;
		}
		return $result;
	}
	
	/**
	 * Общий статичный регистр
	 * @param $open
	 * @param $close
	 * @param $pattern
	 * @return ReplacerInterface
	 */
	public static function getStaticReplacer($open, $close, $pattern){
		$k = md5(serialize([$open,$close, $pattern]));
		if(!isset(self::$static_replacers[$k])){
			self::$static_replacers[$k] = new Replacer($open, $close, $pattern);
		}
		return self::$static_replacers[$k];
	}
	
	/**
	 * Общий статичный регистр
	 * @param $key
	 * @return ReplacerInterface
	 */
	public static function getStaticReplacerByKey($key){
		if(self::$static_replacers[$key]){
			return self::$static_replacers[$key];
		}
		return null;
	}
	
	/**
	 * Общий статичный регистр
	 * @param $key
	 * @param ReplacerInterface $replacer
	 */
	public static function setStaticReplacerByKey($key, ReplacerInterface $replacer){
		self::$static_replacers[$key] = $replacer;
	}
}


