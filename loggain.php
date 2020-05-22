<?php
require_once('layouts/header.php');

if (isset($_SESSION['id'])) {
    header("Location: ./");
}

$error = null;

if (!empty($_POST)) {
    extract($_POST);

    $username = filter_var($username, FILTER_SANITIZE_STRING);
    $password = filter_var($password, FILTER_SANITIZE_STRING);

    if (!App\Auth::verify($username, $password)) {
        $error = "Användarnamn eller lösenord är fel.";
    } else {
        header("Location: ./");
    }
}
?>
<div class="container my-5">
    <div class="row">
        <div class="shadow rounded p-3 mx-auto col-lg-6 col-md-8 col-sm-12 bg-primary text-white">
            <h2>Logga in</h2>
            <?php
            if ($error) {
            ?>
            <div class="alert alert-danger"><?php echo $error ?></div>
            <?php
            }
            ?>
            <form method="post">
                <div class="form-group">
                    <label for="username">Användarnamn</label>
                    <input type="text" name="username" id="username" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="password">Lösenord</label>
                    <input type="password" name="password" id="password" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-light text-primary w-100 font-weight-bold mt-3">Logga in</button>
            </form>
        </div>
    </div>
</div>
<?php
require_once('layouts/footer.php'); ?>