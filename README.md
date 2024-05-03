Install XAMPP (my version was 3.3.0).

(The default installation settings during the installation process are fine.)

Once the installation is complete, start the XAMPP Control Panel.

Start the Apache (server) and the MySQL services.
(If the network is private, allow for private networks, too)

Download the .zip file containing the GitHub repo.
(After extracting the files in it, the .zip file can be deleted.)

Get to the root of the repo. The files should be visible.
To have the Apache server run these files, open the htdocs folder by activating the XAMPP control panel and clicking on the “Explore” button on its right side.
A new explorer window with the XAMPP root folder should open. Next, in that folder look for the htdocs folder and open it.

Delete all these files contained in it.

Go back to the repo’s folder, copy its content (access.php, etc.), and paste it into the htdocs folder.

To import and visualize the database (.sql file), click on “Admin” for the MySQL service on the XAMPP control panel.
Once phpMyAdmin is open click on the “Import” button located in the top (in the middle).

Click on “Choose File” to locate and load the .sql file. It can be found in the root of the htdocs folder, now that we have copied the files of the repository.
Once it’s been located, open it.
Click on “Import”, at the bottom of the page.
Now the database appears on the left pane.
Its structure and data (randomly generated) can now be explored regardless of the logged-in user.

The website can be reached by typing “localhost/” in the browser’s address bar.

If the access page is reached, the server works and the files have been correctly put into the htdocs folder.
By logging in using "test" as both username and password, the state of the database can also be verified (the homepage opens).
