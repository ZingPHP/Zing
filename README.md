Zing
====

Lightweight php framework


##Some Key Features

* Routes
  * Set URL routes to format your URL's as you please
    * Default: `/@page/@action`; <b>Note:</b> `@page` and `@action` are reserved words
    * Example 1: `/@page=home/@id/@title`; This will only take place when page equals `home`
    * Example 2: `/@page=blog/@month/@year`; This will only take place when page equals `blog`
    * Example 3: `/@id/@action/@page`
  * Anything prefixed with an `@` becomes a $_GET variable
* Over-rideable methods
  * `runBefore()` Runs before the main code usually for setting up defaults 
  * `runAfter()` Runs after the main code usually for cleanup
  * `catchAll()` Runs if the called action was not found
* Database aka DBO (Any database supported by PDO)
  * Connect to one or more databases
  * Can interact with a database using a <b>D</b>ata<b>b</b>ase <b>O</b>bject Model
    * Example: `$this->db->getTable("members")->getItemById(123)`
  * You can write queries as you normally would as well
    * Example: `$this->db->getRow("select * from members where member_id = ? limit 1", array(123))`
* Mail
  * Send email with/without attachments
    * Example `$this->mail
                  ->addAttachment("myfile.jpg")
                  ->addRecipients(array("John Doe" => "jdoe@gmail.com))
                  ->send(array("email" => "noreply@example.com", "name" => "No Reply"), "My Title", "My HTML Message")`
* Smarty Templates
* Cache
  * File Caching for those who don't have memcache or APC
  * APC Caching
  * Memcache
* Twitter
  * PUT/GET from Twitters API
* HTTP
  * Get and work with websites using the easy to use cURL API
* Manage users sessions
  * Password tool for secure password storage
  * Validate passwords
  * Log user in
  * Check if user is logged in
  * Force user to be logged in to view a page
* Utilities
  * Mass data check to check if any item is blank (Removes before check: Spaces, Tabs, New Lines, Carage Returns)
    * Example: `$this->util->isBlank($one, $two, $three)`
* Validation
  * Vaidates 
    * Emails
    * IP's
    * URL's
    * User Names (0-9, A-Z, a-z and _)
    * Custom Format Tool
      * Example `$this->validate->isFormat($input, "(###) ###-####")`
* Widgets
  * These are pre-built items that accompany or inhance the actual page
  * These are call using one line of code with optional settings
    * Example: `$this->getWidget("Calendar", array("day" => "full"))`


There are many other items, and new items being added all the time.
