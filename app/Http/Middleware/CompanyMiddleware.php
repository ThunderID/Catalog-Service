<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

use Carbon\Carbon;
use App\Libraries\JSend;
use GenTux\Jwt\GetsJwtToken;

class CompanyMiddleware
{
	 use GetsJwtToken;

	/**
	 * Handle an incoming request.
	 *
	 * @param \Illuminate\Http\Request $request
	 * @param \Closure $next
	 *
	 * @return mixed
	 */
	public function handle($request, Closure $next, $sub)
	{
		$sub 						= explode('-', $sub);

		$payload                    = $this->jwtPayload();
		$now 						= time();

		// 1. check expire
		if($now > $payload['exp'])
		{
			return response()->json( JSend::error(['Token Expired'])->asArray());
		}

		// 2. check scopes
		foreach ($payload['content']['scopes'] as $key => $value) 
		{
			if (str_is($sub[0].'.all.'.$sub[1], $value)) 
			{
				return $next($request);
			}

			if (str_is($sub[0].'.company.'.$sub[1], $value)) 
			{
				$request->input('issuedcompany', $payload['content']['company']['code']);

				return $next($request);
			}

			if (str_is($sub[0].'.my.'.$sub[1], $value)) 
			{
				$request->input('issuedcustomer', $payload['content']['user']['code']);

				return $next($request);
			}
		}

		return response()->json( JSend::error(['Unauthorized User'])->asArray());
	}
}

