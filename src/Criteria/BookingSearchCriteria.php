<?php

    namespace Otodev\Criteria;
    
    use Illuminate\Database\Eloquent\Builder;
    use Illuminate\Database\Eloquent\Model;
    use Illuminate\Http\Request;
    use Otodev\Contracts\CriteriaContract;
    use Otodev\Contracts\RepositoryContract;
	use Otodev\Traits\HasFixCriteriaKeys;
	use Otodev\Traits\HasRequestCriteriaSearch;

    /**
     * Class BookingSearchCriteria
     * @package App\Criteria
     */
    class BookingSearchCriteria implements CriteriaContract {
        use HasFixCriteriaKeys, HasRequestCriteriaSearch;

        /**
         * @var Request
         */
        protected $request;

        /**
         * RequestCriteria constructor.
         *
         * @param Request $request
         */
        public function __construct(Request $request) {
            $this->request = $request;
        }

        /**
         * Apply criteria in query repository.
         *
         * @param Builder|Model      $model
         * @param RepositoryContract $repository
         *
         * @return mixed
         */
        public function apply($model, RepositoryContract $repository) {
            $search = $this->request->get(config('repository.criteria.params.search', 'search'));

            return $this->applySearch($model, $search);
        }
    }
