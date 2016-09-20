<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;

class Controller extends BaseController
{
	use \Thunderlab\Microservices\Controllers\MicroserviceTrait;

	public function __construct(Request $request)
	{
		$this->amqp_init();
	}

	public function doSomething()
	{
		//----------------------------
		// get jwt token    
		//----------------------------
		$jwt_token 	= $this->get_jwt_token();

		//----------------------------
		// call microservice
		//----------------------------
		// use guzzle code

		//----------------------------
		// send or listen to message broker
		//----------------------------
		$this->amqp->publish($data, $routing_key);
		$this->amqp->listen($queue_name, $topic, $callback);
	}
}
