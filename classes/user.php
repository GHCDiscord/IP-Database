<?php
class USER
{
    private $db;
 
    function __construct($DB_con){
      $this->db = $DB_con;
    }

    //Registers a new user
    public function register($username, $password, $expdate){
       try
       {
           $new_password = password_hash($password, PASSWORD_DEFAULT);
   
           $stmt = $this->db->prepare("INSERT INTO `Users`(Username, Password, ExpireDate) 
                                                       VALUES(:uname, :upass, :expdate)");
              
           $stmt->bindparam(":uname", $username);
           $stmt->bindparam(":upass", $new_password);  
           $stmt->bindparam(":expdate", $expdate);
           $stmt->execute(); 
   
           return $stmt; 
       }
       catch(PDOException $e)
       {
           echo $e->getMessage();
       }    
    }

    public function setPassword($password, $id){
      $new_password = password_hash($password, PASSWORD_DEFAULT);
      $stmt = $this->db->prepare("UPDATE `Users` SET `Password`=:pass WHERE `ID`=:id");
      $success = $stmt->execute(array(":pass"=>$new_password, ":id"=>$id));
      return $success;
    }
    //Logs User in
    public function login($username,$password){
       try
       {
          $stmt = $this->db->prepare("SELECT * FROM `Users` WHERE Username=:uname LIMIT 1");
          $stmt->execute(array(':uname'=>$username));
          $userRow=$stmt->fetch(PDO::FETCH_ASSOC);
          if($stmt->rowCount() > 0)
          {
             if(password_verify($password, $userRow['Password']))
             {
                $stmt = $this->db->prepare("UPDATE `Users` SET `Last_Login`=:lastlogin WHERE `ID`=:id");
                $stmt->execute(array(':lastlogin'=>date("Y-m-d H:i:s"), ':id'=>$userRow['ID']));
                $_SESSION['User'] = $userRow['ID'];
                return true;
             }
             else
             {
                return false;
             }
          }
       }
       catch(PDOException $e)
       {
           echo $e->getMessage();
       }
    }

    public function loginDataCorrect($username, $password){
          $stmt = $this->db->prepare("SELECT * FROM `Users` WHERE Username=:uname LIMIT 1");
          $stmt->execute(array(':uname'=>$username));     
          if($stmt->rowCount() > 0){
              $userRow=$stmt->fetch(PDO::FETCH_ASSOC);
              if(password_verify($password, $userRow['Password'])){
                  return true;
              }
          }
          return false;
    }

    public function getTableCount(){
            $stmt = $this->db->prepare('SELECT * FROM `Users` WHERE 1');
            $stmt->execute();

            return $stmt->rowCount();
    }

    //Checks if User is loggedIn
    public function is_loggedin(){
      if(isset($_SESSION['User']))
      {
         return true;
      }
    }
    //Get Username
    public function getName($id){
       if(isset($_SESSION['User'])){
           $stmt = $this->db->prepare("SELECT Username FROM `Users` WHERE ID=:id LIMIT 1");
           $stmt->execute(array(':id'=>$id));
           $userRow=$stmt->fetch(PDO::FETCH_ASSOC);
           if($stmt->rowCount() > 0){
               $name = $userRow['Username'];
           } else {
               $name = "No name found";
           }
           return $name;

       } else {
           return false;
       }
    }

    public function verifyToken($token){
        $stmt = $this->db->prepare("SELECT * FROM `APIToken` WHERE Token=:token LIMIT 1");
        $stmt->execute(array(':token'=>$token));
        if($stmt->rowCount() > 0){
          return true;
        } else {
          return false;
        }
    }

    //Redirect User to URL
    public function redirect($url){
       header("Location: $url");
    }

    //Logout User 
    public function logout(){
        session_destroy();
        unset($_SESSION['User']);
        return true;
    }

    public function returnNotLoggedIn(){
        $string = "<div class='alert alert-danger' role='alert'>
                    <p><strong>You have to be logged in to view this content! <a class='alert-link' href='login.php'>Login here!</a></strong></p>
                   </div>";
        return $string;
    }

    public function nameAvailable($name){
        $stmt = $this->db->prepare("SELECT Username FROM `Users` WHERE Username = :name");
        $stmt->execute(array(":name"=>$name));

        if($stmt->rowCount() > 0){
            return false;
        }
        return true;
    }

    public function discordAvailable($name){
        $stmt = $this->db->prepare("SELECT Username FROM `Users` WHERE DiscordName = :name");
        $stmt->execute(array(":name"=>$name));

        if($stmt->rowCount() > 0){
            return false;
        }
        return true;
    }

    public function findUserWithName($name){
      $stmt = $this->db->prepare("SELECT * FROM `Users` WHERE Username = :name");
      $stmt->execute(array(":name"=>$name));
      $userRow = $stmt->fetch(PDO::FETCH_ASSOC);
      return $userRow["ID"];
    }

    public function findUserWithDiscord($name){
      $stmt = $this->db->prepare("SELECT * FROM `Users` WHERE DiscordName = :name");
      $stmt->execute(array(":name"=>$name));
      if($stmt->rowCount() > 0){
          $userRow = $stmt->fetch(PDO::FETCH_ASSOC);
          return $userRow["ID"];
      } else {
        return false;
      }
    }
    // TRUE IF EXPIRED ; FALSE IF VALID
    public function isExpired($id){
        if($this->hasRole($id, "Admin")){
          return false;
        }
        if($this->hasRole($id, "Moderator")){
          return false;
        }
        $stmt = $this->db->prepare("SELECT * FROM `Users` WHERE ID = :id ORDER BY `ID` ASC");
        $stmt->execute(array(":id"=>$id));
        $userRow = $stmt->fetch(PDO::FETCH_ASSOC);

        $expireDate =$userRow["ExpireDate"];
        $expireDate = strtotime(str_replace("-","/", $expireDate));
        $today = strtotime(date("Y/m/d"));
        if($today > $expireDate){
            return true;
        } else {
            return false;
        }
    }

    public function setExpireDate($id, $date){
        $stmt = $this->db->prepare("UPDATE `Users` SET `ExpireDate`=:expdate WHERE `ID`=:id");
        $success = $stmt->execute(array(":id"=>$id, ":expdate"=>$date));

        return $success;     
    }

    public function generateToken($id){
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ*-_*.()';
        $string = '';

        for ($i = 0; $i < 50; $i++) {
            $string .= $characters[mt_rand(0, strlen($characters) - 1)];
        }

        if($this->getToken($id) == false){
            $stmt = $this->db->prepare("INSERT INTO `APIToken`(UserID,Token) 
                                                       VALUES(:id, :token)");
            $stmt->execute(array("id"=>$id,"token"=>$string));         
        } else {
            $stmt = $this->db->prepare("UPDATE `APIToken` SET `Token`=:token WHERE `UserID`=:id");
            $success = $stmt->execute(array(":id"=>$id, ":token"=>$string));
        }

              

    }

    public function getToken($id){
        $stmt = $this->db->prepare("SELECT * FROM `APIToken` WHERE `UserID`=:id");
        $stmt->execute(array(":id"=>$id));

        if($stmt->rowCount() == 0){
          return false;
        }
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row["Token"];
    }

    public function refreshAccount($id){
        $expdate = date('Y-m-d', strtotime("+30 days"));
        $stmt = $this->db->prepare("UPDATE `Users` SET `ExpireDate`=:expdate WHERE `ID`=:id");
        $success = $stmt->execute(array(":id"=>$id, ":expdate"=>$expdate));

        return $success;
    }

    public function returnExpireDate(){
        $expdate = date('Y-m-d', strtotime("+30 days"));
        return $expdate;
    }
    public function GetUserRole($id){
        $stmt = $this->db->prepare("SELECT `Role` FROM `Users` WHERE ID =:id ORDER BY `ID` ASC");
        $stmt->execute(array(":id"=>$id));
        $userRow = $stmt->fetch(PDO::FETCH_ASSOC);
        $_SESSION['Role'] = $userRow['Role'];
        }
    
  
    public function hasRole($id, $role){
        $stmt = $this->db->prepare("SELECT * FROM `Users` WHERE ID =:id ORDER BY `ID` ASC");
        $stmt->execute(array(":id"=>$id));
        $userRow = $stmt->fetch(PDO::FETCH_ASSOC);
        $_SESSION['Role'] = $userRow['Role'];
       if($userRow['Role'] == $role){
            return true;
        }
        return false;
    }

    public function setRole($id, $role){
        $stmt = $this->db->prepare("UPDATE `Users` SET `Role`=:role WHERE `ID`=:id");
        $success = $stmt->execute(array(":id"=>$id, ":role"=>$role));

        return $success;
    }

    public function setName($id, $name){
        $stmt = $this->db->prepare("UPDATE `Users` SET `Username`=:name WHERE `ID`=:id");
        $success = $stmt->execute(array(":id"=>$id, ":name"=>$name));

        return $success;
    }

    public function setDiscord($id, $discord){
        $stmt = $this->db->prepare("UPDATE `Users` SET `DiscordName`=:discord WHERE `ID`=:id");
        $success = $stmt->execute(array(":id"=>$id, ":discord"=>$discord));

        return $success;
    }
    public function setReputation($id, $rep){
        $stmt = $this->db->prepare("UPDATE `Users` SET `Reputation`=:rep WHERE `ID`=:id");
        $success = $stmt->execute(array(":id"=>$id, ":rep"=>$rep));

        return $success;
    }

    
public function GetUserRep($id){
$stmt = $this->db->prepare("SELECT Reputation FROM `Users` WHERE `ID`=:id");
      $stmt->execute(array(":id"=>$id));

      $row = $stmt->fetch(PDO::FETCH_ASSOC);
      $_SESSION['Rep'] = $row['Reputation'];
      }




    public function reputationIsNull($id){
      $stmt = $this->db->prepare("SELECT Reputation FROM `Users` WHERE `ID`=:id");
      $stmt->execute(array(":id"=>$id));

      $row = $stmt->fetch(PDO::FETCH_ASSOC);
      $_SESSION['Rep'] = $row['Reputation'];
      if($row["Reputation"] == 0 || $row["Reputation"] == NULL){
        return true;
      } else {
        return false;
      }
    }


    public function returnTable(){
        $stmt = $this->db->prepare('SELECT * FROM `Users` WHERE 1');
        
        $stmt->execute();

        $returnString = "";
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            if ($this->isExpired($row["ID"])){
              $expired = "Abgelaufen";
            } else {
              $expired = "Gültig";
            }
            if($this->hasRole($row["ID"], "Admin")){
              $expired = "Admin";
            }
            if($this->hasRole($row["ID"], "Moderator")){
              $expired = "Moderator";
            }

            $rowString = "<tr>
                <td> {$row['ID']} </td>
                <td> {$row['Username']} </td>
                <td>" . $row['Role'] . "</td>
                <td>" . $row['Last_Login'] . "</td>
                <td>" . $row['DiscordName'] . "</td>
                <td>" . $expired . "</td>
                <td> <a href='edituser.php?id={$row["ID"]}' data-placement='top' data-toggle='tooltip' title='Edit'><button class='btn btn-warning btn-xs' ><span class='glyphicon glyphicon-pencil'></span></button></a></td>
                </tr>";
            $returnString .= $rowString;
        }
        return $returnString;
    }

    public function delete($id){
        $stmt = $this->db->prepare('DELETE FROM `Users` WHERE `ID`=:id');
        $success = $stmt->execute(array(":id"=>$id));
        return $success;
    }

    public function getData($column, $id){
        $stmt = $this->db->prepare("SELECT * FROM `Users` WHERE `ID`=:id");
        $stmt->execute(array(":id"=>$id));

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row[$column];
    }

    public function hasReported($userid, $ipid){
        $stmt = $this->db->prepare("SELECT * FROM `IPUserReport` WHERE `UserID`=:userid AND `IPID`=:ipid");
        $stmt->execute(array(":userid"=>$userid, ":ipid"=>$ipid));

        if($stmt->rowCount() > 0){
          return true;
        } else {
          return false;
        }
        return true;
    }


}
?>