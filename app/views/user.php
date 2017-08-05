<!DOCTYPE html>
<html>
<body>

<p>Username: <?= $data->login ?></p>
<p>Balance: <?= $data->balance ?></p>

<?php if ($data->errors) : ?>
    <?php foreach ($data->errors as $error): ?>
        <i>Error: <?= $error ?></i>
    <?php endforeach; ?>
<?php endif; ?>
<form action="/user/checkout/<?= $data->id ?>" method="post">
    Amount:<br>
    <input type="number" name="amount" value="" required>
    <br><br>
    <input type="submit" value="Proceed">
</form>
<a href="/user/logout">Logout</a>

</body>
</html>
