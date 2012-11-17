<?php
namespace wiki\util;

use wcf\util\StringUtil;

/**
 * @author	Rene Gessinger (NurPech)
 * @copyright	2012 woltnet
 * @package	com.woltnet.wiki
 * @subpackage	util
 * @category 	WoltNet - Wiki
 */
class KeywordUtil {

	/**
	 * Returns an array of Keywords
	 *
	 * @param	 string 	$text
	 * @param	 array 		$stopwords
	 * @return	 array 		$keywords
	 */
	public static function getKeywors($text, array $stopwords=array()) {
		$text = StringUtil::replace($stopwords,'',$text);
		$text = StringUtil::replace(array('-','_'),array(' '),$text);
		$pattern = '/\b[A-Z���]{1}+[A-Za-z����������]{2,}+ [A-Z���]{1}+[A-Za-z����������]{2,}+ [A-Z���]{1}+[A-Za-z����������]{2,}+|[A-Z���]{1}+[A-Za-z����������]{2,}+ [A-Z���]{1}+[A-Za-z����������]{2,}+|[A-Z\���]{1}+[A-Za-z����������]{2,}+\b/';
		preg_match_all($pattern, $text, $array);
		$array[0] = array_map('ucwords', array_map('strtolower', $array[0]));
		$output = array_count_values($array[0]);
		$output = array_slice($output,0,39);
		array_multisort($output, SORT_DESC);
		$keywords = array_keys($output);

		return $keywords;
	}
}
