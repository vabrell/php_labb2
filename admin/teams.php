<?php
require_once('../layouts/header.php');
$team = null;
$teams = null;

if (!empty($_POST)) {
    extract($_POST);

    if ($action === 'update') {
        $name = filter_var($name, FILTER_SANITIZE_STRING);
        $activity_id = filter_var($activity_id, FILTER_SANITIZE_NUMBER_INT);

        App\Team::update($id, $name, $activity_id);

        header("Location: ?team=$id");
    }

    if ($action === 'add') {
        $name = filter_var($name, FILTER_SANITIZE_STRING);
        $activity_id = filter_var($activity_id, FILTER_SANITIZE_NUMBER_INT);

        $lastId = App\Team::create($name, $activity_id);

        header("Location: ?team=$lastId");
    }

    if ($action === 'delete') {
        App\Team::delete($id);

        header("Location: ./teams.php");
    }

    if ($action === 'removeMember') {
        App\TeamsMembers::remove($team_id, $member_id);

        header("Location: ?team=$team_id");
    }
}

if (isset($_GET['team'])) {
    $id = $_GET['team'];

    $team = App\Team::find($id);
} else {
    $_team = new App\Team;
    $teams = $_team->orderBy('name')->get();
}
?>
<div class="container">
    <?php
    if (isset($_GET['action']) && $_GET['action'] === 'add') {
    ?>
        <h1>Lägg till ett nytt lag</h1>
        <form method="post">
            <div class="form-group">
                <label for="name">Lagnamn</label>
                <input type="text" name="name" id="name" class="form-control">
            </div>
            <div class="form-group">
                <label for="activity_id">activity_id</label>
                <input type="number" name="activity_id" id="activity_id" class="form-control">
            </div>
            <input type="hidden" name="action" value="add">
            <button type="submit" class="btn btn-primary">Lägg till</button>
        </form>
    <?php
    } else if (!$team) {
    ?>
        <h1>Lag <a href="?action=add" class="h6">Nytt lag</a></h1>
        <?php
        foreach ($teams as $team) {
            echo "<p>
                    <a href='?team=$team->id'>
                    $team->name
                    </a>
                </p>";
        }
    } else {
        ?>
        <a href="./teams.php">&laquo; Tillbaka</a>
        <?php
        if (isset($_GET['action']) && $_GET['action'] === 'edit') {
        ?>
            <form method="post">
                <div class="form-group">
                    <label for="name">Lagnamn</label>
                    <input type="text" name="name" id="name" class="form-control" value="<?php echo $team->name ?>">
                </div>
                <div class="form-group">
                    <label for="activity_id">activity_id</label>
                    <input type="text" name="activity_id" id="activity_id" class="form-control" value="<?php echo $team->activity_id ?>">
                </div>
                <input type="hidden" name="id" value="<?php echo $team->id ?>">
                <input type="hidden" name="action" value="update">
                <button type="submit" class="btn btn-primary">Spara</button>
            </form>
        <?php
        } else {
        ?>
            <h1><?php echo $team->name ?> <a href="?team=<?php echo $team->id ?>&action=edit" class="h6">Editera</a></h1>
            <p>Aktivitet: <a href="activities.php?activity=<?php echo $team->activity()->id ?>"><?php echo $team->activity()->name ?></a></p>
            <div>
                <h3>Medlemmar</h3>
                <ul>
                    <?php
                    foreach ($team->members() as $member) {
                        echo "<li>";
                        echo "<a href='members.php?member=$member->id'>$member->firstName $member->lastName</a>";
                    ?>
                        <form method="post" class="d-inline">
                            <input type="hidden" name="member_id" value="<?php echo $member->id ?>">
                            <input type="hidden" name="team_id" value="<?php echo $team->id ?>">
                            <input type="hidden" name="action" value="removeMember">
                            <button type="submit" class="btn btn-sm btn-outline-danger px-1 py-0">x</button>
                        </form>
                    <?php
                        echo "</li>";
                    }
                    ?>
                </ul>
            </div>
            <form method="post" class="mt-3">
                <input type="hidden" name="id" value="<?php echo $team->id ?>">
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
