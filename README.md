# IP-Database
PervSealy Site

This is the website for the GHC-Community on Discord (https://discord.gg/3bQ5hE5)

# Setup
The website requires an MYSQL-Database to work. Since there is no setup-file, you have to do it yourself.
After logging into your database, follow these steps:
1. Create the IP-Database with the following SQL-Code:
```
 CREATE TABLE `HackersIP` (
 `ID` int(11) NOT NULL AUTO_INCREMENT,
 `IP` varchar(15) NOT NULL,
 `Name` varchar(255) NOT NULL,
 `Added_By` varchar(255) DEFAULT NULL,
 `Reputation` int(11) DEFAULT NULL,
 `Last_Updated` date NOT NULL,
 `Miners` int(11) DEFAULT NULL,
 `Description` varchar(255) DEFAULT NULL,
 PRIMARY KEY (`ID`)
 ) ENGINE=MyISAM AUTO_INCREMENT=15 DEFAULT CHARSET=latin1
 ```

2. Create the User-Database with the following SQL-Code:
```
  CREATE TABLE `Users` (
 `ID` int(11) NOT NULL AUTO_INCREMENT,
 `Username` varchar(255) NOT NULL,
 `Password` varchar(255) NOT NULL,
 `Email` varchar(255) NOT NULL,
 `Role` enum('Admin','Moderator','User') NOT NULL DEFAULT 'User',
 `Last_Login` datetime NOT NULL,
 PRIMARY KEY (`ID`)
 ) ENGINE=MyISAM AUTO_INCREMENT=19 DEFAULT CHARSET=latin1
```
3. Enter your credentials into the 'dbconfig.php' file:
```
  Just replace each variable with "INSERT HERE" with your database-logins.
  $DB_host = The address to your database;
  $DB_user = The username to login to your database;
  $DB_pass = The password for the database;
  $DB_name = The name of the database;
  ```
