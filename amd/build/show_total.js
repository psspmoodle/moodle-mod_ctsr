define(function() {
    return {
        init: function() {
            // get select elements
            const selects = document.querySelectorAll('select[id$="score"]')
            // Update the div on load
            updateSpan(getSum(selects))
            // Wire up selects with event listeners
            selects.forEach(function(el) {
                el.addEventListener('change', function() {
                    updateSpan(getSum(selects))
                })
            })
            function updateSpan(num) {
                const span = document.querySelectorAll('#ctsr-score-total');
                span.forEach(function(el) {
                    el.innerHTML = num.toFixed(1);
                });
            }
            function getSum(selectors) {
                let sum = 0.0;
                selectors.forEach(function(el) {
                    sum += parseFloat(el.value);
                })
                return sum;
            }
        }
    }
})