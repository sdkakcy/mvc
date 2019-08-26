<?php

class YeniIcerikPosta extends Listeners implements SplObserver
{

    public function update(SplSubject $subject)
    {

        $data = array(

            "title" => "İçerik Onay Bekliyor " . "[" . $subject->post_name . "]",
            "preheader" => "İçerik editör onayı için gönderildi, lütfen denetleyin.",
            "hitab" => "",
            "govde" => '"' . $subject->post_name . '"' . " adlı içerik editör onayı için gönderildi. Lütfen denetleyin.",
            "islem" => "İçeriği Görüntüle",
            "link" => $this->host . "/panel/" . ($subject->post_tip == "yazi" ? $subject->post_tip . "lar/" : $subject->post_tip . "ler/") . "duzenle/" . $subject->post_id,

        );

        foreach ($subject->editors as $editor) {

            $data["hitab"] = "Merhaba " . $editor["kullanici_goruntulenecek_ad"] . ",";
            $this->mailGonder($editor["kullanici_eposta"], $data["title"], View::renderView("templates", "default", $data, "true"));

        }

    }

}