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
            // information gathering from the form fields
            var name =$('#itemName').val();
            var description = $('#itemDescription').val();
            var url = $('#controlerURL').attr('url');
            // final check if everything is
            $('#loading-img').show();

            // Making a form data object that will be passed through ajax
            formData = new FormData();
            formData.append('file', $('input[type=file]')[0].files[0]);
            formData.append('name', name);
            formData.append('description', description);

            $.ajax({
                url: url,
                type: "POST",
                data: formData,
                contentType: false,       // The content type used when sending data to the server.
                cache: false,             // To unable request pages to be cached
                processData:false,        // To send DOMDocument or non processed data file it is set to false
                success: function(data)
                {
                    $('#loading-img').hide();
                    console.log(data);
                    $("#imgdisplay").html("<img src='../images/problems"+data.picture+"'style='width: 150px'>");


                },
                error: function(XMLHttpRequest, textStatus, errorThrown)
                {
                    alert('Error : ' + errorThrown);
                }
            });
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
            $('#historyButtonMore').prop('value', 'Peržiūrėti istoriją');
        } else {
            container.hide();
            $.ajax({
                type: "POST",
                url: url,
                cache: "false",
                dataType: "html",
                success: function(result)
                {
                    if (result == undefined) {
                        $(".filters-history").find("li").removeClass("active").addClass("disabled");
                        closeAndScroll('html, body', '#list');
                        var here = '<a data-dismiss="modal">čia!</a>';
                        var initiative = '<i data-toggle="tooltip" data-placement="top" title="Skelbti iniciatyvą" class="fa fa-bullhorn"></i>';
                        container.append(
                            '<p>Paskelbkite problemą ' + here + '<br>' +
                            'Paskelbkite iniciatyvą problemos lange spusteldami ' + initiative + '<br>' +
                            'Arba prisijunkite prie jau sukurtos iniciatyvos spusteldami čia!</p>'
                        ).slideDown('slow');
                    } else {
                        container.append(result).slideDown('slow');
                    }
                },
                error: function(XMLHttpRequest, textStatus, errorThrown)
                {
                    alert('Error : ' + errorThrown);
                },
                complete: function()
                {
                    filters();
                }
            }).done(function () {
                container.data('loaded', true);
                console.log("Ajax done");
            });
        }
    });

    function filters() {
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

    function closeAndScroll(modal, target) {
        $(modal).on('hidden.bs.modal', function() {
            $('html, body').animate({
                scrollTop: $(target).offset().top-151
            }, 1000);
        })
    }

    // End of ajax load history
    //------------------------------------------------------------


    //handle initiative form
    $('.openInitiativeModal').on('click', function(e){
        //hide opened problem modal
        $('.modal').modal('hide');
        //open initiative form for that problem via ID
        $('#ajimedakaqfn').attr('probId', $(this).attr('probId'));
    });
    //handle initiative form data
    var addInitiative = $('#Miestietis_MainBundle_Initiative');
    $('#submitButton').on('click', addInitiative, function(e){
        e.preventDefault();
        if ($('#profileLi').attr('rel') == 'Connected') {
            var description = $('#newDescription').val();
            var year = $('#newDate select:nth-child(1)').val();
            var month = $('#newDate select:nth-child(2)').val();
            var day = $('#newDate select:nth-child(3)').val();
            var hour = $('#newDate div:nth-child(2) select:nth-child(1)').val();
            var minute = $('#newDate div:nth-child(2) select:nth-child(2)').val();
            var date = year.concat(" ", month, " ", day, " ", hour, " ", minute);
            if (description != '' & year != '' & month != '' & day != '' & hour != '' & minute != '') {
                var url = $('#ajimedakaqfn').attr('url');
                var probId = $('#ajimedakaqfn').attr('probId');
                var data = {description: description, date: date, probId:probId};
                console.log(data);
                console.log(url);
                $.ajax({
                    type: "POST",
                    url: url,
                    data: data,
                    success: function (data) {
                        if(data.status != null){
                            alert(data.status);
                        }else{
                            $('#submitButton').attr('class', 'btn btn-success');
                        }
                    },
                    error: function (XMLHttpRequest, textStatus, errorThrown) {
                        alert('Error : ' + errorThrown);
                    }
                });
            } else {
                alert('Visi laukai turi būti užpildyti');
            }
        } else {
            alert('Norėdami paskelti miesto problemą prisijunkite');
        }
    });
    /*
     need to add a check if user has already voted for this problem
     */
    $('.incVote').on('click',function(){ //increment vote
        var item = $(this).children('i');
        var itemDisable = $(this);
        var status = 'Pritarti galite tik vieną kartą!';
        if ($('#profileLi').attr('rel') == 'Connected') {
            var url = $(this).attr('url');
            var probId = $(this).attr('probId');
            var data = {probId: probId};
            $.ajax({
                type: "POST",
                url: url,
                data: data,
                success: function (data) {
                    var probId = data.probId;
                    var votes = data.votes;
                    $('.votes-'.concat(probId)).text(votes);

                },
                complete: function() {
                    itemDisable.addClass('disabled');
                    item.attr('data-original-title', status)
                        .tooltip('fixTitle')
                        .tooltip('show');
                }
            });
        } else {
            $('.modal').modal('hide');
            $('#profileLi > a').css('animation', 'bounceIn 1s')
                               .css('animation-iteration-count', 'infinite');

            $(document).on('scroll shown.bs.modal', function() {
                    $('#profileLi > a').css('animation-iteration-count', '1');
                }
            );
            $('.isotope-item').mouseleave(function() {
                $('#profileLi > a').css('animation-iteration-count', '1');
            });

        }
    });


});