<?php
/**
 * Created by PhpStorm.
 * User: shakmr
 * Date: 24/11/15
 * Time: 20:47
 */

class MailTypes {
    const WINNERMAIL = 0;
    const PARTICIPATIONMAIL = 1;
    const FILE = "";
    const SUBJECT = "";
    const MAIL_ARRAY = "";
    const FROM = "";
    const REPLYTO ="";

    public function subject() {
        return static::SUBJECT;
    }

    public function file() {
        return static::FILE;
    }

    public function mail_array() {
        return static::MAIL_ARRAY;
    }

    public function from() {
        return static::FROM;
    }

    public function replyto() {
        return static::REPLYTO;
    }
}

class MailWinner extends MailTypes {
    const SUBJECT = "Nous avons un gagnant";
    const FILE = "../Strings/winner_template.html";
    const MAIL_ARRAY = "lpiovan@bhv.fr, relationclientele@bhv.fr,aroussillon@bhv.fr,ggutnick@bhv.fr,nzignone@bhv.fr,sauclair@bhv.fr,adupeux@bhv.fr";
//    const MAIL_ARRAY = "borja.arias.upc@gmail.com";
    const FROM = "suilabs@suilabs.com";
    const REPLYTO = "suilabs@suilabs.com";
}

class MailParticipation extends MailTypes
{
    const SUBJECT = "Merci pour votre participation";
    const FILE = "../Strings/participation_template.html";
    const REPLYTO = "relationclientele@bhv.fr";
    const FROM = "BHV-Marais<suilabs@suilabs.com>";
}

class Mailer
{
    private $raw_html;
    /** @var  MailTypes $type */
    private $type;
    private $user_mail;

    /**
     * @param MailTypes $type
     */
    public function __construct($type) {
        $file = $type->file();
        $this->type = $type;
        $this->raw_html = file_get_contents(realpath(dirname(__FILE__))."/".$file);
    }

    public function getMailParametersName() {
        $re = "/\\{\\{([A-z]+)\\}\\}/";
        preg_match_all($re, $this->raw_html, $matches);
        return $matches[1];
    }

    /**
     * @param [] $params
     */
    public function bindParams($params) {
        $keys = array_keys($params);
        foreach ($keys as $key) {
            $re = "/{{".$key."}}/";
            $this->raw_html = preg_replace($re, $params[$key], $this->raw_html);
        }
        $this->user_mail = $params["email"];
    }

    public function to($email) {
        $this->user_mail = $email;
    }

    public function sendMail() {
        $subject = $this->type->subject();
        $aux_mails = $this->type->mail_array();
        $mails = $this->user_mail;
        if ($aux_mails)
            $mails = $aux_mails;
        $from = $this->type->from();
        $rep = $this->type->replyto();
        $this->_sendMail($from, $rep, $mails, $subject, $this->raw_html);
    }


    public function today()
    {
        return date("Y-m-d H:i:s");
    }

    private function _sendMail($_from, $_rep, $_to, $_subj, $_msg) {
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type: text/html; charset=utf-8" . "\r\n";
        $headers .= 'From: '.$_from."\r\n".'Reply-To: '. $_rep . "\r\n" .
            'X-Mailer: PHP/' . phpversion();
        if (!mail($_to, $_subj, $_msg, $headers))
            throw new Exception("Correo no enviado");
        file_put_contents("mail.log", "[".$this->today()."]".$headers."\nto:".$_to, FILE_APPEND);
    }
}