Ext.namespace("Ext.ux.form");
/**
 * 表单myForm元素控件的公用属性
 * @type 
 */
Ext.ux.form.FormItem = {
	/**
	 * 是否在表单中 true是 flase否
	 * 
	 * @type Boolean
	 */
	isInForm : true,
	/**
	 * 是否在增加的表单中
	 * 
	 * @type Boolean
	 */
	isInAddForm : true,
	/**
	 * 是否在编辑的表单中
	 * 
	 * @type Boolean
	 */
	isInEditForm : true,
	/**
	 * 是否加载此控件相应的字段
	 * 
	 * @type Boolean
	 */
	isLoad : true,
	/**
	 * 页面上的值Id
	 * 
	 * @type String
	 */
	valueId : '',
	/**
	 * 控件类型，默认textfield
	 * 
	 * @type String
	 */
	formType : '',
	/**
	 * string date datetime
	 * 
	 * @type String
	 */
	type : '',
	/**
	 * 是否在显示表单中
	 * 
	 * @type Boolean
	 */
	isInView : true,
	/**
	 * 控件名称，以表单的objName+'.'+name命名
	 * 
	 * @type String
	 */
	name : '',
	/**
	 * 控件隐藏域名称,某些控件需要用到，用来存储控件的实际值，如：radioTree下拉单选树，checkboxtree多选树，combogrid下拉表格等
	 * 
	 * @type String
	 */
	hiddenName : '',
	/**
	 * 前台控件匹配后台json数据的格式，一般等于name
	 * 
	 * @type String
	 */
	mapping : '',
	/**
	 * 在表单上所占百分比，默认95%，isOneRow==true则为97%
	 * 
	 * @type String
	 */
	anchor : '95%',
	/**
	 * 是否占据一行
	 * 
	 * @type Boolean
	 */
	isOneRow : false,
	/**
	 * 控件id 默认myform.id + "_" + c.name
	 * 
	 * @type String
	 */
	id : '',
	/**
	 * type==date默认为Y-m-d type==datetime默认为Y-m-d H:i:s
	 * 
	 * @type String
	 */
	format : '',
	/**
	 * 是否审核字段 true且不是审核表单form上isAudit==false，则该控件隐藏
	 * 
	 * @type Boolean
	 */
	isAuditField : false,
	/**
	 * 查看表单时的控件类型，默认查看表单的控件类型是statictextfield
	 * 
	 * @type String
	 */
	viewFormType : 'statictextfield',
	/**
	 * 表单初始化的时候是否从后台加载此属性值
	 * 
	 * @type Boolean
	 */
	isLoad : true

}