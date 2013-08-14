<?php
class alsaPHP_commands
extends soundCard
{
    function __construct($soundCardNumber)
    {
        $this->soundCard  = (int) $soundCardNumber;
        $this->volumeStep = '3';
    }

    public function setVolumeUp($mixer, $channel = '')
    {
        $exec       = 'sget "' . $mixer . '"';
        $execResult = $this->amixer($exec);
        $channels   = self::listSoundCardChannels($this->soundCard, $mixer);
        $channels   = implode('|', $channels);
        $pattern    ='/\s+([' . $channels . ']+):[\w\d\s]*\[(\d+)%\]/';
        preg_match_all($pattern, $execResult, $matches);
        $result['mixer'] = $mixer;
        if (is_array($matches[1])) {
            foreach ($matches[1] as $key => $value) {
                if (!empty($channel)) {
                    if (in_array($value, $channel)) {
                        $commandLine  .= $this->volumeStep . '%+,';
                    }
                    else {
                        $commandLine .= $matches[2][$key] . '%,';
                    }
                }
                else {
                    $commandLine  .= $this->volumeStep . '%+,';
                }
            }
            $commandLine = rtrim($commandLine, ',');
        }
        $exec = 'sset "' . $mixer . '" ' . $commandLine;
        $execResult = $this->amixer($exec);
        return $execResult;
    }

    public function setVolumeDown($mixer, $channel = '')
    {
        $exec       = 'sget "' . $mixer . '"';
        $execResult = $this->amixer($exec);
        $channels   = self::listSoundCardChannels($this->soundCard, $mixer);
        $channels   = implode('|', $channels);
        $pattern    ='/\s+([' . $channels . ']+):[\w\d\s]*\[(\d+)%\]/';
        preg_match_all($pattern, $execResult, $matches);
        $result['mixer'] = $mixer;
        if (is_array($matches[1])) {
            foreach ($matches[1] as $key => $value) {
                if (!empty($channel)) {
                    if (in_array($value, $channel)) {
                        $commandLine  .= $this->volumeStep . '%-,';
                    }
                    else {
                        $commandLine .= $matches[2][$key] . '%,';
                    }
                }
                else {
                    $commandLine  .= $this->volumeStep . '%-,';
                }
            }
            $commandLine = rtrim($commandLine, ',');
        }
        $exec = 'sset "' . $mixer . '" ' . $commandLine;
        $execResult = $this->amixer($exec);
        return $execResult;
    }

    public function setVolume($volume, $mixer, $channel = '')
    {
        $exec       = 'sget "' . $mixer . '"';
        $execResult = $this->amixer($exec);
        $channels   = self::listSoundCardChannels($this->soundCard, $mixer);
        $channels   = implode('|', $channels);
        $pattern    ='/\s+([' . $channels . ']+):[\w\d\s]*\[(\d+)%\]/';
        preg_match_all($pattern, $execResult, $matches);
        $result['mixer'] = $mixer;
        if (is_array($matches[1])) {
            foreach ($matches[1] as $key => $value) {
                if (!empty($channel)) {
                    if (in_array($value, $channel)) {
                        $commandLine  .= $volume . '%,';
                    }
                    else {
                        $commandLine .= $matches[2][$key] . '%,';
                    }
                }
                else {
                    $commandLine  .= $volume . '%,';
                }
            }
            $commandLine = rtrim($commandLine, ',');
        }
        $exec = 'sset "' . $mixer . '" ' . $commandLine;
        $execResult = $this->amixer($exec);
        return $execResult;
    }

    public function toggle($mixer, $exec = TRUE)
    {
        if ($exec) {
            $exec = 'sset "' . $mixer . '" toggle';
        }
        else {
            $exec = 'sget "' . $mixer . '"';
        }
        $execResult = $this->amixer($exec);
        $channels   = self::listSoundCardChannels($this->soundCard, $mixer);
        $channels   = implode('|', $channels);
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