/*
 * Name: Maxwell Lee
 * Date: 6/6/2019
 * File: final-project.js
 * Description: handles the search-page.html page and how it handles all output given from Walmart's API
 */

//displays all the products given from Walmart
$("#btnSearch").on("click", setForm);

//makes it so you can search by pressing enter
$("#product").on("keydown", function(event) {
    var code;

    if (event.key !== undefined) {
        code = event.key;
    }

    if(code === "Enter")
    {
        setForm();
    }
});

var prices = [];
var items = [];
var images = [];
//handles the product given and sends it to Walmart's API and then displays it on search-page.html
function setForm()
{
    if(document.readyState === "complete") {
        //walmart buying section
        // create an empty array called prices
        prices = [];
        // create an empty array called items
        items = [];
        // create an empty array for pictures
        images = [];

        var output = $("#output");
        var product = $("#product").val();

        // to request data from a web server using .ajax
        $.ajax({
            // method we are using to send to server
            // POST is used to send data to a server to create/update a resource.
            method: 'POST',
            // send request to action_page.php
            url: 'api/action_page.php',

            //data we are sending to the server
            data: {"product": product},
            crossDomain: true,
            //we are expecting back JSON from our server
            dataType: "JSON",

            // before requesting data set cookie values
            // indicates whether or not cross-site Access-Control requests should be made using credentials
            beforeSend: function (xhr) {
                xhr.withCredentials = true;
            }
        }).done(function (data) { // run this function if request was successful
            // takes a JSON string and transforms it into a JavaScript object
            data = JSON.parse(data);

           // $("#product").autocomplete(data);

            //print the name of an item to the div
            output.html(data.items.length+" results for: "+data.query + "<br>");
            $("#outputTable").html("<thead><tr><th>Image</th><th>Description</th>" +
                "<th>Price</th><th>Buy It</th></tr></thead><tbody>");
            for (var i = 0; i < data.items.length; i++) {
                items[i] = data.items[i].name;
                prices[i] = data.items[i].salePrice;
                images[i] = data.items[i].mediumImage;
                $("#outputTable").append('<tr><td><img src="' + data.items[i].mediumImage + '"></td><td>' + data.items[i].name +
                    '</td><td>$' + data.items[i].salePrice + '</td><td><button type="button" id="buy'+i+'" ' +
                    'name="cartItem" ' +
                    'value="buy'+i+'" class="btn btn-info waves-effect buying">Add to Cart</button></td></tr>');
            }
            $("outputTable").append("</tbody>");

            // Makes the modal display the selected products info
            $(".buying").on("click", function () {
                var index = $(this).attr("id").substring(3, 4);

                var modal = $('#modal');
                modal.html('<img src="'+images[index]+'" alt="product">');
                modal.append('<p>Name: '+items[index]+'</p>');
                modal.append('<p>Price: $'+prices[index]+'</p>');

                modal.append('<input hidden type="text" name="image" value="'+images[index]+'">');
                modal.append('<input hidden type="text" name="name" value="'+items[index]+'">');
                modal.append('<input hidden type="text" name="price" value="'+prices[index]+'">');

                $('#exampleModal').modal();
            });
        });
    }
}