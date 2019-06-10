<?php

/**
 * @param string $name
 *
 * @return string
 */
function route_return(string $name): string
{
    return route($name, ['return' => (isset($_GET['return']) ? $_GET['return'] : $_SERVER['REQUEST_URI'])]);
}

/**
 * @return string
 */
function getReturn(): string
{
    return (isset($_GET['return']) ? $_GET['return'] : $_SERVER['REQUEST_URI']);
}

/**
 * @param string $name
 *
 * @return string
 */
function getRouteReturn(string $name = 'home'): string
{
    return (request()->get('return')) ? request()->get('return') : route($name);
}

/**
 * Add 'is-active' class (as parameter) if given boolean is true.
 *
 * @param bool $boolean
 * @param bool $withClass
 */
function isActive(bool $boolean, bool $asParameter = true): void
{
    if ($boolean) {
        echo ($asParameter) ? 'class="is-active"' : ' is-active';
    }
}

/**
 * Meger giver array with User's ID.
 *
 * @param array $attributes
 *
 * @return array
 */
function appendUserId(array $attributes): array
{
    return array_merge($attributes, ['user_id' => auth()->id()]);
}

/**
 * @return array
 */
function paginateAppends(array $additional = []): array
{
    $parameters = [];

    if (count($_GET)) {
        foreach ($_GET as $key => $value) {
            if ($value !== "") {
                $parameters[$key] = $value;
            }
        }
    }

    return array_merge($parameters, $additional);
}

/**
 * @return bool
 */
function isRequestEmpty(): bool
{
    $params = paginateAppends();

    if (isset($params['page'])) {
        unset($params['page']);
    }

    if (isset($params['sort_key'])) {
        unset($params['sort_key']);
    }

    if (isset($params['sort_type'])) {
        unset($params['sort_type']);
    }

    return count($params) == 0;
}

/**
 * @param array $statistic
 * @param int   $mall_id
 * @param       $key
 *
 * @return int|mixed
 */
function compare_value(array $statistic, int $mall_id, $key)
{
    return isset($statistic[$mall_id][$key]) ? $statistic[$mall_id][$key] : 0;
}

/**
 * @param int $current
 * @param int $past
 *
 * @return float|int
 */
function compare_diff(int $current, int $past)
{
    return round((1 - $past / $current) * 100, 2, PHP_ROUND_HALF_EVEN);
}

/**
 * @param float $diff
 *
 * @return string
 */
function compare_color(float $diff): string
{
    if ($diff == '') {
        return '';
    }

    if ($diff >= 5 || $diff <= -5) {
        return ($diff >= 5) ? 'is-success' : 'is-danger';
    }

    return '';
}

/**
 * @param float $diff
 *
 * @return string
 */
function compare_background(float $diff): string
{
    if ($diff == '') {
        return '';
    }

    if ($diff >= 20 || $diff <= -20) {
        return ($diff >= 20) ? 'is-success-background' : 'is-danger-background';
    }

    return '';
}

/**
 * @param float $diff
 *
 * @return string
 */
function compare_arrow(float $diff): string
{
    if ($diff == 0) {
        return '';
    }

    if ($diff > 0) {
        return ($diff >= 5) ? 'up' : 'right';
    } else {
        return ($diff <= -5) ? 'down' : 'left';
    }
}