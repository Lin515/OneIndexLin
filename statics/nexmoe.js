$ = mdui.JQ;
$.fn.extend({
    sortElements: function (comparator, getSortable) {
        getSortable = getSortable || function () { return this; };

        var placements = this.map(function () {
            var sortElement = getSortable.call(this),
                parentNode = sortElement.parentNode,
                nextSibling = parentNode.insertBefore(
                    document.createTextNode(''),
                    sortElement.nextSibling
                );

            return function () {
                parentNode.insertBefore(this, nextSibling);
                parentNode.removeChild(nextSibling);
            };
        });

        return [].sort.call(this, comparator).each(function (i) {
            placements[i].call(getSortable.call(this));
        });
    }
});

function downall() {
     let dl_link_list = Array.from(document.querySelectorAll("li a"))
         .map(x => x.href) // 所有list中的链接
         .filter(x => x.slice(-1) != "/"); // 筛选出非文件夹的文件下载链接

     let blob = new Blob([dl_link_list.join("\r\n")], {
         type: 'text/plain'
     }); // 构造Blog对象
     let a = document.createElement('a'); // 伪造一个a对象
     a.href = window.URL.createObjectURL(blob); // 构造href属性为Blob对象生成的链接
     a.download = "folder_download_link.txt"; // 文件名称，你可以根据你的需要构造
     a.click() // 模拟点击
     a.remove();
}

function thumb(){
	if($('#format_list').text() == "apps"){
		$('#format_list').text("format_list_bulleted");
		$('.nexmoe-item').removeClass('thumb');
		$('.nexmoe-item .mdui-icon').show();
		$('.nexmoe-item .mdui-list-item').css("background","");
	}else{
		$('#format_list').text("apps");
		$('.nexmoe-item').addClass('thumb');
		$('.mdui-col-xs-12 i.mdui-icon').each(function(){
			if($(this).text() == "image"){
				var href = $(this).parent().parent().attr('href');
				var thumb =(href.indexOf('?') == -1)?'?t=220':'&t=220';
				$(this).hide();
				$(this).parent().parent().parent().css("background","url("+href+thumb+")  no-repeat center top");
			}
		});
	}

}	


$(function(){
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
var inst1 = new mdui.Fab('#myFab');

//分享链接
var inst4 = new mdui.Dialog('#share');
document.getElementById('sharebtn').addEventListener('click', function () {
    inst4.open();
});
var sharedialog = document.getElementById('share');
sharedialog.addEventListener('open.mdui.dialog', function () {
    var textarea_value=new Array()
    for(var i=0;i<check_val.length;i++){
        textarea_value[i] = window.location.protocol+'//'+window.location.host+document.getElementById(check_val[i]).getElementsByTagName('a')[0].getAttribute('href');
    }
    document.getElementById('sharelinks').value=textarea_value.join('\r\n');
});

//当前页关键词过滤
mdui.JQ('#pagesearch').on('click', function () {
    mdui.prompt('请输入过滤的关键词或后缀',
        function (value) {
			var filterKey = value.toUpperCase();
			var dom_items = document.getElementsByClassName('filter');
			document.getElementById('pending').style.display = "";
			for(var i=0; i<dom_items.length; i++){
				var name = dom_items[i].getAttribute('data-sort-name');
				if(name!=null && name.toUpperCase().indexOf(filterKey)==-1)
					dom_items[i].style.display = "none";
				else
					dom_items[i].style.display = "";
			}
			document.getElementById('pending').style.display = "none";
        },
        function (value) {
        },
        {
            confirmText:'确认',
            cancelText:'取消'
        }
    );
});

function FilterChange() {
	var filterKey = document.getElementById("filteredit").value.toUpperCase();
	var dom_items = document.getElementsByClassName('filter');

	document.getElementById('pending').style.display = "";
	for(var i=0; i<dom_items.length; i++) {
		var name = dom_items[i].getAttribute('data-sort-name');
		if(name!=null && name.toUpperCase().indexOf(filterKey)==-1)
			dom_items[i].style.display = "none";
		else
			dom_items[i].style.display = "";
	}
	document.getElementById('pending').style.display = "none";
}
$("#filteredit").on("change keyup",FilterChange);

//文件选中某个文件后
function onClickHander(){
    checkitems = document.getElementsByName("itemid");
    check_val = [];
    for (k in checkitems) {
        if (checkitems[k].checked) check_val.push(checkitems[k].value);
    }
}
//选中所有文件
function checkall(){
    var checkall = document.getElementById("checkall");
    var itemsbox = document.getElementsByName("itemid");
    if (checkall.checked == false) {
        for (var i = 0; i < itemsbox.length; i++) {
            itemsbox[i].checked = false;
        }
    } else {
        for (var i = 0; i < itemsbox.length; i++) {
            itemsbox[i].checked = true;
        }
    }
    onClickHander();
}