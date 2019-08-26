<?php


class YeniMesajPosta extends Listeners implements SplObserver
{

    public function update(SplSubject $subject)
    {
        $data = array(

            "title" => "Yeni Mesaj [" . $subject->konu . "]",
            "hitab" => "Merhaba " . $subject->alici . ",",
            "govde" => $subject->gonderen . " tarafından yeni bir mesaj aldınız. Lütfen kontrol edin.",
            "mesaj" => $subject->mesaj,
            "islem" => "Yanıtla",
            "link" => $this->host . "/panel/mesajlar/{$subject->dugum}",

        );

        $this->mailGonder($subject->aliciposta, $data["title"], View::renderView("templates", "default", $data, "true"));
    }
}