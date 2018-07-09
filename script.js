function insertParam(key, value)
{
    key = encodeURI(key); 
	value = encodeURI(value);
    var kvp = document.location.search.substr(1).split('&');
    var i=kvp.length; 
	var x; 
	while(i--) {
        x = kvp[i].split('=');
        if (x[0]==key) {
            x[1] = value;
            kvp[i] = x.join('=');
            break;
        }
    }
    if(i < 0)
		kvp[kvp.length] = [key,value].join('=');
	document.location.search = kvp.join('&'); 
}
// jQuery
// gets run when document is loaded
$(function() {
	// make update button say delete when any images are selected
	$('#imagesToEdit :input:checkbox').click(function() {
		if($("#imagesToEdit :input:checkbox:checked").length === 0)
			$('#submitButton').prop('name', 'update').prop('value', 'Update');
		else
			$('#submitButton').prop('name', 'delete').prop('value', 'Delete');
	});
	// make search append to url
	$("#submitsearch").click(function() {
		var searchquery = $("#search").val();
		if(searchquery.length > 0)
			insertParam("searchstring", searchquery);
	});
	// make view type glyphicons append to url
	$("#viewbuttonmultiple").click(function() {
		insertParam("viewType", "multiple");
	});
	// make view type glyphicons append to url
	$("#viewbuttonsingle").click(function() {
		insertParam("viewType", "single");
		insert("nav", "next");
	});
});