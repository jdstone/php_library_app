php_library_app
===============

PHP Library Web App (aka StoneLibrary)

StoneLibrary is a free, open-source, web-based application for cataloging and 
organizing your various types of media. Media such as your books, movies, music, and possibly in the 
future, a few more. This product will initially be targeted to those in their late teens up to those in their 
mid-to-high 20s, but other age groups are expected to join in.

This product will provide a valuable service allowing users to easily catalog and organize their 
various types of media.  In the near future, users will be able to share media catalogs with other users, 
as well as the ability to mark items as "lent" or "checked-out". This easily allows users to keep track of 
the items they lend to their friends.

<h1>Requirements</h1>

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
