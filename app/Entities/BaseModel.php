<?php 

namespace App\Entities;

use Jenssegers\Mongodb\Eloquent\Model;

use Illuminate\Support\MessageBag;
use Validator;

/**
 * Abstract class for eloquent models Models
 * 
 * @author cmooy
 */
abstract class BaseModel extends Model 
{
	/* ---------------------------------------------------------------------------- ERRORS ----------------------------------------------------------------------------*/

	protected $errors;
	
	/**
	 * return errors
	 *
	 * @return MessageBag
	 **/
	function getError()
	{
		return $this->errors;
	}

	/* ---------------------------------------------------------------------------- RELATIONSHIP ----------------------------------------------------------------------------*/
	
	/* ---------------------------------------------------------------------------- QUERY BUILDER ----------------------------------------------------------------------------*/
	
	/* ---------------------------------------------------------------------------- MUTATOR ----------------------------------------------------------------------------*/
	
	/* ---------------------------------------------------------------------------- ACCESSOR ----------------------------------------------------------------------------*/
	
	/* ---------------------------------------------------------------------------- FUNCTIONS ----------------------------------------------------------------------------*/
		
	/**
	 * construct function, iniate error
	 *
	 */
	function __construct() 
	{
		parent::__construct();

		$this->errors = new MessageBag;
	}

	/**
	 * boot function inherit eloquent
	 *
	 */
	public static function boot() 
	{
        parent::boot();

        static::saving(function($model)
		{
			if(isset($model['rules']))
			{
				$validator 				= Validator::make($model['attributes'], $model['rules']);

				if(!$validator->passes())
				{
					$model['errors'] 	= $validator->errors();

					return false;
				}

			}
		});
    }

	/* ---------------------------------------------------------------------------- SCOPES ----------------------------------------------------------------------------*/

	/**
	 * scope search based on id (pk)
	 *
	 * @param string or array of id
	 */	
	public function scopeID($query, $variable)
	{
		if(is_array($variable))
		{
			return 	$query->whereIn('_id', $variable);
		}

		if(is_null($variable))
		{
			return $query;
		}

		return 	$query->where('_id', $variable);
	}

	/**
	 * scope search based on not id (pk)
	 *
	 * @param string or array of id
	 */	
	public function scopeNotID($query, $variable)
	{
		if(is_array($variable))
		{
			return 	$query->whereNotIn('_id', $variable);
		}

		if(is_null($variable))
		{
			return $query;
		}

		return 	$query->where('_id', '<>', $variable);
	}
}