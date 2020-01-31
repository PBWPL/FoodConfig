function shoppingList(id) {
    jQuery.ajax({
        url: "/foodconfig/user/shoppinglist/"+id,
        dataType: 'json',
    }).done(function(res) {
        if(res.status == 'SUCCESS') {
            swal("Dobra robota!", "Wiadomość z listą zakupów została wysłana", "success");
        }
        else
        {
            swal("Uuuups!", "Coś poszło nie tak", "error");
        }
    });
}