<?php
require_once('../layouts/header.php');
$member = null;
$members = null;

if (!empty($_POST)) {
    extract($_POST);

    if ($action === 'update') {
        $firstName = filter_var($firstName, FILTER_SANITIZE_STRING);
        $lastName = filter_var($lastName, FILTER_SANITIZE_STRING);

        if ($membership) {
            $datetime = new DateTime();
            App\Member::update($id, $firstName, $lastName, $datetime->format('Y-m-d H-m-s'));
        } else {
            App\Member::update($id, $firstName, $lastName);
        }

        header("Location: ?member=$id");
    }

    if ($action === 'add') {
        $firstName = filter_var($firstName, FILTER_SANITIZE_STRING);
        $lastName = filter_var($lastName, FILTER_SANITIZE_STRING);

        $lastId = App\Member::create($firstName, $lastName);

        header("Location: ?member=$lastId");
    }

    if ($action === 'delete') {
        App\Member::delete($id);

        header("Location: ./members.php");
    }
}

if (isset($_GET['member'])) {
    $id = $_GET['member'];

    $member = App\Member::find($id);
} else {
    $_member = new App\Member;
    $members = $_member->orderBy('firstName')->get();
}
?>
<div class="container">
    <?php
    if (isset($_GET['action']) && $_GET['action'] === 'add') {
    ?>
        <h1>Lägg till ny medlem</h1>
        <form method="post">
            <div class="form-group">
                <label for="firstName">Förnamn</label>
                <input type="text" name="firstName" id="firstName" class="form-control">
            </div>
            <div class="form-group">
                <label for="lastName">Efternamn</label>
                <input type="text" name="lastName" id="lastName" class="form-control">
            </div>
            <input type="hidden" name="action" value="add">
            <button type="submit" class="btn btn-primary">Lägg till</button>
        </form>
    <?php
    } else if (!$member) {
    ?>
        <h1>Medlemmar <a href="?action=add" class="h6">Ny medlem</a></h1>
        <?php
        foreach ($members as $member) {
            echo "<p>
                    <a href='?member=$member->id'>
                    $member->firstName $member->lastName
                    </a>
                </p>";
        }
    } else {
        ?>
        <a href="./members.php">&laquo; Tillbaka</a>
        <?php
        if (isset($_GET['action']) && $_GET['action'] === 'edit') {
        ?>
            <form method="post">
                <div class="form-group">
                    <label for="firstName">Förnamn</label>
                    <input type="text" name="firstName" id="firstName" class="form-control" value="<?php echo $member->firstName ?>">
                </div>
                <div class="form-group">
                    <label for="lastName">Efternamn</label>
                    <input type="text" name="lastName" id="lastName" class="form-control" value="<?php echo $member->lastName ?>">
                </div>
                <div class="form-group form-check">
                    <input type="checkbox" name="membership" id="membership" class="form-check-input" <?php if ($member->membership) {
                                                                                                            echo 'checked';
                                                                                                        } ?>>
                    <label for="membership" class="form-check-label">Medlemskap betalt</label>
                </div>
                <input type="hidden" name="id" value="<?php echo $member->id ?>">
                <input type="hidden" name="action" value="update">
                <button type="submit" class="btn btn-primary">Spara</button>
            </form>
        <?php
        } else {
        ?>
            <h1><?php echo $member->firstName . ' ' . $member->lastName ?> <a href="?member=<?php echo $member->id ?>&action=edit" class="h6">Editera</a></h1>
            <p><strong>Medlemskap betalt:</strong> <?php echo $member->membership ?? 'Ej betalt' ?></p>
            <form method="post">
                <input type="hidden" name="id" value="<?php echo $member->id ?>">
                <input type="hidden" name="action" value="delete">
                <button type="submit" class="btn btn-sm btn-outline-danger">Ta bort</button>
            </form>
    <?php
        }
    }
    ?>
</div>
<?php
require_once('../layouts/footer.php');
