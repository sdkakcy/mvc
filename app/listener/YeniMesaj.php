<?php


class YeniMesaj implements SplSubject
{

    protected $observers = [];

    public $alici;

    public $aliciposta;

    public $gonderen;

    public $dugum;

    public $konu;

    public $mesaj;


    public function __construct($alici, $aliciposta, $gonderen, $dugum, $konu, $mesaj)
    {

        $this->alici = $alici;
        $this->aliciposta = $aliciposta;
        $this->gonderen = $gonderen;
        $this->dugum = $dugum;
        $this->konu = $konu;
        $this->mesaj = $mesaj;

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