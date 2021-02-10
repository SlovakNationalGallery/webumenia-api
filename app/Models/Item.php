<?php

namespace App\Models;

use ElasticScoutDriverPlus\Builders\BoolQueryBuilder;
use ElasticScoutDriverPlus\CustomSearch;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class Item extends Model
{
    use CustomSearch, HasFactory, Searchable;

    public $incrementing = false;

    public static $filterables = [
        'author',
        'topic',
        'additionals.frontend',
        'additionals.category.keyword',
        'additionals.set.keyword',
    ];

    public static $rangeables = [
        'date_earliest',
        'date_latest',
        'additionals.order',
    ];

    public static $sortables = [
        'additionals.order',
    ];

    public static function filterQuery(array $filter, BoolQueryBuilder $builder = null)
    {
        $builder = $builder ?: new BoolQueryBuilder();
        foreach ($filter as $field => $value) {
            if (is_string($value) && in_array($field, self::$filterables, true)) {
                $builder->filter('term', [$field => $value]);
            } else if (is_array($value) && in_array($field, self::$rangeables, true)) {
                $range = collect($value)
                    ->only(['lt', 'lte', 'gt', 'gte'])
                    ->transform(function ($value) {
                        return (string)$value;
                    })
                    ->all();
                $builder->filter('range', [$field => $range]);
            }
        }
        return $builder;
    }

    public function searchableAs()
    {
        return sprintf(
            '%sitems_%s',
            config('scout.prefix'),
            app()->getLocale()
        );
    }
}
