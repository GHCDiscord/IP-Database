<?php

require_once "dbconfig.php";

if(!$user->is_loggedin()){
    $user->redirect("index.php");
}

if(!$user->hasRole($_SESSION["User"], "Admin")){
    $user->redirect("index.php");
}

if(isset($_GET["error"])){
    $error = true;
}


include "templates/header.php";
include "templates/menu.php";
$string = $user->returnTable();
?>

<div class="container">
    <div class="col-md-12">
        <?php
        if(isset($_GET["editsuccess"])){

        echo '<div class="alert alert-success" role="alert">
                    <a href="#" class="alert-link">Daten erfolgreich bearbeitet!</a>
                </div>';
        }
        ?>
    </div>
    <div class="col-xs-6">
        <input type="search" id="myInput" onkeyup="searchTable()" class="form-control" placeholder="Search">
    </div>
    <div class="col-md-12">
        <table class="table sortable" id="myTable">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>E-Mail</th>
                    <th>Rolle</th>
                    <th>Last Login (Y-M-D H:M:S)</th>
                    <th>Discord</th>
                    <th>Gültigkeit</th>
                    <th>Edit</th>
                <tr>
            <thead>
            <tbody id="tbody">
                <?php
                    echo $string;
                ?>
            </tbody>
        </table>
    </div>
</div>

<script src="js/sorttable.js"></script>
<script>
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
            $("#myTable").tablesorter(); 
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
include 'templates/footer.php';