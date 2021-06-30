<?php

    namespace Otodev\Traits;

    use Illuminate\Database\Eloquent\Builder;
    use Illuminate\Database\Eloquent\Model;

    trait HasRequestCriteriaColumns {

        /**
         * @param Builder|Model $model
         * @param array|null    $columns
         *
         * @return Builder|Model
         */
        protected function applyColumns($model, ?array $columns) {
            if(!empty($columns)) {

                if(is_array($columns)) {
                    $columns = collect($this->fixRequestKeys($columns))->intersect($model->getFillable())->all();

                    return $model->select($columns);
                }
            }

            return $model;
        }
    }
