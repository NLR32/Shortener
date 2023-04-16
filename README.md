# Shortener

URL: http://ec2-18-188-67-75.us-east-2.compute.amazonaws.com/~lev/tamid/Shorten.html


Development Process:

This URL shortener was made using HTML, CSS, JavaScript. The back end was Apache with PHP and the database is implemented through PHPMyAdmin and MySQL. THe site is hosted throught AWS. 

The process started with creating a barebones structure for the html of the page, including input boxes, tables, and buttons. After that, the database was created with tw tables, one for users (witha. username as its primary key) and one for urls (having a user as a foreign key and shorturl as a primary key). When a user signs up, a new user is created in the data base and they continue to the next section of the page (previous is hidden behind javascript) and when they login with an an existing user the same happens. Once logged in users can enter in a full url and enter in the custom extension of their choosing for the shortened URL. Once they do, it is sent to the database and stored, and all current urls created for that specific user are reloaded from the database. all existing urls show their extension, full path, and copy, delete and edit buttons. Copying will copy the path to the site plus the extension, delete will remove the url from the table and the data base and enter will show a popup and allow the user to change the details of their url. Users are also able to log out whenever they wish.

Some of the issue that were run into during development was the uniqueness of usernames and shorturls. the way our database is set up, the user name is the primary key, thus there can be only one occuranc. We decided that we would limit users by nly allowing one user to have a specific username instead of assigning ids. Similarly with short urls, if urls have the same name, they would be reference in the same way. For this reason, if a short url is currently taken, the urlid is appended to allow for a unique path to the full url.

If there was more time to complete the project the site would be able to have a custom url seperate from the AWS instance. Because the EC2 instance url is already so long, the "shortened" version is quite long. We would hope to find from webframework or have some funds to enhance this feature of our website. 
