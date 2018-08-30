var show_page = function(page) {
	$("#transferGrid").yxgrid("reload");
};
$(function() {
	$("#transferGrid").yxgrid({
		model : 'hr_transfer_transfer',
		param : {
			'userNo' : $('#userNo').val()
		},
		title : '调动记录',
		showcheckbox : false,
		isAddAction : false,
		isEditAction : false,
		isDelAction : false,
		isViewAction : false,
		isOpButton:false,
		bodyAlign:'center',
		//列信息
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		},  {
			name : 'formCode',
			display : '单据编号',
			sortable : true,
			width:120,
			process : function(v,row){
				return "<a href='#' onclick='location=\"?model=hr_transfer_transfer&action=toViewJobTran&id=" + row.id +"\"'>" + v + "</a>";
			}
		}, {
			name : 'userNo',
			display : '员工编号',
			width:80,
			sortable : true
		},{
			name : 'userName',
			display : '员工姓名',
			sortable : true,
			width : 70
		}, {
			name : 'stateC',
			display : '单据状态',
			width : 70
		}, {
			name : 'ExaStatus',
			display : '审批状态',
			sortable : true,
			width : 70
		}, {
			name : 'transferTypeName',
			display : '调动类型',
			sortable : true,
			width : 200
		}, {
			name : 'entryDate',
			display : '入职日期',
			sortable : true,
			width : 80
		}, {
			name : 'applyDate',
			display : '申请日期',
			sortable : true,
			width : 80
		}, {
			name : 'preUnitTypeName',
			display : '调动前单位',
			sortable : true,
			hide : true
		}, {
			name : 'preUnitName',
			display : '调动前公司',
			sortable : true
		}, {
			name : 'afterUnitTypeName',
			display : '调动后单位类型',
			sortable : true,
			hide : true
		}, {
			name : 'afterUnitName',
			display : '调动后公司',
			sortable : true
		}, {
			name : 'preBelongDeptName',
			display : '调动前所属部门',
			sortable : true
		}, {
			name : 'afterBelongDeptName',
			display : '调动后所属部门',
			sortable : true
		}, {
			name : 'preDeptNameS',
			display : '调动前二级部门',
			hide : true
		}, {
			name : 'preDeptNameT',
			display : '调动前三级部门',
			hide : true
		}, {
			name : 'afterDeptNameS',
			display : '调动后二级部门',
			hide : true
		}, {
			name : 'afterDeptNameT',
			display : '调动后三级部门',
			hide : true
		}, {
			name : 'preJobName',
			display : '调动前职位',
			sortable : true
		}, {
			name : 'afterJobName',
			display : '调动后职位',
			sortable : true
		}, {
			name : 'preUseAreaName',
			display : '调动前归属区域',
			sortable : true,
			width : 90
		}, {
			name : 'afterUseAreaName',
			display : '调动后归属区域',
			sortable : true,
			width : 90
		}, {
			name : 'reason',
			display : '调动原因',
			sortable : true,
			align:'left',
			width : 130
		}, {
			name : 'remark',
			display : '备注说明',
			sortable : true,
			hide : true,
			width : 130
		}],
		lockCol:['formCode','userNo','userName'],//锁定的列名
		toViewConfig : {
			action : 'toView',
			formHeight : 500,
			formWidth : 900
		},

		menusEx:[
			{  text:'查看',
			   icon:'view',
			   action:function(row,rows,grid){
			   		if(row){
						 showThickboxWin("?model=hr_transfer_transfer&action=toView&id=" + row.id + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=450&width=800");
			   		}
			   }
			},
			{
			 	text:'修改',
			 	icon:'edit',
			 	showMenuFn:function(row){
			 	   if(row.ExaStatus=="未提交"||row.ExaStatus=="打回"){
			 	   		return true;
			 	   }
			 	   return false;
			 	},
			 	action:function(row){
			 	   if(row){
						location = "?model=hr_transfer_transfer&action=toEditTran&id="  + row.id;
			 	   }
			 	}
			},
			{
			    text:'删除',
			    icon:'delete',
			    showMenuFn:function(row){
			    	if(row.ExaStatus=="未提交"){
			    	   return true;
			    	}
			    	return false;
			    },
			    action:function(row,rows,grid){
			    	if(row){
			    		if(window.confirm("确认要删除?")){
			    		     $.ajax({
			    		         type:"POST",
			    		         url:"?model=hr_transfer_transfer&action=ajaxdeletes",
			    		         data:{
			    		         	id:row.id
			    		         },
			    		         success:function(msg){
			    		            if(msg==1){
			    		                alert('删除成功!');
			    		                show_page();
			    		            }else{
			    		                alert('删除失败!');
			    		                show_page();
			    		            }
			    		         }
			    		     });
			    		}
			    	}
			    }
			}],

		comboEx:[{
			text:'审批状态',
			key:'ExaStatus',
			data:[{
			   text:'未提交',
			   value:'未提交'
			},{
			   text:'部门审批',
			   value:'部门审批'
			},{
			   text:'完成',
			   value:'完成'
			}]
		}],
		/**
		 * 快速搜索
		 */
		searchitems : [{
			display : '员工名称',
			name : 'userNameSearch'
		}, {
			display : '调动前部门',
			name : 'preDeptName'
		}, {
			display : '调动后部门',
			name : 'afterDeptName'
		}]
	});
});