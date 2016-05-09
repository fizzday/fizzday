<?php


use Illuminate\Database\Capsule\Manager as Capsule;

require BASE_PATH.'config/database.php';

// Eloquent ORM
$capsule = new Capsule;

$capsule->addConnection($db[$config['dbDefault']]);

$capsule->setAsGlobal();

$capsule->bootEloquent();