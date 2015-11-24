$('.openInitiativeModal').on('click', function(e){
    //hide opened problem modal
    $('.modal').modal('hide');
    //open initiative form for that problem via ID
    $('#ajimedakaqfn').attr('probId', $(this).attr('probId'));
});

var addInitiative = $('#Miestietis_MainBundle_Initiative');
$('#submitButton').on('click', addInitiative, function(e){
        e.preventDefault();
        if ($('#profileLi').attr('rel') == 'Connected') {
            var description = $('#newDescription').val();
            var year = $('#newDate select:nth-child(1)').val();
            var month = $('#newDate select:nth-child(2)').val();
            var day = $('#newDate select:nth-child(3)').val();
            var date = year.concat(" ", month, " ", day);
            if (description != '' & year != '' & month != '' & day != '') {
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