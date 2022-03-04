define(['jquery','core/localstorage', 'theme_boost/tab', 'theme_boost/tooltip'], function($, LocalStorage) {

    const TABKEY = 'mod_ctsr/tab-' + getCmid()
    const AUDIOKEY = 'mod_ctsr/audio-' + getCmid()
    const AUDIOEL = document.querySelector('#ctsr-audio')
    const SELECTS = document.querySelectorAll('select[id$="score"]')
    const TOTALSPANS = document.querySelectorAll('#ctsr-score-total')

    /**
     * Get course module ID from body class.
     * @returns {string}
     */
    function getCmid() {
        return document.querySelector('body').className.match(/cmid-(\d+)/)[1];
    }

    /**
     * The mustache template outputs without an activated tab. These classes need to exist on a tab to make the BS tabs work.
     * @param tab int
     */
    function showInitialTab(tab) {
        document.querySelector('#pills-' + tab + '-tab').classList.add('active')
        document.querySelector('#pills-' + tab).classList.add('active', 'show')
    }

    /**
     * Set initial time for the audio player.
     * @param time
     */
    function setAudioTime(time) {
        AUDIOEL.currentTime = time
    }

    /**
     * Update span with passed-in number.
     * @param num
     */
    function updateSpan(num) {
        TOTALSPANS.forEach(function(el) {
            el.innerHTML = num.toFixed(1);
        });
    }

    /**
     * Get current total of all selected scores.
     * @returns {number}
     */
    function getSum() {
        let sum = 0.0;
        SELECTS.forEach(function(el) {
            sum += parseFloat(el.value);
        })
        return sum;
    }

    /**
     * Update local storage with tab number on click so it persists on page reload.
     */
    function addTabListener() {
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('ctsr-tab')) {
                LocalStorage.set(TABKEY, e.target.id.match(/\d+/))
            }
        });
    }

    /**
     * Update local storage with current audio time so it persists on page reload.
     */
    function addAudioListener() {
        AUDIOEL.addEventListener('timeupdate', function() {
            LocalStorage.set(AUDIOKEY, AUDIOEL.currentTime);
            if (AUDIOEL.ended) {
                LocalStorage.set(AUDIOKEY, '0');
            }
        })
    }

    /**
     * Update total score spans on select list change.
     */
    function addSelectListener() {
        SELECTS.forEach(function(el) {
            el.addEventListener('change', function() {
                updateSpan(getSum())
            })
        })
    }

    /**
     * Set up page on load.
     */
    this.prepareCtsr = function() {
        // Add tooltip functionality
        $('[data-toggle="tooltip"]').tooltip()
        // Check for local storage
        const tab = LocalStorage.get(TABKEY) || 1
        const time = LocalStorage.get(AUDIOKEY) || 0
        showInitialTab(tab)
        setAudioTime(time)
        // Set total score
        updateSpan(getSum())
        // Wire up listeners
        addTabListener()
        addAudioListener()
        addSelectListener()
    }

    return {
        init: prepareCtsr
    }
})