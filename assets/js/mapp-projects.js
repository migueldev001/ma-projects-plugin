
jQuery(function($){
    //Init vars
    var page = 1;
    var search_term = '';

    //Function to retrieve and display projects
    function mappLoadProjects(page, search_term) {
        setTimeout(function(){
            $.get(ajax_object.ajax_url, {action: 'mapp_load_projects', page: page, search_term: search_term, mapp_nonce: ajax_object.mapp_nonce}, function(response) {
                $('.mapp-projects').slideUp(600, function(){
                    if (response.slice(-1) == 0) {
                        response = response.slice(0, -1);
                    }
                    $('.mapp-projects').html(response);
                    $('.mapp-projects').slideDown(600);
                    
                    $('.mapp-icon-search').show();
                    $('.mapp-search-loader').hide();
                });
            });            
        }, 200);
    }

    $(document).on('click', '#mapp-next-page', function(e) {
        e.preventDefault();

        $(this).find('.mapp-btn-loader').fadeIn();

        page++;
        mappLoadProjects(page, search_term);
    });
    
    $(document).on('click', '#mapp-previous-page', function(e) {
        e.preventDefault();

        $(this).find('.mapp-btn-loader').fadeIn();

        if (page > 1) {
            page--;
            mappLoadProjects(page, search_term);
        }
    });

    $(document).on('submit', '#mapp-search-form', function(e) {
        e.preventDefault();

        search_term = $(this).find('input[name="search_term"]').val();
        page = 1;

        $('.mapp-icon-search').hide();
        $('.mapp-search-loader').show();

        mappLoadProjects(page, search_term);
    });

    $(document).on('click', '.mapp-search-submit', function(e) {
        e.preventDefault();

        $("#mapp-search-form").submit();
    });
});