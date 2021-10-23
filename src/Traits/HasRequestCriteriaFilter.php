<?php

    namespace Otodev\Traits;

	use Illuminate\Database\Eloquent\Builder;
	use Illuminate\Database\Eloquent\Model;
	use Illuminate\Support\Arr;

    trait HasRequestCriteriaFilter {

        /**
         * @param Builder|Model $model
         * @param               $filter
         *
         * @return Builder|Model
         */
        protected function applyFilter($model, $filter) {
            if(!empty($filter)) {
            	
            	if(Arr::accessible($filter)) {
            		$filter = collect($this->fixRequestMapKeys($filter))->intersectByKeys(array_fill_keys($model->getModel()->getFillable(), ''))->all();
            		
                    foreach($filter as $key => $value) {
						$model = $model->where($key, $value);
                    }
                    
                }
            }

            return $model;
        }
    }
