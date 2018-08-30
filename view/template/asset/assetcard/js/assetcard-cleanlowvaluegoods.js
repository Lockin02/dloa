$(function() {
	//初始化
	initList(0);
	//返回顶部按钮
    var bt = $('#toolBackTop');
    $(window).scroll(function() {
        var st = $(window).scrollTop();
        if(st > 30){                        
        	bt.show();                        
        }else{
            bt.hide();
        }
    });
 });

//查询待清理低耐值用品列表
function initList(type){
	var year = $("#year").val();
	var month = $("#month").val();
	var day = $("#day").val();
	//查询时,使用周期验证
	if(type != '0'){
		if(year != '' && !isNum(year)){
			alert("(按年)请输入正整数!");
			$("#year").focus();
			return false;
		}
		if(month != '' && !isNum(month)){
			alert("(按月)请输入正整数!");
			$("#month").focus();
			return false;
		}
		if(day != '' && !isNum(day)){
			alert("(按天)请输入正整数!");
			$("#day").focus();
			return false;
		}
	}
    showLoading(); // 显示加载图标
	var objGrid = $("#assetCardGrid");
    //请求数据
    $.ajax({
        url : '?model=asset_assetcard_assetcard&action=searchCleanLowValueGoods',
        data : {
        	dateType : $("#dateType").val(),
        	year : year,
        	month : month,
        	day : day,
        	assetCode : $("#assetCode").val(),
        	assetName : $("#assetName").val()
        },
        type : 'POST',
        async : false,
        success : function(data){
            if(objGrid.html() != ""){
                objGrid.empty();
            }
            objGrid.html(data);
            hideLoading(); // 隐藏加载图标
        }
    });
}

//显示loading
function showLoading(){
	$("#loading").show();
}

//隐藏loading
function hideLoading(){
	$("#loading").hide();
}

//全选
function checkAll(){
	$("input[type='checkbox']").each(function(){
		$(this).attr("checked",$("#checkAll").attr("checked"));
	});
}

//清空搜索条件
function emptySearch(){
	$("#year").val('');
	$("#month").val('');
	$("#day").val('');
	$("#assetCode").val('');
	$("#assetName").val('');
}

//清理勾选的低值耐用品
function cleanCard(){
	//构建id数组
	var ids = [];
	$("input[type='checkbox']:checked").each(function(){
		id = $(this).val();
		if(id != ""){
			ids.push(id);
		}
	});
	if(ids.length > 0){
		//将id数组转换成以逗号隔开的字符串
		ids = ids.join(",");
		if (confirm('确定要清理所选的低值耐用品？')){
			$.ajax({
				type : 'POST',
				url : '?model=asset_assetcard_assetcard&action=ajaxCleanLowValueGoods',
				data : {
					id : ids
				},
				success : function(data) {
					if (data == 1) {
						alert("清理成功");
					    //重载列表
						initList();
					} else {
						alert("清理失败");
					}
				}
			});
		}
	}else{
		alert("请至少选择一条数据");
	}
}

//查看资产卡片明细
function searchDetail(assetId){
	window.open('?model=asset_assetcard_assetcard&action=init&perm=view&id='
			+ assetId
			+ '&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=900');
}