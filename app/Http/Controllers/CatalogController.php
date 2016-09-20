<?php

namespace App\Http\Controllers;

use App\Libraries\JSend;

use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

use App\Entities\Catalog;

/**
 * Catalog  resource representation.
 *
 * @Resource("Catalog", uri="/catalogs")
 */
class CatalogController extends Controller
{
	public function __construct(Request $request)
	{
		$this->request 				= $request;
	}

	/**
	 * Show all Catalogs
	 *
	 * @Get("/")
	 * @Versions({"v1"})
	 * @Transaction({
	 *      @Request({"search":{"_id":"string","name":"string","slug":"string","type":"simple|grouped|downloadable|affiliate|variable","price":"array|string"},"sort":{"newest":"asc|desc","price":"desc|asc","name":"desc|asc"}, "take":"integer", "skip":"integer"}),
	 *      @Response(200, body={"status": "success", "data": {"data":{"_id":"string","name":"string","slug":"string","type":"simple|grouped|downloadable|affiliate|variable","contents":{"string"},"images":{"path":"string"},"display":{"net_price":"number","discount":"number","stock":"number"}},"count":"integer"} })
	 * })
	 */
	public function index()
	{
		$result						= new Catalog;

		if(Input::has('search'))
		{
			$search					= Input::get('search');

			foreach ($search as $key => $value) 
			{
				switch (strtolower($key)) 
				{
					case '_id':
						$result		= $result->id($value);
						break;
					case 'name':
						$result		= $result->name($value);
						break;
					case 'slug':
						$result		= $result->slug($value);
						break;
					case 'type':
						$result		= $result->type($value);
						break;
					case 'price':
						$result		= $result->displaynetprice($value);
						break;
					default:
						# code...
						break;
				}
			}
		}

		if(Input::has('sort'))
		{
			$sort					= Input::get('sort');

			foreach ($sort as $key => $value) 
			{
				if(!in_array($value, ['asc', 'desc']))
				{
					return response()->json( JSend::error([$key.' harus bernilai asc atau desc.'])->asArray());
				}
				switch (strtolower($key)) 
				{
					case 'newest':
						$result		= $result->orderby('created_at', $value);
						break;
					case 'price':
						$result		= $result->orderby('display.net_price', $value)->orderby('display.varian.net_price', $value);
						break;
					case 'name':
						$result		= $result->orderby('name', $value);
						break;
					default:
						# code...
						break;
				}
			}
		}
		else
		{
			$result		= $result->orderby('name', 'asc');
		}

		$count						= count($result->get());

		if(Input::has('skip'))
		{
			$skip					= Input::get('skip');
			$result					= $result->skip($skip);
		}

		if(Input::has('take'))
		{
			$take					= Input::get('take');
			$result					= $result->take($take);
		}

		$result 					= $result->get();
		
		return response()->json( JSend::success(['data' => $result->toArray(), 'count' => $count])->asArray())
				->setCallback($this->request->input('callback'));
	}

	/**
	 * Store Catalog
	 *
	 * @Post("/")
	 * @Versions({"v1"})
	 * @Transaction({
	 *      @Request({"_id":"string","name":"string","slug":"string","type":"simple|grouped|downloadable|affiliate|variable","contents":{"string"},"images":{"path":"string"},"display":{"net_price":"number","discount":"number","stock":"number"}}),
	 *      @Response(200, body={"status": "success", "data": {"_id":"string","name":"string","slug":"string","type":"simple|grouped|downloadable|affiliate|variable","contents":{"string"},"images":{"path":"string"},"display":{"net_price":"number","discount":"number","stock":"number"}}}),
	 *      @Response(200, body={"status": {"error": {"code must be unique."}}})
	 * })
	 */
	public function post()
	{
		$id 			= Input::get('_id');

		if(!is_null($id) && !empty($id))
		{
			$result		= Catalog::id($id)->first();
		}
		else
		{
			$result 	= new Catalog;
		}
		

		$result->fill(Input::only('name', 'slug', 'type', 'contents', 'images', 'display'));

		if($result->save())
		{
			return response()->json( JSend::success($result->toArray())->asArray())
					->setCallback($this->request->input('callback'));
		}
		
		return response()->json( JSend::error($result->getError())->asArray());
	}

	/**
	 * Delete Catalog
	 *
	 * @Delete("/")
	 * @Versions({"v1"})
	 * @Transaction({
	 *      @Request({"id":null}),
	 *      @Response(200, body={"status": "success", "data": {"_id":"string","name":"string","slug":"string","type":"simple|grouped|downloadable|affiliate|variable","contents":{"string"},"images":{"path":"string"},"display":{"net_price":"number","discount":"number","stock":"number"}}}),
	 *      @Response(200, body={"status": {"error": {"code must be unique."}}})
	 * })
	 */
	public function delete()
	{
		$catalog		= Catalog::id(Input::get('_id'))->first();
		
		$result 		= $catalog;

		if($catalog && $catalog->delete())
		{
			return response()->json( JSend::success($result->toArray())->asArray())
					->setCallback($this->request->input('callback'));
		}

		if(!$catalog)
		{
			return response()->json( JSend::error(['ID tidak valid'])->asArray());
		}

		return response()->json( JSend::error($catalog->getError())->asArray());
	}
}