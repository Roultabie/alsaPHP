<?php
class soundCard
{
    function __construct($card = 0)
    {
        #
    }

    public function getMixer()
    {
        return $this->mixer;
    }

    public function setMixer($mixer)
    {
        $this->mixer[$mixer] = '';
    }

    public function addChannel($mixer, $channel)
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
            $cards[$i]['card']       = $matches[2][$i];
            $cards[$i]['cardType']   = $matches[3][$i];
            $cards[$i]['cardName']   = $matches[4][$i];
            $cards[$i]['device']     = $matches[6][$i];
            $cards[$i]['deviceType'] = $matches[7][$i];
            $cards[$i]['deviceName'] = $matches[8][$i];
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
            $mixers[$i] = $matches[1][$i];
        }
        return $mixers;
    }

    public static function listSoundCardChannels($card, $mixer)
    {
        $commandResult = shell_exec('amixer -c ' . (int) $card . ' sget ' . $mixer);
        $wantedLines   = '/(Playback|Capture) channels: (.*)/';
        preg_match_all($wantedLines, $commandResult, $matches);
        if (!empty($matches[2][0])) {
            $elements = explode(' - ', $matches[2][0]);
            foreach ($elements as $key => $value) {
                $channels[$key] = $value;
            }
            return $channels;
        }
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