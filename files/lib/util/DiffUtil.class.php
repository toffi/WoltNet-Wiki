<?php
namespace wiki\util;

use wcf\util\StringUtil;

/**
 *
 * @author Rene Gessinger (NurPech)
 * @copyright 2012 woltnet
 * @package com.woltnet.wiki
 * @subpackage util
 * @category WoltNet - Wiki
 *          
 *           Paul's Simple Diff Algorithm v 0.1
 *           (C) Paul Butler 2007 <http://www.paulbutler.org/>
 *           May be used and distributed under the zlib/libpng license.
 */
class DiffUtil {
	
	/**
	 * Returns an array of Differences between two given arrays
	 *
	 * @param array $old        	
	 * @param array $new        	
	 * @return array $data
	 */
	public static function diff(array $old, array $new) {
		$maxlen = 0;
		foreach($old as $oindex => $ovalue) {
			$nkeys = array_keys($new, $ovalue);
			foreach($nkeys as $nindex) {
				$matrix[$oindex][$nindex] = isset($matrix[$oindex - 1][$nindex - 1]) ? $matrix[$oindex - 1][$nindex - 1] + 1 : 1;
				if($matrix[$oindex][$nindex] > $maxlen) {
					$maxlen = $matrix[$oindex][$nindex];
					$omax = $oindex + 1 - $maxlen;
					$nmax = $nindex + 1 - $maxlen;
				}
			}
		}
		if($maxlen == 0)
			return array (
					array (
							'd' => $old,
							'i' => $new 
					) 
			);
		return array_merge(self::diff(array_slice($old, 0, $omax), array_slice($new, 0, $nmax)), array_slice($new, $nmax, $maxlen), self::diff(array_slice($old, $omax + $maxlen), array_slice($new, $nmax + $maxlen)));
	}
	
	/**
	 * Returns an array of Differences between two given arrays
	 *
	 * @param array $old        	
	 * @param array $new        	
	 * @return array $data
	 */
	public static function getDiffArray(array $old, array $new) {
		$l = 0;
		$data = array ();
		foreach(self::diff($old, $new) as $lineNum => $line) {
			$data[$lineNum] = array (
					'line' => $lineNum,
					'normal' => '',
					'add' => '',
					'del' => '' 
			);
			if(! is_array($line)) {
				$data[$lineNum]['normal'] = chop(StringUtil::replace("<", "&lt;", $line));
			} else {
				$i = 0;
				while(isset($line['d'][$i]) || isset($line['i'][$i])) {
					if(isset($line['d'][$i]) && (! isset($line['i'][$i]) || ($line['d'][$i] != $line['i'][$i]))) {
						$data[$lineNum]['del'] = chop(StringUtil::replace("<", "&lt;", $line['d'][$i]));
					}
					if(isset($line['i'][$i]) && (! isset($line['d'][$i]) || ($line['i'][$i] != $line['d'][$i]))) {
						$data[$lineNum]['add'] = chop(StringUtil::replace("<", "&lt;", $line['i'][$i]));
					}
					$i ++;
				}
				$l ++;
			}
		}
		return $data;
	}
}
