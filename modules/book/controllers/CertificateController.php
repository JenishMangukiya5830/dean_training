<?php

namespace book\controllers;

use course\service\CourseGateway;
use layout\Footer;
use layout\NavBar;
use layout\TitleBar;
use Pingle\Controller\Controller;
use Pingle\Model\Storage;

Class CertificateController extends Controller {

    public function IndexAction() {
        $View = $this->View();
        //get the flag
        $flag = $this->getFlag();
        $View->setSessionFlag($flag['text'], $flag['type']);
        $View->setTitle("Dean Training - Certificate Postage");
        $View->setMetaname("Dean Training offer SIA qualified close protection &amp; bodyguard training, including door supervisor courses that will enhance your security qualification portfolio");
        $View->setKeyword("close protection training, sia training, security qualification, sia license training, security industry authority,SIA");

        $NavBar = new NavBar();
        $TitleBar = new TitleBar();
        $Footer = new Footer();

        $db = $this->getHelper()->DatabaseConnection();
        $storage = new Storage();
        $courseGateway = new CourseGateway($this->getHelper(), $storage, $db);

        $View->Render(array(
            'titlebar' => $TitleBar->render(array('title' => 'Request a Certficate Postage')),
            'navbar' => $NavBar->render(array('active' => 'Request a Certficate Postage')),
            'footer' => $Footer->render(array()),
            'course' => $courseGateway->courseDropDownForPostage()
        ));
    }

}
