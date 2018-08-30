
$(function() {
	// 产品清单
	$("#productInfo").yxeditgrid({
		objName : 'borrowreturnDis[product]',
		url:'?model=projectmanagent_borrowreturn_borrowreturnDisequ&action=listJson',
		tableClass : 'form_in_table',
		isAddAndDel : false,
		//type:'view',
		param:{
        	'disposeId' : $("#id").val()
        },
        event : {
			reloadData : function(event, g) {
				var rowCount = g.getCurRowNum();
				for (var i = 0; i < rowCount; i++) {
					var num = $("#productInfo_cmp_executedNum" + i).val();
					$("#productInfo_cmp_number" + i).val(num);
				}
			}
		},
		colModel : [{
			display : 'id',
			name : 'id',
			tclass : 'readOnlyTxtNormal',
			readonly : true,
			type : 'hidden'
		},{
			display : '物料编号',
			name : 'productNo',
			tclass : 'readOnlyTxtNormal',
			readonly : true
		},{
			display : '物料名称',
			name : 'productName',
			tclass : 'readOnlyTxtNormal',
			readonly : true
		}, {
			display : '物料Id',
			name : 'productId',
			type : 'hidden'
		}, {
			display : '待处理数量',
			name : 'disposeNum',
			tclass : 'readOnlyTxtNormal',
			readonly : true,
			isSubmit : true
		}, {
			display : '赔偿金额',
			name : 'money',
			type : 'txt',
			process : function(v){
            	return '<span style="padding:0px 10px 0px 0px;">'+moneyFormat2(v)+'</span>';
            },
            event : {
            	blur : function (){
            		var itemTableObj = $("#productInfo");
            		//获取表格上所有字段
            	    var moneyArr = itemTableObj.yxeditgrid("getCmpByCol", "money");
            	    var allMoney = 0;
            	    if (moneyArr.length > 0) {
            	        //循环
            	    	moneyArr.each(function() {
            	            //accAdd加法
            	        	allMoney = accAdd(allMoney, $(this).val(), 2);
            	        });
            	    }
            	    setMoney("money",allMoney);
            	}
            }
		}, {
			name : 'serialName',
			display : '序列号',
			tclass : 'readOnlyTxtNormal',
			readonly : 'readonly'
		}],
		isAddOneRow:false,
		isAdd : false
	});
	/**
	 * 验证信息
	 */
	validate({
		"money" : {
			required : true
		},
		"money_v" : {
			required : true
		}

	});
});



