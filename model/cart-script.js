/**
 * Script used on the cart page to handle modals and order submissions
 * @author Max Lee
 * @copyright 6/6/2019
 */

//When a user tries to place an order, if they aren't logged in then they cannot continue
$("#confirmOrderBtn").on("click", function ()
{
    if($('#userID').val() === '')
    {
        $('#error').html("<p class='alert alert-danger'>Please login to finish your order</p>")
    }
    else
    {
        $('#cartModal').modal();
    }
});

//attempts to submit the cart to the database
$("#cartSubmit").on("click", function() {
    let totalCost = $('#costWShipping').val();

    let costString = $('#priceString').val();
    let pictureString = $('#pictureString').val();
    let itemString = $('#itemString').val();

    let address = $('#address').val();
    let shipping = $("input:radio[name='shipping']:checked").val();
    let userID = $('#userID').val();

    if(address.length === 0)
    {
        $('#addressError').html('<p class="alert alert-danger">Please give an address</p>')
    }
    else
    {
        $.post(
            'model/cart-submit.php',
            {costString: costString, pictureString: pictureString, itemString: itemString,
                address: address, shipping: shipping, userID: userID, totalCost: totalCost},
            function(result) {
                $('#cartModal').modal('hide');
                $('#confirmModal').modal();
                $('#modal2').html(result);
            });
    }
});

//handles dynamically changing the price displayed based on shipping selected
$("input:radio[name='shipping']").on('click', function() {
    let activeRadio = $("input:radio[name='shipping']:checked");
    let cost = $('#totalCost').val();
    let display = $('#costDisplay');
    let totalCost = $('#costWShipping');
    let costFloat = parseFloat(cost);
    display.html("Price Total: $");

    if(activeRadio.val() === 'standard')
    {
        let costWTax = costFloat.toFixed(2);
        display.append(costWTax);
        totalCost.val(costWTax);
    }
    else if(activeRadio.val() === 'expedited')
    {
        let costWTax = (costFloat+10).toFixed(2);
        display.append(costWTax);
        totalCost.val(costWTax);
    }
    else if(activeRadio.val() === 'overnight')
    {
        let costWTax = (costFloat+20).toFixed(2);
        display.append(costWTax);
        totalCost.val(costWTax);
    }
});