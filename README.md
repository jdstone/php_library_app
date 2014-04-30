PHP Library Web App (aka StoneLibrary)
===============

StoneLibrary is a free, open-source, web-based application for cataloging and 
organizing your various types of media. Media such as your books, movies, music, and possibly in the 
future, a few more. This product will initially be targeted to those in their late teens up to those in their 
mid-to-high 20s, but other age groups are expected to join in.

This product will provide a valuable service allowing users to easily catalog and organize their 
various types of media.  In the near future, users will be able to share media catalogs with other users, 
as well as the ability to mark items as "lent" or "checked-out". This easily allows users to keep track of 
the items they lend to their friends.

Requirements
============

-  Operating System: Ubuntu Server 10.04 (Linux 2.6.32)
-  HTTP Web Server Software: Apache 2.2.14 (Ubuntu)
-  Development Language: PHP 5.3.2
-  Backend Database: MySQL 5.1.41
-  Tested with Google Chrome 13.0.782.112+ & Mozilla Firefox 5.0.1+
-  Email address is used as their username
-  Sessions are used in place of cookies for authentication as well as to maintain the users 'state'
   while on the site. Sessions only work while the browser is open, so once the user closes their
   browser, any information associated with the session as well as the session itself, is deleted
   from the server. No personal information is stored in these sessions, only a unique id and the
   usergroup (usergroup may be used for a future enhancement) they belong to.
-  Easily scales to accommodate high user traffic

Feature Summary
===============

<h3>Accounts</h3>
User's will be required to login to access the site's content. First, they will need to create an account 
(future versions will authenticate users using their Google accounts and/or Facebook accounts). This 
registration process will ask for their first name, a valid email address, and a password of their choice.

Visits to the site thereafter will allow the user to login to their account using their email address as their 
"username".  A session (which is stored on the server) containing a unique, non personally identifiable ID number and usergroup (which is the level of access they have, full admin access to their account for 
now) they belong to will be created when the user logs in.

Once logged in, the user's account settings page provides the user the ability to edit their account. 
Editing the account allows the user to change their password and email address.  When the user is 
finished, he/she may logout by clicking the logout link or simply closing the browser (destroying the 
session).

<h3>Libraries</h3>
In addition to allowing the user to edit his/her account settings, he/she has the ability to manipulate the 
various media libraries available to them. This includes, creating, reading, updating, and deleting items 
in their movies, books, and music libraries.  Creating and updating library items include properties such 
as title of a movie or author of a book.

Once library items are created, they begin to show up in the appropriate library, with a dynamically 
generated image as the cover art with the title of the said item centered in the middle on a white 
background.  From here, the user is able to delete or edit the item. To delete the item, the user must 
simply click the delete icon, which is located in the upper right corner of each library item.  Clicking 
the cover art allows the user to view (read) detailed information and update the information associated 
with the item.

See https://github.com/jdstone/php_library_app/blob/master/Product_Documentation.pdf for more info.
