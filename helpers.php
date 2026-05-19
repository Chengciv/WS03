<?php

if (defined('HELPERS_LOADED')) return;
define('HELPERS_LOADED', true);

/**
 * Get the base path
 * @param string $path
 * @return string
 */
function basePath($path = '')
{
    return __DIR__ . '/' . $path;
}

/**
 * Load a view
 * @param string $name
 * @return void
 */
function loadView($name, $data = [])
{
    $viewPath = basePath("App/views/{$name}.view.php");
    if (file_exists($viewPath)) {
        extract($data);
        require $viewPath;
    } else {
        echo "View {$name} not found";
    }
}

/**
 * Load a partial
 * @param string $name
 * @return void
 */
function loadPartials($name)
{
    $partialPath = basePath("App/views/partials/{$name}.php");
    if (file_exists($partialPath)) {
        require $partialPath;
    } else {
        echo "Partial {$name} not found";
    }
}

/**
 * Inspect a value
 * @param mixed $value
 * @return void
 */

function inspect($value)
{
    echo '<pre>';
    var_dump($value);
    echo '</pre>';
}

function formatSalary($salary) {
    return '$' . number_format(floatval($salary));
}