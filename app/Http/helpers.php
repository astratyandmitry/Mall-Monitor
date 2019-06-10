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
 * @param bool $boolean
 * @param bool $asParameter
 *
 * @return string|void
 */
function isActive(bool $boolean, bool $asParameter = true)
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
