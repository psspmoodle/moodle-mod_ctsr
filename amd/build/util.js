define(function() {
    return {
        getCmid: function() {
            return document.querySelector('body').className.match(/cmid-(\d+)/)[1];
        }
    }
});