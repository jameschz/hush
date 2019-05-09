<?php
require_once 'Hush/Mail.php';

class Core_Util_Mail
{
    public static function send_smtp ($conf = array())
    {
        $mail = new Hush_Mail("UTF-8");
        require_once 'Zend/Mail/Transport/Smtp.php';
        $mail_title = isset($conf['mail_title']) ? $conf['mail_title'] : '';
        $mail_body = isset($conf['mail_body']) ? $conf['mail_body'] : '';
        $mail_from = isset($conf['mail_from']) ? $conf['mail_from'] : '';
        $mail_from_name = isset($conf['mail_from_name']) ? $conf['mail_from_name'] : '';
        $mail_to = isset($conf['mail_to']) ? $conf['mail_to'] : '';
        $login_user = isset($conf['login_user']) ? $conf['login_user'] : '';
        $login_pass = isset($conf['login_pass']) ? $conf['login_pass'] : '';
        $transport = new Zend_Mail_Transport_Smtp('smtp.mxhichina.com', array(
//             'ssl' => 'ssl',
            'auth' => 'login',
            'username' => $login_user,
            'password' => $login_pass,
            'port' => 25, // default
        ));
        $mail->setDefaultTransport($transport);
        $mail->setSubject($mail_title)
            ->setBodyHtml($mail_body)
            ->setFrom($mail_from, $mail_from_name)
            ->addTo($mail_to)
            ->send();
    }
}