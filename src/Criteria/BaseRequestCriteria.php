<?php

namespace Otodev\Criteria;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Otodev\Contracts\CriteriaContract;
use Otodev\Contracts\RepositoryContract;
use Otodev\Traits\HasFixCriteriaKeys;
use Otodev\Traits\HasRequestCriteriaColumns;
use Otodev\Traits\HasRequestCriteriaFilter;
use Otodev\Traits\HasRequestCriteriaSort;

/**
 * Class BaseRequestCriteria
 * @package App\Criteria
 */
class BaseRequestCriteria implements CriteriaContract
{
    use HasFixCriteriaKeys, HasRequestCriteriaColumns, HasRequestCriteriaFilter, HasRequestCriteriaSort;

    /**
     * @var Request
     */
    protected $request;

    /**
     * RequestCriteria constructor.
     *
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Apply criteria in query repository.
     *
     * @param Builder|Model $model
     * @param RepositoryContract $repository
     *
     * @return mixed
     */
    public function apply($model, RepositoryContract $repository)
    {
        $columns = $this->request->get(config('repository.criteria.params.columns', 'fields'));
        $sort = $this->request->get(config('repository.criteria.params.sort', 'sort'));
        $order = $this->request->get(config('repository.criteria.params.order', 'order'));
        $filter = $this->request->get(config('repository.criteria.params.filter', 'filter'));

        $model = $this->applyColumns($model, $columns);
        $model = $this->applyFilter($model, $filter);
        $model = $this->applySort($model, $sort, $order);

        return $model;
    }
}
