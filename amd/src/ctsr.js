define(['jquery','core/localstorage', 'theme_boost/tab', 'theme_boost/tooltip'], function($, LocalStorage) {

    const TABKEY = 'mod_ctsr/tab-' + getCmid()
    const AUDIOKEY = 'mod_ctsr/audio-' + getCmid()
    const AUDIOEL = document.querySelector('#ctsr-audio')
    const SELECTS = document.querySelectorAll('select[id$="score"]')
    const comments = [...Array(13).keys()].slice(1).map(function(i) { return "#id_item_" + String(i).padStart(2,0) + "_comments"})
    const COMMENTS = document.querySelectorAll(comments)
    const SUBMITBUTTON = document.querySelector('#id_submitbutton')
    const SUBMITWRAPPER = document.querySelectorAll('#submit-buttom-wrapper')
    let scores = [...Array(13).keys()].slice(1).map(function(i) { return "#ctsr-score-total-" + i})
    const TOTALSPANS = document.querySelectorAll(scores)

    /**
     * Get course module ID from body class.
     * @returns {string}
     */
    function getCmid() {
        return document.querySelector('body').className.match(/cmid-(\d+)/)[1]
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
            el.innerHTML = num.toFixed(1)
        });
    }

    /**
     * Get current total of all selected scores.
     * @returns {number}
     */
    function getSum() {
        var sum = 0.0
        SELECTS.forEach(function(el) {
            if (el.value > -1) sum += parseFloat(el.value)
        })
        return sum
    }

    /**
     * Update local storage with tab number on click so it persists on page reload.
     */
    function addTabListener() {
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('ctsr-tab')) {
                LocalStorage.set(TABKEY, e.target.id.match(/\d+/))
            }
        })
    }

    /**
     * Update local storage with current audio time so it persists on page reload.
     */
    function addAudioListener() {
        AUDIOEL.addEventListener('timeupdate', function() {
            LocalStorage.set(AUDIOKEY, AUDIOEL.currentTime)
            if (AUDIOEL.ended) {
                LocalStorage.set(AUDIOKEY, '0')
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
                validateFields()
            })
        })
    }

    function addTextareaListener() {
        COMMENTS.forEach(function(el) {
            el.addEventListener('change', function() {
                validateFields()
            })
        })
    }

    function validateFields() {
        if (!SUBMITBUTTON) {
            return false
        }
        const filteredScores = [...SELECTS].filter(function(el) {
            return el.value > -1
        })
        scoresResult = filteredScores.length == SELECTS.length
        const filteredComments = [...COMMENTS].filter(function(el) {
            let html = $.parseHTML(el.value)
            return $(html).text()
        })
        let commentsResult = filteredComments.length == COMMENTS.length
        let done = scoresResult && commentsResult
        if (done) {
            SUBMITBUTTON.removeAttribute('style', 'pointer-events: initial')
            SUBMITBUTTON.removeAttribute('disabled', 'disabled')
        } else {
            SUBMITBUTTON.setAttribute('style', 'pointer-events: none')
            SUBMITBUTTON.setAttribute('disabled', 'disabled')
        }
        alterSubmitTooltip(done)
    }

    function alterSubmitTooltip(done) {
        if (done) {
            SUBMITWRAPPER.removeAttribute('data-tooltip')
        } else {
            SUBMITWRAPPER.setAttribute('data-tooltip', 'tooltip')
        }
    }

    /**
     * Set up page on load.
     */
    this.prepareCtsr = function() {
        // Add tooltip functionality
        $('[data-toggle="tooltip"]').tooltip()
        // See if it's already complete
        validateFields()
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
        addTextareaListener()
    }

    return {
        init: prepareCtsr
    }
})