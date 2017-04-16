<?php
class USER
{
    private $db;
 
    function __construct($DB_con){
      $this->db = $DB_con;
    }

    //Registers a new user
    public function register($username, $email,$password){
       try
       {
           $new_password = password_hash($password, PASSWORD_DEFAULT);
   
           $stmt = $this->db->prepare("INSERT INTO `Users`(Username,Email,Password) 
                                                       VALUES(:uname, :umail, :upass)");
              
           $stmt->bindparam(":uname", $username);
           $stmt->bindparam(":umail", $email);
           $stmt->bindparam(":upass", $new_password);            
           $stmt->execute(); 
   
           return $stmt; 
       }
       catch(PDOException $e)
       {
           echo $e->getMessage();
       }    
    }

    //Logs User in
    public function login($username,$password){
       try
       {
          $stmt = $this->db->prepare("SELECT * FROM `Users` WHERE Username=:uname OR Email=:umail LIMIT 1");
          $stmt->execute(array(':uname'=>$username, ':umail'=>$username));
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

    public function emailAvailable($email){
        if(filter_var($email, FILTER_VALIDATE_EMAIL)){
            $stmt = $this->db->prepare("SELECT EMail FROM `Users` WHERE Email = :email");
            $stmt->execute(array(":email"=>$email));
            if($stmt->rowCount() > 0){
                return false;
            }
            return true;
        }
        return false;
    }

    public function findUserWithName($name){
      $stmt = $this->db->prepare("SELECT * FROM `Users` WHERE Username = :name");
      $stmt->execute(array(":name"=>$name));
      $userRow = $stmt->fetch(PDO::FETCH_ASSOC);
      return $userRow["ID"];
    }

    // TRUE IF EXPIRED ; FALSE IF VALID
    public function isExpired($id){
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

    public function hasRole($id, $role){
        $stmt = $this->db->prepare("SELECT * FROM `Users` WHERE ID = :id ORDER BY `ID` ASC");
        $stmt->execute(array(":id"=>$id));
        $userRow = $stmt->fetch(PDO::FETCH_ASSOC);
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

    public function setEmail($id, $email){
        $stmt = $this->db->prepare("UPDATE `Users` SET `Email`=:mail WHERE `ID`=:id");
        $success = $stmt->execute(array(":id"=>$id, ":mail"=>$email));

        return $success;
    }
    public function setDiscord($id, $discord){
        $stmt = $this->db->prepare("UPDATE `Users` SET `DiscordName`=:discord WHERE `ID`=:id");
        $success = $stmt->execute(array(":id"=>$id, ":discord"=>$discord));

        return $success;
    }


    public function returnTable(){
        $stmt = $this->db->prepare('SELECT * FROM `Users` WHERE 1');
        
        $stmt->execute();

        $returnString = "";
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)){

          $expireDate =$row["ExpireDate"];
          $expireDate = strtotime(str_replace("-","/", $expireDate));


          $today = strtotime(date("Y/m/d"));
          if($today > $expireDate){
            $expired = "Abgelaufen";
          } else {
            $expired = "Gültig";
          }
            $rowString = "<tr>
                <td> {$row['ID']} </td>
                <td> {$row['Username']} </td>
                <td>" . $row['Email'] . "</td>
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



}
?>