<?php

class Forms extends Zing{

    public function main(){
        $register = $this->form->createForm("register", array("action" => "/home/register", "method" => "post"));

        // Email
        $register->createFormItem()->text("email", [
            "attr"   => ["class" => "text", "id" => "email", "placeholder" => "Email"],
            "format" => "email"
        ]);

        // Username
        $register->createFormItem()->text("username", [
            "attr" => ["class" => "text", "id" => "username", "placeholder" => "Username"],
            "minlen" => 4
        ]);

        // Password
        $register->createFormItem()->password("password", [
            "attr"     => ["class" => "text", "id" => "password", "placeholder" => "Password"],
            "minlen"   => 6,
            "remember" => false
        ]);

        // Phone Number
        $register->createFormItem()->text("phone", [
            "attr"   => ["class" => "text", "id" => "phone", "placeholder" => "Phone Number"],
            "format" => "###-###-####"
        ]);

        // Submit Button
        $register->createFormItem()->submit("submit", [
            "attr" => ["class" => "button", "id" => "submit", "value" => "Register"]
        ]);

        $this->smarty->assign("register", $register->getForm());
    }

    public function register(){
        $this->setTemplate("Home/main");
        if(!$this->form->validate("register")){
            var_dump($this->form->getErrors());
        }else{
            echo "Good";
        }
        $register = $this->form->rebuildForm("register");
        $this->smarty->assign("register", $register->getForm());

        /*
          echo "<pre>";
          print_r($_POST);
          print_r($_SESSION);
          echo "</pre>";
         */
    }

    public function aboutMe(){
        echo "<h1>About Me</h1>";
    }

}
