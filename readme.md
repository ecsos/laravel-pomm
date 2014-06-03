## Pomm Service Provider for Laravel

This package provides a very basic service provider to access a pomm connection through your default connection (which should be postgresql).

#### Usage
	$map = $app['pomm.connection']->getMapFor('/Your/Pomm/Class');
	