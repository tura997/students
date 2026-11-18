
<?php
require_once "db.php";

$result = $conn->query("SELECT * FROM students ORDER BY id DESC");

function e($value) {
    return htmlspecialchars((string)$value, ENT_QUOTES, "UTF-8");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>School Management System</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f0f4f8;
            padding: 30px;
        }

        .container {
            max-width: 1100px;
            margin: auto;
            background: white;
            padding: 25px;
            border-radius: 10px;
        }

        h1 {
            text-align: center;
            color: darkblue;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }

        th {
            background: darkblue;
            color: white;
        }

        .add, .edit, .delete {
            display: inline-block;
            padding: 8px 12px;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            border: none;
            cursor: pointer;
            font-size: 14px;
        }

        .add {
            background: green;
        }

        .edit {
            background: blue;
        }

        .delete {
            background: red;
        }

        .delete-form {
            display: inline;
        }
    </style>
</head>
<body>

<div class="container">

    <h1>School Management System</h1>

    <a class="add" href="create.php">+ Add Student</a>

    <table>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Age</th>
            <th>Course</th>
            <th>Actions</th>
        </tr>

        <?php if ($result && $result->num_rows > 0): ?>

            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= (int)$row["id"] ?></td>
                    <td><?= e($row["name"]) ?></td>
                    <td><?= e($row["email"]) ?></td>
                    <td><?= e($row["age"]) ?></td>
                    <td><?= e($row["course"]) ?></td>

                    <td>
                        <!-- EDIT BUTTON -->
                        <a class="edit"
                           href="edit.php?id=<?= (int)$row["id"] ?>">
                            Edit
                        </a>

                        <!-- DELETE BUTTON -->
                        <form class="delete-form"
                              action="delete.php"
                              method="POST"
                              onsubmit="return confirm('Are you sure you want to delete this student?');">

                            <input type="hidden"
                                   name="id"
                                   value="<?= (int)$row["id"] ?>">

                            <button class="delete" type="submit">
                                Delete
                            </button>
                        </form>
                    </td>
                </tr>
            <?php endwhile; ?>

        <?php else: ?>
            <tr>
                <td colspan="6">No students found.</td>
            </tr>
        <?php endif; ?>

    </table>

</div>

</body>
</html>
