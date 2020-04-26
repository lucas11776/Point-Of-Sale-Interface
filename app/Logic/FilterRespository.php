<?php


namespace App\Logic;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class FilterRespository implements FilterInterface
{
    /**
     * Query Builder.
     *
     * @var Builder
     */
    private $query;

    /**
     * FilterRespository constructor.
     *
     * @param Model $model
     */
    public function __construct(Model $model)
    {
        $this->query = $model->query();
    }

    /**
     * Search record usings args as key value pair key=column.
     *
     * @param mixed ...$args
     * @return $this
     */
    public function search(...$args): FilterRespository
    {
        return $this;
    }

    /**
     * Order result by column name.
     *
     * @param string $column
     * @return FilterRespository
     */
    public function order(string $column = 'id'): FilterRespository
    {
        return $this;
    }

    /**
     * Filter result by date using column.
     *
     * @param string $start
     * @param string $end
     * @param string $column
     * @return FilterRespository
     */
    public function date(string $start = '', string $end = '', string $column = 'created_at'): FilterRespository
    {
        return $this;
    }
}
