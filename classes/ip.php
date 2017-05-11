<?php
class IP {
    private $db;
    

    function __construct($DB_con){
      $this->db = $DB_con;
    }

    public function add($ipstring, $name, $rep=0, $description="", $miners=0, $addedbyid, $clan){
        try{
            $stmt = $this->db->prepare('INSERT INTO `HackersIP`(`IP`, `Name`, `Reputation`, `Last_Updated`, `Miners`, `Description`, `Added_By`, `Clan`) VALUES (:ip, :name, :rep, :lastup, :miners, :description, :addedby, :clan)');
            $stmt->bindParam(':ip', $ipstring);
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':rep', $rep);
            $stmt->bindParam(':lastup', date('Y-m-d H:i:s'));
            $stmt->bindParam(':miners', $miners);
            $stmt->bindParam(':description', $description);
            $stmt->bindParam(':addedby', $addedbyid);
            $stmt->bindParam(':clan', $clan);
            
            $success = $stmt->execute(); 

            return $success;
        } catch (PDOException $e){
            echo $e.getMessage();
        }
    }

    public function remove($id){
        try{
            $stmt = $this->db->prepare('DELETE FROM `HackersIP` WHERE `ID`=:id');
            $stmt->bindParam(':id', $id);

            $stmt->execute();
            return $stmt;

        } catch (PDOException $e){
            echo $e.getMessage();
        }
    }

    public function getTableCount(){
            $stmt = $this->db->prepare('SELECT * FROM `HackersIP` WHERE 1');
            $stmt->execute();

            return $stmt->rowCount();
    }

    public function returnDateCount($date){
            $stmt = $this->db->prepare('SELECT * FROM `HackersIP` WHERE `Last_Updated`=:lastdate');
            $stmt->execute(array(":lastdate"=>$date));

            return $stmt->rowCount();
    }

    // Returns a String
    public function returnTable(){
        $stmt = $this->db->prepare('SELECT `HackersIP`.`ID`, `IP`, `HackersIP`.`Name`, `HackersIP`.`Reputation`, `Last_Updated`, `Description`, `Miners`, `Clan`, `Adder`.`Username`, `CountsName`.`CountName`, COALESCE(`CountsIPRepo`.`CountIPRepo`, 0) AS `CountIPRepo`, COALESCE(`UsersIPRepo`.`UserIPRepo`, 0) AS `UserIPRepo` FROM `HackersIP` 
LEFT JOIN `Users` ON `HackersIP`.`Name` = `Users`.`Username` 
JOIN `Users` AS `Adder` ON `HackersIP`.`Added_By` = `Adder`.`ID` 
JOIN (SELECT COUNT(1) AS `CountName`, `HackersIP`.`Name` FROM `HackersIP` GROUP BY `HackersIP`.`Name`) AS `CountsName` ON `CountsName`.`Name` = `HackersIP`.`Name`
LEFT JOIN (SELECT COUNT(1) AS `CountIPRepo`, `IPUserReport`.`IPID` FROM `IPUserReport` GROUP BY `IPUserReport`.`IPID`) AS `CountsIPRepo` ON `CountsIPRepo`.`IPID` = `HackersIP`.`ID`
LEFT JOIN (SELECT COUNT(1) AS `UserIPRepo`, `IPUserReport`.`IPID` FROM `IPUserReport` WHERE `IPUserReport`.`UserID` = :uid GROUP BY `IPUserReport`.`IPID`) AS `UsersIPRepo` ON `UsersIPRepo`.`IPID` = `HackersIP`.`ID`
WHERE `Users`.`Last_Login` < DATE_SUB( now(), INTERVAL 30 DAY) OR `Users`.`Last_Login` IS NULL');

 $stmt->bindParam(':uid', $_SESSION['User']);
        $stmt->execute();
        
        $returnString = "";
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
          

            if($row["UserIPRepo"] > 0){
                $onclick = "unreport";
                $symbol = "glyphicon glyphicon-ok";
                $color = "btn-success";
                $title = "Unreport";
            } else {
                $onclick = "report";
                $symbol = "glyphicon glyphicon-alert";
                $color = "btn-warning";
                $title = "Report";
            } 
            $class = "";
            // Reputation größer als 75% der eigenen
            if($row["Reputation"] > $_SESSION["Rep"] * 0.75 && !$_SESSION["Rep"] == 0){
                $class = "info";
            }
       
           
            // Reputation kleiner als 25% der Eigenen
            if($row["Reputation"] < $_SESSION["Rep"] * 0.25 && !$_SESSION["Rep"] == 0){
                $class = "warning";
            }
            
            //Einfärben der gemeldeten IPs
            if($row["CountIPRepo"] >= 1){
               $class = "danger";
          }
            $nameCount = "";
            if($row["CountName"] > 1){
                $nameCount = "<button id='{$row['ID']}tooltip' href='#' class='btn btn-link btn-xs btn-alert' data-toggle='tooltip' data-placement='top' title='Dieser Name existiert öfters!'><span class='glyphicon glyphicon-info-sign'</button>";
            }

            $rowString = "<tr id='row{$row["ID"]}' class='{$class}'>
                          <td>  <button class='btn btn-link btn-xs' data-clipboard-text='{$row['IP']}'>" . $row['IP'] . "</button></td>" . 
                         "<td>" . $row['Name'] . " {$nameCount}" . "</td>" . 
                         "<td>" . $row['Reputation'] . "</td>" . 
                         "<td>" . $row['Last_Updated'] . "</td>" . 
                         "<td>" . $row['Description'] . "</td>" . 
                         "<td>" . $row['Miners'] . "</td>" .
                         "<td>" . $row['Clan'] . "</td>" .
                         "<td>" . $row['Username'] . "</td>" . 
                         "<td><a id='report{$row['ID']}' onclick='{$onclick}({$row['ID']})' data-placement='top' data-toggle='tooltip' title='{$title}' class='btn {$color} btn-xs'><span class='{$symbol}'></span></a>" . "</td>";
                         if($_SESSION["Role"] == "Admin" || $_SESSION["Role"] == "Moderator"){
                             $rowString .= "<td><a href='editip.php?id={$row["ID"]}' data-placement='top' data-toggle='tooltip' title='Edit'><button class='btn btn-success btn-xs' ><span class='glyphicon glyphicon-pencil'></span></button></a></td>";
                         }
                         $rowString .= "</tr>";
          $returnString .= $rowString;
       
        }
       return $returnString;
    }

    public function ipAvailable($str){
        $stmt = $this->db->prepare("SELECT * FROM `HackersIP` WHERE `IP` =:ip");
        $stmt->execute(array(":ip"=>$str));

        if($stmt->rowCount() > 0){
            return false;
        }
        return true;
    }

    public function nameCount($str){
        $stmt = $this->db->prepare("SELECT * FROM `HackersIP` WHERE `Name`=:name");
        $stmt->execute(array(":name"=>$str));

        return $stmt->rowCount();
    }
    public function setIP($ip, $id){
        $stmt = $this->db->prepare("UPDATE `HackersIP` SET `IP`=:ip WHERE `ID`=:id");
        $stmt->execute(array(":ip"=>$ip, ":id"=>$id));
    }

    public function setAttackedName($attackedName, $id){
        $stmt = $this->db->prepare("UPDATE `HackersIP` SET `Name`=:name WHERE `ID`=:id");
        $stmt->execute(array(":name"=>$attackedName, ":id"=>$id));
    }

    public function setAddedBy($addedBy, $id){
        $stmt = $this->db->prepare("UPDATE `HackersIP` SET `Added_By`=:addedby WHERE `ID`=:id");
        $stmt->execute(array(":addedby"=>$addedBy, ":id"=>$id));
    }

    public function setLastUpdated($lastUpdated, $id){
        $stmt = $this->db->prepare("UPDATE `HackersIP` SET `Last_Updated`=:lastupdated WHERE `ID`=:id");
        $stmt->execute(array(":lastupdated"=>$lastUpdated, ":id"=>$id));
    }

    public function setReputation($reputation, $id){
        $stmt = $this->db->prepare("UPDATE `HackersIP` SET `Reputation`=:rep WHERE `ID`=:id");
        $stmt->execute(array(":rep"=>$reputation, ":id"=>$id));
    }

    public function setDescription($description, $id){
        $stmt = $this->db->prepare("UPDATE `HackersIP` SET `Description`=:descr WHERE `ID`=:id");
        $stmt->execute(array(":descr"=>$description, ":id"=>$id));
    }

    public function setMiners($miners, $id){
        $stmt = $this->db->prepare("UPDATE `HackersIP` SET `Miners`=:miners WHERE `ID`=:id");
        $stmt->execute(array(":miners"=>$miners, ":id"=>$id));    
    }

    public function setClan($clan, $id){
        $stmt = $this->db->prepare("UPDATE `HackersIP` SET `Clan`=:clan WHERE `ID`=:id");
        $stmt->execute(array(":clan"=>$clan, ":id"=>$id));       
    }

    public function clearReports($id){
        $stmt = $this->db->prepare('DELETE FROM `IPUserReport` WHERE `IPID`=:id');
        $stmt->execute(array(":id"=>$id));
    }

    public function getData($column, $id){
        $stmt = $this->db->prepare("SELECT * FROM `HackersIP` WHERE `ID`=:id");
        $stmt->execute(array(":id"=>$id));

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row[$column];
    }

    // Reports an IP
    public function report($ipid, $userid){
            $stmt = $this->db->prepare('INSERT INTO `IPUserReport`(`UserID`,`IPID`) VALUES (:user, :ip)');
            $stmt->bindParam(':user', $userid);
            $stmt->bindParam(':ip', $ipid);

            $success = $stmt->execute();
            return $success;
    }

    // Reports an IP
    public function unreport($ipid, $userid){
            $stmt = $this->db->prepare('DELETE FROM `IPUserReport` WHERE `IPID`=:ipid AND `UserID`=:user');
            $stmt->bindParam(':user', $userid);
            $stmt->bindParam(':ipid', $ipid);

            $success = $stmt->execute();
            return $success;
    }

    public function reportCount($id){
        $stmt = $this->db->prepare("SELECT * FROM `IPUserReport` WHERE `IPID`=:id");
        $stmt->execute(array(':id'=>$id));

        return $stmt->rowCount();
    }

    public function listReportNames($id){
        $stmt = $this->db->prepare("SELECT * FROM `IPUserReport` WHERE `IPID`=:id");
        $stmt->execute(array(':id'=>$id));      
        $user = new USER($this->db);
        $outputstring = "<ul>";
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $outputstring .= "<li>" . $user->getData("Username", $row["UserID"]) . "</li>";
        }
        $outputstring .= "</ul>";
        return $outputstring;
    }
}
