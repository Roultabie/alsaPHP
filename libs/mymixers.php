<?php
class myMixers
{

    private static $myMixers;

    function __construct()
    {
        $this->arrayToObject($myMixers);
    }

    public function getMixer($mixer)
    {
        return $this->$mixer;
    }

    public function getMixers($mixers = '')
    {

    }

    private function setMixer($mixer, $channel = '', $key = '') // $key if is mixer edition
    {
        if (empty($key)) {
            if (is_array($channel)) {
                if (is_array($this->$mixer)) {
                    $key = key(end($this->$mixer)) + 1;
                    $this->$mixer += array($key => (object) $channel);
                }
                else {
                    $this->$mixer = array( (object) $channel);
                }
            }
                
        }
        else {
            $this->$mixer = array($key => (object) $channel);
        }
    }

    private function addChannel($mixer, $soundCardNumber, $soundCardMixer, $soundCardChannel)
    {
        $elements = array('soundCardNumber' => $soundCardNumber, 'soundCardMixer' => $soundCardMixer, 'soundCardChannel' => $soundCardChannel);
        $this->setMixer($mixer, $elements);
    }

    private function arrayToObject()
    {
        if (is_array(self::$myMixers)) {
            foreach (self::$myMixers as $mixerName => $mixers) {
                foreach ($mixers as $element) {
                    $this->addChannel($mixerName, $element['soundCardNumber'], $element['soundCardMixer'], $element['soundCardChannel']);
                }
            }
        }
    }

    public static function getMyMixers()
    {
        return self::$myMixers;
    }

    public static function setMyMixers($myMixers)
    {
        self::$myMixers = $myMixers;
    }
}
?>