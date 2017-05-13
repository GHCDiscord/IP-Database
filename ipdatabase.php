<?php

/*$start = microtime(true);*/

require_once 'dbconfig.php';

include "templates/header.php";
include "templates/navbar.php";

if($user->is_loggedin())
{
    $loggedin = true;
    
    $user->GetUserRole($_SESSION["User"]);
                $user->GetUserRep($_SESSION["User"]);
} else {
    $loggedin = false;
}
if(isset($_GET['current'])){
	$_SESSION['curpage'] = $_GET['current'];
	
	}
	
if(isset($_GET['item'])){
	$_SESSION['curitem'] = $_GET['item'];
	
	}



if(isset($_GET["addIP"])){
    $error = false;
    $ipstring = $_POST["IP"];
    $name = $_POST["name"];
    $reputation = $_POST["reputation"];
    $description = $_POST["description"];
    $miners = $_POST["miners"];
    $clan = $_POST["clan"];
    if(strlen($ipstring) == 0){
        $error = true;
        message_error("IPMessage", "IPDiv", "<p>Bitte eine IP angeben!</p>");
    }
    if(strlen($name) == 0){
        $error = true;
        message_error("nameMessage", "nameDiv", "<p>Bitte einen Namen angeben!</p>");
    }

    if(!$error&&$loggedin){
        $ipavailable = $ip->ipAvailable($ipstring);
        if(!$ipavailable) {
        $error = true;
        message_error("IPMessage", "IPDiv", "<p>Diese IP gibt es schon!</p>");
        }
    }

    if(!$error&&$loggedin){
        $success = $ip->add($ipstring, $name, $reputation, $description, $miners, $_SESSION["User"], $clan);
        if($success) {
        } else {
        echo 'Beim Abspeichern ist leider ein Fehler aufgetreten<br>';
        }
    }
}

