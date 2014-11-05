<?php

namespace Modules;

use Exception;

class Mail extends Module{

    protected $attachments = array();
    protected $recipients  = array();
    protected $email       = null, $name        = null;

    /**
     * Adds files to be sent with the message
     * @param string|array $filname,... A list of filenames
     * @return \Mail
     */
    public function addAttachment($filename){
        if(is_array($filename)){
            $args = $filename;
        }else{
            $args = func_get_args();
        }
        foreach($args as $arg){
            $this->attachments[] = $arg;
        }
        return $this;
    }

    /**
     * Adds recipients to the message
     * @param array $recipients
     * @param integer &$valid
     * @param integer &$invalid
     * @return \Mail
     */
    public function addRecipients(array $recipients, &$valid = 0, &$invalid = 0){
        foreach($recipients as $email => $name){
            if(filter_var($email, FILTER_VALIDATE_EMAIL)){
                $this->recipients[$email] = $name;
                $valid++;
            }else{
                $invalid++;
            }
        }
        return $this;
    }

    /**
     * Set the sender information
     * @param type $email
     * @param type $name
     * @return Mail
     * @throws Exception
     */
    public function setSender($email, $name = ""){
        if(filter_var($email, FILTER_VALIDATE_EMAIL)){
            $this->email = $email;
            $this->name  = $name;
        }else{
            $this->email = null;
            $this->name  = null;
            throw new Exception("'$email' is not a valid email address.");
        }
        return $this;
    }

    /**
     * Sends a message to the list of recipients.<br>
     * Note: Once the email is sent, everything gets reset.
     * @param string $subject   The subject of the message
     * @param string $message   The body of the message
     * @throws Exception
     * @return \Mail
     */
    public function send($subject = "", $message = ""){
        if(count($this->recipients) === 0){
            throw new Exception("No recipents set. Emails must be valid.");
        }
        if($this->email === null){
            throw new Exception("No sender set.");
        }
        if(!empty($this->attachments)){
            $random_hash   = md5(date('r', time()));
            $mime_boundary = "==Multipart_Boundary_x{$random_hash}x";
        }
        foreach($this->recipients as $email => $name){
            $headers = "MIME-Version: 1.0\r\n";
            $headers .= "To: $name <$email>\r\n";
            $name    = isset($this->name) ? $this->name : "";
            $headers .= "From: $name <$this->email>\r\n";
            if(!empty($this->attachments)){
                $headers .= "Content-Type: multipart/mixed; boundary=\"$mime_boundary\";\r\n";
                $msg_final = "--$mime_boundary\r\n" .
                        "Content-Type: text/html; charset=utf-8\r\n" .
                        $message . "\r\n\r\n";
                $msg_final .= $this->attachFiles($mime_boundary);
            }else{
                $msg_final = $message;
                $headers .= "Content-type: text/html; charset=utf-8\r\n";
            }
            mail($email, $subject, $msg_final, $headers);
        }
        $this->reset();
        return $this;
    }

    /**
     * Resets the message settings
     */
    protected function reset(){
        $this->attachments = array();
        $this->recipients  = array();
    }

    protected function attachFiles($mime_boundary){
        $msg_final = "";
        foreach($this->attachments as $file){
            if(!empty($file)){
                if(!is_file($file)){
                    continue;
                }
                $finfo = finfo_open(FILEINFO_MIME_TYPE);
                $mime  = finfo_file($finfo, $file);
                finfo_close($finfo);
                $data  = chunk_split(base64_encode(file_get_contents($file)));
                $msg_final .= "--{$mime_boundary}\r\n" .
                        "Content-Type: $mime; name=\"" . basename($file) . "\"\r\n" .
                        "Content-Transfer-Encoding: base64\r\n" .
                        "Content-Disposition: attachment; filename=\"" . basename($file) . "\";\r\n\r\n" .
                        $data . "\r\n\r\n" .
                        "--{$mime_boundary}\r\n";
            }
        }
        return $msg_final;
    }

}
