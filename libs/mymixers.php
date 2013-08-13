<?php
class myMixers
extends soundCard
{

    private static $myMixers;

    function __construct()
    {
        $this->arrayToObject();
    }

    public function getMixer($mixer)
    {
        return $this->$mixer;
    }

    /*public function getMixers($mixers = '')
    {

    }*/

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
        if (is_array(self::getMyMixers())) {
            foreach (self::getMyMixers() as $mixerName => $mixers) {
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

    public static function setMyMixers($mixers)
    {
        // ajouter le test d'existence des cards / mixers / channels
        self::$myMixers = $mixers;
    }

    public static function addMyMixer($mixerName, $soundCardNumber, $soundCardMixer, $soundCardChannel)
    {
        // V1
        self::$myMixers[$mixerName][] = array('soundCardNumber' => $soundCardNumber, 'soundCardMixer' => $soundCardMixer, 'soundCardChannel' => $soundCardChannel);
        // V2 quand sera ajouté le contrôle des éléments dans self::setMyMixers();
        /*$mixers               = self::getMyMixers();
        $mixers[$mixerName][] = array('soundCardNumber' => $soundCardNumber, 'soundCardMixer' => $soundCardMixer, 'soundCardChannel' => $soundCardChannel);
        self::setMyMixers($mixers);*/
    }
}
?>