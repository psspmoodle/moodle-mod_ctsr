define(['jquery', 'theme_boost/tooltip'], function($) {
    return {
        init: function() {
            $('[data-toggle="tooltip"]').tooltip();
        }
    };
});