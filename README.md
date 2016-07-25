Extract Contacts from Unisender
-----------------------------------------------

***extraction.php***

Extract all the contacts from all your list and save it into a file. You must have your API key.

````shell
      php extraction.php
````

***insertion.php***

Insert all your contacts (previusly extracted with extraction.php) into a MySQL database.

````shell
      php insertion.php unisender-contacts.txt
````


***table.sql***

SQL script to crate the "unisender" table for our contacts.

