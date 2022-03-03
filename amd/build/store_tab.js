define(['core/localstorage', 'mod_ctsr/util'], function(LocalStorage, util) {
    return {
        init: function() {
            let key = 'mod_ctsr/tab-' + util.getCmid()
            if (LocalStorage.get(key)) {
                const activeTab = LocalStorage.get(key)
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
                    LocalStorage.set(key, tab);
                }
            });
        }
    }
});