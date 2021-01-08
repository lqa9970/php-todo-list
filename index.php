<?php
require 'vendor/autoload.php';
$pdo = new \PDO("sqlite:" . "db/sqlite.db");

if (isset($_POST['submit'])) {
    $description = $_POST['description'];
    $sth = $pdo->prepare("INSERT INTO todos (description) VALUES (:description)");
    $sth->bindValue(':description', $description, PDO::PARAM_STR);
    $sth->execute();
}
# Delete TODO
elseif (isset($_POST['delete'])) {
    $id = $_POST['id'];
    $sth = $pdo->prepare("delete from todos where id = :id");
    $sth->bindValue(':id', $id, PDO::PARAM_INT);
    $sth->execute();
}
# Update completion status
elseif (isset($_POST['complete'])) {
    $id = $_POST['id'];
    $sth = $pdo->prepare("UPDATE todos SET complete = 1 where id = :id");
    $sth->bindValue(':id', $id, PDO::PARAM_INT);
    $sth->execute();
}
?>

<!DOCTYPE HTML>
<html lang="en">

<head>
    <title>Simple Todo List</title>
</head>

<body class="container">
    <h1>Todo List</h1>
    <form method="post" action="">
        <input type="text" name="description" value="">
        <input type="submit" name="submit" value="Add">
    </form>
    <h2>All tasks:</h2>
    <table class="table table-striped">
        <thead>
            <th>Tasks:</th>
            <th></th>
            <th></th>
        </thead>
        <tbody>

            <?php
            $sth = $pdo->prepare("SELECT * FROM todos ORDER BY id DESC");
            $sth->execute();

            foreach ($sth as $row) {
            ?>
                <tr>
                    <td>
                        <?= htmlspecialchars($row['description']) ?>
                    </td>
                    <td>
                        <?php
                        if (!$row['complete']) {
                        ?>
                            <form method="POST">
                                <button type="submit" name="complete">Completed</button>
                                <input type="hidden" name="id" value="<?= $row['id'] ?>">
                                <input type="hidden" name="complete" value="true">
                            </form>
                        <?php
                        } else {
                        ?>
                            Task Completed!
                        <?php
                        }
                        ?>
                    </td>
                    <td>
                        <form method="POST">
                            <button type="submit" name="delete">Delete</button>
                            <input type="hidden" name="id" value="<?= $row['id'] ?>">
                            <input type="hidden" name="delete" value="true">
                        </form>
                    </td>
                </tr>
            <?php
            }
            ?>
        </tbody>
    </table>
</body>

</html>