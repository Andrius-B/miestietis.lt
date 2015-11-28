/* Theme Name: Worthy - Free Powerful Theme by HtmlCoder
 * Author:HtmlCoder
 * Author URI:http://www.htmlcoder.me
 * Version:1.0.0
 * Created:November 2014
 * License: Creative Commons Attribution 3.0 License (https://creativecommons.org/licenses/by/3.0/)
 * File Description: Place here your custom scripts
 */
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

    var $addProblem = $('#newProblemAjaxForm');
    $('body').on('submit', $addProblem, function(e){
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

});