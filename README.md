Zing
====

Lightweight php framework

##Installation

* Apache
  1. Copy the follow files/directories into the root of your web directory
    1. Websites/\*
    2. Zing/\*
    3. index.php
    4. .htaccess
  2. Make sure Mod_Rewrite is enabled if it is not:
    1. Enable Mod_Rewrite
    2. Restart Apache
* Nginx
  1. Copy the follow files/directories into the root of your web directory
    1. Websites/\*
    2. Zing/\*
    3. index.php
  2. Place <b>nginx.cfg</b> in your sites config directory
  3. Reload Nginx `service nginx reload` (use sudo if needed)

Next you will need to modify the config file:

```php
$config = array(
    "websites" => array(
        "host" => "example.com"
    ),
    "databases" => array(
        "localhost" => array(// Global databases all sites can use
            "hostname" => "localhost",
            "username" => "my_user",
            "password" => "my_password",
            "database" => "my_database"
        )
    )
);
```

1. Replace `example.com` with your domain.
2. Either remove the databases section or modify the values for `hostname`, `username`, `password` and `database`

If all goes well, when you navigate to your domain you should see the following message:

    Success!
    Welcome to the Zing framework! Looks like all is well!

##Some Key Features (Light List)

* Routes
  * Set URL routes to format your URL's as you please
    * Default: `/@page/@action`; <b>Note:</b> `@page` and `@action` are reserved words
    * Example 1: `/@page=home/@id/@title`; This will only take place when page equals `home`
    * Example 2: `/@page=blog/@month/@year`; This will only take place when page equals `blog`
    * Example 3: `/@id/@action/@page`
  * Anything prefixed with an `@` becomes a `$_GET` variable
* Over-rideable methods
  * `runBefore()` Runs before the main code usually for setting up defaults 
  * `runAfter()` Runs after the main code usually for cleanup
  * `catchAll()` Runs if the called action was not found
* Database aka DBO (Any database supported by PDO)
  * Connect to one or more databases
  * Can interact with a database using a <b>D</b>ata<b>b</b>ase <b>O</b>bject Model
  ```php
  $this->db->getTable("members")->getItemById(123);
  ```
  * You can write queries as you normally would as well
  ```php
  $this->db
      ->getAll("select * from movies where title like ? ", array("peter%"))
      ->each(function($row){
          echo "<p>" . $row["title"] . ": " . $row["description"] . "</p>";
      });
  ```
* Mail
  * Send email with/without attachments
  ```php
  $this->mail
      ->addAttachment("myfile.jpg")
      ->addRecipients(array("John Doe" => "jdoe@gmail.com"))
      ->send(array("email" => "noreply@example.com", "name" => "No Reply"), "My Title", "My HTML Message");
  ```
* Smarty Templates
* Cache
  * File Caching for those who don't have memcache or APC
  ```php
  $trending = $this->cache->setEngine()->cache("trending_news", 300, function(){
      return $this->db->getAll("select * from news where votes > 50 order by last_vote desc");
  });
  ```
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

##Features still in the works

* Image
  * Image Manipulation
    * Resizing
    * Croping
    * Filters
    * Blending
  * Image CAPTCHA

There are many other items, and new items being added all the time.
