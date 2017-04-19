<?php

$DB_host = "";
$DB_user = "";
$DB_pass = "";
$DB_name = "";
$charset = "";

try
{
     $DB_con = new PDO("mysql:host={$DB_host};dbname={$DB_name};charset={$charset}",$DB_user,$DB_pass);
     $DB_con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch(PDOException $e)
{
     echo $e->getMessage();
}


$createuserstable = "
CREATE TABLE `Users` (
 `ID` int(11) NOT NULL AUTO_INCREMENT,
 `Username` varchar(255) NOT NULL,
 `DiscordName` varchar(255) DEFAULT NULL,
 `Password` varchar(255) NOT NULL,
 `Email` varchar(255) NOT NULL,
 `Role` enum('Admin','Moderator','User') NOT NULL DEFAULT 'User',
 `Reputation` int(11) DEFAULT NULL,
 `Last_Login` datetime NOT NULL,
 `ExpireDate` date NOT NULL,
 PRIMARY KEY (`ID`)
) ENGINE=MyISAM AUTO_INCREMENT=37 DEFAULT CHARSET=latin1
";

$createiptable = "
CREATE TABLE `HackersIP` (
 `ID` int(11) NOT NULL AUTO_INCREMENT,
 `IP` varchar(15) NOT NULL,
 `Name` varchar(255) NOT NULL,
 `Added_By` varchar(255) DEFAULT NULL,
 `Reputation` int(11) DEFAULT NULL,
 `Clan` varchar(10) DEFAULT NULL,
 `Last_Updated` date NOT NULL,
 `Miners` int(11) DEFAULT NULL,
 `Description` varchar(255) DEFAULT NULL,
 PRIMARY KEY (`ID`)
) ENGINE=MyISAM AUTO_INCREMENT=36 DEFAULT CHARSET=latin1
";

$createapitokentable = "
CREATE TABLE `APIToken` (
 `UserID` int(11) NOT NULL,
 `Token` varchar(50) NOT NULL,
 PRIMARY KEY (`Token`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1
";

$createfavoritetable = "
CREATE TABLE `IPUserFav` (
 `UserID` int(11) NOT NULL,
 `IPID` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1
";

$createreporttable = "
CREATE TABLE `IPUserReport` (
 `UserID` int(11) NOT NULL,
 `IPID` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1
";

$stmt = $DB_con->prepare($createuserstable);
$stmt->execute();
$stmt = $DB_con->prepare($createiptable);
$stmt->execute();
$stmt = $DB_con->prepare($createfavoritetable);
$stmt->execute();
$stmt = $DB_con->prepare($createreporttable);
$stmt->execute();
$stmt = $DB_con->prepare($createapitokentable);
$stmt->execute();


$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
$charactersLength = strlen($characters);
$password = '';
for ($i = 0; $i < 10; $i++) {
    $password .= $characters[rand(0, $charactersLength - 1)];
}

$new_password = password_hash($password, PASSWORD_DEFAULT);
   
$stmt = $DB_con->prepare("INSERT INTO `Users`(Username, Password, Role) VALUES(:uname, :upass, 'Admin')");
$admin = "admin";
$stmt->bindparam(":uname", $admin);
$stmt->bindparam(":upass", $new_password);  
$stmt->execute(); 
echo "Ein Admin-Account wurde erstellt! <br> Name: admin <br> Password: {$password}";
return $stmt; 