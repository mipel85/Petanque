<?php

use \App\controllers\InitDays;
use \App\items\Days;
use \App\items\Rankings;
use \App\core\Debug;

$days_list = [];
$current_day = new \DateTime();
$current_month = $current_day->format('m');
$current_year = $current_day->format('Y');
foreach (Days::days_list() as $value) {
    $day_date = explode('-', $value['date']);
    if ($day_date[1] === $current_month && $day_date[2] === $current_year) {
        $days_list[] = $value['id'];
    }
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
$standings_list = Rankings::rankings_month_list($days_list);
usort($standings_list, 'standings_sort');

?>
<section>
    <header class="section-header flex-between-center">
            <h1><?= $lang['standings.title'] ?><?= $lang['month.' . $current_month] ?> - <?= $current_year ?></h1>
    </header>
    <article id="ranking-day">
        <?php if (Days::started_day()): ?>
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
        <?php else: ?>
            <div class="message-helper full-notice"><?= $lang['rankings.no.days'] ?></div>
        <?php endif ?>
    </article>
    <footer></footer>
</section>
<script>
    // Reorder horizontal order to vertical
    $(document).ready(function() { rowtocolumn('#standings-list', '.row-item', 'row-col', 6); });
</script>