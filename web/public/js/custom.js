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
});