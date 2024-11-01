<?php
class dateWns {
	static public function _($time = NULL) {
		if(is_null($time)) {
			$time = time();
		}
		return date(WNS_DATE_FORMAT_HIS, $time);
	}
}