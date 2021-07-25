<?php

if (! function_exists('capsule'))
{
	function capsule()
	{
		return service('dbtoolkit')->capsule();
	}
}