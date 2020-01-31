function search(page) {
    var form = jQuery('#search-filters');
    if(page) jQuery('.page',form).val(page);
    var data = form.serialize();
    jQuery('#loader').show();
    jQuery.ajax({
        url: '',
        data: data,
        method: 'GET'
    }).done(function(html){
        jQuery('#ajax-container .results').html(jQuery('.results',jQuery(html)).html());
        jQuery('#loader').hide();
    })
    return false;
}

function addLike(id) {
    jQuery.ajax({
        url: "/foodconfig/user/like/"+id,
        dataType: 'json',
    }).done(function(res) {
        if(res.status == 'SUCCESS') {
            jQuery('.dish-'+id+' .like').addClass('active');
            jQuery('.dish-'+id+' .like.like').attr("onclick",'removeLike('+id+')');
            swal("Dobra robota!", "Produkt został dodany do ulubionych", "success");
        }
        else
        {
            swal("Uuuups!", "Coś poszło nie tak", "error");
        }
    });
}

function removeLike(id) {
    jQuery.ajax({
        url: "/foodconfig/user/unlike/"+id,
        dataType: 'json',
    }).done(function(res) {
        if(res.status == 'SUCCESS') {
            jQuery('.dish-'+id+' .like').removeClass('active');
            jQuery('.dish-'+id+' .like.like').attr("onclick",'addLike('+id+')');
            swal("Dobra robota!", "Produkt został usunięty z ulubionych", "success");
        }
        else
        {
            swal("Uuuups!", "Coś poszło nie tak", "error");
        }
    });
}

function removeDish(id) {
    jQuery.ajax({
        url: "/foodconfig/dish/remove/"+id,
        dataType: 'json',
    }).done(function(res) {
        if(res.status == 'SUCCESS') {
            jQuery('row').removeClass('.dish-'+id+'');
            swal("Dobra robota!", "Produkt został usunięty", "success");
            window.location.reload();
        }
        else
        {
            swal("Uuuups!", "Coś poszło nie tak", "error");
        }
    });
}