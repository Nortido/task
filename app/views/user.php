<!DOCTYPE html>
<html>
<body>

<p>Username: <?= $data->login ?></p>
<p>Balance: <?= $data->balance ?></p>

<form action="/checkout" method="post">
    Amount:<br>
    <input type="text" name="amount" value="">
    <br><br>
    <input type="submit" value="Proceed">
</form>
<a href="/user/logout">Logout</a>

</body>
</html>
