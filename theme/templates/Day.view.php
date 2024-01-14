<?php

use \App\items\Days;
use \App\items\Rounds;
use \App\items\Teams;
use \App\items\Matches;
use \App\items\Fields;
use \App\controllers\InitDays;
use \App\controllers\Rules;
use \App\core\Debug;

$today           = Days::today();
$day_id          = InitDays::day_id();
$hidden_day      = InitDays::hidden_day();
$c_started_day   = Days::started_day();
$round_label     = InitDays::round_label();
$hidden_round    = InitDays::hidden_round();
$disabled_round  = InitDays::disabled_round();
$i_order         = InitDays::i_order();
$players_number  = InitDays::players_number();

?>
<section>
    <header class="section-header flex-between">
        <h1><?= $lang['days.title'] ?></h1>
        <article id="day-manager" class="content<?= $hidden_day ?>" data-day_ready="<?= $day_id ?>">
            <header id="add-day" class="<?= $hidden_day ?>">
                <h3><?= $lang['days.init'] ?></h3>
            </header>
            <input type="hidden" id="day-date" name="day-date" value="<?= $today ?>" />
        </article>
        <div id="add-round-container" class="hero hidden flex-between-center">
            <div class="playgrounds-list flex-between-center">
                <span><?= $lang['days.select.fields'] ?></span>
                <div class="field-container">
                    <?php if($c_started_day): ?>
                        <?php foreach (Fields::fields_checkbox_list($day_id) as $checkboxes): ?>
                            <?php foreach ($checkboxes as $index => $checked): ?>
                                <?php $is_checked = $checked ? ' checked' : '';?>
                                <div class="icon-checkbox">
                                    <label for="field_<?= $index ?>" class="checkbox">
                                        <input 
                                                class="checkbox-field"
                                                type="checkbox"
                                                name="field-<?= $index ?>"
                                                data-fields_id="<?= Fields::field_id($day_id) ?>"
                                                id="field_<?= $index ?>"<?= $is_checked ?>>
                                        <span><?= $index ?></span>
                                    </label>
                                </div>
                            <?php endforeach ?>
                        <?php endforeach ?>
                    <?php endif ?>
                </div>
            </div>
            <div class="line align-center">
                <button
                        class="button full-success<?= $hidden_round ?>"
                        data-day_id="<?= $day_id ?>"
                        data-i_order="<?= $i_order ?>"
                        data-players_number="<?= $players_number ?>"
                        id="add-round"
                        <?= $disabled_round ?>>
                    <?= str_replace(':number', $i_order, $lang['days.add.round']) ?>
                </button>
                <span id="round-description" class="description"><?= $round_label ?></span>
            </div>
        </div>
    </header>
    <?php if($c_started_day): ?>
        <article id="rounds-list" class="tabs-container">
            <?php $day_round_list = array_reverse(Rounds::day_rounds_list($day_id)); ?>
            <div class="tabs-menu flex-between">
                <div class="tabs-menu-left">
                    <?php foreach($day_round_list as $round): ?>
                        <?php $active_tab = InitDays::latest_round_id($day_id) == $round['id'] ? ' active-tab' : ''; ?>
                        <span data-trigger="rounds-<?= $round['i_order'] ?>" class="tab-trigger<?= $active_tab ?>" onclick="openTab(event, 'rounds-<?= $round['i_order'] ?>');">Partie <?= $round['i_order'] ?></span>
                    <?php endforeach ?>
                </div>
            </div>
            <?php foreach($day_round_list as $round): ?>
                <?php $active_tab = InitDays::latest_round_id($day_id) == $round['id'] ? ' active-tab' : ''; ?>
                <?php
                    $round_id = $round['id'];
                    $hidden_remove_round = !InitDays::is_scored($day_id, $round_id) && InitDays::latest_round_id($day_id) == $round_id ? '' : ' hidden';
                    $hidden_teams_list = Teams::round_teams_list($day_id, $round_id) ? '' : ' hidden';
                    $hidden_teams_btn = Teams::round_teams_list($day_id, $round_id) ? ' hidden' : '';
                    $hidden_matches_list = Matches::round_matches_list($day_id, $round_id) ? '' : ' hidden';
                    $hidden_matches_btn = Matches::round_matches_list($day_id, $round_id) ? ' hidden' : '';
                ?>
                <div id="rounds-<?= $round['i_order'] ?>"
                        class="tab-content<?= $active_tab ?>"
                        data-scored="<?= InitDays::is_scored($day_id, $round_id) ?>">
                    <div class="flex-between">
                        <span></span>
                        <button type="submit" 
                                class="icon-button remove-round<?= $hidden_remove_round ?>" 
                                data-day_id="<?= $day_id ?>" 
                                data-round_id="<?= $round_id ?>"
                                data-round_i_order="<?= $round['i_order'] ?>">
                            <i class="fa fa-fw fa-2x fa-square-xmark error"></i>
                        </button>
                    </div>
                    <?php
                        $matches_ready = count(Matches::round_matches_list($day_id, $round_id));
                        $hidden_matches_ready = $matches_ready ? ' class="hidden"' : '';
                    ?>
                    <div id="teams-list-<?= $round_id ?>"<?= $hidden_matches_ready ?>
                            data-round_ready="<?= $hidden_teams_btn ?>"
                            data-day_id="<?= $day_id ?>"
                            data-round_id="<?= $round_id ?>">
                        <header class="flex-between-center">
                            <h4><?= $round['players_number'] ?> joueurs</h4>
                            <span class="description"><strong>Partie <?= $round['i_order'] ?></strong> - <?= Rules::matching_rule($round['players_number']) ?></span>
                        </header>
                        <div id="teams-round-list-<?= $round_id ?>" class="teams-round-list cell-flex cell-columns-4<?= $hidden_teams_list ?>">
                            <?php foreach (Teams::round_teams_list($day_id, $round_id) as $index => $team): ?>
                                <div class="row-item row-vertical flex-start-center bgc-sub">
                                    <div><?= $team['player_1_name'] ?></div>
                                    <div><?= $team['player_2_name'] ?></div>
                                    <?php if ($team['player_3_name']): ?><div><?= $team['player_3_name'] ?></div><?php endif ?>
                                </div>
                            <?php endforeach ?>
                        </div>
                    </div>
                    <?php $team_ready = count(Teams::round_teams_list($day_id, $round_id)); ?>
                    <div id="matches-list-<?= $round_id ?>" 
                            class="<?= $hidden_teams_list ?>"
                            data-day_id="<?= $day_id ?>"
                            data-round_id="<?= $round_id ?>"
                            data-teams_ready="<?= $team_ready ?>"
                            data-matches_ready="<?= $hidden_matches_btn ?>">
                        <div class="expand-container">
                            <span data-minimize="rounds-<?= $round['i_order'] ?>" data-expand="expand-rounds-<?= $round['i_order'] ?>" class="expand-button" id="expand-<?= $round_id ?>"></span>
                        <header class="flex-between">
                            <h4>Liste des rencontres</h4>
                            <span class="description"><strong>Partie <?= $round['i_order'] ?></strong> - <?= $round['players_number'] ?> joueurs - <?= Rules::matching_rule($round['players_number']) ?></span>
                        </header>
                            <div id="matches-round-list-<?= $round_id ?>" class="match-list big-line cell-flex cell-columns-4<?= $hidden_matches_list ?>">
                                <?php foreach (Matches::round_matches_list($day_id, $round_id) as $index => $match): ?>
                                    <?php $validated_score = $match['score_status'] ? ' full-success' : ''; ?>
                                    <div 
                                            class="row-item flex-between-center<?= $validated_score ?>"
                                            data-field="<?= $match['field'] ?>">
                                        <div class="width-75 align-center">
                                            <span class="big stamp full-main"><?= $match['field'] ?></span>
                                        </div>
                                        <div class="flex-main">
                                            <?php foreach (Teams::get_team_members($match['team_1_id']) as $players): ?>
                                                <span data-member_id="<?= $players[0] ?>" class="d-block"><?= $players[1] ?></span>
                                                <span data-member_id="<?= $players[2] ?>" class="d-block"><?= $players[3] ?></span>
                                                <span data-member_id="<?= $players[4] ?>" class="d-block"><?= $players[5] ?></span>
                                            <?php endforeach ?>
                                        </div>
                                        <div>
                                            <?php foreach (Teams::get_team_members($match['team_2_id']) as $players): ?>
                                                <span data-member_id="<?= $players[0] ?>" class="d-block"><?= $players[1] ?></span>
                                                <span data-member_id="<?= $players[2] ?>" class="d-block"><?= $players[3] ?></span>
                                                <span data-member_id="<?= $players[4] ?>" class="d-block"><?= $players[5] ?></span>
                                            <?php endforeach ?>
                                        </div>
                                    </div>
                                <?php endforeach ?>
                            </div>
                            <script>
                                $(document).ready(function() { reorderitems('#matches-round-list-<?= $round_id ?>', '.row-item', 'field'); });
                            </script>
                        </div>
                    </div>
                </div>
            <?php endforeach ?>
        </article>
    <?php endif ?>
    <script>
        $(document).ready(function() {
            // if no day, hide round
            if ($('#add-day').hasClass('hidden'))
                $('#add-round-container').removeClass('hidden');

            // hidden add-round button and fields selection
            // get data-scored of first of rounds in the dom if exists
            let first = $('[data-scored]').first().data('scored');
                add_button = $('#add-round');
            add_button.removeClass('hidden');
            if (first == '') {
                add_button.addClass('hidden');
                $('.playgrounds-list').addClass('hidden')
                $('#round-description').html("<?= $lang['days.no.round.allowed'] ?>")
            }
        })
    </script>
</section>