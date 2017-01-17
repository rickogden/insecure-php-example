<?php

require_once __DIR__ . '/../vendor/autoload.php';

$app = new Silex\Application();

$app['db'] = function() {
    return new PDO('mysql:host=localhost;dbname=vulnerable', 'vulnerable', 'vulnerable');
};

$app->get('/profile', function(Silex\Application $app){
    /** @var PDO $db */
    $db = $app['db'];
    $id = $_GET['id'];

    $statement = $db->query("SELECT * FROM users WHERE id = $id");

    $results = $statement->fetchAll();
    var_dump($results);
    return <<<EOF
    <dl>
    <dt>Username:</dt><dd>
    </dl>
EOF;
});
$app->get('/login', function(){

    return <<<EOF
    <form action="/login" method="post">
    <label>Username: <input type="text" name="username" /></label>
    <label>Password: <input type="password" name="password" /></label>
    <input type="submit" value="submit" />
    </form>
           
EOF;
});

$app->post('/login', function(Silex\Application $app) {

    /** @var PDO $db */
    $db = $app['db'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $statement = $db->query("SELECT * FROM users WHERE username = '$username' AND password = '$password'");
    $results = $statement->fetchAll();
    if(count($results) > 0) {
        return "Authenticated";
    } else {
        return "Invalid username/password";
    }
});

$app->run();