<?php

// Подключение автозагрузки через composer
require __DIR__ . '/../vendor/autoload.php';

use Slim\Factory\AppFactory;
use DI\Container;
//use Illuminate\Support\Collection;

use function Symfony\Component\String\s;

$container = new Container();

$container->set('renderer', function () {
    return new \Slim\Views\PhpRenderer(__DIR__ . '/../templates');
});

$app = AppFactory::createFromContainer($container);
$app->addErrorMiddleware(true, true, true);

$app->get('/', function ($request, $response) {
      $response2 = $response->withStatus(204);
    return $response;
});

$users = ['mike', 'mishel', 'adel', 'keks', 'kamila'];

$app->get('/users', function ($request, $response) use ($users) {
    $term = $request->getQueryParam('term');
    $result = collect($users)->filter(
        fn($user) => empty($term) ? true : s($user)->ignoreCase()->startsWith($term)
    );
    $params = [
        'users' => $result,
        'term' => $term
    ];
    return $this->get('renderer')->render($response, 'users/index.phtml', $params);
});


$app->get('/users/{id}', function ($request, $response, $args) {
    $params = ['id' => $args['id'], 'nickname' => 'user-' . $args['id']];
    return $this->get('renderer')->render($response, '/users/show.phtml', $params);
});



$app->get('/companies', function ($request, $response) {
    return $response->write('GET /companies');
});

$app->post('/users', function ($request, $response) {
    return $response->withStatus(302);
});

$app->run();
