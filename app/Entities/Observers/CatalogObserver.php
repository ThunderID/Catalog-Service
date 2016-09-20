<?php 

namespace App\Entities\Observers;

use Illuminate\Support\MessageBag;
use Illuminate\Support\Str;

use App\Entities\Catalog as Model; 

/**
 * Used in CLient model
 *
 * @author cmooy
 */
class CatalogObserver 
{
	public function saving($model)
	{
		$model->slug 			= $this->generateslug($model->name, (is_null($model->id) ? 0 : $model->id));

		if(isset($model['display']['varian']))
		{
			$display 			= $model['display'];

			foreach ($model['display']['varian'] as $key => $value) 
			{
				$display['varian'][$key]['net_price']	= $model['display']['varian'][$key]['net_price'] * 1;
			}

			$model->display 							= $display;
		}

		if(isset($model['display']['net_price']))
		{
			$display 				= $model['display'];
			$display['net_price']	= $model['display']['net_price'] * 1;	
		
			$model->display			= $display;
		}

		return true;
	}

	public function generateslug($name, $id)
	{
		do
		{
			$name 				= $name.'-'.uniqid();

			$slug 				= Str::slug($name);

			$exists_slug 		= Model::slug($slug)->notid($id)->first();
		}

		while($exists_slug);

		return $slug;
	}
}
