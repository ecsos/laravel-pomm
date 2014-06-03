<?php namespace Ecsos\LaravelPomm\Facades;

use Illuminate\Support\Facades\Facade;

class LaravelPomm extends Facade {

	protected static function getFacadeAccessor() {
		return 'laravelpomm';
	}

}