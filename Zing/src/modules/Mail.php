<?php

namespace Modules;

class Mail extends Module{

    protected $attachments = array();
    protected $recipients  = array();

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
        foreach($recipients as $name => $email){
            if(filter_var($email, FILTER_VALIDATE_EMAIL)){
                $this->recipients[$name] = $email;
                $valid++;
            }else{
                $invalid++;
            }
        }
        return $this;
    }

    /**
     * Sends a message to the list of recipients.<br>
     * Note: Once the email is sent, everything gets reset.
     * @param array $from       Who the email is from
     *                          array("email" => "ex@site.com", "name" => "Billy Bob")
     * @param string $subject   The subject of the message
     * @param string $message   The body of the message
     * @throws Exception
     * @return \Mail
     */
    public function send(array $from, $subject = "", $message = ""){
        if(count($this->recipients) == 0){
            throw new Exception("No recipents set. Emails must be valid.");
        }
        if(!filter_var($from["email"], FILTER_VALIDATE_EMAIL)){
            throw new Exception("'{$from["email"]}' is not a valid email address.");
        }
        if(!empty($this->attachments)){
            $random_hash   = md5(date('r', time()));
            $mime_boundary = "==Multipart_Boundary_x{$random_hash}x";
        }
        foreach($this->recipients as $name => $email){
            $headers = "MIME-Version: 1.0\r\n";
            $headers .= "To: $name <$email>\r\n";
            $name    = isset($from["name"]) ? $from["name"] : "";
            $headers .= "From: $name <{$from["email"]}>\r\n";
            if(!empty($this->attachments)){
                $headers .= "Content-Type: multipart/mixed; boundary=\"$mime_boundary\";\r\n";
                $msg_final = "--$mime_boundary\r\n" .
                        "Content-Type: multipart/mixed; boundary=\"{$mime_boundary}\";\r\n" .
                        "Content-Transfer-Encoding: 7bit\r\n\r\n" .
                        $message . "\r\n\r\n";
                foreach($this->attachments as $file){
                    if(!empty($file)){
                        if(is_file($file)){
                            continue;
                        }
                        $data = chunk_split(base64_encode(file_get_contents($file)));
                        $msg_final .= "--{$mime_boundary}\r\n" .
                                "Content-Type: multipart/alternative; name=\"" . basename($file) . "\"\r\n" .
                                "Content-Transfer-Encoding: base64\r\n" .
                                "Content-Disposition: attachment; filename=\"" . basename($file) . "\";\r\n\r\n" .
                                $data . "\r\n\r\n" .
                                "--{$mime_boundary}\r\n";
                    }
                }
            }else{
                $headers .= "Content-type: text/html; charset=utf-8\r\n";
            }
            mail($email, $subject, $msg_final, $headers);
            $this->reset();
            return $this;
        }
    }

    /**
     * Resets the message settings
     */
    protected function reset(){
        $this->attachments = array();
        $this->recipients  = array();
    }

}
