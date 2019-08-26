<?php


class HesapOnayPosta extends Listeners implements SplObserver
{

    public function update(SplSubject $subject)
    {

        $data = array(

            "title" => "Hesabınızı Etkinleştirin",
            "hitab" => "Merhaba " . $subject->kadi . ",",
            "govde" => "Efendi Dergi ailesine hoş geldiniz. Yapmanız gereken tek bir şey kaldı. Hesabınızı aşağıdaki butona basarak etkinleştirebilirsiniz.",
            "islem" => "Hesabımı Etkinleştir",
            "link" => $this->host . "/panel/aktiflestir/?k={$subject->kadi}&a={$subject->ac}",

        );

        $this->mailGonder($subject->eposta, $data["title"], View::renderView("templates", "default", $data, "true"));

    }
}