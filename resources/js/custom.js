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

window.clickToClick = function (source, dest) {
    // when source clicked
    $(source).click(function (e) {

        // clicking destination
        $(dest).click();
    })
};

(function () {

    // handling file upload process
    $('.process').click(function (e) {

        //dry run requested or not
        let dryRun = $(this).data('dry');

        //show spinner
        $('.loading').toggleClass('hidden');

        axios
            .post('/home/process', {
                dry: dryRun
            })
            .then(function (res) {

                // add to the console
                $('#console #log').append(res.data);

                // hide spinner
                $('.loading').toggleClass('hidden');
            })
            .catch(function (err) {
                console.log(err);
            });
    });

    // csv upload
    $('.csv-upload-btn').click(function (e) {

        //show spinner
        $('.loading').toggleClass('hidden');
    });

    // handling console clear
    $('#console-clear').click(function (e) {
        $('#console #log').html('');
    });
})();
