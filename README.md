Zing
====

Lightweight php framework


##Key Features

* Database aka DBO (Any database supported by PDO)
** Connect to one or more databases
** Can interact with a database using a **D**ata**b**ase **O**bject Model
*** Example: `$this->db->getTable("members")->getItemById(123)`
** You can write queries as you normally would as well
*** Example: `$this->db->getRow("select * from members where member_id = ? limit 1", array(123))`
* Mail
** Send email with/without attachments
*** Example `$this->mail
                  ->addAttachment("myfile.jpg")
                  ->addRecipients(array("John Doe" => "jdoe@gmail.com))
                  ->send(array("email" => "noreply@example.com", "name" => "No Reply"), "My Title", "My HTML Message")`
* Smarty Templates
* Cache
** File Caching for those who don't have memcache or APC
** APC Caching
** Memcache
* Twitter
** PUT/GET from Twitters API
* HTTP
** Get and work with websites using the easy to use cURL API
* Manage users sessions
** Password tool for secure password storage
** Validate passwords
** Log user in
** Check if user is logged in
** Force user to be logged in to view a page
* Utilities
** Mass data check to check if any item is blank (Removes before check: Spaces, Tabs, New Lines, Carage Returns)
*** Example: `$this->util->isBlank($one, $two, $three)`
* Validation
** Vaidates 
*** Emails
*** IP's
*** URL's
*** User Names (0-9, A-Z, a-z and _)
*** Custom Format Tool
**** Example `$this->validate->isFormat($input, "(###) ###-####")`

There are many other items, and new items being added all the time.
