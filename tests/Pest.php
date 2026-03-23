<?php

/*
|--------------------------------------------------------------------------
| CI (GitHub Actions): base de datos MySQL del workflow
|--------------------------------------------------------------------------
|
| Si el entorno "Testing" de GitHub define DB_CONNECTION=sqlite (u otras DB_*),
| pisa el env del job y Pest termina usando database.sqlite inexistente.
| GITHUB_ACTIONS está definido en los runners; forzamos MySQL antes de bootear Laravel.
|
*/
if ((getenv('GITHUB_ACTIONS') ?: ($_SERVER['GITHUB_ACTIONS'] ?? '')) === 'true') {
    $ciDb = [
        'DB_CONNECTION' => 'mysql',
        'DB_HOST' => '127.0.0.1',
        'DB_PORT' => '3306',
        'DB_DATABASE' => 'cos_test',
        'DB_USERNAME' => 'root',
        'DB_PASSWORD' => 'password',
    ];
    foreach ($ciDb as $key => $value) {
        putenv("{$key}={$value}");
        $_ENV[$key] = $value;
        $_SERVER[$key] = $value;
    }
}

/*
|--------------------------------------------------------------------------
| Test Case
|--------------------------------------------------------------------------
|
| The closure you provide to your test functions is always bound to a specific PHPUnit test
| case class. By default, that class is "PHPUnit\Framework\TestCase". Of course, you may
| need to change it using the "pest()" function to bind a different classes or traits.
|
*/

pest()->extend(Tests\TestCase::class)
    ->use(Illuminate\Foundation\Testing\RefreshDatabase::class)
    ->in('Feature');

pest()->extend(Tests\TestCase::class)
    ->use(Illuminate\Foundation\Testing\RefreshDatabase::class)
    ->in('Unit');

/*
|--------------------------------------------------------------------------
| Expectations
|--------------------------------------------------------------------------
|
| When you're writing tests, you often need to check that values meet certain conditions. The
| "expect()" function gives you access to a set of "expectations" methods that you can use
| to assert different things. Of course, you may extend the Expectation API at any time.
|
*/

expect()->extend('toBeOne', function () {
    return $this->toBe(1);
});

/*
|--------------------------------------------------------------------------
| Functions
|--------------------------------------------------------------------------
|
| While Pest is very powerful out-of-the-box, you may have some testing code specific to your
| project that you don't want to repeat in every file. Here you can also expose helpers as
| global functions to help you to reduce the number of lines of code in your test files.
|
*/

function something()
{
    // ..
}
