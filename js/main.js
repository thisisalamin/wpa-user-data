jQuery(document).ready(function($) {
    let search_form = $('#my-search-form');

    search_form.submit(function(e) {
        e.preventDefault();
        let search_term = $('#my-search-term').val();
        let formData = new FormData();
        formData.append('action', 'wpaud_search');
        formData.append('search_term', search_term);
        
        $.ajax({
            url: my_ajax_object.ajax_url,
            type: 'post',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
               $('#my-table-results').html(response);
            },
            error: function(errorThrown) {
                console.log(errorThrown);
            }
        })
    });
})