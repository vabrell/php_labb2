<?php
require_once('layouts/header.php');

$_activity = new App\Activity;
$activities = $_activity->where('name', '=', 'skidor')->get();

$_team = new App\Team;
$teams = $_team->where('activity_id', '=', $activities[0]->id)->get();

?>
<div class="container">
    <h1>Skidor</h1>
        <?php 
            foreach ($teams as $team) {
                echo '<div><h3>' . $team->name . '</h3><ul>';
                foreach ($team->members() as $member) {
                    echo '<li>' . $member->firstName . ' ' . $member->lastName . '</li>';
                }
                echo '</ul></div>';
            }
        ?>
</div>
<?php
require_once('layouts/footer.php');