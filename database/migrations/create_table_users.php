<?php

//use Opis\Database\Database;
//use Opis\Database\Connection;
//use Opis\Database\Schema\CreateTable;
//
//$connection = new Connection(
//    'sqlite:../database.sqlite'
//);
//
//$db = new Database($connection);
//
//$db->schema()->create('users', function(CreateTable $table){
//    $table->integer('id')->primary()->autoincrement();
//    $table->string('name')->notNull();
//    $table->string('name')->notNull()->unique();
//    $table->string('name')->notNull();
//    $table->timestamp('created_at');
//});

try {
    $db = new PDO('sqlite:database/database.sqlite');
    $db->exec("CREATE TABLE users (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        name VARCHAR(50) NOT NULL,
        email VARCHAR(50) NOT NULL UNIQUE,
        password VARCHAR(50) NOT NULL
    )");
} catch (PDOException $e) {
    echo $e->getMessage();
}
