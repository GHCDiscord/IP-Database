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
include "templates/navbar.php";

$string = $user->returnTable();
?>
<script>
    document.getElementById('navAdmin').classList.add("active");
</script>

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
    <div style="margin-top: 25px;">
            <form class="form-inline">
                <label class="sr-only" for="inlineFormInput">Search</label>
                <input type="search" id="myInput" onkeyup="searchTable()" class="form-control mb-2 mr-sm-2 mb-sm-0" placeholder="Search" style="margin-right: 15px; margin-bottom: 10px;">
                
            </form>
        </div>
    <div class="col-md-12">
        <table class="table sortable" id="AdminUserTable">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
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


<script>
</script>
<script>
    $(document).ready(function() 
        { 
            $("#AdminUserTable").tablesorter(); 
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
<?php include 'templates/footer.php'; ?>