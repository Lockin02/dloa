var show_page = function(page) {
	$("#temppersonGrid").yxgrid("reload");
};
$(function() {
	$("#temppersonGrid").yxgrid({
		model : 'engineering_tempperson_tempperson',
		action : 'myPageJson',
		title : '��Ƹ��Ա��',
		//����Ϣ
		colModel : [{
				display : 'id',
				name : 'id',
				sortable : true,
				hide : true
			}, {
				name : 'personName',
				display : '����',
				sortable : true,
				width : 80,
				process : function(v,row){
					if(row.id == 'noId') return v;
					return "<a href='javascript:void(0)' onclick='showThickboxWin(\"?model=engineering_tempperson_tempperson&action=toView&id=" + row.id + '&skey=' + row.skey_ + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=450&width=800\")'>" + v + "</a>";
				}
			}, {
				name : 'idCardNo',
				display : '���֤',
				sortable : true,
				width : 140
			}, {
				name : 'country',
				display : '����',
				sortable : true,
				hide : true,
				width : 80
			}, {
				name : 'province',
				display : '����(ʡ)',
				sortable : true,
				width : 60
			}, {
				name : 'city',
				display : '����(��)',
				sortable : true,
				width : 60
			}, {
				name : 'address',
				display : '��ͥסַ',
				sortable : true,
				width : 130
			}, {
				name : 'phone',
				display : '�ֻ�',
				sortable : true
			}, {
				name : 'specialty',
				display : 'ר��',
				sortable : true,
				width : 120
			}, {
				name : 'ability',
				display : '����',
				sortable : true,
				width : 120
			}, {
				name : 'allDays',
				display : '�ۼƹ�������',
				sortable : true,
				width : 80
			}, {
				name : 'allMoney',
				display : '�ۼ�֧������',
				sortable : true,
				width : 80,
				process : function(v){
					return moneyFormat2(v);
				},
				hide : true
			}, {
				name : 'createName',
				display : '������',
				sortable : true,
				hide : true
			}, {
				name : 'createTime',
				display : '����ʱ��',
				sortable : true,
				hide : true
			}],
		toAddConfig : {
			formHeight : 450
		},
		toEditConfig : {
			action : 'toEdit',
			formHeight : 450
		},
		toViewConfig : {
			action : 'toView'
		},
		toDelConfig : {
			text : 'ɾ��',
			/**
			 * Ĭ�ϵ��ɾ����ť�����¼�
			 */
			toDelFn : function(p, g) {
				var rows = g.getCheckedRows();
				var key = "";
				for (var i = 0; i < rows.length; i++) {
					if(rows[i].allDays*1 != 0){
						alert('��Ƹ��Ա ['+ rows[i].personName +'] �Ѵ������Ƹ�ü�¼�����ܽ���ɾ������');
						return false;
					}
				}
				if (rows) {
					if (window.confirm("ȷ��Ҫɾ��?")) {
						$.ajax({
							type : "POST",
							url : "?model=" + p.model + "&action="
									+ p.toDelConfig.action
									+ p.toDelConfig.plusUrl,
							data : {
								id : g.getCheckedRowIds().toString(),
								skey : key
							},
							success : function(msg) {
								if(msg == 1){
									alert('ɾ���ɹ�');
									show_page();
								}else if(msg == 0){
									alert('ɾ��ʧ��');
								}
							}
						});
					}
				} else {
					alert('��ѡ��һ�м�¼��');
				}
			}
		},
		searchitems : [{
			display : "����",
			name : 'personNameSearch'
		},{
			display : "���֤",
			name : 'idCardNoSearch'
		},{
			display : "�ֻ���",
			name : 'phoneSearch'
		}]
	});
});