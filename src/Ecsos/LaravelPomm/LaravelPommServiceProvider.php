<?php namespace Ecsos\LaravelPomm;

use Illuminate\Support\ServiceProvider;

use Config;
use Pomm\Service;

class LaravelPommServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;

	/**
	 * Bootstrap the application events.
	 *
	 * @return void
	 */
	public function boot()
	{
		$this->package('ecsos/laravel-pomm');
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		
		//
		$this->app['pomm.class_name'] = 'Pomm\Service';
		$this->app['pomm'] = $this->app->share(function($app){

			
			$dbc = Config::get('database.connections.'.Config::get('database.default'));
			//// laravel database config example
			// 'driver'   => 'pgsql',
			// 'host'     => 'localhost',
			// 'database' => 'uits',
			// 'username' => '',
			// 'password' => '',
			// 'charset'  => 'utf8',
			// 'prefix'   => '',
			// 'schema'   => 'schemaname',
			$pomm_database_config = array();
			$pomm_database_config['dsn'] = $dbc['driver'].'://'.$dbc['username'];
			if (!empty($dbc['password'])) { $pomm_database_config['dsn'] .=  ':'.$dbc['password']; }
			$pomm_database_config['dsn'] .= '@'.$dbc['host'].'/'.$dbc['database'];
			if (isset($dbc['connect_timeout'])) { $pomm_database_config['dsn'] .= ' connect_timeout='.$dbc['connect_timeout']; }
			if (isset($dbc['application_name'])) { $pomm_database_config['dsn'] .= ' application_name='.$dbc['application_name']; }
			
			return new $this->app['pomm.class_name'](array($pomm_database_config));
		});

		$this->app['pomm.connection'] = $this->app->share(function($app){

			$dbc = Config::get('database.connections.'.Config::get('database.default'));
			$conn = $app['pomm']->getDatabase()->getConnection();

			$charset = $dbc['charset'];

			$res = $conn->createPreparedQuery('SET client_encoding TO '.$charset)->execute();
			if (!empty($dbc['schema'])) {
					$schema = $dbc['schema'];

					$res = $conn->createPreparedQuery('SET search_path TO '.$schema)->execute();
			}
			return $conn;
		});
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return array('pomm', 'pomm.connection', 'pomm.class_name');
	}

}
