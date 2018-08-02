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
 * Return array of  not empty $_GET parameters
 *
 * @return array
 */
function getNotEmptyQueryParameters(): array
{
    $parameters = [];

    if (count($_GET)) {
        foreach ($_GET as $key => $value) {
            if ($value !== "") {
                $parameters[$key] = $value;
            }
        }
    }

    return $parameters;
}