function message_error($id, $idDiv, $message){
    echo "
    <script>
        $( document ).ready(function() {
            document.getElementById('$id').innerHTML = '$message';
            document.getElementById('$idDiv').className = 'form-group has-error';
        });

    </script>
    ";
}
if($loggedin){
$totalRecords = $ip->getTableCount();
$paginator = new Paginator();
$paginator->total = $totalRecords;
$paginator->paginate();
	
$anfang = intval(($paginator->currentPage-1)*$paginator->itemsPerPage);
$limit = intval($paginator->itemsPerPage) ;

$string = $ip->returnIPTable($anfang, $limit);
?>
<div class="container">
    <?php

 
            if(isset($_GET["editsuccess"])){

            echo '<div class="alert alert-success" role="alert">
                        <a href="#" class="alert-link">Daten erfolgreich bearbeitet!</a>
                    </div>';
            }
                        if($_SESSION["Rep"] ==  0){
            echo    '<div class="alert alert-warning alert-dismissible" role="alert">
                      <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                      <p>Gib deine Reputation in den Einstellungen an, damit wir für dich relevante IPs anzeigen können!</p>
                    </div>';
          }
    ?>
            <script type="text/javascript">
				$('#addIPModal').on('shown.bs.modal', function () {
				  getElementById("inputIP").focus()
				  return false
				})
            </script>     

         <div id="tableInfo" style="margin-top: 15px;">
            <h4>Um die Tabelle nach mehreren Spalten gleichzeitig zu sortieren, halte 'Shift' und wähle die anderen Spalten aus.</h4>
            <h4>Du kannst eine IP kopieren, indem du auf sie klickst.</h4>
        </div>

        <div style="margin-top: 25px;">
            <form class="form-inline">
                <label class="sr-only" for="inlineFormInput">Search</label>
                <input type="search" id="myInput" onkeyup="searchTable()" class="form-control mb-2 mr-sm-2 mb-sm-0" placeholder="Search" style="margin-right: 15px; margin-bottom: 10px;">
                <button type="button" class="btn btn-primary" data-toggle="modal" id="openModalButton" data-target="#addIPModal" style="margin-bottom: 10px;">Neue IP</button>
            </form>
        </div>

       

        

        <!-- Modal begins here -->
            <div class="modal fade" id="addIPModal" tabindex="-1" role="dialog" aria-labelledby="addIPLabel">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id="addIPLabel">Neue IP hinzufügen</h4>
                        </div>
                        <div class="modal-body">
                            <form id="addIPForm" action="?addIP=1" method="post">
                                <div class="form-group" id="IPDiv">
                                    <label for="inputIP">IP</label>
                                    <input autofocus type="text" name="IP" id="inputIP" class="form-control" placeholder="123.123.123.123" onchange="checkifIPRegistered(this.value)">
                                    <span id="IPMessage" class="help-block"></span>
                                </div>
                                <div class="form-group" id="nameDiv">
                                    <label for="inputName">Name</label>
                                    <input type="text" name="name" class="form-control" id="inputName" placeholder="MaxDerHacker" onchange="checkifIPNameRegistered(this.value)">
                                    <span id="nameMessage" class="help-block"></span>
                                </div>
                                <div class="form-group" id="reputationDiv">
                                    <label for="inputReputation">Reputation</label>
                                    <input type="number" name="reputation" class="form-control" id="inputReputation" placeholder="42">
                                    <span id="reputationMessage" class="help-block"></span>
                                </div>
                                <div class="form-group" id="minersDiv">
                                    <label for="inputMiners">Miners</label>
                                    <input type="number" name="miners" class="form-control" id="inputMiners" placeholder="42">
                                    <span id="minersMessage" class="help-block"></span>
                                </div>
                                <div class="form-group" id="clanDiv">
                                    <label for="inputClan">Clan</label>
                                    <input type="text" name="clan" class="form-control" id="inputClan" placeholder="ABC">
                                    <span id="clanMessage" class="help-block"></span>
                                </div>
                                <div class="form-group" id="descriptionDiv">
                                    <label for="inputDescription">Description</label>
                                    <textarea type="text" name="description" class="form-control" rows="5" id="inputDescription" placeholder="i = inaktiv"></textarea>
                                    <span id="descriptionMessage" class="help-block"></span>
                                </div>
                            </form>
                        </div> <!-- Modal Body -->
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary" form="addIPForm">Add IP</button>

                        </div> <!-- Modal Footer -->
                    </div> <!-- Modal Content -->
                </div> <!-- Modal-Dialog -->
            </div> <!-- Modal -->
            	<?php
            echo "<center>";
            echo $paginator->pageNumbers();
            echo $paginator->itemsPerPage()."</center>";
                
            ?>
            <div class="table-responsive">
            
            <table class="table sortable table-responsive tablesorter ipTable" id="myTable" style="margin-top: 25px;">
                <thead>
                    <tr>
                        <th class="col-md-1" style="padding-right: 20px;">IP</th>
                        <th class="col-md-2" style="padding-right: 20px;">Name</th>
                        <th class="col-md-1" style="padding-right: 20px;">Reputation</th>
                        <th class="col-md-1" style="padding-right: 20px;">Updated</th>
                        <th class="col-md-2" style="padding-right: 20px;">Description</th>
                        <th class="col-md-1" style="padding-right: 20px;">Miners</th>
                        <th class="col-md-1" style="padding-right: 20px;">Clan</th>
                        <th class="col-md-2" style="padding-right: 20px;">Added By</th>
                        <th class='sorter-false col-md-1'>Report</th>
                        <?php
                        if($_SESSION["Role"] == "Moderator" || $_SESSION["Role"] == "Admin"){
                            echo "<th class='sorter-false'>Edit</th>";
                        }
                        ?>
                    <tr>
                <thead>
                <tbody id="tbody">
                    <?php
                        echo $string;
                    ?>
                </tbody>
            </table>
            </div>
 

</div> <!-- /Container-->

<!-- Scripts -->
<script type="text/javascript">
    function checkifIPRegistered(str) {
        if(str == ""){
            document.getElementById("IPMessage").innerHTML = "";
            return;
        } else {
            if(window.XMLHttpRequest){
                xmlhttp = new XMLHttpRequest();
            }else {
                xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
            }
            xmlhttp.onreadystatechange = function() {
            if(this.readyState == 4 && this.status == 200){
                if(this.responseText == "true"){
                    document.getElementById("IPMessage").innerHTML = "<p class='has-success'>Diese IP ist verfügbar!</p>";
                    document.getElementById("IPDiv").className = "form-group has-success";
                } else {
                    document.getElementById("IPMessage").innerHTML = "<p class='has-error'>Diese IP gibt es schon!</p>";
                    document.getElementById("IPDiv").className = "form-group has-error";
                }
            }
        }; // OnReadyStateChange
        xmlhttp.open("GET","api/ipavailable.php?q="+str,true);
        xmlhttp.send();
        }
    }
</script>
<script type="text/javascript">
        function checkifIPNameRegistered(str) {
        if(str == ""){
            document.getElementById("IPMessage").innerHTML = "";
            return;
        } else {
            if(window.XMLHttpRequest){
                xmlhttp = new XMLHttpRequest();
            }else {
                xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
            }
            xmlhttp.onreadystatechange = function() {
            if(this.readyState == 4 && this.status == 200){
                if(this.responseText == "false"){
                    document.getElementById("nameMessage").innerHTML = "<p class='has-success'>Dieser Name ist noch nicht eingetragen!</p>";
                    document.getElementById("nameDiv").className = "form-group has-success";
                } else {
                    document.getElementById("nameMessage").innerHTML = "<p class='has-error'>Dieser Name wurde schon mal eingetragen!</p>";
                    document.getElementById("nameDiv").className = "form-group has-error";
                }
            }
        }; // OnReadyStateChange
        xmlhttp.open("GET","api/checkifipnameregistered.php?q="+str,true);
        xmlhttp.send();
        }
    }
</script>
<script type="text/javascript">
    function report(id){
            if(window.XMLHttpRequest){
                xmlhttp = new XMLHttpRequest();
            }else {
                xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
            }
            xmlhttp.onreadystatechange = function() {
            if(this.readyState == 4 && this.status == 200){
                document.getElementById("report" + id).className += " disabled"
            }
        }; // OnReadyStateChange
        xmlhttp.open("GET","api/reportip.php?id="+id,true);
        xmlhttp.send();
        }
</script>

<script type="text/javascript">
    function unreport(id){
            if(window.XMLHttpRequest){
                xmlhttp = new XMLHttpRequest();
            }else {
                xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
            }
            xmlhttp.onreadystatechange = function() {
            if(this.readyState == 4 && this.status == 200){
                document.getElementById("report" + id).className += " disabled"
            }
        }; // OnReadyStateChange
        xmlhttp.open("GET","api/unreportip.php?id="+id,true);
        xmlhttp.send();
        }
</script>

    <script src="js/clipboard.min.js" type="text/javascript"></script>

<script>
    var clipboard = new Clipboard('.btn');
    clipboard.on('success', function(e) {
        console.log(e);
    });
    clipboard.on('error', function(e) {
        console.log("Fehler oderso :" + e);
    });
</script>
<script>
    $(document).ready(function() 
        { 
            $("#myTable").tablesorter({ widthFixed: true }); 
            $(function(){
             $('[data-toggle="tooltip"]').tooltip()
         	});
        } 
    );     
</script>
<script>

function searchTable(){

  var searchText = document.getElementById('myInput').value;
    		var targetTable = document.getElementById('tbody');
    		var targetTableColCount;

    		//Loop through table rows
    		for (var rowIndex = 0; rowIndex < targetTable.rows.length; rowIndex++) {
    			var rowData = '';

    			//Get column count from header row
    			if (rowIndex == 0) {
    				targetTableColCount = targetTable.rows.item(rowIndex).cells.length;
    			}

    			//Process data rows. (rowIndex >= 1)
    			for (var colIndex = 0; colIndex < targetTableColCount; colIndex++) {
    				var cellText = '';

    				if (navigator.appName == 'Microsoft Internet Explorer')
    					cellText = targetTable.rows.item(rowIndex).cells.item(colIndex).innerText;
    				else
    					cellText = targetTable.rows.item(rowIndex).cells.item(colIndex).textContent;

    				rowData += cellText;
    			}

    			// Make search case insensitive.
    			rowData = rowData.toLowerCase();
    			searchText = searchText.toLowerCase();

    			//If search term is not found in row data
    			//then hide the row, else show
    			if (rowData.indexOf(searchText) == -1)
    				targetTable.rows.item(rowIndex).style.display = 'none';
    			else
    				targetTable.rows.item(rowIndex).style.display = 'table-row';
    		}

}



</script>
<?php
}else{
	    
        echo $user->returnNotLoggedIn();
    } 





include "templates/footer.php";



/*$end = microtime(true);
$creationtime = ($end - $start);
printf("Page created in %.6f seconds.", $creationtime);*/
?>
