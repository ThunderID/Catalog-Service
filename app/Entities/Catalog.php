<?php

namespace App\Entities;

use App\Entities\Observers\CatalogObserver;

/**
 * Used for Catalog Models
 * 
 * @author cmooy
 */
class Catalog extends BaseModel
{
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $collection			= 'mt_catalog';

	/**
	 * Date will be returned as carbon
	 *
	 * @var array
	 */
	protected $dates				=	['created_at', 'updated_at', 'deleted_at'];

	/**
	 * The appends attributes from mutator and accessor
	 *
	 * @var array
	 */
	protected $appends				=	[];

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden 				= [];

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable				=	[
											'name'							,
											'slug'							,
											'type'							,
											'contents'						,
											'images'						,
											'display'						,
										];
										
	/**
	 * Basic rule of database
	 *
	 * @var array
	 */
	protected $rules				=	[
											'name'							=> 'required|max:255',
											'slug'							=> 'max:255',
											'type'							=> 'required|in:simple,grouped,downloadable,affiliate,variable',

											'images.*.path'					=> 'required|url',

											'display.net_price'				=> 'required_without:display.varian|min:0',
											'display.discount'				=> 'min:0',
											'display.stock'					=> 'required_without:display.varian|min:0',

											'display.varian.*.net_price'	=> 'required_without:display.net_price|min:0',
											'display.varian.*.stock'		=> 'required_without:display.stock|min:0',
										];


	/* ---------------------------------------------------------------------------- RELATIONSHIP ----------------------------------------------------------------------------*/
	
	/* ---------------------------------------------------------------------------- QUERY BUILDER ----------------------------------------------------------------------------*/
	
	/* ---------------------------------------------------------------------------- MUTATOR ----------------------------------------------------------------------------*/
	
	/* ---------------------------------------------------------------------------- ACCESSOR ----------------------------------------------------------------------------*/
	
	/* ---------------------------------------------------------------------------- FUNCTIONS ----------------------------------------------------------------------------*/
		
	/**
	 * boot
	 * observing model
	 *
	 */
	public static function boot() 
	{
        parent::boot();

		Catalog::observe(new CatalogObserver);
    }

	/* ---------------------------------------------------------------------------- SCOPES ----------------------------------------------------------------------------*/

	/**
	 * scope to get condition where slug
	 *
	 * @param string or array of slug
	 **/
	public function scopeSlug($query, $variable)
	{
		if(is_array($variable))
		{
			return 	$query->whereIn('slug', $variable);
		}

		return $query->where('slug', 'regexp', '/^'. preg_quote($variable) .'$/i');
	}

	/**
	 * scope to get condition where name
	 *
	 * @param string or array of name
	 **/
	public function scopeName($query, $variable)
	{
		if(is_array($variable))
		{
			return 	$query->whereIn('name', $variable);
		}

		return $query->where('name', 'regexp', '/^'. preg_quote($variable) .'$/i');
	}

	/**
	 * scope to get condition where type
	 *
	 * @param string or array of type
	 **/
	public function scopeType($query, $variable)
	{
		if(is_array($variable))
		{
			return 	$query->whereIn('type', $variable);
		}

		return $query->where('type', 'regexp', '/^'. preg_quote($variable) .'$/i');
	}

	/**
	 * scope to get condition where net price
	 *
	 * @param string or array of net price
	 **/
	public function scopeDisplayNetPrice($query, $variable)
	{
		if(is_array($variable))
		{
			$min = (double) $variable[0];
			$max = (double) $variable[1];

			if ($min > $max)
			{
				$tmp = $min;
				$min = $max;
				$max = $tmp;
			}

			return $query->where(function($query) use ($min, $max) {
						return $query->where('display.net_price', '>=', $min)->where('display.net_price', '<=', $max);	
					})->orwhere(function($query) use ($min, $max) {
						return $query->where('display.varian.net_price', '>=', $min)->where('display.varian.net_price', '<=', $max);	
					});
		}

		return $query->where(function($query) use ($variable) {
					return $query->where('display.net_price', '<=', ($variable*1));	
				})->orwhere(function($query) use ($variable) {
					return $query->where('display.varian.net_price', '<=', ($variable*1));	
				});
	}
}
