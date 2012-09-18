<?php
		header("Content-Type:text/css");
		header("Cache-Control:max-age=". (3600 * 24 * 30));
		$expire_date = date('r', time() + (3600 * 24 * 30));
		header("Expires:". $expire_date);
		echo "aaabbb";
