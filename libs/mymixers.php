<?php
class myMixers
{
    public function getMixer($mixer)
    {
        return $this->$mixer;
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

    public function addChannel($mixer, $soundCardNumber, $soundCardMixer, $soundCardChannel)
    {
        $elements = array('soundCardNumber' => $soundCardNumber, 'soundCardMixer' => $soundCardMixer, 'soundCardChannel' => $soundCardChannel);
        $this->setMixer($mixer, $elements);
    }
}
?>