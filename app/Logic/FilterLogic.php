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
    public function __construct(Model $model = null)
    {
        is_null($model) ?: $this->query = $model->query();
    }

    /**
     * Intialize query model.
     *
     * @param Model $model
     * @return FilterLogic
     */
    public function filter(Model $model): FilterLogic
    {
        $this->query = $model->query();

        return $this;
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
        ! isset($dates['start']) ?: $this->dateStartQuery($column, $dates['start']);
        ! isset($dates['end']) ?: $this->dateEndQuery($column, $dates['end']);
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
        // :TODO
        // must implement better search
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
        $validator = request()->validate(['search' => ['nullable', 'string']]);

        return $validator['search'] ?? '';
    }

    /**
     * Check if request order attribute is valid.
     *
     * @return string
     */
    protected function orderValidator(): string
    {
        $validator = request()->validate(['order' => ['nullable', 'in:asc,desc,ASC,DESC']]);

        return $validator['order'] ?? '';
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
     * Start getting query result form date.
     *
     * @param string $column
     * @param string $date
     */
    protected function dateStartQuery(string $column, string $date): void
    {
        $this->query->whereDate($column, '>=', $this->formatDate($date));
    }

    /**
     * End result form date.
     *
     * @param string $column
     * @param string $date
     */
    protected function dateEndQuery(string $column, string $date): void
    {
        $this->query->whereDate($column, '<=', $this->formatDate($date));
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
