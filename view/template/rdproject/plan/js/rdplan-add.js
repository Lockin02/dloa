$().ready(function() {
	$.formValidator.initConfig({
				formid : "form1",
				// autotip: true,
				onerror : function(msg) {
					// alert(msg);
					return false;
				},
				onsuccess : function() {
					if ($("#planDateClose").val() < $("#planDateStart").val()) {
						alert('启动时间不能大于完成时间');
						return false;
					} else {
						return true;
					}
				}
			});

	$("#planName").formValidator({
				onshow : "请填写计划名称",
				oncorrect : "OK"
			}).inputValidator({
				min : 1,
				empty : {
					leftempty : false,
					rightempty : false,
					emptyerror : "两边不能有空符号"
				},
				onerror : "不能为空"
			}); // .defaultPassed();

	$("#planDateStart").formValidator({
				onshow : "请选择计划启动时间",
				onfocus : "请选择日期",
				oncorrect : "你输入的日期合法"
			}).inputValidator({
				min : "1900-01-01",
				max : "2100-01-01",
				type : "date",
				onerror : "日期必须在\"1900-01-01\"和\"2100-01-01\"之间"
			}); // .defaultPassed();

	$("#planDateClose").formValidator({
				onshow : "请选择计划完成时间",
				onfocus : "请选择日期",
				oncorrect : "你输入的日期合法"
			}).inputValidator({
				min : "1900-01-01",
				max : "2100-01-01",
				type : "date",
				onerror : "日期必须在\"1900-01-01\"和\"2100-01-01\"之间"
			}).compareValidator({
				desid : "planDateStart",
				operateor : ">=",
				onerror : "计划完成日期不能小于计划开始日期"
			}); // .defaultPassed();
	setTimeout(function() {
		var projectId = $("#projectId").val();
		filetree = new Ext.ux.tree.MyTree({
			url : '?model=rdproject_uploadfile_uploadfiletype&action=tree&projectId='
					+ projectId + "&parentId=",
			rootId : -1,
			rootVisible : false,
			listeners : {
				click : function(node) {
					uploadfile.addPostParam("typeId", node.id);
					uploadfile.addPostParam("typeName", node.text);
				}
			}
		});
		fileCombo = new Ext.ux.combox.ComboBoxTree({
					emptyText : '请选择附件类型...',
					applyTo : 'uploadfiletype',
					hiddenField : 'uploadfiletypeId',
					tree : filetree
				});
	}, 10)
})