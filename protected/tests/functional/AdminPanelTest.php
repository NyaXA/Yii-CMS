<?php

class AdminPanelTest extends WebTestCase
{
    public function testAllPages()
    {
        $this->setBrowser("firefox3");

        $this->open('/banners/bannerAdmin/manage');
        var_dump($this->isTextPresent('Таблица'));
    }
}
