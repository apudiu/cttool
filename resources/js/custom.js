/**
 * Creates string of array
 * @param arr   array   input array
 * @param glu   string   delimiter string [,]
 * @param prefixOrSuffixValue   string|bool  prefix of suffix value [false]
 * @param prefix    bool    true fo prefix or false for suffix
 * @returns {string}
 */
window.implode = function(arr, glu=',', prefixOrSuffixValue=false, prefix=true) {
    return _.join(arr.map(item => {

        let newItem = item;

        // if prefix or suffix requested
        if (prefixOrSuffixValue) {

            // prefix or suffix to be used
            newItem = (prefix) ? `${prefixOrSuffixValue}${item}` : `${item}${prefixOrSuffixValue}`;
        }

        // returning modified item
        return newItem;
    }), glu);
};

