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

    $(".moreHistory").on('click', function(event){
        event.preventDefault();
        var url = $('.moreHistory').attr('href');
        var container = $(".history-content");
        if (container.data('loaded')) {
            console.log('data already loaded');
            $('#historyButtonMore').prop('value', 'Peržiūrėti istoriją');
            container.slideUp('slow', function() {
                $(this).empty();
            }).data('loaded', false)
        } else {
            container.hide();
            $.ajax({
                type: "POST",
                url: url,
                cache: "false",
                dataType: "html",
                success: function(result)
                {
                    container.append(result).slideDown('slow');
                    $('#historyButtonMore').blur().prop('value', 'Paslėpti istoriją');
                },
                error: function(XMLHttpRequest, textStatus, errorThrown)
                {
                    alert('Error : ' + errorThrown);
                }
            }).done(function () {
                container.data('loaded', true);
            });
        }
    });

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
                }
            });
        }else{alert("Norėdami balsuoti, turite prisijungti!");}
    });

});