<?php


class MesajYanitPosta extends Listeners implements SplObserver
{

    public function update(SplSubject $subject)
    {
        $data = array(

            "title" => "Mesaj Yanıtlandı [" . $subject->konu . "]",
            "hitab" => "Merhaba " . $subject->alici . ",",
            "govde" => $subject->gonderen . " mesajı yanıtladı. Lütfen kontrol edin.",
            "mesaj" => $subject->mesaj,
            "islem" => "Yanıtla",
            "link" => $this->host . "/panel/mesajlar/{$subject->dugum}",

        );

        $this->mailGonder($subject->aliciposta, $data["title"], View::renderView("templates", "default", $data, "true"));
    }

}