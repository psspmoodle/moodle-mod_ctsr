define(['jquery', 'core/modal_factory'], function($, ModalFactory) {
    return {
        init: function() {
            var trigger = $('#id_submitbutton');
            ModalFactory.create({
                type: 'SAVE_CANCEL',
                title: 'Confirm submission',
                body: '<p>Are you sure you want to submit?</p>'
            }, trigger)
                .done(function(modal) {
                    modal.setSaveButtonText('Submit');
            });
        }
    }
});