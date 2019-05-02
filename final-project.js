/*
 * Name: Maxwell Lee, Jittima Goodrich
 * Date: 12/03/2018
 * File: final-project.js
 * Desciption: site to play a slot machine and money from the slot machine page
 * links to a walmart shopping webpage
 */

// Onload call
window.onload = setForm;

function setForm() {
    // create tabs
    $("#tabs").tabs();
    // create an empty array called nums
    var nums = [];
    
    // slot machine section
    // how much the money    
    var cash = 20;
    cash = parseFloat(cash);
    $("#btnTest").on("click", function () {
        if (cash < 1) {
            var color = document.getElementById('cash');
            color.className = "red";
            var colorw = document.getElementById('cashW');
            colorw.className = "red";
            alert("You are out of money!");     
        }
        else {
            setNums();
            cash -= 1;
            win();
            $("#cash").html("$"+cash.toFixed(2));
            $("#cashW").html("$"+cash.toFixed(2));
        }
    });
    
    // for a slot machine handle and the drop box
    $("#lever").draggable({revert: "valid"});
    $("#leverDrop").droppable({
        drop: function () {
            $(this).find("p").html("Dropped");
            if (cash < 1) {
            alert("You are out of money!");     
            }
            else {
                setNums();
                cash -= 1;
                win();
                $("#cash").html("$"+cash.toFixed(2));
                $("#cashW").html("$"+cash.toFixed(2));
            }
        }
    });
    
    // winning money for the slot machine section
    function win() {
        // create the rowsMatch count
        var rowsMatch = 0;
        // create the colsMatch count
        var colsMatch = 0;
        // use if else to see if the numbers are matched
        if (nums[0]==nums[1] && nums[0]==nums[2]) {
            cash += 5;
            rowsMatch++;
        }
        if (nums[3]==nums[4] && nums[3]==nums[5]) {
            cash += 5;
            rowsMatch++;
        }
        if (nums[6]==nums[7] && nums[6]==nums[8]) {
            cash += 5;
            rowsMatch++;
        }
        if (nums[0]==nums[3] && nums[0]==nums[6]) {
            cash += 5;
            colsMatch++;
        }
        if (nums[1]==num5 && nums[1]==nums[7]) {
            cash += 5;
            colsMatch++;
        }
        if (nums[2]==nums[5] && nums[2]==nums[8]) {
            cash += 5;
            colsMatch++;
        }
        
        // if 3 rows or 3 columns are matched, you win $25
        if (rowsMatch == 3 || colsMatch == 3) {
            cash += 25;
        }
        // both 3 rows and 3 columns are matched, then win $50
        else if (rowsMatch == 3 && colsMatch == 3) {
            cash += 50;
        }
    }
    
    // function to call random numbers for the slot machine
    function setNums() {
        for(var i = 1; i <= 9;i++) {
            var rand = Math.floor(Math.random()* 10);
            $("#num"+i).html(rand);
            nums[i-1] = rand;
        }
    }
    
    //walmart buying section
    // create an empty array called prices
    var prices = [];
    // create an empty array called items
    var items = [];
    $("#btnSearch").on("click", function() {
        var output = $("#output");
        var product = $("#product").val();
        
        // to request data from a web server using .ajax
        $.ajax({
          // method we are using to send to server
          // POST is used to send data to a server to create/update a resource.
          method: 'POST',
          // send request to action_page.php
          url: 'action_page.php',
          
          //data we are sending to the server
          data: {"product": product},         
          crossDomain: true,        
          //we are expecting back JSON from our server
          dataType: "JSON",
          
          // before requesting data set cookie values
          // indicates whether or not cross-site Access-Control requests should be made using credentials
          beforeSend: function(xhr){
              xhr.withCredentials = true;
          }
      }).done(function(data){ // run this function if request was successful
            // takes a JSON string and transforms it into a JavaScript object
            data = JSON.parse(data);            
            
            //print the name of an item to the div            
            output.html(data.query + ": <br>");
            $("#outputTable").html("<tr><th>Image</th><th>Description</th><th>Price</th><th>Buy It</th></tr>");
            for (var i = 0; i < data.items.length; i++){
                items[i] = data.items[i].name;
                prices[i] = data.items[i].salePrice;
                $("#outputTable").append('<tr><td><img src="'+data.items[i].mediumImage+'"></td><td>'+data.items[i].name+
                                       '</td><td>$'+data.items[i].salePrice+'</td><td><button id="buy'+i+'" class="buying">Buy</button></td></tr>');
            }
            
            // buy button section           
            $(".buying").on("click", function() {
                var index = $(this).attr("id").substring(3,4);
                var diff = cash - prices[index];
                diff = parseFloat(diff);
                if (diff < 0) {
                  alert("You don't have enough money.");  
                }
                else {
                  cash = diff;
                  cash = cash.toFixed(2);
                  $("#cash").html("$"+cash);
                  $("#cashW").html("$"+cash);
                  // list of items in your cart
                  $("ol").append("<li>" + items[index] + "</li>");
                }                          
            });
          });
    });
}

