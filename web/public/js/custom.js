$(document).on('change', '.btn-file :file', function() {
    var input = $(this),
        numFiles = input.get(0).files ? input.get(0).files.length : 1,
        label = input.val().replace(/\\/g, '/').replace(/.*\//, '');
    input.trigger('fileselect', [numFiles, label]);
});

$(document).ready( function() {
    $('.btn-file :file').on('fileselect', function(event, numFiles, label) {

        var input = $(this).parents('.input-group').find(':text'),
            log = numFiles > 1 ? numFiles + ' files selected' : label;

        if( input.length ) {
            input.val(log);
        } else {
            if( log ) alert(log);
        }

    });

    var addProblem = $('#newProblemAjaxForm');
    $('#newProblem').on('click', addProblem, function(e){
        e.preventDefault();
        if($('#profileLi').attr('rel') == 'Connected'){
            var name =$('#itemName').val();
            var description = $('#itemDescription').val();
            var file = $('#itemFile').val();
            if(name != '' & description!= ''& file != ''){
                var url = $('#beletristika').attr('url');
                var data = {name: name, description: description, picture: file};
                console.log(data);
                console.log(url);
                $.ajax({
                    type: "POST",
                    url: url,
                    data: data,
                    success: function(data)
                    {
                        alert(data.picture);
                    },

                    error: function(XMLHttpRequest, textStatus, errorThrown)
                    {
                        alert('Error : ' + errorThrown);
                    }
                });
            }else {
                alert('Visi laukai turi būti užpildyti'); // custom modal or message line
            }
        }else{
            alert('Norėdami paskelbti miesto problemą prisijunkite'); // custom modal or message line
        }
    });

    // -------------------------------------------------
    // Ajax request to load profile modal with history

    $(".profileHistory").on('click', function(event){
        event.preventDefault();
        var url = $('.profileHistory').attr('href');
        var container = $(".history-content");
        if (container.data('loaded')) {
            console.log('data already loaded');
            $('#historyButtonMore').prop('value', 'Peržiūrėti istoriją');
        } else {
            $.ajax({
                type: "POST",
                url: url,
                cache: "false",
                dataType: "html",
                success: function(result)
                {
                    container.append(result).slideDown('slow');
                    console.log("Ajax success");

                },
                error: function(XMLHttpRequest, textStatus, errorThrown)
                {
                    alert('Error : ' + errorThrown);
                },
                complete: function()
                {
                    console.log("Ajax complete: before filters");
                    Filters();
                    console.log("Ajax complete: after filters");
                }
            }).done(function () {
                container.data('loaded', true);
                console.log("Ajax done");
            });
        }
    });

    function Filters() {
        console.log("Filters called");
        if ($('.table-like').length>0) {
            $('.table-like').fadeIn('slow');
            $('#profile-more').on('shown.bs.modal', function() {
                var $cont = $('.table-like').isotope({
                    itemSelector: '.table-like__item',
                    layoutMode: 'vertical',
                    transitionDuration: '0.6s',
                    filter: "*"
                });
                $('.filters-history').on( 'click', 'ul.nav-hist li a', function() {
                    var filterValue = $(this).attr('data-filter');
                    $(".filters-history").find("li.active").removeClass("active");
                    $(this).parent().addClass("active");
                    $cont.isotope({ filter: filterValue });
                    return false;
                });
            });
        }
    }

    // End of ajax load history
    //------------------------------------------------------------
});