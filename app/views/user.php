<!DOCTYPE html>
<html>
<body>

<p>Username: <?= $user->login ?></p>
<p>Balance: <?= $user->balance ?></p>

<?php if ($errors) : ?>
    <ul>
    <?php foreach ($errors as $error): ?>
        <i>Error: <?= $error ?></i><br>
    <?php endforeach; ?>
    </ul>
<?php endif; ?>
<form action="/user/checkout/<?= $user->id ?>" method="post">
    Amount:<br>
    <input type="number" step="0.01" name="amount" value="0" required>
    <br><br>
    <input type="submit" value="Proceed">
</form>
<a href="/user/logout">Logout</a>

</body>
</html>
