<?php
require_once('../layouts/header.php');
$activity = null;
$activities = null;

if (!empty($_POST)) {
    extract($_POST);

    if ($action === 'update') {
        $name = filter_var($name, FILTER_SANITIZE_STRING);

        App\Activity::update($id, $name);

        header("Location: ?activity=$id");
    }

    if ($action === 'add') {
        $name = filter_var($name, FILTER_SANITIZE_STRING);

        $lastId = App\Activity::create($name, $activity_id);

        header("Location: ?activity=$lastId");
    }

    if ($action === 'delete') {
        App\Activity::delete($id);

        header("Location: ./activities.php");
    }
}

if (isset($_GET['activity'])) {
    $id = $_GET['activity'];

    $activity = App\Activity::find($id);
} else {
    $_activity = new App\Activity;
    $activities = $_activity->orderBy('name')->get();
}
?>
<div class="container">
    <?php
    if (isset($_GET['action']) && $_GET['action'] === 'add') {
    ?>
        <h1>Lägg till ett nytt lag</h1>
        <form method="post">
            <div class="form-group">
                <label for="name">Ny aktivitet</label>
                <input type="text" name="name" id="name" class="form-control">
            </div>
            <input type="hidden" name="action" value="add">
            <button type="submit" class="btn btn-primary">Lägg till</button>
        </form>
    <?php
    } else if (!$activity) {
    ?>
        <h1>Aktiviteter <a href="?action=add" class="h6">Ny aktivitet</a></h1>
        <?php
        foreach ($activities as $activity) {
            echo "<p>
                    <a href='?activity=$activity->id'>
                    $activity->name <span class='badge badge-primary'>{$activity->memberCount()}</span>
                    </a>
                </p>";
        }
    } else {
        ?>
        <a href="./activities.php">&laquo; Tillbaka</a>
        <?php
        if (isset($_GET['action']) && $_GET['action'] === 'edit') {
        ?>
            <form method="post">
                <div class="form-group">
                    <label for="name">Aktivitetsnamn</label>
                    <input type="text" name="name" id="name" class="form-control" value="<?php echo $activity->name ?>">
                </div>
                <input type="hidden" name="id" value="<?php echo $activity->id ?>">
                <input type="hidden" name="action" value="update">
                <button type="submit" class="btn btn-primary">Spara</button>
            </form>
        <?php
        } else {
        ?>
            <h1><?php echo $activity->name ?> <a href="?activity=<?php echo $activity->id ?>&action=edit" class="h6">Editera</a></h1>
            <div>
                <h3>Teams</h3>
                <ul>
                    <?php
                    foreach ($activity->teams() as $team) {
                        echo "<li><a href='teams.php?team=$team->id'>$team->name</a> <span class='badge badge-primary'>{$team->memberCount()}</span></li>";
                    }
                    ?>
                </ul>
            </div>
            <form method="post" class="mt-3">
                <input type="hidden" name="id" value="<?php echo $activity->id ?>">
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
