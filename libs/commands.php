<?php
class alsaPHP_commands
extends soundCard
{
    function __construct($soundCardNumber)
    {
        $this->soundCard = (int) $soundCardNumber;
    }

    public function toggle($mixer)
    {
        $channels   = self::listSoundCardChannels($this->soundCard, $mixer);
        $channels   = implode('|', $channels);
        $exec       = 'sset "' . $mixer . '" toggle';
        $execResult = $this->amixer($exec);
        $pattern    ='/\s+([' . $channels. ']+):.*\[([on|off]+)\]/';
        preg_match_all($pattern, $execResult, $matches);
        $result['mixer'] = $mixer;
        if (is_array($matches[1])) {
            foreach ($matches[1] as $key => $value) {
                $result[$value] = $matches[2][$key];
            }
        }
        else {
            $result['error'] = '';
        }
        return $result;
    }

    private function amixer($command)
    {
        $commandLine  = 'amixer -c ' . $this->soundCard . ' ';
        $commandLine .= $command;
        return shell_exec($commandLine);
    }
}
?>