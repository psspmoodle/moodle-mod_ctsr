define([], function() {
    const selects = document.querySelectorAll('select[id$="score"]')
    selects.foreach(function(el) {
        el.addEventListener('change', getSum(selects))
    })
    const div = document.querySelector('#total');
    div.innerHTML = getSum(selectors);
    function getSum(selectors) {
        let sum = 0.0;
        selectors.forEach(function(el) {
            sum += parseFloat(el.value);
        })
        return sum;
    }

})