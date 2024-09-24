<?php
require dirname(__DIR__) . "/vendor/autoload.php";

use src\DatabaseModel;


if ($_SERVER['REQUEST_METHOD'] === "POST")
{

    $dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__));
    $dotenv->load();

    $database = new DatabaseModel($_ENV["DB_HOST"], $_ENV["DB_NAME"], $_ENV["DB_USER"], $_ENV["DB_PASS"]);
    $conn = $database->getConnection();


    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $api_key = bin2hex(random_bytes(16));


    $sql = "INSERT INTO user_urls (name, username, password_hash, api_key)
           VALUES (:name, :username, :password_hash, :api_key)";
    $smt = $conn->prepare($sql);
    $smt->bindValue(":name", $_POST["name"], \PDO::PARAM_STR);
    $smt->bindValue(":username", $_POST['username'], \PDO::PARAM_STR);
    $smt->bindValue(":password_hash", $password, \PDO::PARAM_STR);
    $smt->bindValue(":api_key", $api_key, \PDO::PARAM_STR);
    $smt->execute();
    echo "Thanks for registering. Your api key is $api_key";
    exit;
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@picocss/pico@2/css/pico.min.css" />
</head>

<body>
    <main class="container">
        <h1>Register</h1>

        <form action="" method="post">
            <label for="name">
                Name
                <input type="text" name="name" id="name">
            </label>
            <label for="username">
                Username
                <input type="text" name="username" id="username">
            </label>
            <label for="password">
                Password
                <input type="password" name="password" id="password">
            </label>
            <button>
                Register
            </button>
        </form>

    </main>
</body>

</html>