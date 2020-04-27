<?php


namespace App\Logic;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Validator;

class FilterLogic implements FilterInterface
{
    /**
     * Query Builder.
     *
     * @var Builder
     */
    private $query;

    /**
     * FilterLogic constructor.
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
    public function search(...$args): FilterLogic
    {
        if($search = $this->searchValidator()) {
            $this->searchQueryBuilder($search, $args);
        }

        return $this;
    }

    /**
     * Order result by column name.
     *
     * @param string $column
     * @param string $direction
     * @return FilterLogic
     */
    public function order(string $column = 'id', $direction = 'ASC'): FilterLogic
    {
        $order = $this->orderValidator();

        $this->query->orderBy($column, empty($order) ? $direction : $order);

        return $this;
    }

    /**
     * Filter result by date using column.
     *
     * @param string $column
     * @return FilterLogic
     */
    public function date(string $column = 'created_at'): FilterLogic
    {
        $dates = $this->dateValidator();

        ! isset($dates['start']) ?: $this->query->whereDate($column, '>=', $this->formatDate($dates['start']));
        ! isset($dates['end']) ?: $this->query->whereDate($column, '<=', $this->formatDate($dates['end']));

        return $this;
    }

    /**
     * Get filter query builder.
     *
     * @return Builder
     */
    public function builder(): Builder
    {
        return $this->query;
    }

    /**
     * Builder search query.
     *
     * @param string $term
     * @param array $args
     */
    protected function searchQueryBuilder(string $term, array $args): void
    {
        for($i = 0; $i < count($args); $i++) {
            if($i == 0) {
                $this->query->where($args[$i][0], 'LIKE', "%$term%");
            }
        }
    }

    /**
     * Validate search value in attributes.
     *
     * @return string
     */
    protected function searchValidator(): string
    {
        return request()->validate([
            'search' => ['nullable', 'string']
        ])['search'] ?? '';
    }

    /**
     * Check if request order attribute is valid.
     *
     * @return string
     */
    protected function orderValidator(): string
    {
        return request()->validate([
            'order' => ['nullable', 'in:asc,desc,ASC,DESC']
        ])['order'] ?? '';
    }

    /**
     * Check if dates are valid.
     *
     * @return array
     */
    protected function dateValidator(): array
    {
        return request()->validate([
            'start' => ['nullable','date'],
            'end' => ['nullable','date']
        ]);
    }

    /**
     * Format date to application date.
     *
     * @param string $date
     * @return string
     */
    protected function formatDate(string $date): string
    {
        return Date::createFromTimestamp(strtotime($date));
    }
}
