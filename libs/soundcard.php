<?php
class soundCard
{
    function __construct($soundCardNumber)
    {
        #
    }

    public static function listSoundCards()
    {
        $commandResult = shell_exec('aplay -l');
        $wantedLines     = '/(\w+)\s(\d+):\s(\w+)\s\[([\w\d\s]+)\],\s(\w+)\s(\d+):\s([\w\d\s]+)\[([\w\d\s]+)\]/ui';
        preg_match_all($wantedLines, $commandResult, $matches);
        $nbCards = count($matches[0]);
        for ($i = 0; $i < $nbCards; $i++) {
            $currentCard['soundCardNumber']     = $matches[2][$i];
            $currentCard['soundCardType']       = $matches[3][$i];
            $currentCard['soundCardName']       = $matches[4][$i];
            $currentCard['soundCardDevice']     = $matches[6][$i];
            $currentCard['soundCardDeviceType'] = $matches[7][$i];
            $currentCard['soundCardDeviceName'] = $matches[8][$i];
            $cards[$i] = (object) $currentCard;
        }
        return $cards;
    }

    public static function listSoundCardMixers($card)
    {
        $commandResult = shell_exec('amixer -c ' . (int) $card . ' scontrols');
        $wantedLines   = "/Simple mixer control '([\w\d\s-_\.]+)',\d+/ui";
        preg_match_all($wantedLines, $commandResult, $matches);
        $nbMixers = count($matches[0]);
        for ($i = 0; $i < $nbMixers; $i++) {
            $currentMixer['mixerName'] = $matches[1][$i];

            $mixerCommand = shell_exec('amixer -c ' . (int) $card . ' sget "' . $matches[1][$i] . '"');

            // Capabilities
            $capLine = "/\s+Capabilities: ([\w\-_ ]+)/";
            if (preg_match($capLine, $mixerCommand, $capabilities)) {
                $currentMixer['capabilities'] = explode(' ', $capabilities[1]);
            }
            else {
                unset($currentMixer['capabilities']);
            }

            // Limits
            $limLine = "/\s+Limits: [\w\-_ ]* (\d+ \- \d+)/";
            if (preg_match($limLine, $mixerCommand, $limits)) {
                $currentMixer['limits'] = explode(' - ', $limits[1]);
            }
            else {
                unset($currentMixer['limits']);
            }

            
            $mixers[$i] = (object) $currentMixer;
        }
        return $mixers;
    }

    public static function listSoundCardChannels($card, $mixer)
    {
        $commandResult = shell_exec('amixer -c ' . (int) $card . ' sget "' . $mixer . '"');
        $wantedLines   = '/\s+(Playback|Capture) channels: (.*)/';
        $wantedItems   = "/\s+Items: ([\w\-_' ]+)/";
        if (preg_match_all($wantedLines, $commandResult, $matches)) {
            $channels = explode(' - ', $matches[2][0]);
        }
        return $channels;
    }

    public static function listSoundCardStates($card, $mixer)
    {
        $commandResult = shell_exec('amixer -c ' . (int) $card . ' sget "' . $mixer . '"');
        $wantedLines   = "/\s+Items: ([\w\-_' ]+)/";
        if (preg_match($wantedLines, $commandResult, $items)) {
            $states = explode(' ', str_replace("'", "", $items[1]));
        }
        return $states;
    }

    private static function isInAudioGroup()
    {
        $httpUser = shell_exec('whoami');
        $result   = shell_exec('id ' . $httpUser . ' |grep audio');
        if (!empty($result)) {
            return TRUE;
        }
        else {
            return FALSE;
        }
    }
}
?>