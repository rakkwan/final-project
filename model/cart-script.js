$("#confirmOrderBtn").on("click", function () {
    $('#cartModal').modal();
});

$("#cartSubmit").on("click", function() {
    //todo: Make this submit to the database
    let cost = $('#costWShipping').val();
    let address = $('#address').val();
    let shipping = $("input:radio[name='shipping']:checked").val();

    $.post('model/cart-submit.php', {cost: cost, address: address, shipping: shipping}, function(result) {
        $('#modal').append(result);
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
        display.append(costFloat.toFixed(2));
        totalCost.val(cost);
    }
    else if(activeRadio.val() === 'expedited')
    {
        display.append((costFloat+10).toFixed(2));
        totalCost.val(cost+10);
    }
    else if(activeRadio.val() === 'overnight')
    {
        display.append((costFloat+20).toFixed(2));
        totalCost.val(cost+20);
    }
});