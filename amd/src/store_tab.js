define(['core/localstorage'], function(LocalStorage) {
    return {
        init: function() {
            if (LocalStorage.get('mod_ctsr/tab')) {
                const activeTab = LocalStorage.get('mod_ctsr/tab')
                document.querySelector('#pills-' + activeTab + '-tab').classList.add('active')
                document.querySelector('#pills-' + activeTab).classList.add('active', 'show')
                document.querySelector('#pills-1-tab').classList.remove('active')
                document.querySelector('#pills-1').classList.remove('active', 'show')
            }
            document.addEventListener('click', function(e) {
                if (e.target.classList.contains('ctsr-tab')) {
                    const tab = e.target.id.match(/\d+/)
                    console.log(tab)
                    LocalStorage.set('mod_ctsr/tab', tab);
                }
            });
        }
    };
});