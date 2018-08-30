var show_page = function(page) {
	$("#transferGrid").yxgrid("reload");
};
$(function() {
	$("#transferGrid").yxgrid({
		model : 'hr_transfer_transfer',
		param : {
			'userNo' : $('#userNo').val()
		},
		title : '������¼',
		showcheckbox : false,
		isAddAction : false,
		isEditAction : false,
		isDelAction : false,
		isViewAction : false,
		isOpButton:false,
		bodyAlign:'center',
		//����Ϣ
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		},  {
			name : 'formCode',
			display : '���ݱ��',
			sortable : true,
			width:120,
			process : function(v,row){
				return "<a href='#' onclick='location=\"?model=hr_transfer_transfer&action=toViewJobTran&id=" + row.id +"\"'>" + v + "</a>";
			}
		}, {
			name : 'userNo',
			display : 'Ա�����',
			width:80,
			sortable : true
		},{
			name : 'userName',
			display : 'Ա������',
			sortable : true,
			width : 70
		}, {
			name : 'stateC',
			display : '����״̬',
			width : 70
		}, {
			name : 'ExaStatus',
			display : '����״̬',
			sortable : true,
			width : 70
		}, {
			name : 'transferTypeName',
			display : '��������',
			sortable : true,
			width : 200
		}, {
			name : 'entryDate',
			display : '��ְ����',
			sortable : true,
			width : 80
		}, {
			name : 'applyDate',
			display : '��������',
			sortable : true,
			width : 80
		}, {
			name : 'preUnitTypeName',
			display : '����ǰ��λ',
			sortable : true,
			hide : true
		}, {
			name : 'preUnitName',
			display : '����ǰ��˾',
			sortable : true
		}, {
			name : 'afterUnitTypeName',
			display : '������λ����',
			sortable : true,
			hide : true
		}, {
			name : 'afterUnitName',
			display : '������˾',
			sortable : true
		}, {
			name : 'preBelongDeptName',
			display : '����ǰ��������',
			sortable : true
		}, {
			name : 'afterBelongDeptName',
			display : '��������������',
			sortable : true
		}, {
			name : 'preDeptNameS',
			display : '����ǰ��������',
			hide : true
		}, {
			name : 'preDeptNameT',
			display : '����ǰ��������',
			hide : true
		}, {
			name : 'afterDeptNameS',
			display : '�������������',
			hide : true
		}, {
			name : 'afterDeptNameT',
			display : '��������������',
			hide : true
		}, {
			name : 'preJobName',
			display : '����ǰְλ',
			sortable : true
		}, {
			name : 'afterJobName',
			display : '������ְλ',
			sortable : true
		}, {
			name : 'preUseAreaName',
			display : '����ǰ��������',
			sortable : true,
			width : 90
		}, {
			name : 'afterUseAreaName',
			display : '�������������',
			sortable : true,
			width : 90
		}, {
			name : 'reason',
			display : '����ԭ��',
			sortable : true,
			align:'left',
			width : 130
		}, {
			name : 'remark',
			display : '��ע˵��',
			sortable : true,
			hide : true,
			width : 130
		}],
		lockCol:['formCode','userNo','userName'],//����������
		toViewConfig : {
			action : 'toView',
			formHeight : 500,
			formWidth : 900
		},

		menusEx:[
			{  text:'�鿴',
			   icon:'view',
			   action:function(row,rows,grid){
			   		if(row){
						 showThickboxWin("?model=hr_transfer_transfer&action=toView&id=" + row.id + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=450&width=800");
			   		}
			   }
			},
			{
			 	text:'�޸�',
			 	icon:'edit',
			 	showMenuFn:function(row){
			 	   if(row.ExaStatus=="δ�ύ"||row.ExaStatus=="���"){
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
			    text:'ɾ��',
			    icon:'delete',
			    showMenuFn:function(row){
			    	if(row.ExaStatus=="δ�ύ"){
			    	   return true;
			    	}
			    	return false;
			    },
			    action:function(row,rows,grid){
			    	if(row){
			    		if(window.confirm("ȷ��Ҫɾ��?")){
			    		     $.ajax({
			    		         type:"POST",
			    		         url:"?model=hr_transfer_transfer&action=ajaxdeletes",
			    		         data:{
			    		         	id:row.id
			    		         },
			    		         success:function(msg){
			    		            if(msg==1){
			    		                alert('ɾ���ɹ�!');
			    		                show_page();
			    		            }else{
			    		                alert('ɾ��ʧ��!');
			    		                show_page();
			    		            }
			    		         }
			    		     });
			    		}
			    	}
			    }
			}],

		comboEx:[{
			text:'����״̬',
			key:'ExaStatus',
			data:[{
			   text:'δ�ύ',
			   value:'δ�ύ'
			},{
			   text:'��������',
			   value:'��������'
			},{
			   text:'���',
			   value:'���'
			}]
		}],
		/**
		 * ��������
		 */
		searchitems : [{
			display : 'Ա������',
			name : 'userNameSearch'
		}, {
			display : '����ǰ����',
			name : 'preDeptName'
		}, {
			display : '��������',
			name : 'afterDeptName'
		}]
	});
});