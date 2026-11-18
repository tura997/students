
<?php
require_once "db.php";

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = trim($_POST["name"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $age = trim($_POST["age"] ?? "");
    $course = trim($_POST["course"] ?? "");

    if ($name === "" || $email === "" || $age === "" || $course === "") {
        $error = "Please fill in all fields.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Enter a valid email address.";
    } elseif (
        filter_var($age, FILTER_VALIDATE_INT) === false ||
        (int)$age < 1 || (int)$age > 120
    ) {
        $error = "Enter a valid age.";
    } else {
        $age = (int)$age;

        $stmt = $conn->prepare(
            "INSERT INTO students (name, email, age, course)
             VALUES (?, ?, ?, ?)"
        );

        if (!$stmt) {
            $error = "SQL error: " . $conn->error;
        } else {
            $stmt->bind_param("ssis", $name, $email, $age, $course);

            if ($stmt->execute()) {
                $stmt->close();
                $conn->close();

                header("Location: index.php");
                exit;
            } else {
                $error = "Insert failed: " . $stmt->error;
            }

            $stmt->close();
        }
    }
}

function e($value) {
    return htmlspecialchars((string)$value, ENT_QUOTES, "UTF-8");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Student</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #eef2f7;
            padding: 30px;
        }
        .box {
            max-width: 450px;
            margin: auto;
            background: white;
            padding: 25px;
            border-radius: 10px;
        }
        h1 { text-align: center; color: darkblue; }
        label { display: block; margin-top: 15px; }
        input {
            width: 100%;
            box-sizing: border-box;
            padding: 10px;
            margin-top: 5px;
        }
        button {
            width: 100%;
            padding: 12px;
            margin-top: 20px;
            background: green;
            color: white;
            border: none;
            cursor: pointer;
        }
        .error { color: red; }
    </style>
</head>
<body>

<div class="box">
    <h1>Register Student</h1>

    <?php if ($error !== ""): ?>
        <p class="error"><?= e($error) ?></p>
    <?php endif; ?>

    <form method="POST" action="create.php">
        <label for="name">Full Name</label>
        <input type="text" id="name" name="name"
               value="<?= e($_POST['name'] ?? '') ?>" required>

        <label for="email">Email</label>
        <input type="email" id="email" name="email"
               value="<?= e($_POST['email'] ?? '') ?>" required>

        <label for="age">Age</label>
        <input type="number" id="age" name="age"
               min="1" max="120"
               value="<?= e($_POST['age'] ?? '') ?>" required>

        <label for="course">Course</label>
        <input type="text" id="course" name="course"
               value="<?= e($_POST['course'] ?? '') ?>" required>

        <button type="submit">Save Student</button>
    </form>

    <p><a href="index.php">View Student List</a></p>
</div>

</body>
</html>
