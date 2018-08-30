$import("Ext.FormPanel");
$import("Ext.form.Hidden");
$import("Ext.Msg");
$import("Ext.data.JsonReader");
$import("Ext.data.Store");
$import("Ext.data.MemoryProxy");
$import("Ext.data.HttpProxy");
$import("Ext.form.VTypes");
$import("Ext.DatePicker");
$import("Ext.form.DateField");

$package("Ext.ux.form");
$import("Ext.ux.form.FieldHelp");
$import("Ext.ux.form.FileUploadField");
$import("Ext.ux.form.MultiSelect");
$import("Ext.ux.form.StaticTextField"); // 静态文本控件
$import("Ext.ux.plugins.RemoteValidator"); // ajax表单控件服务器验证插件

Ext.namespace("Ext.ux.form");
/**
 * 通用的表单控件 以下为控件的公用属性
 * 
 * 1.isInForm 是否在表单中 true是 flase否
 * 
 * 2.isInAddForm 是否在增加的表单中
 * 
 * 3.isInEditForm 是否在编辑的表单中
 * 
 * 4.isLoad 是否加载此控件相应的字段
 * 
 * 5.valueId 页面上的值Id
 * 
 * 6.formType 控件类型，默认textfield
 * 
 * 7.type string date datetime
 * 
 * 8.isInView 是否在显示表单中
 * 
 * 9.name 控件名称，以表单的objName+'.'+name命名
 * 
 * 10.hiddenName 控件隐藏域名称
 * 某些控件需要用到，用来存储控件的实际值，如：radioTree下拉单选树，checkboxtree多选树，combogrid下拉表格等
 * 
 * 11.mapping 前台控件匹配后台json数据的格式，一般等于name
 * 
 * 12.anchor 在表单上所占百分比，默认95%，isOneRow==true则为97%
 * 
 * 13.isOneRow 是否占据一行
 * 
 * 14.id 控件id 默认myform.id + "_" + c.name
 * 
 * 15.format type==date默认为Y-m-d type==datetime默认为Y-m-d H:i:s
 * 
 * 16.isAuditField 是否审核字段 true且不是审核表单form上isAudit==false，则该控件隐藏
 * 
 * 17.viewFormType 查看表单时的控件类型，默认查看表单的控件类型是statictextfield
 * 
 * 18.isLoad 表单初始化的时候是否从后台加载此属性值
 * 
 * @class Ext.ux.form.MyForm
 * @extends Ext.FormPanel
 */
