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
        protected function applyFilter(Builder|Model $model, $filter): Builder|Model {
            if(!empty($filter)) {

                if(Arr::accessible($filter)) {
                    $filter = $this->fixRequestMapKeys($filter);

                    foreach($filter as $key => $value) {
                        $model->where($key, $value);
                    }

                }
            }

            return $model;
        }
    }
