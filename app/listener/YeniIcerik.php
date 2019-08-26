<?php


class YeniIcerik implements SplSubject
{

    protected $observers = [];

    public $post_name;

    public $post_id;

    public $post_tip;

    public $editors = [];


    public function __construct($post_id, $post_name, $post_tip, $editors)
    {

        $this->post_id = $post_id;
        $this->post_name = $post_name;
        $this->post_tip = $post_tip;
        $this->editors = $editors;

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