Ext.ux.form.MyForm = Ext.extend(Ext.FormPanel, {
	/**
	 * 表单label宽度
	 * 
	 * @type Number
	 */
	labelWidth : 80,
	/**
	 * 表单控件label默认局右
	 * 
	 * @type String
	 */
	labelAlign : 'right',
	/**
	 * 是否自动滚动
	 * 
	 * @type Boolean
	 */
	autoScroll : true,
	/**
	 * 表单背景透明
	 * 
	 * @type Boolean
	 */
	frame : true,
	/**
	 * 表单边框
	 * 
	 * @type Boolean
	 */
	border : false,
	/**
	 * 是否显示表单按钮，false则表单所有按钮不显示
	 * 
	 * @type Boolean
	 */
	isButton : true,
	/**
	 * 是否显示保存按钮
	 * 
	 * @type Boolean
	 */
	isSave : true,
	/**
	 * 是否显示重置按钮
	 * 
	 * @type Boolean
	 */
	isReset : true,
	/**
	 * 是否显示返回按钮
	 * 
	 * @type Boolean
	 */
	isFormReturn : false,
	/**
	 * 是否显示关闭按钮
	 * 
	 * @type Boolean
	 */
	isClose : true,
	/**
	 * 保存时是否执行关闭窗口事件
	 * 
	 * @type Boolean
	 */
	isSaveClose : true,
	/**
	 * 按钮在表单上的位置,3.0 默认是right 2.x默认是center
	 * 
	 * @type String
	 */
	buttonAlign : 'center',
	/**
	 * 表单自定义按钮 排序按照buttonOrder顺序
	 * 
	 * @type
	 */
	formButtons : [],
	/**
	 * 保存表单url，如果为空，保存表单使用默认url
	 * 
	 * @type String
	 */
	newUrl : '',
	/**
	 * 返回url
	 * 
	 * @type String
	 */
	returnUrl : '',
	/**
	 * 是否上传表单
	 * 
	 * @type Boolean
	 */
	fileUpload : false,
	/**
	 * 表单默认高跟宽
	 * 
	 * @type Number
	 */
	// height : 400,
	/**
	 * 放置非inpu元素用于效验
	 * 
	 * @type Array
	 */
	componentArray : [],
	/**
	 * 是否查看表单，是则表单上所有控件都为静态文本
	 * 
	 * @type Boolean
	 */
	isViewForm : false,
	/**
	 * 默认表单列数
	 * 
	 * @type Number
	 */
	formCol : 1,
	/**
	 * 表单数据结构
	 * 
	 * @type Array
	 */
	structure : [],
	/**
	 * 是否审核表单（单级），如果false则标示为isAuditField的控件设置为隐藏
	 * 
	 * @type Boolean
	 */
	isAudit : false,
	/**
	 * 初始化组件
	 */
	initComponent : function() {
		var mygrid = this.myGrid ? this.myGrid : this;
		mygrid.radioText = mygrid.radioText ? mygrid.radioText : this.radioText;
		mygrid.radioText2 = mygrid.radioText2
				? mygrid.radioText2
				: this.radioText2;
		this.initStructure();
		// 通过initialConfig来初始化BasicForm
		// this.initialConfig.fileUpload = true;
		this.initialConfig.fileUpload = mygrid.fileUpload; // 附件上传

		this.initialConfig.reader = this.reader;// 非常重要的一句话，否则不能加载表单数据
		Ext.ux.form.MyForm.superclass.initComponent.call(this);
		this.end();

	},
	/**
	 * 初始化事件
	 */
	initEvents : function() {
		Ext.ux.form.MyForm.superclass.initEvents.call(this);

	},
	/**
	 * 初始化表单结构
	 * 
	 * @param {}
	 *            id 初始化数据id
	 */
	initStructure : function(id) {
		var passwordId;
		var repasswordId;
		this.componentArray = [];
		// 如果把myGrid作为参数传入，则使用myGrid上的参数
		if (this.myGrid) {
			this.myGrid.myForm = this;// 把myForm赋给myGrid
			var mygrid = this.myGrid;
			this.labelWidth = mygrid.labelWidth;// 表单标签长度
			this.labelAlign = mygrid.labelAlign;// 表单布局
			this.formCol = mygrid.formCol;
			this.structure = mygrid.structure;
			this.isViewForm = mygrid.isViewForm;
			if (this.isViewForm) {
				mygrid.isSave = false;
				mygrid.isReset = false;
			}
			// Ext.copyTo(this,mygrid,"labelWidth,labelAlign,formCol,structure");
			this.width = '100%';
			this.height = mygrid.formHeight ? (mygrid.formHeight == '100%'
					? '100%'
					: mygrid.formHeight - 30) : '';
			// this.isAudit = mygrid.isAudit;
			this.autoHeight = mygrid.formAutoHeight;

		} else {
			var mygrid = this;
		}
		var myform = this;
		var readerArr = [];// 表单数据解析器数组
		var objName = mygrid.objName;
		function doFormItems(structure, formCol) {
			var oField = [];// 表单控件数组
			// ========== 初始化字段信息 开始==============
			for (var i = 0, l = structure.length; i < l; i++) {
				var c = structure[i];
				var isInForm = c.isInForm == false ? false : true;
				if (Ext.isEmpty(mygrid.initId)) {
					if (c.isInAddForm == false)
						isInForm = false;
				} else {
					c.isLoad == c.isLoad == false ? false : true;
					if (c.isInEditForm == false)
						isInForm = false;
				}
				if (c.valueId && Ext.fly(c.valueId)) {
					c.value = Ext.fly(c.valueId).getValue();
				}
				c.formType = c.formType || 'textfield'; // 默认类型为textfield
				if (c.formType == 'datefield' || c.type == 'datetime') {
					c.type = 'date';
				}
				// c.isInForm = c.isInForm == false ? c.isInForm : true;
				var isInView = c.isInView == true ? true : false;
				if (c.isParentCmp)// 当表单从树的节点中创建，设置是否自动加载节点表单的父节点控件
					mygrid.parentCmpId = c.name;
				if (isInForm || isInView) {
					if (c.hiddenName) {
						readerArr.push({
									name : objName + '.' + c.hiddenName,
									mapping : c.hiddenName
								});
					}
					if (c.name) {
						readerArr.push({
									name : objName + '.' + c.name,
									type : c.type,
									mapping : c.mapping ? c.mapping : c.name,
									dateFormat : c.type == 'date'
											|| c.type == 'datetime'
											? 'Y-m-d H:i:s'
											: ''
								});
					}

					c.anchor = c.anchor ? c.anchor : (c.isOneRow
							? '97%'
							: '95%');

					// if (!mygrid.isView) {
					var fieldId = myform.id + "_" + c.name;
					c.format = c.format ? c.format : (c.type == 'date'
							? 'Y-m-d'
							: (c.type == 'datetime' ? 'Y-m-d H i s' : ''));
					// 查看表单且不为审核控件
					if (mygrid.isViewForm == true && c.isAuditField != true
							&& c.formType != 'hidden'
							&& c.formType != 'editgrid'
							&& c.formType != 'fieldset'
							&& c.formType != 'upload'
							&& c.formType != 'panelForm') {
						c.formType = c.viewFormType
								? c.viewFormType
								: "statictextfield";
					} else if (c.isAuditField == true)// 如果是审核控件，并且不是审核表单，则审核控件隐藏
					{
						if (!mygrid.isAudit) {
							c.formType = "statictextfield";
							c.isLoad = true;
						} else if (c.auditValue) {// 审核时候的默认值
							c.value = c.auditValue;
						}
					}
					/**
					 * 开始进行表单控件类型判断
					 */
					switch (c.formType) {
						/**
						 * 文本输入控件
						 */
						case 'textfield' :
							oField.push({
								xtype : 'textfield',
								id : c.id ? c.id : fieldId,
								name : objName + '.' + c.name,
								fieldLabel : c.header,
								anchor : c.anchor,
								vtype : c.vtype ? c.vtype : '',// 默认有alpha字母，alphanum字母数字，email,url
								listeners : c.listeners,
								allowBlank : c.required ? false : true,
								readOnly : c.readOnly,
								maxLength : c.maxLength ? c.maxLength : 50,
								isOneRow : c.isOneRow,
								value : c.value,
								hidden : c.formHidden,// 传入formHidden隐藏字段，也隐藏字段标签
								hideLabel : c.formHidden,
								disabled : c.disabled,
								isLoad : c.isLoad
									// 是否加载此字段数据，flase则表单在load的时候不加载此字段
								});
							break;
						/**
						 * 数字文本控件
						 */
						case 'numberfield' :
							oField.push({
										xtype : 'numberfield',
										id : c.id ? c.id : fieldId,
										name : objName + '.' + c.name,
										fieldLabel : c.header,
										anchor : c.anchor,
										listeners : c.listeners,
										allowBlank : c.required ? false : true,
										maxLength : c.maxLength
												? c.maxLength
												: 12,
										isOneRow : c.isOneRow,
										value : c.value,
										hidden : c.formHidden,
										hideLabel : c.formHidden,
										readOnly : c.readOnly,
										disabled : c.disabled
									});
							break;
						/**
						 * 大文本框控件
						 */
						case 'textarea' :
							oField.push({
										xtype : 'textarea',
										id : c.id ? c.id : fieldId,
										name : objName + '.' + c.name,
										fieldLabel : c.header,
										height : c.height,
										anchor : c.anchor,
										allowBlank : c.required ? false : true,
										maxLength : c.maxLength
												? c.maxLength
												: 300,
										isOneRow : c.isOneRow,
										// grow : c.grow ? false :
										// true,//textarea是否自动高度
										value : c.value,
										hidden : c.formHidden,
										hideLabel : c.formHidden,
										readOnly : c.readOnly,
										disabled : c.disabled
									});
							break;
						/**
						 * 密码文本框控件
						 */
						case 'password' :
							passwordId = c.id ? c.id : fieldId;
							oField.push({
										xtype : 'textfield',
										id : c.name,
										name : objName + '.' + c.name,
										fieldLabel : c.header,
										anchor : c.anchor,
										inputType : 'password',
										allowBlank : c.required ? false : true,
										isOneRow : c.isOneRow,
										hidden : c.formHidden,
										hideLabel : c.formHidden
									});
							break;
						/**
						 * 重复密码文本框控件
						 */
						case 'repassword' :
							repasswordId = c.id ? c.id : fieldId;
							oField.push({
										xtype : 'textfield',
										id : c.id ? c.id : fieldId,
										name : c.name,
										fieldLabel : c.header,
										anchor : c.anchor,
										inputType : 'password',
										vtype : 'password',// 验证函数
										initialPassField : passwordId,
										allowBlank : c.required ? false : true,
										isOneRow : c.isOneRow,
										hidden : c.formHidden,
										hideLabel : c.formHidden
									});
							break;
						/**
						 * Ext自带编辑器控件
						 */
						case 'htmleditor' :
							oField.push({
										xtype : 'htmleditor',
										id : c.id ? c.id : fieldId,
										name : objName + '.' + c.name,
										fieldLabel : c.header,
										anchor : c.anchor,
										allowBlank : c.required ? false : true,
										isOneRow : c.isOneRow,
										hidden : c.formHidden,
										hideLabel : c.formHidden,
										disabled : c.disabled
									});
							break;
						/**
						 * fckeditor编辑器控件
						 */
						case 'fckeditor' :
							mygrid.fckeditorId = c.id ? c.id : fieldId;
							oField.push({
								xtype : "textarea",
								name : objName + '.' + c.name,
								id : mygrid.fckeditorId,
								fieldLabel : c.header,
								listeners : {
									"render" : function(f) {
										mygrid.fckEditor = new FCKeditor(mygrid.fckeditorId);
										mygrid.fckEditor.Height = 400;
										mygrid.fckEditor.Width = 650;
										mygrid.fckEditor.ToolbarSet = "CRMToolbar";
										// fckEditor.BasePath =
										// "crm/common/js/myext/common/fckeditor/";
										// fckEditor.Config['CustomConfigurationsPath']
										// =
										// "/crm/crm/common/js/myext/common/fckeditor/fckconfig.js"
										mygrid.fckEditor.ReplaceTextarea();
									}
								},
								isOneRow : c.isOneRow
							});
							break;
						/**
						 * 日期控件
						 */
						case 'datefield' :
							oField.push({
								xtype : 'datefield',
								id : c.id ? c.id : fieldId,
								name : objName + '.' + c.name,
								fieldLabel : c.header,
								value : c.valueId ? new Date(c.value) : c.value,
								maxValue : c.maxValue,
								minValue : c.minValue,
								format : c.format,
								anchor : c.anchor,
								allowBlank : c.required ? false : true,
								isOneRow : c.isOneRow,
								disabledDays : c.disabledDays,
								vtype : c.vtype,
								startDateField : c.startDateField ? objName
										+ '.' + c.startDateField : '',
								endDateField : c.endDateField ? objName + '.'
										+ c.endDateField : '',
								listeners : c.listeners,
								hidden : c.formHidden,
								hideLabel : c.formHidden,
								readOnly : c.readOnly,
								disabled : c.disabled,
								plugins : [Class
										.forName("Ext.ux.form.DateFieldPlus")
										.newInstance('week')]
							});
							break;
						/**
						 * 日期时间控件
						 */
						case 'datetimefield' :
							Class.forName("Ext.ux.form.DateTimeField");
							oField.push({
								xtype : 'datetimefield',
								id : c.id ? c.id : fieldId,
								name : objName + '.' + c.name,
								fieldLabel : c.header,
								value : c.valueId ? new Date(c.value) : c.value,
								anchor : c.anchor,
								allowBlank : c.required ? false : true,
								isOneRow : c.isOneRow,
								listeners : c.listeners,
								hidden : c.formHidden,
								hideLabel : c.formHidden,
								readOnly : c.readOnly,
								disabled : c.disabled
							});
							break;
						/**
						 * 时间控件
						 */
						case 'timefield' :
							oField.push({
										xtype : 'timefield',
										id : c.id ? c.id : fieldId,
										name : objName + '.' + c.name,
										fieldLabel : c.header,
										value : c.value,
										format : c.format ? c.format : 'H:i',
										invalidText : "{0} 不是一个有效的时间！",
										minText : "时间必须在 {0}之后！",
										maxText : "时间必须在{0}之前！",
										minValue : c.minValue
												? c.minValue
												: '6:00',
										maxValue : c.maxValue
												? c.maxValue
												: '24:00',
										anchor : c.anchor,
										allowBlank : c.required ? false : true,
										isOneRow : c.isOneRow,
										hidden : c.formHidden,
										hideLabel : c.formHidden,
										readOnly : c.readOnly,
										vtype : c.vtype,
										listeners : c.listeners,
										id : c.id,
										disabled : c.disabled
									});
							break;
						/**
						 * 上传控件
						 */
						case 'fileuploadfield' :
							Class.forName("Ext.ux.form.FileUploadField");
							oField.push({
										xtype : 'fileuploadfield',
										id : c.id ? c.id : fieldId,
										// name : objName + '.' + c.name,
										name : c.name,
										emptyText : '请选择...',
										buttonText : c.buttonText,
										fieldLabel : c.header,
										anchor : c.anchor,
										fileType : c.fileType,
										isOneRow : c.isOneRow,
										hidden : c.formHidden,
										hideLabel : c.formHidden,
										disabled : c.disabled
									});
							break;
						/**
						 * 多选控件
						 */
						case 'checkbox' :
							oField.push({
								xtype : 'checkbox',
								id : c.id ? c.id : fieldId,
								name : objName + '.' + c.name,
								fieldLabel : c.header,
								inputValue : c.inputValue ? c.inputValue : true,
								checked : c.checked == true ? true : false,
								isOneRow : c.isOneRow,
								hidden : c.formHidden,
								hideLabel : c.formHidden,
								readOnly : c.readOnly,
								disabled : c.disabled
							});
							break;
						/**
						 * 单选控件1 建议使用radioArr
						 */
						case 'radio' :
							var radioText = c.radioText ? c.radioText : ['无效',
									'有效'];
							oField.push({
										fieldLabel : c.header,
										xtype : 'radiogroup',
										columns : 2,
										isOneRow : c.isOneRow,
										listeners : c.listeners,
										items : [{
													boxLabel : radioText[1],
													name : objName + '.'
															+ c.name,
													inputValue : 1,
													checked : c.value == 1
												}, {
													boxLabel : radioText[0],
													name : objName + '.'
															+ c.name,
													inputValue : 0,
													checked : c.value == 0
												}],
										hidden : c.formHidden,
										hideLabel : c.formHidden,
										readOnly : c.readOnly,
										disabled : c.disabled

									});
							break;
						/**
						 * 单选控件2 建议使用radioArr
						 */
						case 'radio2' :
							var radioText = c.radioText ? c.radioText : ['否',
									'是'];
							oField.push({
										fieldLabel : c.header,
										xtype : 'radiogroup',
										columns : 2,
										isOneRow : c.isOneRow,
										items : [{
													boxLabel : radioText[1],
													name : objName + '.'
															+ c.name,
													inputValue : 1,
													checked : c.value == 1,
													listeners : c.listeners
												}, {
													boxLabel : radioText[0],
													name : objName + '.'
															+ c.name,
													inputValue : 0,
													checked : c.value == 0,
													listeners : c.listeners
												}],
										hidden : c.formHidden,
										hideLabel : c.formHidden,
										readOnly : c.readOnly,
										disabled : c.disabled

									});
							break;
						/**
						 * 单选控件
						 */
						case 'radioArr' :
							var radioArr = [];
							for (var u = 0, t = c.fobj.length; u < t; u++) {
								radioArr.push({
											boxLabel : c.fobj[u].dataName,
											inputValue : c.fobj[u].dataCode,
											name : objName + '.' + c.name,
											listeners : c.fobj[u].listeners,
											checked : c.fobj[u].checked
													? c.fobj[u].checked
													: false
										});
							}
							oField.push({
										fieldLabel : c.header,
										xtype : 'radiogroup',
										columns : c.fobj.length,
										isOneRow : c.isOneRow,
										items : radioArr,
										hidden : c.formHidden,
										hideLabel : c.formHidden,
										readOnly : c.readOnly,
										// listeners : c.listeners,
										disabled : c.disabled
									});
							break;
						/**
						 * 隐藏域控件
						 */
						case 'hidden' :
							var hiddenName = objName + '.' + c.name;
							oField.push({
										xtype : 'hidden',
										id : c.id ? c.id : fieldId,
										name : hiddenName,
										value : c.value ? c.value : null
									});
							break;
						/**
						 * 下拉单选控件，只提交真实值
						 */
						case 'combo' :
							// 初始化下拉列表数据
							c.valueField = c.valueField || 'dataCode';
							c.displayField = c.displayField || 'dataName';
							c.tips = c.tips || 'tips';
							var ds = myform.initCombo(c);
							oField.push({
										xtype : 'combo',
										id : c.id ? c.id : fieldId,
										// name : objName + '.' + c.name,
										hiddenName : objName + '.' + c.name,
										fieldLabel : c.header,
										anchor : c.anchor,
										// lazyInit : false, //3.0影响默认宽度
										isOneRow : c.isOneRow,
										// valueNotFoundText : rootText,
										store : ds,
										value : c.value,
										tpl : '<tpl for="."><div  ext:qtip="{'
												+ c.tips
												+ '}" class="x-combo-list-item">{'
												+ c.displayField
												+ '}</div></tpl>',
										displayField : c.displayField,
										valueField : c.valueField,
										typeAhead : true,// 输入过程中是否自动匹配剩余部分文本
										triggerAction : 'all',// 单击触发按钮时执行的默认操作
										selectOnFocus : true,// 当获得焦点时立刻选择一个已经存在的表项
										forceSelection : c.forceSelection
												? false
												: true,// 输入值是否为待选列表中存在的值
										pageSize : c.pageSize ? c.pageSize : '',
										queryParam : 'searchValue',// 这里设置会把此combo输入的值作为参数传入action,默认是query
										minChars : 1,// 自动选择前需输入最小字符数量
										resizable : true,
										allowBlank : c.required ? false : true,
										listeners : c.listeners,
										hidden : c.formHidden,
										hideLabel : c.formHidden,
										readOnly : c.readOnly,
										disabled : c.disabled,
										isLoad : c.isLoad
									});
							break;
						/**
						 * 下拉单选控件，同时提交显示值跟真实值
						 */
						case 'comboEx' :
							Class.forName("Ext.ux.combox.MyComboBox");
							// 初始化下拉列表数据 支持冗余字段
							c.valueField = c.valueField || 'dataCode';
							c.displayField = c.displayField || 'dataName';
							c.tips = c.tips || 'tips';
							var ds = myform.initCombo(c);
							oField.push({
										xtype : 'mycombo',
										id : c.id ? c.id : fieldId,
										name : objName + '.' + c.name,
										hiddenName : c.hiddenName ? objName
												+ '.' + c.hiddenName : null,
										fieldLabel : c.header,
										anchor : c.anchor,
										// lazyInit : false, //3.0影响默认宽度
										isOneRow : c.isOneRow,
										store : ds,
										value : c.value,
										tpl : '<tpl for="."><div  ext:qtip="{'
												+ c.tips
												+ '}" class="x-combo-list-item">{'
												+ c.displayField
												+ '}</div></tpl>',
										displayField : c.displayField,
										valueField : c.valueField,
										typeAhead : true,// 输入过程中是否自动匹配剩余部分文本
										triggerAction : 'all',// 单击触发按钮时执行的默认操作
										selectOnFocus : true,// 当获得焦点时立刻选择一个已经存在的表项
										forceSelection : c.forceSelection
												? false
												: true,// 输入值是否为待选列表中存在的值
										pageSize : c.pageSize ? c.pageSize : '',
										queryParam : 'searchValue',// 这里设置会把此combo输入的值作为参数传入action,默认是query
										minChars : 1,// 自动选择前需输入最小字符数量
										resizable : true,
										allowBlank : c.required ? false : true,
										listeners : c.listeners,
										hidden : c.formHidden,
										hideLabel : c.formHidden,
										readOnly : c.readOnly,
										disabled : c.disabled,
										isLoad : c.isLoad
									});
							break;
						/**
						 * 下拉多选控件
						 */
						case 'multiSelect' :
							Class.forName("Ext.ux.form.MultiSelect")
							c.valueField = c.valueField || 'code';
							c.displayField = c.displayField || 'name';
							c.tips = c.tips || 'tips';
							var ds = myform.initCombo(c);
							oField.push({
										xtype : 'multiSelect',
										id : c.id ? c.id : fieldId,
										hiddenName : objName + '.' + c.name,
										fieldLabel : c.header,
										anchor : c.anchor,
										store : ds,
										value : c.value,
										displayField : c.displayField,
										valueField : c.valueField,
										tips : c.tips,
										triggerAction : 'all',
										resizable : true,
										// mode:'local',
										isOneRow : c.isOneRow,
										allowBlank : c.required ? false : true,
										listeners : c.listeners
									});
							break;
						/**
						 * 下拉单选树控件
						 */
						case 'radioTree' :
							Class.forName("Ext.ux.combox.ComboBoxTree");
							var tree = Class.forName("Ext.ux.tree.MyTree")
									.newInstance({
										url : c.url,
										rootVisible : false,
										rootText : c.rootText
												? c.rootText
												: mygrid.boName + '根',
										parentFieldType : c.parentFieldType
												? c.parentFieldType
												: '',
										listeners : c.listeners
									});
							if (c.hiddenName)
								oField.push(new Ext.form.Hidden({
											name : objName + '.' + c.hiddenName,
											disabled : c.disabled,
											hideTag : true,
											value : c.hiddenValue
										}));
							oField.push({
										xtype : 'combotree',
										fieldLabel : c.header,
										anchor : c.anchor,
										id : c.id ? c.id : fieldId,
										name : objName + '.' + c.name,
										isOneRow : c.isOneRow,
										// hiddenName : objName + '.' +
										// c.hiddenName,
										hiddenField : c.hiddenName
												? oField[oField.length - 1].id
												: null,
										resizable : true,
										listWidth : c.listWidth,
										tree : tree,
										keyUrl : c.keyUrl,
										value : c.value,
										allowBlank : c.required ? false : true,
										listeners : c.listeners,
										hidden : c.formHidden,
										selectNodeModel : c.selModel,
										hideLabel : c.formHidden,
										readOnly : c.readOnly,
										disabled : c.disabled
									});
							break;
						/**
						 * 下拉多选树控件
						 */
						case 'checkTree' :
							Class.forName("Ext.ux.combox.ComboBoxCheckTree");
							var tree = Class.forName("Ext.ux.tree.MyTree")
									.newInstance({
										url : c.url,
										checkModel : c.checkModel
												? c.checkModel
												: 'cascade',
										onlyLeafCheckable : false,
										rootVisible : false,
										rootText : c.rootText
												? c.rootText
												: mygrid.boName + '根',
										parentFieldType : c.parentFieldType
												? c.parentFieldType
												: '',
										listeners : c.listeners,
										keyUrl : c.keyUrl
									});
							if (c.hiddenName)
								oField.push(new Ext.form.Hidden({
											name : objName + '.' + c.hiddenName,
											disabled : c.disabled,
											hideTag : true,
											value : c.hiddenValue
										}));
							oField.push({
										xtype : 'combochecktree',
										fieldLabel : c.header,
										anchor : c.anchor,
										id : c.id ? c.id : fieldId,
										name : objName + '.' + c.name,
										isOneRow : c.isOneRow,
										hiddenField : c.hiddenName
												? oField[oField.length - 1].id
												: null,
										resizable : true,
										listWidth : c.listWidth,
										tree : tree,
										allowBlank : c.required ? false : true,
										listeners : c.listeners,
										selectValueModel : c.selectModel
												? c.selectModel
												: 'leaf',
										hidden : c.formHidden,
										hideLabel : c.formHidden,
										readOnly : c.readOnly,
										disabled : c.disabled,
										value : c.value
									});
							break;
						/**
						 * 下拉表格控件
						 */
						case 'combogrid' :
							Class.forName("Ext.ux.combox.MyGridComboBox");
							c.myGrid.selectType = c.myGrid.selectType
									? c.myGrid.selectType
									: '';
							c.myGrid.isToExcel = false;
							c.myGrid.isToPDF = false;
							c.myGrid.isReturn = false;
							c.myGrid.viewConfig = {
								forceFit : true
							};
							c.myGrid.height = 200;
							if (c.lazyLoad == false) {
								c.myGrid.lazyLoad = false;
								c.myGrid = Class.forName("Ext.ComponentMgr")
										.create(c.myGrid, c.myGrid.xtype);
							} else
								c.myGrid.lazyLoad = true;
							if (!c.myGrid.objName) {// 如果grid没初始化，通过对组件的name来获取objName，这样在加载表单的时候则可以获取到显示值
								c.myGrid.objName = c.name.substring(0, c.name
												.indexOf('.'));
							}
							// c.myGrid.removeListener('rowdblclick',
							// c.myGrid.editFunction);// 屏蔽表格双击编辑事件
							if (c.hiddenName) {
								oField.push(new Ext.form.Hidden({
											name : objName + '.' + c.hiddenName,
											disabled : c.disabled,
											hideTag : true
										}));
							}
							oField.push({
										xtype : 'combogrid',
										fieldLabel : c.header,
										anchor : c.anchor,
										id : c.id ? c.id : fieldId,
										name : objName + '.' + c.name,
										allowBlank : c.required ? false : true,
										myGrid : c.myGrid,
										myForm : myform,
										listeners : c.listeners,
										hiddenFieldId : c.hiddenName
												? oField[oField.length - 1].id
												: null,
										gridName : c.gridName,// 下拉表格显示的属性
										gridValue : c.gridValue,
										isOneRow : c.isOneRow,
										hidden : c.formHidden,
										hideLabel : c.formHidden,
										readOnly : c.readOnly,
										disabled : c.disabled,
										hideTrigger : c.hideTrigger,
										// plugins:[Class.forName("Ext.ux.form.FieldHelp")
										// .newInstance("aaaa")],
										listWidth : c.listWidth
												? c.listWidth
												: '500'
									});
							break;
						/**
						 * 动态编辑表格
						 */
						case 'editgrid' :
							if (c.xtype) {
								c.myForm = myform;
								c = Class.forName("Ext.ComponentMgr").create(c,
										c.xtype);
								myform.componentArray.push(c);
							}

							oField.push(c);
							break;
						case 'grid' :
							if (c.xtype) {
								c.myForm = myform;
								c = Class.forName("Ext.ComponentMgr").create(c,
										c.xtype);
								myform.componentArray.push(c);
							}

							oField.push(c);
							break;
						/**
						 * 对象面板
						 */
						case 'panelForm' :
							if (c.xtype) {
								c = Class.forName("Ext.ComponentMgr").create(c,
										c.xtype);
							}
							oField.push(c);
							break;
						/**
						 * 静态文本域
						 */
						case 'statictextfield' :
							Class.forName("Ext.ux.form.StaticTextField");
							if (c.hiddenName)
								oField.push(new Ext.form.Hidden({
											name : objName + '.' + c.hiddenName,
											hideTag : true,
											value : c.hiddenValue
										}));
							oField.push({
								xtype : 'statictextfield',
								id : c.id ? c.id : fieldId,
								name : objName + '.' + c.name,
								fieldLabel : c.header,
								anchor : c.anchor,
								value : c.value,
								isOneRow : c.isOneRow,
								height : c.height,
								format : c.format ? c.format : c.renderer,
								submitValue : c.submitValue == false
										? false
										: true,
								hidden : c.formHidden,
								hideLabel : c.formHidden
									// 是否提交静态变量值
								});
							break;
						/**
						 * 按钮
						 */
						case 'button' :
							oField.push({
										xtype : 'button',
										id : c.id ? c.id : fieldId,
										text : c.text,
										handler : c.handler
									});
							break;
						/**
						 * 附件上传组件
						 */
						case 'upload' :
							oField.push(new Ext.form.Hidden({
										name : 'fileIds',
										hideTag : true
									}));
							var url = c.url;
							var fileId = oField[oField.length - 1].id;
							var dialog = null;
							var isViewed = c.isView;
							if (mygrid.isViewForm == true) {
								isViewed = mygrid.isViewForm;
							}
							oField.push({
								xtype : 'button',
								text : c.header ? c.header : '上传附件',
								id : objName + '.' + c.name,
								listeners : {
									click : function() {
										if (dialog == null) {
											dialog = new Ext.ux.UploadDialog.Dialog(
													{
														url : url,
														isView : isViewed == true
																? true
																: false,
														// animateTarget :
														// 'south',
														width : 450,
														height : 300,
														minWidth : 450,
														minHeight : 300,
														draggable : true,
														resizable : true,
														reset_on_hide : true,
														allow_close_on_upload : false, // 关闭上传窗口是否仍然上传文件
														upload_autostart : false, // 是否自动上传文件
														hiddenFieldId : fileId,
														base_params : {
															refId : myform.form
																	.findField(objName
																			+ '.id')
																	.getValue()
														}
													});
										}
										dialog.show();
										var refId = myform.form
												.findField(objName + '.id')
												.getValue();
										if (!Ext.isEmpty(refId)) {
											dialog.grid_panel.store.proxy = new Ext.data.HttpProxy(
													{
														url : 'fileupload!getFilesToJson.action?refId='
																+ refId
													});
											dialog.grid_panel.store.reload();
										}
									}
								}
							});
							break;
						/**
						 * 控件块
						 */
						case 'fieldset' :
							oField.push({
										xtype : 'fieldset',
										title : c.title,
										id : c.id,
										collapsible : true,
										autoHeight : true,
										anchor : c.anchor,
										hidden : c.hidden == true
												? true
												: false,
										collapsed : c.collapsed == true
												? true
												: false,
										isOneRow : c.isOneRow
												? c.isOneRow
												: true,
										items : doFormItems(c.items, 2),
										listeners : c.listeners
									});
							break;
					}// switch结束
					var f = oField[oField.length - 1];
					// if (c.formType == 'textfield' || c.formType ==
					// 'combogrid') {
					if (!f.plugins) {
						f.plugins = [];
					}
					/**
					 * 文本框边的提示信息
					 */
					if (!Ext.isEmpty(c.helpText)) {
						f.plugins.push(Class.forName("Ext.ux.form.FieldHelp")
								.newInstance(c.helpText));
					}
					/**
					 * 如果isCheck==true,则进行ajax检测，大多用于后台重复性校验
					 */
					if (c.isCheck == true) {
						f.plugins.push(Ext.ux.plugins.RemoteValidator)
						f.rvOptions = {
							url : c.checkUrl ? c.checkUrl : mygrid.urlAction
									+ 'checkRepeat.action',// 检查重复的url路径
							objName : objName,
							fieldName : c.name,
							checkName : c.checkName ? c.checkName : [],
							checkValue : c.checkValue ? c.checkValue : [],
							fieldId : myform.id + "_id"
						}
					}
					// }
					// 进行公用性属性赋值（待整理）
					f.isUnReset = c.isUnReset;
					f.emptyText = c.emptyText
							? c.emptyText
							: ((c.formType == 'textfield'
									|| c.formType == 'numberfield' || c.formType == 'textarea')
									? ''
									: '请选择...');

					// if (f.name == 'projectInfo.projectName') {
					// alert(f.name + ":" + f.emptyText)
					// }
					// f.id = c.id ? c.id : fieldId;
					// f.name = objName + '.' + c.name;
					// f.fieldLabel = c.header;
					// f.anchor = c.anchor;
					// Ext.applyIf(oField[oField.length - 1], c);
				} else {
					// if (c.isInView != false)
					// oField[oField.length] = {
					// xtype : 'statictextfield',
					// id : c.id ? c.id : fieldId,
					// name : objName + '.' + c.name,
					// fieldLabel : c.header,
					// anchor : c.anchor,
					// value : c.value,
					// renderer : c.viewRenderer
					// ? c.viewRenderer
					// : c.renderer
					// };
				}

			}// for结束

			var fieldArr = []; // 总体变量
			var evenArr = []; // 左变量
			var oddArr = []; // 右变量
			var j = 1;// j用于构建奇偶列函数时遇到单行表单需重新赋值
			for (var i = 0, l = oField.length; i < l; i++) {
				if (formCol == 1 || oField[i].hideTag == true
						|| oField[i].xtype == 'hidden') { // 单列或者隐藏域 直接赋值
					fieldArr.push(oField[i]);
				} else {
					if (oField[i].isOneRow != true) {
						if (j++ % 2 == 0) {
							oddArr.push(oField[i]);
						} else {
							evenArr.push(oField[i]);
						}
					} else {
						fieldArr = pushColumn(evenArr, oddArr, fieldArr); // 设置列对象
						fieldArr.push(oField[i]); // 设置oneRow对象
						// 清空变量重新赋值
						evenArr = [];
						oddArr = [];
						j = 1;
					}
				}
			}
			// 最后一行如果是OneRow则在上面循环已经处理完毕，如果不是则设置列对象处理
			if (!oField[oField.length - 1].isOneRow) {
				fieldArr = pushColumn(evenArr, oddArr, fieldArr); // 设置列对象
			}

			return fieldArr;
		};
		// 设置列对象
		function pushColumn(evenArr, oddArr, fieldArr) {
			var columnField = [];
			if (evenArr.length != 0)
				columnField.push({
							columnWidth : .5,
							layout : 'form',
							items : evenArr
						});
			if (oddArr.length != 0)
				columnField.push({
							columnWidth : .5,
							layout : 'form',
							items : oddArr
						});
			if (columnField.length != 0)
				fieldArr.push({
							layout : 'column',
							items : columnField
						});
			return fieldArr;
		}

		if (this.structure)
			this.items = doFormItems(this.structure, this.formCol);// 构建表单控件
		/**
		 * 这个属性决定了load和submit中对数据的处理，collection必须是一个集合类型，json格式应该是[]包含的一个数组
		 */
		this.reader = new Ext.data.JsonReader({
					root : 'collection',
					successProperty : 'success'
				}, readerArr);
		// ------------- 初始化表单信息 结束------------

		// this.items = fieldArr;// 构建表单控件
		if (this.isButton) {
			this.buttons = [];
			// 构建表单按钮
			if (mygrid.isView)
				this.buttons = [{
							text : '编辑',
							name : 'edit',
							iconCls : 'save',
							handler : function() {
								// 这里应该根据父组件类型去判断执行什么操作，如果是window，则关闭，如果是panel则更新
								myform.closeWin();
								mygrid.doEdit(mygrid.initId);
								if (!myform.ownerCt.maximizable) {
									// 更新myform内容
									// myform.load();
								}
							}
						}];
			if (mygrid.isSave)
				this.buttons.push({
							text : '保存',
							name : 'save',
							iconCls : 'save',
							buttonOrder : 1,// 按钮顺序
							handler : function() {
								myform.doSubmitForm()
							}
						});
			/**
			 * 单级审核按钮
			 */
			if (mygrid.isAudit)
				this.buttons.push({
							text : '确认审核',
							name : 'audit',
							iconCls : 'save',
							buttonOrder : 2,
							handler : function() {
								myform.doSubmitAuditForm()
							}
						});
			/**
			 * 多级审核工作流提交按钮
			 */
			if (mygrid.topicField || mygrid.topicFieldPlus)
				this.buttons.push({
							text : '提交送审',
							name : 'submitAudit',
							iconCls : 'save',
							buttonOrder : 1,
							handler : function() {
								myform.doSubmitWorkFlowForm();
							}
						});
			if (mygrid.isReset)
				this.buttons.push({
							text : '重置',
							name : 'reset',
							iconCls : 'clean',
							buttonOrder : 997,
							handler : function() {
								if (!Ext.isEmpty(myform.initId)) {
									myform.initForm();
								} else {
									myform.getForm().reset();
								}
								// mygrid.resetFn(myform);
							}
						});
			if (mygrid.isClose)
				this.buttons.push({
							text : '关闭',
							name : 'close',
							iconCls : 'close',
							buttonOrder : 998,
							handler : function() {
								myform.closeWin();
							}
						});
			if (mygrid.isFormReturn) {
				this.buttons.push({
							// iconCls : 'icon-pdf',
							name : 'return',
							tooltip : "返回上一页",
							buttonOrder : 999,
							text : "返回",
							handler : function() {
								if (!Ext.isEmpty(myform.returnUrl)) {
									document.location = myform.returnUrl;
								} else {
									history.go(-1);
								}
							}
						});
			}

			/**
			 * 添加自定义按钮
			 */
			if (!Ext.isEmpty(mygrid.formButtons)) {
				this.buttons = this.buttons.concat(mygrid.formButtons);
			}
			if (!Ext.isEmpty(this.formButtons)) {
				this.buttons = this.buttons.concat(this.formButtons);
			}
			/**
			 * 按钮排序
			 */
			this.buttons.sort(function(x, y) {
						return (x.buttonOrder ? x.buttonOrder : 0)
								- (y.buttonOrder ? y.buttonOrder : 0);
					});
		}
	},
	/**
	 * 从服务器初始化表单数据
	 */
	initForm : function() {
		var mygrid = this.myGrid ? this.myGrid : this;
		var myform = this;
		var id = mygrid.initId;
		myform.initId = id;
		if (!Ext.isEmpty(id)) {
			/**
			 * 初始化表当前执行事件
			 */
			if (mygrid.beforeInitForm)
				mygrid.beforeInitForm(myform);
			var initUrl = mygrid.initUrl ? mygrid.initUrl : mygrid.urlAction
					+ "init.action?" + mygrid.objName + ".id=" + id;
			this.form.load({
				url : initUrl,
				waitTitle : '请稍候',
				waitMsg : '正在加载数据,请稍后......',
				success : function(form, action) {
					/**
					 * 成功初始化表单后执行事件
					 */
					if (mygrid.afterInitForm)
						mygrid.afterInitForm(myform);
					/**
					 * 判断是否有fck编辑器，有把areatext值赋给fck编辑器
					 */
					if (mygrid.fckEditor && typeof(FCKeditorAPI) != 'undefined') {
						mygrid.fckEditor.Value = Ext.getCmp(mygrid.fckeditorId)
								.getValue();
						var editor = FCKeditorAPI
								.GetInstance(mygrid.fckeditorId);
						editor.SetHTML(Ext.getCmp(mygrid.fckeditorId)
								.getValue());

					}
				},
				failure : doFailure

			});
		} else {
			/**
			 * 添加表单后执行事件
			 */
			if (mygrid.afterAddForm)
				mygrid.afterAddForm(myform);
		}
		/**
		 * 添加/编辑表单后执行事件
		 */
		if (mygrid.afterForm)
			mygrid.afterForm(myform);

	},
	/**
	 * 关闭表单窗口
	 */
	closeWin : function() {
		var mygrid = this.myGrid ? this.myGrid : this;
		// if (this.ownerCt.maximizable == true) {// 如果父容器是windows,则关闭
		if (mygrid.isClose) { // update time 09-09-15 swb
			// this.destroy();
			this.ownerCt.close();
			// this.ownerCt.destroy();
			this.destroy(); // 3.0
		}
	},

	/**
	 * 提交表单数据
	 * 
	 * @param {}
	 *            isShowMessage 是否显示提示信息
	 * @return {Boolean}
	 */
	doSubmitForm : function(isShowMessage) {
		isShowMessage = (isShowMessage == false ? false : true);
		var mygrid = this.myGrid ? this.myGrid : this;
		var myform = this;
		var fn = true;
		if (myform.componentArray.length > 0) {// 效验非input元素
			for (var i = 0, l = myform.componentArray.length; i < l; i++) {
				if (!myform.componentArray[i].isValid()) {
					fn = false;
					break;
				}
			}
		}
		if (myform.form.isValid() && fn) {
			if (mygrid.fckeditorId && mygrid.fckeditorId != '') {// 判断是否有fck编辑器，有把值赋给areatext
				var editor = FCKeditorAPI.GetInstance(mygrid.fckeditorId);
				Ext.getCmp(mygrid.fckeditorId).setValue(editor.GetHTML(true));
			}

			if (mygrid.beforeSave) {
				var saveFn = mygrid.beforeSave(myform);
				if (saveFn == false) {
					return false;
				}
			}
			myform.form.submit({
						waitTitle : "请稍候",
						waitMsg : "正在提交表单数据，请稍候.......",
						url : mygrid.newUrl ? mygrid.newUrl : mygrid.urlAction
								+ "save.action",
						params : mygrid.formParams
								? mygrid.formParams
								: myform.formParams,
						success : function(form, action) {
							if (mygrid.store)
								mygrid.getStore().reload();
							if (isShowMessage) {
								Ext.Msg.info({
											message : action.result.message
													? action.result.message
													: '保存所选' + mygrid.boName
															+ '成功！'
										});
							}
							// 执行保存成功后事件
							if (mygrid.afterSave)
								mygrid.afterSave(myform, action.result);
							if (myform.isSaveClose)
								myform.closeWin();
							if (mygrid.selectedNode) {
								var parentNode = mygrid.selectedNode.parentNode;
								if (!mygrid.initId) {// 如果是添加节点
									if (mygrid.selectedNode.attributes.leaf == true) {
										parentNode.reload();
										parentNode.expand(true);
									} else
										mygrid.selectedNode.reload();
								} else {// 如果是修改节点
									parentNode.reload();
									parentNode.expand(true);
								}
							};

						},
						failure : function(form, action) {
							if (isShowMessage) {
								var message = null;
								if (action == null)
									return;
								if (action.message)
									message = action.message;
								else if (action.result.message)
									message = action.result.message;
								Ext.Msg.info({
											success : false,
											message : message
													? message
													: '服务器未响应，请稍后再试！'
										});
							}
							if (mygrid.afterFailSave)
								mygrid.afterFailSave();
						}
					});
		} else {
			Ext.Msg.info({
						message : '保存失败！请检查效验字段',
						success : false
					});
		}

	},

	/**
	 * 提交表单工作流信息
	 * 
	 * @return {Boolean}
	 */
	doSubmitWorkFlowForm : function() {
		var mygrid = this.myGrid ? this.myGrid : this;
		var myform = this;
		var fn = true;
		if (myform.componentArray.length > 0) {// 效验非input元素
			for (var i = 0, l = myform.componentArray.length; i < l; i++) {
				if (!myform.componentArray[i].isValid()) {
					fn = false;
					break;
				}
			}
		}
		if (myform.form.isValid() && fn) {
			if (mygrid.beforeSave) {
				var saveFn = mygrid.beforeSave(myform);
				if (saveFn == false) {
					return false;
				}
			}
			myform.form.submit({
				url : mygrid.newUrl ? mygrid.newUrl : mygrid.urlAction
						+ "save.action",
				waitTitle : "请稍候",
				waitMsg : "正在提交审核信息，请稍候......",
				params : mygrid.formParams
						? mygrid.formParams
						: myform.formParams,
				success : function(form, action) {
					var objId = mygrid.initId;
					var basicForm = myform.form;
					if (Ext.isEmpty(mygrid.initId)) {
						objId = action.result.collection[0].id
					}
					var url = mygrid.urlAction + 'toAudit.action?'
							+ mygrid.objName + '.id=' + objId;
					var topic = "";
					if (mygrid.topicField
							&& basicForm.findField(mygrid.objName + "."
									+ mygrid.topicField)) {
						topic = basicForm.findField(mygrid.objName + "."
								+ mygrid.topicField).getValue();
						if (Ext.isDate(topic)) {
							topic = topic.format('Y-m-d');
						}

					}
					if (mygrid.topicFieldPlus) {
						topic = mygrid.topicFieldPlus + topic;
					}
					// 只有审核状态才对审核控件数据进行提交
					if (mygrid.operType == "1") {
						var auditOption = ""
						if (basicForm
								.findField(mygrid.objName + '.auditOption')) {
							auditOption = basicForm.findField(mygrid.objName
									+ '.auditOption').getValue();
						}
						if (basicForm.findField(mygrid.objName + '.auditState')) {
							var state = basicForm.findField(mygrid.objName
									+ '.auditState').getValue();
							if (state == 0) {
								location.href = 'pendingtask!rollBackWorkitem.action?businessFlowParam.businessId='
										+ objId
										+ '&businessFlowParam.url='
										+ url
										+ '&businessFlowParam.topic='
										+ topic
										+ '&businessFlowParam.auditOption='
										+ auditOption;
								return;
							}
						};
					}
					// 流程结束后执行的service的spring的beanid
					var executeClass = mygrid.objName + "Service";
					// 流程结束后执行的方法:更改审核状态为已审核
					var executeMethod = "saveState";
					var url = 'pendingtask!submitWorkItem.action?businessFlowParam.businessId='
							+ objId
							+ '&businessFlowParam.url='
							+ url
							+ '&businessFlowParam.topic='
							+ topic
							+ '&businessFlowParam.executeClass='
							+ executeClass
							+ '&businessFlowParam.executeMethod='
							+ executeMethod;
					if (!Ext.isEmpty(auditOption)) {
						url += '&businessFlowParam.auditOption=' + auditOption;
					}
					if (mygrid.isToParentUrl == true) {
						parent.location.href = url;
					} else {
						location.href = url;
					}
				},
				failure : doFailure
			});
		} else {
			Ext.Msg.info({
						message : '保存失败！请检查效验字段',
						success : false
					});
		}
	},
	/**
	 * 提交审核信息（单级审核）
	 */
	doSubmitAuditForm : function() {
		var mygrid = this.myGrid ? this.myGrid : this;
		var myform = this;
		myform.form.submit({
					url : mygrid.auditUrl ? mygrid.auditUrl : mygrid.urlAction
							+ "audit.action",
					waitTitle : "请稍候",
					waitMsg : "正在提交审核信息，请稍候。。。。。。",
					success : function(form, action) {
						Ext.Msg.info({
									message : '审核成功！',
									success : true
								});
					}
				})
	},

	/**
	 * 功能：初始化表单combo控件数据
	 * 
	 * @param {}
	 *            c:combo控件
	 * @return {} Store
	 */
	initCombo : function(c) {
		var ds = null;
		if (typeof c.fobj != 'object') {// fobj为静态json数组数据
			var reader = new Ext.data.JsonReader({
						totalProperty : 'totalSize',
						root : 'collection'
					}, [{
								name : c.valueField
							}, {
								name : c.displayField
							}, {
								name : c.tips
							}]);

			ds = new Ext.data.Store({
						proxy : new Ext.data.HttpProxy({
									url : c.url
								}),
						reader : reader
					});

			ds.on('beforeload', function() {
						var para = {
							pagesize : c.pageSize,
							name : c.fobj
						};
						Ext.apply(ds.baseParams, para);
					});

		} else {
			ds = new Ext.data.Store({
						proxy : new Ext.data.MemoryProxy(c.fobj),
						reader : new Ext.data.JsonReader({}, [{// new
									// Ext.data.ArrayReader
									name : c.valueField
								}, {
									name : c.displayField
								}, {
									name : c.tips
								}])
					});
			ds.reload();
		}
		return ds;
	},
	end : function() {
		var myform = this;
		/**
		 * 开始日期不能大于结束日期判断
		 */
		Ext.apply(Ext.form.VTypes, {
			daterange : function(val, field) {
				var date = field.parseDate(val);
				if (!date) {
					return;
				}
				if (field.startDateField
						&& (!this.dateRangeMax || (date.getTime() != this.dateRangeMax
								.getTime()))) {
					var start = myform.form.findField(field.startDateField);
					start.setMaxValue(date);
					start.validate();
					this.dateRangeMax = date;
				} else if (field.endDateField
						&& (!this.dateRangeMin || (date.getTime() != this.dateRangeMin
								.getTime()))) {
					var end = myform.form.findField(field.endDateField);
					end.setMinValue(date);
					end.validate();
					this.dateRangeMin = date;
				}
				/*
				 * Always return true since we're only using this vtype to set
				 * the min/max allowed values (these are tested for after the
				 * vtype test)
				 */
				return true;
			}
		});
	}
})
Ext.reg('myform', Ext.ux.form.MyForm);