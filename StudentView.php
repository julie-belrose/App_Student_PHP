<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student Manager</title>
    <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body>

<section class="form-section">
    <h2>Student Form</h2>
    <form method="POST" action="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>">
        <div class="form-section__group">
            <label class="form-section__label">ID (for update):</label>
            <input class="form-section__input" type="text" name="id" pattern="^[a-f\d]{24}$" title="MongoDB ObjectId (24 hex chars)">
        </div>

        <div class="form-section__group">
            <label class="form-section__label">First Name:</label>
            <input class="form-section__input" type="text" name="firstname" required>
        </div>

        <div class="form-section__group">
            <label class="form-section__label">Last Name:</label>
            <input class="form-section__input" type="text" name="lastname" required>
        </div>

        <div class="form-section__group">
            <label class="form-section__label">Date of Birth (YYYY-MM-DD):</label>
            <input class="form-section__input" type="text" name="dob" pattern="\d{4}-\d{2}-\d{2}" placeholder="e.g., 2001-06-15">
        </div>

        <div class="form-section__group">
            <label class="form-section__label">Email:</label>
            <input class="form-section__input" type="email" name="email">
        </div>

        <button class="form-section__button" type="submit" name="action" value="add">Add</button>
        <button class="form-section__button" type="submit" name="action" value="edit">Edit</button>
    </form>
</section>

<section class="form-section">
    <h2>Delete Student</h2>
    <form method="POST" action="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>">
        <div class="form-section__group">
            <label class="form-section__label">Student ID to delete:</label>
            <input class="form-section__input" type="text" name="delete_id" required pattern="^[a-f\d]{24}$">
        </div>
        <button class="form-section__button form-section__button--danger" type="submit" name="action" value="delete">Delete</button>
    </form>
</section>

<h2>Student List</h2>
<table>
    <thead>
    <tr>
        <th>ID</th><th>First Name</th><th>Last Name</th><th>Date of Birth</th><th>Email</th>
    </tr>
    </thead>
    <tbody>
    <?php if (!empty($students)): ?>
        <?php foreach ($students as $student): ?>
            <tr>
                <td><?= htmlspecialchars($student->id) ?></td>
                <td><?= htmlspecialchars($student->firstname) ?></td>
                <td><?= htmlspecialchars($student->lastname) ?></td>
                <td><?= htmlspecialchars($student->date_of_birth) ?></td>
                <td><?= htmlspecialchars($student->email) ?></td>
            </tr>
        <?php endforeach; ?>
    <?php else: ?>
        <tr><td colspan="5">No students in the database.</td></tr>
    <?php endif; ?>
    </tbody>
</table>

</body>
</html>
