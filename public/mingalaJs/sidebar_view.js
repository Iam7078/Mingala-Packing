$(document).ready(function () { // DONEEE
    $('#export-database').click(function () {
        exportDatabase();
    });

    function exportDatabase() {
        window.location = '/export-database';
    }
});