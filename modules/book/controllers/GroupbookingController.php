<?php

/**
 * Pingle
 *
 *
 * @copyright 2014 The Pingle.
 * @link www.thepingle.com
 * @author Faiz Rasool <faiz708@gmail.com>
 */

namespace book\controllers;

use layout\Footer;
use layout\NavBar;
use layout\TitleBar;
use library\pingle\SafeInput;
use Pingle\Controller\Controller;

Class GroupbookingController extends Controller
{

    public function IndexAction()
    {
        $View = $this->View();
        //get the flag
        $flag = $this->getFlag();
        $View->setSessionFlag($flag['text'], $flag['type']);
        $View->setTitle("Dean Training - Group Booking");
        $View->setMetaname("Dean Training offer SIA qualified close protection &amp; bodyguard training, including door supervisor courses that will enhance your security qualification portfolio");
        $View->setKeyword("close protection training, sia training, security qualification, sia license training, security industry authority,SIA");
        $NavBar = new NavBar();
        $Footer = new Footer();
        $TitleBar = new TitleBar();
        $View->AddJavascript([
            'https://www.google.com/recaptcha/api.js?render=6LfXscIUAAAAAE1anUk2VLhxzVPN1zpI89WVemJO',
            '/js/group-booking.js'
        ]);
        $View->Render(array(
            'navbar' => $NavBar->render(array('active' => 'home')),
            'titlebar' => $TitleBar->render(array('title' => 'Group Booking / In House Training')),
            'footer' => $Footer->render(array()),
        ));
    }

    public function requestAction()
    {
        if ($_POST) {
            $safe = new SafeInput();
            $_POST = $safe->clean($_POST);
            if (isset($_POST['name']) && isset($_POST['email']) && isset($_POST['number']) && isset($_POST['message'])
                && isset($_POST['token'])) {

                $captcha = filter_input(INPUT_POST, 'token', FILTER_SANITIZE_STRING);
                $secretKey = "6LfXscIUAAAAAI1cjlsH2PybdBedXElh98yWepld";
                $url = 'https://www.google.com/recaptcha/api/siteverify';
                $data = array('secret' => $secretKey, 'response' => $captcha);
                $options = array(
                    'http' => array(
                        'header' => "Content-type: application/x-www-form-urlencoded\r\n",
                        'method' => 'POST',
                        'content' => http_build_query($data)
                    )
                );
                $context = stream_context_create($options);
                $response = file_get_contents($url, false, $context);
                $responseKeys = json_decode($response, true);
                header('Content-type: application/json');
                if ($responseKeys["success"]) {
                    $to = "accounts@deantraining.co.uk";
                    $to = 'faiz708@gmail.com';
                    $cc = "dean@deantraining.co.uk";

                    $from = "{$_POST['email']}";
                    $subject = "Group Booking Request - Dean Training";
                    $headers = "From: " . strip_tags($from) . "\r\n";
                    $headers .= 'Cc: ' . strip_tags($cc) . "\r\n";
                    $headers .= "Reply-To: " . strip_tags($from) . "\r\n";
                    $headers .= "MIME-Version: 1.0\r\n";
                    $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
                    $message = '<html><body>';
                    $message .= "<h3>Contact Request</h3>
                          <table border='1' cellpadding='0' cellspacing='0' height='100%' width='100%'>
                          <thead><th>Item</th><th>Description</th></thead>
                          <tbody>
                          <tr>
                            <th>Name</th>
                            <td>{$_POST['name']}</td>
                          </tr>
                          <tr>
                            <th>Email</th>
                            <td>{$_POST['email']}</td>
                          </tr>
                          <tr>
                            <th>Mobile</th>
                            <td>{$_POST['number']}</td>
                          </tr>
                          <tr>
                            <th>Message</th>
                            <td><pre>{$_POST['message']}</pre></td>
                          </tr>                          
                          </tbody>
                          </table>";
                    $message .= '</body></html>';
                    mail($to, $subject, $message, $headers);
                    # copy to sales
                    mail('sales@deantraining.co.uk', $subject, $message, $headers);
                    $this->setFlag('Request Sent!');
                    echo json_encode(array('success' => 'true'));
                } else {
                    echo json_encode(array('success' => 'false'));
                }
            }
        }
    }

}
