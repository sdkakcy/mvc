<?php


class HesapOnay implements SplSubject
{

    protected $observers = [];

    public $kadi;

    public $kgad;

    public $eposta;

    public $ac;


    public function __construct($kadi, $eposta, $ac, $kgad = null)
    {

        $this->kadi = $kadi;
        $this->eposta = $eposta;
        $this->ac = $ac;
        $this->kgad = $kgad;

    }

    public function attach(SplObserver $observer)
    {
        $key = spl_object_hash($observer);
        $this->observers[$key] = $observer;

        return $this;
    }


    public function detach(SplObserver $observer)
    {
        $key = spl_object_hash($observer);
        unset($this->observers[$key]);
    }

    public function notify()
    {
        foreach ($this->observers as $observer) {
            $observer->update($this);
        }
    }

}