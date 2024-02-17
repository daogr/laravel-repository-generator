<?php

namespace Otodev\Traits;

use Otodev\Greeklish\Greeklish;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

trait HasRequestCriteriaSearch
{

    /**
     * @var string
     */
    protected $matchFields = '`first_name`, `last_name`, `email`, `phone`, `notes`';

    /**
     * @param Builder|Model $model
     * @param               $search
     *
     * @return Builder|Model
     */
    protected function applySearch($model, $search)
    {
        if (!empty($search)) {
            $s = trim(preg_replace('/[^\p{L}\p{N}_]+/u', ' ', trim($search)));

            if (!empty($s)) {
                $bindings = "$s*";

                if (!is_numeric($s)) {
                    $sG = Greeklish::toGreek($s);
                    $bindings = ($s == $sG ? "$s*" : "$s*$sG*");
                }
                $model = $model->whereRaw(DB::raw("MATCH ($this->matchFields) AGAINST(? IN BOOLEAN MODE)"), [$bindings]);

                if (is_numeric($s)) {
                    $model = $model->orWhere('id', $s);
                }
            }
        }

        return $model;
    }
}
