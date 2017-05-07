var botCommandObject;

$(document).ready(function() {
    $.getJSON("botCommands.json", function(data) {
        botCommandObject = data;

        var dropdown = $("#botcommandDropdown");

        for (var i = 0; i < Object.keys(botCommandObject).length; i++) {
            var commandName = Object.keys(botCommandObject)[i];

            dropdown.append('<li><a href="#" onclick="showJumbotron(this); return false;">!' + commandName + '</a></li>');
        }
    });
});

//show jumbotron
function showJumbotron(linkElement) {
    var command = linkElement.text.substring(1);

    var oldCommandAnswer = botCommandObject[command].answer;
    var availableVariablesString;

    for (var i = 0; i < botCommandObject[command].availableVariables.length; i++) {

        if (i == 0) {
            availableVariablesString = botCommandObject[command].availableVariables[i];
        } else {
            availableVariablesString += botCommandObject[command].availableVariables[i];
        }

        if (i != botCommandObject[command].availableVariables.length - 1) {
            availableVariablesString += "; ";
        }
    }

    document.getElementById("messageContainer").innerHTML = "";
    document.getElementById("jumbotronContainer").innerHTML =
        '<div class="jumbotron botCIJumbotron" id="changeBotCIJumbotron">' +
        '<div class="jumbotronHeader">' +
        '<h3 class="display-3 jumbotronHeaderElement" id="headerValue">' + linkElement.text + ' command</h3>' +
        '<button type="button" class="btn btn-primary jumbotronHeaderElement" id="commitChanges" onclick="changeBotCommand()">commit change</button>' +
        '</div>' +
        //TODO
        '<p>TODO: Hier aufklappbaren Text einfügen, der erklärt, wie man variablen in den text einfügt und wie man n $ einfügt (/$)</p>' +
        '<hr>' +
        '<h4>New output</h4>' +
        '<textarea class="form-control" placeholder="Insert Text" id="newOutput" rows="3" required></textarea>' +
        '<h4>Old output</h4>' +
        '<div style="display: flex;">' +
        '<textarea class="form-control col-sm-10" readonly id="oldOutput" rows="3" required>' + oldCommandAnswer + '</textarea>' +
        '<button class="btn btn-primary copy col-sm-2" data-clipboard-action="copy" data-clipboard-target="#oldOutput" id="copyButton" type="button" style="height: 83px; font-size: 17px;"><i class="fa fa-clipboard fa-lg" aria-hidden="true"></i>&nbsp; Copy</button>' +
        '</div>' +
        '<hr>' +
        '<h4>Available Variables</h4>' +
        '<p>' + availableVariablesString + '</p>' +
        '</div>'

    //copy functionality
    var clipboard = new Clipboard('.copy');

    clipboard.on("error", function(e) {
        console.log(e);
    });

    clipboard.on("success", function(e) {
        //TODO setCaretToPos function
    });
}

//TODO setCaretToPos function

function changeBotCommand() {
    try {
        var headerValue = document.getElementById("headerValue").innerText;
        var command = headerValue.substring(1, headerValue.length - 8);
        var newOutput = document.getElementById("newOutput").value;

        botCommandObject[command].answer = newOutput;

        // convert data structure to JSON and post
        $.post("botCI.php", { botCommandJSON: JSON.stringify(botCommandObject) });

        document.getElementById("messageContainer").innerHTML =
            '<div class="alert alert-success alert-dismissable fade in">' +
            '<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>' +
            '<strong>Your changes were successfully commited.</strong>' +
            '</div>';
    } catch (e) {
        document.getElementById("messageContainer").innerHTML =
            '<div class="alert alert-danger alert-dismissable fade in">' +
            '<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>' +
            '<strong>Error! Please contact "! Jonas" on the GHC Discord server and send him this:</strong>' +
            '<p class="mb-0">' + e + "; botcommand: " + command + '</p>' +
            '</div>';
    }
}