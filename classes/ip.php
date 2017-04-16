<?
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
            
            $stmt->execute(); 

            return $stmt;
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

    // Returns a String
    public function returnTable(){
        $stmt = $this->db->prepare('SELECT * FROM `HackersIP` ORDER BY `ID` ASC');
        
        $stmt->execute();
        
        $returnString = "";
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            $user = new USER($this->db);

            $userID = $user->findUserWithName($row["Name"]);

            if(!($user->isExpired($userID))){
                continue;
            }

            $rowString = "<tr>
                          <td>  <button class='btn btn-link btn-xs' data-clipboard-text='{$row['IP']}'>" . $row['IP'] . "</button></td>" . 
                         "<td>" . $row['Name'] . "</td>" . 
                         "<td>" . $row['Reputation'] . "</td>" . 
                         "<td>" . $row['Last_Updated'] . "</td>" . 
                         "<td>" . $row['Description'] . "</td>" . 
                         "<td>" . $row['Miners'] . "</td>" .
                         "<td>" . $row['Clan'] . "</td>" .
                         "<td>" . $user->getName($row['Added_By']) . "</td>";
                         if($user->hasRole($_SESSION["User"], "Admin") || $user->hasRole($_SESSION["User"], "Moderator")){
                             $rowString .= "<td><a href='editip.php?id={$row["ID"]}' data-placement='top' data-toggle='tooltip' title='Edit'><button class='btn btn-warning btn-xs' ><span class='glyphicon glyphicon-pencil'></span></button></a></td>";
                         }
                         $rowString .= "</tr>";
            $returnString .= $rowString;
        }
        return $returnString;
    }


    // DEPRECATED
    public function returnFilteredTable($search){
        $search = "%" . $search;
        $search = $search . "%";
        $stmt = $this->db->prepare('SELECT * FROM `HackersIP` WHERE `Name` LIKE :search OR `IP` LIKE :search');
        $stmt->execute(array(":search"=>$search));
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)){


            $user = new USER($this->db);
            $rowString = "<tr>
                          <td>  <button class='btn btn-link btn-xs' data-clipboard-text='{$row['IP']}'>" . $row['IP'] . "</button></td>" . 
                         "<td>" . $row['Name'] . "</td>" . 
                         "<td>" . $row['Reputation'] . "</td>" . 
                         "<td>" . $row['Last_Updated'] . "</td>" . 
                         "<td>" . $row['Description'] . "</td>" . 
                         "<td>" . $row['Miners'] . "</td>" .
                         "<td>" . $user->getName($row['Added_By']) . "</td>";
                         if($user->hasRole($_SESSION["User"], "Admin") || $user->hasRole($_SESSION["User"], "Moderator")){
                             $rowString .= "<a href='editip.php?id={$row["ID"]}' data-placement='top' data-toggle='tooltip' title='Edit'><button class='btn btn-warning btn-xs' ><span class='glyphicon glyphicon-pencil'></span></button></a></td>";
                         }
                         $rowString .= "</tr>";
            $returnString .= $rowString;
        }
        return $returnString;
    }

    public function ipAvailable($str){
        $stmt = $this->db->prepare("SELECT * FROM `HackersIP` WHERE `IP` ='{$str}'");
        $stmt->execute();

        if($stmt->rowCount() > 0){
            return false;
        }
        return true;
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


    public function getData($column, $id){
        $stmt = $this->db->prepare("SELECT * FROM `HackersIP` WHERE `ID`=:id");
        $stmt->execute(array(":id"=>$id));

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row[$column];
    }


}