define(['core/localstorage'], function(LocalStorage) {
    return {
        init: function() {
            // const cmid = document.querySelector('body').classList.contains();
            if (LocalStorage.get('mod_ctsr/tab')) {
                const activeTab = LocalStorage.get('mod_ctsr/tab')
                document.querySelector('#pills-1-tab').classList.remove('active')
                document.querySelector('#pills-1').classList.remove('active', 'show')
                document.querySelector('#pills-' + activeTab + '-tab').classList.add('active')
                document.querySelector('#pills-' + activeTab).classList.add('active', 'show')
            } else {
                document.querySelector('#pills-1-tab').classList.add('active')
                document.querySelector('#pills-1').classList.add('active', 'show')
            }
            document.addEventListener('click', function(e) {
                if (e.target.classList.contains('ctsr-tab')) {
                    const tab = e.target.id.match(/\d+/)
                    LocalStorage.set('mod_ctsr/tab', tab);
                }
            });
        }
    }
});