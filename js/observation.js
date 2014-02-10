$(document).ready(function () {
    $('.js-not-responding').hide();
    var updateClockInterval = 1000;
    var addRowInterval = 120; // in seconds
    var observationTime = 0;
    var currentlyObserving = false;
    var modelTableRow = $('.row_to_clone').clone();
    setInterval(tick, updateClockInterval);

    $('.observation-toggle-legend').click(function () {
        $('.table-observation-legend').toggle();
    });
    $('.observation-toggle-tick').click(function () {
        $(this).html(currentlyObserving ? 'Start Watch' : 'Stop Watch');
        currentlyObserving = !currentlyObserving;
    });
    function tick() {
        var currentTime = new Date();
        var time = (currentTime.getHours() > 9 ? '' : '0') + currentTime.getHours() + ':'
            + (currentTime.getMinutes() > 9 ? '' : '0') + currentTime.getMinutes() + ':'
            + (currentTime.getSeconds() > 9 ? '' : '0') + currentTime.getSeconds()
        $('.observation-time').html(time);
        if (currentlyObserving)
            tock();
    }

    function tock() {
        observationTime++;
        var secs = observationTime % 60;
        var mins = (observationTime - secs) / 60;
        var tick = (mins > 9 ? '' : '0') + mins + ':'
            + (secs > 9 ? '' : '0') + secs
        $('.observation-tick').html(tick);

        if (observationTime % addRowInterval == 0) {
            addRow();
            $('#myModal').modal('show');
        }
    }

  $(document).on('click', '.js-dismiss-alert', function() {
    $(this).parent('.alert').hide();
  });
  $(document).on('click', '.js-save', saveForm);
  $(document).on('click', '.js-save-locally', saveFormLocally);
});

function saveForm(e) {
  e.preventDefault();
  var $submitButton = $(e.target);
  $submitButton.attr('disabled', 'disabled');
  var $form = $submitButton.parents('form');
  $.ajax({
    type: 'POST',
    url: COPUS_ENDPOINT + '/observation/create',
    data: $form.serialize(),
    dataType: 'html',
    success: function(result) {
      console.log(result);
      window.location = COPUS_ENDPOINT + '/site/index';
    },
    error: function(jqXHR, textStatus) {
      $('.js-not-responding').show();
    },
    complete: function() {
      $submitButton.removeAttr('disabled');
    }
  });
}

function saveFormLocally(e) {
  e.preventDefault();
  var $form = $(e.target).parents('form');
  window.open('data:text;charset=utf-8,' + $form.serialize());
}

// Sticky sidebar w/jQuery
// @author https://gist.github.com/pinchyfingers/2414459
sidebarwidth = $(".sidebar-width").css('width');
bodypaddingtop = $(".navbar-fixed-top").css('height');
sidebarheight = $("body").css('height');
$('.sidebar-nav-fixed').css('width', sidebarwidth);
$('.sidebar-nav-fixed').css('height', sidebarheight);
$('body').css('paddingTop', bodypaddingtop)
contentmargin = parseInt(sidebarwidth)
$('.span-fixed-sidebar').css('marginLeft', contentmargin);
$('.span-fixed-sidebar').css('paddingLeft', 60);

/*
 * addrow.js - an example JavaScript program for adding a row of input fields
 * to an HTML form
 *
 * This program is placed into the public domain.
 *
 * The original author is Dwayne C. Litzenberger.
 * Home page: http://www.dlitz.net/software/addrow/
 */
function addRow() {
    /* Declare variables */
    var elements, templateRow, rowCount, row, className, newRow, element;
    var i, s, t;

    /* Get and count all "tr" elements with class="row".    The last one will
     * be serve as a template. */
    if (!document.getElementsByTagName)
        return false;
    /* DOM not supported */
    elements = document.getElementsByTagName("tr");
    templateRow = null;
    rowCount = 0;
    for (i = 0; i < elements.length; i++) {
        row = elements.item(i);

        /* Get the "class" attribute of the row. */
        className = null;
        if (row.getAttribute)
            className = row.getAttribute('class')
        if (className == null && row.attributes) {    // MSIE 5
            /* getAttribute('class') always returns null on MSIE 5, and
             * row.attributes doesn't work on Firefox 1.0.    Go figure. */
            className = row.attributes['class'];
            if (className && typeof(className) == 'object' && className.value) {
                // MSIE 6
                className = className.value;
            }
        }

        /* This is not one of the rows we're looking for.    Move along. */
        if (className != "row_to_clone")
            continue;

        /* This *is* a row we're looking for. */
        templateRow = row;
        rowCount++;
    }
    if (templateRow == null)
        return false;
    /* Couldn't find a template row. */

    /* Make a copy of the template row */
    newRow = templateRow.cloneNode(true);

    /* Change the form variables e.g. price[x] -> price[rowCount] */
    var inputs = newRow.getElementsByTagName("input");
    var selects = newRow.getElementsByTagName("select");
    var spans = newRow.getElementsByTagName("span");

    for (i = 0; i < spans.length; i++) {
        spans.item(i).innerText = 0;
    }

    for (i = 0; i < inputs.length + selects.length; i++) {
        element = (i < inputs.length) ? inputs.item(i) : selects.item(i - inputs.length);
        s = null;
        s = element.getAttribute("name");
        if (s == null)
            continue;
        t = s.split("[");
        if (t.length < 2)
            continue;
        s = t[0] + "[" + t[1] + "[" + rowCount.toString() + "]";
        element.setAttribute("name", s);
        element.value = "";
        element.checked = "";
        element.selected = "";
    }
    //(parseInt(newRow.cells[0].innerHTML.slice(0, -4))+2) + " min";
    /* Add the newly-created row to the table */
    var x = newRow.cells[0].innerHTML;
    var y = x.split("-");
    y[1] = y[1].slice(0, -4);
    newRow.cells[0].innerHTML = (parseInt(y[0]) + 2) + "-" + (parseInt(y[1]) + 2) + " min";
    templateRow.parentNode.appendChild(newRow);

    // Add 2 mins to #maxTime
    var maxTime = document.getElementById('maxTime');
    var maxTimeValue = parseInt(maxTime.value) + 2;
    maxTime.value = maxTimeValue;
    return true;
}
