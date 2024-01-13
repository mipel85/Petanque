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
        return $b["victory"] - $a["victory"]; // sort victory DESC
    } elseif ($a["pos_points"] != $b["pos_points"]) {
        return $b["pos_points"] - $a["pos_points"]; // then sort pos_points DESC
    } else {
        return $a["neg_points"] - $b["neg_points"]; // then sort neg_points ASC
    }
}
$standings_list = Standings::standings_list();
usort($standings_list, 'standings_sort');

?>
<?php if (count(Days::days_list()) > 0): ?>
    <section id="standings">
        <header class="section-header flex-between-center">
                <h1 id="ranking-title"><?= Standings::month_label(Standings::get_month()) ?> - <?= Standings::get_year() ?></h1>
                <button class="button" type="submit" onclick="print_content('#ranking-month')"><?= $lang['common.print'] ?></button>
                <form id="form-month" action="" method="post">
                    <select class="select" name="month-choice" id="" onchange='this.form.submit()'>
                        <?php foreach (Standings::month_select() as $option): ?>
                            <?= $option ?>
                        <?php endforeach ?>
                    </select>
                </form>
        </header>
        <article id="ranking-month" class="content">
            <?php if (count(Days::days_list()) > 0): ?>
                <div id="standings-table" class="">
                    <?php $i = 0 ?>
                    <?php foreach ($standings_list as $index => $player): ?>
                        <?php if ($i === 0): ?> <div class="row-group"> <?php endif ?>
                            <div class="row-item">
                                <span id="rank-<?= $index + 1 ?>"><?= $index + 1 ?></span>
                                <div class="player-name flex-main" id="<?= $player['member_id'] ?>"><?= $player['member_name'] ?></div>
                                <div
                                        class="stamp full-notice"
                                        data-percent="<?= round($player['victory'] / $player['played'] * 100, 2, PHP_ROUND_HALF_UP) ?>">
                                    <?= $player['victory'] ?><!-- /<?= $player['played'] ?> -->
                                </div>
                            </div>
                        <?php $i++ ?>
                        <?php if ($i === 22): ?> </div> <?php endif ?>
                        <?php if($i === 22) $i = 0 ?>
                    <?php endforeach ?>
                </div>
            <?php else: ?>
                <div class="message-helper bgc-notice"><?= $lang['common.no.elements'] ?></div>
            <?php endif ?>
        </article>
        <footer></footer>
    </section>
<?php else: ?>
    <section>
        <header>
            <h1></h1>
        </header>
        <article class="content">
            <div class="message-helper bgc-notice"><?= $lang['common.no.elements'] ?></div>
        </article>
    </section>
<?php endif ?>

<script>
    // Reorder horizontal order to vertical
    // $(document).ready(function() { rowtocolumn('#standings-table', '.row-item', 'row-col', 2); });
</script>