<?php
$GLOBALS['config']['mixersDb'] = 'mixers.php';
require 'libs/soundcard.php';
require 'libs/mymixers.php';

if (file_exists($GLOBALS['config']['mixersDb'])) {
    require $GLOBALS['config']['mixersDb'];
}

myMixers::setMyMixers($GLOBALS['myMixers']);

$soundCards = soundCard::listSoundCards();

if (is_array($soundCards)) {
    //$html = '';
    foreach ($soundCards as $soundCard) {
        $html .= '<div class="container"><ul class="soundCard"><li>' . $soundCard->soundCardName . ', ' .$soundCard->soundCardNumber . ':' . $soundCard->soundCardDevice . '</li>';
        $soundCardsMixers = soundCard::listSoundCardMixers($soundCard->soundCardNumber);
        if (is_array($soundCardsMixers)) {
            $html .= '<ul class="mixer">';
            foreach ($soundCardsMixers as $mixer) {
                if (is_array($mixer->limits)) {
                    $limits = ' (limits: ' . $mixer->limits[0] . ' -> ' . $mixer->limits[1] . ')';
                }
                $html .= '<li>' . $mixer->mixerName . $limits . '</li>';
                $soundCardsChannels = soundCard::listSoundCardChannels($soundCard->soundCardNumber, $mixer->mixerName);
                if (is_array($soundCardsChannels)) {
                    $html .= '<ul class="channel">';
                    foreach ($soundCardsChannels as $channel) {
                        $html .= '<li>' . $channel . '</li>';
                    }
                    $html .= '</ul>';
                }
                $soundCardsStates = soundCard::listSoundCardStates($soundCard->soundCardNumber, $mixer->mixerName);
                if (is_array($soundCardsStates)) {
                    $html .= '<ul class="state">';
                    foreach ($soundCardsStates as $state) {
                        $html .= '<li>' . $state . '</li>';
                    }
                    $html .= '</ul>';
                }
            }
            $html .= '</ul></ul></div>';
        }
    }
    //$html .= '';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>alsaPHP example</title>
    <style type="text/css">
    .container {
        margin-right: 5px;
        float:left;
        border: 1px solid;
        display: inline;
    }
    ul.soundCard {
        margin: 5px;
        padding: 0;
    }
    ul.mixer {
        margin: 5px;
        padding: 0;
    }
    ul.channel, ul.state {
        margin-bottom: 7px;
    }
    .soundCard li {
        display: inline;
    }
    .soundCard > li {
        font-weight: bold;
    }
    .channel > li, .state > li {
        font-style: italic;
    }
    .channel > li:after, .state > li:after {
        content: " |";
    }
    .channel > li:last-child:after, .state > li:last-child:after {
        content: "";
    }
    </style>
</head>
<body>
    <code>
        <?php echo $html ?>
    </code>

</body>
</html>