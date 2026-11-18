

<?php
require_once "db.php";

function e($value) {
    return htmlspecialchars((string)$value, ENT_QUOTES, "UTF-8");
}

$id = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);

if (!$id || $id < 1) {
    die("Invalid student ID.");
}

$stmt = $conn->prepare(
    "SELECT name, email, age, course FROM students WHERE id = ?"
);
$stmt->bind_param("i", $id);
$stmt->execute();
$stmt->bind_result($name, $email, $age, $course);

if (!$stmt->fetch()) {
    $stmt->close();
    die("Student not found.");
}
$stmt->close();

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = trim($_POST["name"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $ageInput = trim($_POST["age"] ?? "");
    $course = trim($_POST["course"] ?? "");

    if (
        $name === "" || $email === "" ||
        $ageInput === "" || $course === ""
    ) {
        $error = "Please fill in all fields.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Enter a valid email address.";
    } elseif (
        filter_var($ageInput, FILTER_VALIDATE_INT) === false ||
        (int)$ageInput < 1 ||
        (int)$ageInput > 120
    ) {
        $error = "Enter a valid age.";
    } else {
        $age = (int)$ageInput;

        $stmt = $conn->prepare(
            "UPDATE students
             SET name = ?, email = ?, age = ?, course = ?
             WHERE id = ?"
        );

        $stmt->bind_param(
            "ssisi", $name, $email, $age, $course, $id
        );

        if ($stmt->execute()) {
            $stmt->close();
            header("Location: index.php");
            exit;
        } else {
            $error = "Could not update the student.";
            $stmt->close();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Student</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f0f4f8;
            padding: 30px;
        }

        .form-box {
            max-width: 450px;
            margin: auto;
            background: white;
            padding: 25px;
            border-radius: 10px;
        }

        h1 {
            text-align: center;
            color: darkblue;
        }

        label {
            display: block;
            margin-top: 15px;
        }

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
            background: blue;
            color: white;
            border: none;
            cursor: pointer;
        }

        .error {
            color: red;
        }
    </style>
</head>
<body>

<div class="form-box">
    <h1>Edit Student</h1>

    <?php if ($error !== ""): ?>
        <p class="error"><?= e($error) ?></p>
    <?php endif; ?>

    <form method="POST" action="edit.php?id=<?= (int)$id ?>">
        <label>Full Name</label>
        <input type="text" name="name"
               value="<?= e($name) ?>" required>

        <label>Email Address</label>
        <input type="email" name="email"
               value="<?= e($email) ?>" required>

        <label>Age</label>
        <input type="number" name="age" min="1" max="120"
               value="<?= e($age) ?>" required>

        <label>Course</label>
        <input type="text" name="course"
               value="<?= e($course) ?>" required>

        <button type="submit">Update Student</button>
    </form>

    <p><a href="index.php">Back to Student List</a></p>
</div>

</body>
</html>
