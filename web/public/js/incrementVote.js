/**
 * Created by andrius on 15.11.24.
 */
/*
need to add a check if user has already voted for this problem
 */
$('.incVote').on('click',function(){
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