$('#login').on("click", function(){

    let value = $('#email').val();

    $.post("lookuplogin.php", { value : value }, function(result) {
            $('.result').html(result);
        }
    );
});