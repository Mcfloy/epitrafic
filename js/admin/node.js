function add_node() {
	var message = $('#node_add_message').val();
	$.ajax({
		url: "/views/admin/node.php",
		method: "POST",
		dataType: 'html',
		data: "action=add&message=" + encodeURIComponent(message)
	}).done(function (data) {
		$(".col-centered").prepend(data);
		$("#node_add_message").val("");
	});
}

function update_node(id) {
	var message = $('#panel_node_input_' + id).val();
	$.ajax({
		url: "/views/admin/node.php",
		method: "POST",
		dataType: 'html',
		data: "action=edit&id=" + id + "&message=" + encodeURIComponent(message)
	}).done(function (data) {
		$(".col-centered").prepend(data);
	});
}

function delete_node(id) {
	$.ajax({
		url: "/views/admin/node.php",
		method: "POST",
		dataType: 'html',
		data: "action=delete&id=" + id
	}).done(function (data) {
		$(".col-centered").prepend(data);
		$("#panel_node_" + id).remove();
	});
}