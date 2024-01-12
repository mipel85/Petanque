<?php

use \App\controllers\Standings;
use \App\items\Days;
use \App\items\Rankings;
use \App\core\Debug;

if (isset($_POST['month-choice'])) {
    $url  = 'index.php?page=standings' . "&date=" . $_POST['month-choice'];
    header("Location: ".$url);
    exit;
}

function standings_sort($a, $b)
{
    if ($a["victory"] != $b["victory"]) {
        return $b["victory"] - $a["victory"];
    } elseif ($a["pos_points"] != $b["pos_points"]) {
        return $b["pos_points"] - $a["pos_points"];
    } else {
        return $a["neg_points"] - $b["neg_points"];
    }
}
$standings_list = Standings::standings_list();
usort($standings_list, 'standings_sort');

?>
<section>
    <header class="section-header flex-between-center">
            <h1><?= Standings::month_label(Standings::get_month()) ?> - <?= Standings::get_year() ?></h1>
            <form action="" method="post">
                <select class="select" name="month-choice" id="" onchange='this.form.submit()'>
                    <?php foreach (Standings::month_select() as $option): ?>
                        <?= $option ?>
                    <?php endforeach ?>
                </select>
            </form>
    </header>
    <article id="ranking-day">
        <div id="standings-list" class="cell-flex cell-columns-6">
            <?php foreach ($standings_list as $index => $player): ?>
                <div class="row-item">
                    <span id="rank-<?= $index + 1 ?>"><?= $index + 1 ?></span>
                    <div class="player-name flex-main" id="<?= $player['member_id'] ?>"><?= $player['member_name'] ?></div>
                    <div class="stamp full-notice"><?= $player['victory'] ?></div>
                </div>
            <?php endforeach ?>
        </div>
        <!-- <table class="table rankings-table">
            <thead>
                <tr>
                    <th><?= $lang['rankings.place'] ?></th>
                    <th class="player-name"><?= $lang['rankings.name'] ?></th>
                    <th><?= $lang['rankings.played'] ?></th>
                    <th><?= $lang['rankings.victory'] ?></th>
                    <th><?= $lang['rankings.loss'] ?></th>
                    <th><?= $lang['rankings.points.for'] ?></th>
                    <th><?= $lang['rankings.points.against'] ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($standings_list as $index => $player): ?>
                    <tr>
                        <td><?= $index + 1 ?></td>
                        <td class="player-name" id="<?= $player['member_id'] ?>"><?= $player['member_name'] ?></td>
                        <td><?= $player['played'] ?></td>
                        <td><?= $player['victory'] ?></td>
                        <td><?= $player['loss'] ?></td>
                        <td><?= $player['pos_points'] ?></td>
                        <td><?= $player['neg_points'] ?></td>
                    </tr>
                <?php endforeach ?>
            </tbody>
        </table> -->
    </article>
    <footer></footer>
</section>
<script>
    // Reorder horizontal order to vertical
    $(document).ready(function() { rowtocolumn('#standings-list', '.row-item', 'row-col', 6); });
</script>