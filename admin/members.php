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

    if ($action === 'addTeam') {
        App\TeamsMembers::add($team_id, $member_id);

        header("Location: ./members.php?member=$member_id");
    }

    if ($action === 'removeTeam') {
        App\TeamsMembers::remove($team_id, $member_id);

        header("Location: ./members.php?member=$member_id");
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
    } else if (isset($_GET['action']) && $_GET['action'] === 'addTeam') {
    ?>
        <h1>Lägg till medlem i team</h1>
        <form method="post">
            <div class="form-group">
                <label for="firstName">Medlem</label>
                <input type="text" id="firstName" class="form-control disabled" disabled value="<?php echo $member->firstName . ' ' . $member->lastName ?>">
                <input type="hidden" name="member_id" value="<?php echo $member->id ?>">
            </div>
            <div class="form-group">
                <label for="activity">Aktivitet</label>
                <select name="activity_id" id="activity" class="form-control" required>
                    <option selected disabled>-- Välj en aktivitet --</option>
                    <?php
                    foreach (App\Activity::all() as $activity) {
                        echo "<option value='$activity->id'>$activity->name</option>";
                    }
                    ?>
                </select>
            </div>
            <?php
            foreach (App\Activity::all() as $activity) {
            ?>
                <div id="activity-<?php echo $activity->id ?>" class="form-group d-none">
                    <label for="team">Team/Lag</label>
                    <select name="team_id" id="team" class="form-control">
                        <option selected disabled>-- Välj en aktivitet --</option>
                        <?php

                        foreach ($activity->teams() as $team) {
                            echo "<option value='$team->id'>$team->name</option>";
                        }
                        ?>
                    </select>
                </div>
            <?php
            }
            ?>
            <input type="hidden" name="action" value="addTeam">
            <button type="submit" class="btn btn-primary">Lägg till</button>
        </form>
    <?php
    } else if (!$member) {
    ?>
        <h1>Medlemmar <a href="?action=add" class="h6">Ny medlem</a></h1>
        <?php
        foreach ($members as $member) {
            $check = $member->membership ? '&check;' : '';
            echo "<p>
                    <a href='?member=$member->id'>
                    $member->firstName $member->lastName
                    </a> $check
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
            <div>
                <a href="?member=<?php echo $member->id ?>&action=addTeam" class="btn btn-sm btn-outline-primary mb-2">Lägg till i team</a>
                <?php
                foreach ($member->teams() as $team) {
                    echo "<h3>{$team->activity()->name}</h3>";
                    echo "<a href='teams.php?team=$team->id'>$team->name</a>";
                ?>
                    <form method="post" class="d-inline">
                        <input type="hidden" name="member_id" value="<?php echo $member->id ?>">
                        <input type="hidden" name="team_id" value="<?php echo $team->id ?>">
                        <input type="hidden" name="action" value="removeTeam">
                        <button type="submit" class="btn btn-sm btn-outline-danger px-1 py-0">x</button>
                    </form>
                <?php
                }
                ?>
            </div>
            <form method="post" class="mt-3">
                <input type="hidden" name="id" value="<?php echo $member->id ?>">
                <input type="hidden" name="action" value="delete">
                <button type="submit" class="btn btn-sm btn-outline-danger">Ta bort</button>
            </form>
    <?php
        }
    }
    ?>

    <script>
        document.querySelector("#activity").addEventListener("change", (e) => {
            if (oEl = document.querySelector(".d-block")) {
                oEl.classList.remove('d-block')
                oEl.classList.add('d-none')
            }

            let nEl = document.querySelector("#activity-" + e.target.value).classList
            nEl.remove('d-none')
            nEl.add('d-block')
        })
    </script>
</div>
<?php
require_once('../layouts/footer.php');
