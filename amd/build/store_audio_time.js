define(['core/localstorage', 'mod_ctsr/util'], function(LocalStorage, util) {
    return {
        init: function() {
            let key = 'mod_ctsr/audio_time-' + util.getCmid()
            let audio = document.querySelector('#ctsr-audio');
            if (LocalStorage.get(key)) {
                audio.currentTime = LocalStorage.get(key)
            }
            audio.addEventListener('timeupdate', function(e) {
                LocalStorage.set(key, audio.currentTime);
                if (audio.ended) {
                    LocalStorage.set(key, '0');
                }
            })
        }
    };
});