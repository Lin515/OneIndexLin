// 右侧小菜单
var inst1 = new mdui.Fab('#myFab');
$(function() {
	$('.file a').each(function(){
		$(this).on('click', function () {
			var form = $('<form target=_blank method=post></form>').attr('action', $(this).attr('href')).get(0);
			$(document.body).append(form);
			form.submit();
			$(form).remove();
			return false;
		});
	});
	$('.icon-sort').on('click', function () {
        let sort_type = $(this).attr("data-sort"), sort_order = $(this).attr("data-order");
        let sort_order_to = (sort_order === "less") ? "more" : "less";
        $('li[data-sort]').sortElements(function (a, b) {
            let data_a = $(a).attr("data-sort-" + sort_type), data_b = $(b).attr("data-sort-" + sort_type);
            let rt = data_a.localeCompare(data_b, undefined, {numeric: true});
            return (sort_order === "more") ? 0-rt : rt;
        });
        $(this).attr("data-order", sort_order_to).text("expand_" + sort_order_to);
    });
});

// 切换为缩略图显示
function thumb(){
	if ($('#format_list').text() == "apps") {
		$('#format_list').text("format_list_bulleted");
		$('.nexmoe-item').removeClass('thumb');
		$('.mdui-checkbox').show();
		$('.nexmoe-item .mdui-icon').show();
		$('.nexmoe-item .mdui-list-item').css("background","");
	} else {
		$('#format_list').text("apps");
		$('.nexmoe-item').addClass('thumb');
        $('.mdui-checkbox').hide();
		$('.mdui-col-xs-12 i.mdui-icon').each(function(){
			if($(this).text() == "image"){
				var href = $(this).parent().parent().attr('href');
				var thumb =(href.indexOf('?') == -1)?'?t=220':'&t=220';
				$(this).hide();
				$(this).parent().parent().parent().css("background","url("+href+thumb+")  no-repeat center top");
				$(this).parent().parent().parent().css("background-size","cover");
			}
		});
	}
}

// 复制分享链接到剪切板
check_val = [];
var inst4 = new mdui.Dialog("#share");
var clipboard = new ClipboardJS(document.getElementById("sharebtn"), { text: function(trigger) {
    if (0 == check_val.length)
    {
        mdui.alert("你尚未选中任何文件。");
        return ""; // 返回空不会产生复制操作
    }
    var textarea_value = new Array();
    for (var i=0;i<check_val.length;i++){
        textarea_value[i] = window.location.protocol+"//"+window.location.host+document.getElementById(check_val[i]).getElementsByTagName("a")[0].getAttribute("href");
    }
    return textarea_value.join("\r\n");
}});
clipboard.on('success', function(e) {
    mdui.alert("以下链接已复制到剪切板：\n" + e.text);
});
clipboard.on('error', function(e) {
    document.getElementById("sharelinks").value = e.text;
    inst4.open();
});

// 筛选文件
function FilterChange() {
	var filterKey = document.getElementById("filteredit").value.toUpperCase();
	var dom_items = document.getElementsByClassName("filter");

	for(var i=0; i<dom_items.length; i++) {
		var name = dom_items[i].getAttribute("data-sort-name");
		if(name!=null && name.toUpperCase().indexOf(filterKey)==-1)
			dom_items[i].style.display = "none";
		else
			dom_items[i].style.display = "";
	}
}
$("#filteredit").on("change keyup",FilterChange);

// 文件选中某个文件后
function onClickHander(){
    checkitems = document.getElementsByName("itemid");
    check_val = [];
    for (k in checkitems) {
        if (checkitems[k].checked) check_val.push(checkitems[k].value);
    }
}

// 选中所有文件
function checkall(){
    var checkall = document.getElementById("checkall");
    var itemsbox = document.getElementsByName("itemid");
	var dom_items = document.getElementsByClassName("filter");

    if (checkall.checked == false) {
        for (var i = 0; i < itemsbox.length; i++) {
			if (dom_items[i].style.display == "") // 只对没有被隐藏的项目进行操作
            	itemsbox[i].checked = false;
        }
    } else {
        for (var i = 0; i < itemsbox.length; i++) {
			if (dom_items[i].style.display == "")
            	itemsbox[i].checked = true;
        }
    }
    onClickHander();
}