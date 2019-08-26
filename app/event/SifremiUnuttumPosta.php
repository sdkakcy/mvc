<?php


class SifremiUnuttumPosta extends Listeners implements SplObserver
{

    public function update(SplSubject $subject)
    {
        $data = array(

            "title" => "Şifre Sıfırlama Talebi",
            "hitab" => "Merhaba " . $subject->kgad . ",",
            "govde" => "Hesabın için şifre sıfırlama isteğinde bulunuldu. Eğer bunu sen yapmadıysan bu e-postayı göz ardı edebilirsin. Şifreni yenilemek için aşağıdaki butona tıklayabilirsin.",
            "islem" => "Şiremi Yenile",
            "link" => $this->host . "/panel/yeniSifre/?k={$subject->kadi}&a={$subject->ac}",

        );

        $this->mailGonder($subject->eposta, $data["title"], View::renderView("templates", "default", $data, "true"));
    }
}