$("#confirmOrderBtn").on("click", function () {
    $('#cartModal').modal();
});

$("#cartSubmit").on("click", function() {
    //todo: Make this submit to the database
    let cost = $('#costWShipping').val();
    let address = $('#address').val();
    let shipping = $("input:radio[name='shipping']:checked").val();
    let userID = $('#userID').val();

    $.post(
        'model/cart-submit.php',
        {cost: cost, address: address, shipping: shipping, userID: userID},
        function(result) {
            $('#cartModal').modal('hide');
            $('#confirmModal').modal();
            $('#modal2').html(result);
    });
});

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