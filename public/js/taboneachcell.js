
var currCell = $('.taboncell td').first();
var editing = false;

// User clicks on a cell
$('.taboncell td').click(function () {
    currCell = $(this);

    var col = $(this).parent().children().index($(this)) + 1;
    var row = $(this).parent().parent().children().index($(this).parent()) + 1;
    // alert('Row: ' + row + ', Column: ' + col + ', Value: ' + currCell.html());

    //   edit();
});


$('table.taboncell').keydown(function (e) {
    //$('ul#example1 li').keydown(function (e) {
    //alert(2);
    var c = "";
    if (e.which == 39) {
        // Right Arrow
        c = currCell.next();
    } else if (e.which == 37) {
        // Left Arrow
        c = currCell.prev();
    } else if (e.which == 38) {
        // Up Arrow
        c = currCell.closest('tr').prev().find('td:eq(' + currCell.index() + ')');
    } else if (e.which == 40) {
        // Down Arrow
        c = currCell.closest('tr').next().find('td:eq(' + currCell.index() + ')');
    } else if (!editing && (e.which == 13 || e.which == 32 || e.which == 113)) {
        // Enter, Spacebar, F2 - edit cell
        e.preventDefault();
        // edit();
    } else if (!editing && (e.which == 9 && !e.shiftKey)) {
        // Tab
        e.preventDefault();
        c = currCell.next();
    } else if (!editing && (e.which == 9 && e.shiftKey)) {
        // Shift + Tab
        e.preventDefault();
        c = currCell.prev();
    }

    // If we didn't hit a boundary, update the current cell
    if (c.length > 0) {
        currCell = c;
        currCell.focus();
    }
});


 
$(".taboncell").on("keydown","td",function(e){
 
	var keyCode = e.keyCode || e.which;
 
		if (keyCode == 13) {
		 
			if($(this).find('a').length>0){
				$(this).find('a').trigger("click");
			}
		}
});
 
