<?php
require_once("./app/init.php");

$queryBuilder = new QueryBuilder($connection,$config->APP_DEBUG);

// $userData = ['username'=>'Parot', 'email'=>'parot@gamil.com', 'password'=>'parot'];
// $queryBuilder->table('users')
//             ->insert($userData);

// dd($queryBuilder->table('users')
//             ->where('username','LIKE','P%')
//             ->update(['email'=>'pqr@gamil.com']));


// dd($queryBuilder->table('users')
//             ->where('username','LIKE','P%')
//             ->get());

// dd($queryBuilder->table('users')
//             ->where('username','LIKE','par%')
//             ->delete());

// dd($queryBuilder->table('users')
//             ->where('username','LIKE','P%')
//             ->limit(1,3)
//             ->get());

// dd($queryBuilder->table('users')
//             ->where('username','LIKE','P%')
//             ->count());
echo date("Y-m-d H:i:s");