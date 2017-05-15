<?php
class REPORTED
{
    private $db;
 
    function __construct($DB_con){
      $this->db = $DB_con;
    }
    
    public function returnReportTable(){
    	$stmt = $this->db->prepare('SELECT `Users`.`Username` AS `Reporter`, `IP`, `RID`, `Name`, `Added_by`, `Clan`, `Last_Updated`, `Miners`, `Description`, `IPID`, `HackersIP`.`Reputation`, `UserID` FROM `IPUserReport` 
left join `HackersIP` on `IPUserReport`.`IPID` = `HackersIP`.`ID` 
JOIN `Users` ON `IPUserReport`.`UserID` = `Users`.`ID`');
        
        $stmt->execute();
        
        
        $returnString = "";
         
     while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            

            $rowString = "<tr>
                <td> {$row['RID']} </td>
                <td> {$row['Reporter']} </td>
                <td>" . $row['IP'] . "</td>
                <td>" . $row['Name'] . "</td>
                <td>" . $row['Reputation'] . "</td>
                <td>" . $row['Added_by'] . "</td>
                <td>" . $row['Clan'] . "</td>
                <td>" . $row['Miners'] . "</td>
                <td>" . $row['Description'] . "</td>
                <td>" . $row['Last_Updated'] . "</td>
               
                <td> <a href='editip.php?id={$row["IPID"]}' data-placement='top' data-toggle='tooltip' title='Edit'><button class='btn btn-warning btn-xs' ><span class='glyphicon glyphicon-pencil'></span></button></a></td>
                <td> <a href='api/unreportip.php?id={$row["IPID"]}&uid={$row["UserID"]}' data-placement='top' data-toggle='tooltip' title='Edit'><button class='btn btn-warning btn-xs' ><span class='glyphicon glyphicon-trash'></span></button></a></td>
                </tr>";
            $returnString .= $rowString;
        }
        return $returnString;
    
    }
    
    
    }
    

    
?>