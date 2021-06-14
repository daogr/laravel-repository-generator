<?php

    namespace Otodev\Traits;

    use Illuminate\Database\Eloquent\Builder;
    use Illuminate\Database\Eloquent\Model;
    use Illuminate\Support\Arr;

    trait HasRequestCriteriaSort {

        /**
         * @param Builder|Model $model
         * @param               $sort
         * @param               $order
         *
         * @return Builder|Model
         */
        protected function applySort(Builder|Model $model, $sort, $order): Builder|Model {
            if(!empty($sort) && !empty($order)) {

                if(Arr::accessible($sort) && Arr::accessible($order)) {

                    $sort = $this->fixRequestKeys($sort);

                    foreach($sort as $index => $sort_by) {

                        if(isset($order[$index])) {
                            $order_by = $order[$index];

                            return $model->orderBy($sort_by, $order_by);
                        }
                    }

                }

            }

            return $model;
        }
    }
