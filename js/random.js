function RandomUser() {

    $.ajax({
        type: "POST",
        url: "ajax/random.php",
        success: function(msg){
            //alert(msg)
            var ranUser = JSON.parse ( msg );
            if (ranUser.status == 1){
                $( "input[name='random_user']" ).val(ranUser.fn+ " " + ranUser.ln+"("+ranUser.id+")");
            }else{
               $( "input[name='random_user']" ).val('Ничего не найдено');
            }
            //
        }
    });



}