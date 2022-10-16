<?php

namespace Nihilsen\BoxBilling;

use Illuminate\Support\Arr;
use Illuminate\Support\LazyCollection;
use Nihilsen\BoxBilling\API\API;
use Nihilsen\BoxBilling\Exceptions\UnexpectedAPIResultException;

class Collection extends LazyCollection
{
    /**
     * Create a new BoxBiling lazy collection instance.
     *
     * @return void
     */
    protected function __construct(
        /**
         * The collection result items.
         *
         * @var array
         */
        protected array $list,
        public readonly int $page,
        public readonly int $pages,
        public readonly int $per_page,
        public readonly int $total,
        public readonly API $api,
        public readonly string $method,
        public readonly array $parameters
    ) {
        return parent::__construct(function () {
            foreach ($this->list as $result) {
                yield $result;
            }

            if ($this->pages > $this->page) {
                /** @var static */
                $nextPageCollection = $this->api->query(
                    $this->method,
                    array_merge(
                        $this->parameters,
                        ['page' => $this->page + 1]
                    )
                );

                if (! $nextPageCollection instanceof static) {
                    throw new UnexpectedAPIResultException('API did not return iterable results for pagination.');
                }

                foreach ($nextPageCollection as $result) {
                    yield $result;
                }
            }
        });
    }

    public static function canCollect(mixed $result): bool
    {
        return
            is_array($result) &&
            Arr::has(
                $result,
                [
                    'list',
                    'page',
                    'pages',
                    'per_page',
                    'total',
                ]
            );
    }

    /**
     * Collect the given API $result, and indicate that the next chunk
     * may be retrievable via the given callable $next.
     *
     * @param  array{pages: int, page: int, per_page: int, total: int, list: array}  $result
     * @return static
     */
    public static function collectResultFromAPI(
        array $result,
        API $api,
        string $method,
        array $parameters
    ): static {
        /**
         * @var int $pages
         * @var int $page
         * @var int $per_page
         * @var int $total
         * @var array $list
         */
        extract($result);

        return new static(
            $list,
            $page,
            $pages,
            $per_page,
            $total,
            $api,
            $method,
            $parameters
        );
    }
}
