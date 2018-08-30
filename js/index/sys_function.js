//菜单图片地址
var CSS_PATH = "css/default/index/images/icon/";

var loginUser = {uid:2, user_id:"chr", user_name:"XXX"};
var logoutText = "轻轻的您走了，正如您轻轻的来……";

//退出时是否打开登录页面，如果是需设置为1
var ispirit = "";

//-- 一级菜单 ,排放顺序就是菜单显示的顺序--
var first_array = ["10","20","30","40","50","60","70","80","90"];

//-- 二级菜单 --   要以m开头
var second_array = [];
second_array["m30"] = ["31","32","33","34","35","36","37","38","39","310","311","312","313"];
second_array["m80"] = ["81","82","83"];
second_array["m90"] = ["91","92","93","94","95","96","97"];

//-- 三级菜单 -- 要以f开头
var third_array = [];
third_array["f34"] = ["3401","3402","3403","3404","3405","3406","3407","3408","3409","3410","3411","3412"];
//third_array["f05"] = ["51","52","53","54"];


//  id:m开头为一级菜单，f开头为二级或三级菜单，s开头为四级菜单
// 【0】为Tab的标签名称，
// 【1】为URL,一级菜单如果有二级菜单，当前url随便设置,如果当前二级菜单下有三级菜单，url前必须加@
// 【2】为PNG图标名称，不写就使用默认图标，并且该图标只对一二级菜单生效
// 【3】打开方式 (默认是空，如果需要在新窗口打开设置为1)
var func_array = [];
func_array["m10"] = ["工作桌面","content/desk/desk.html","desk"];
func_array["m20"] = ["个人办公","content/office/work-menu.html","work"];
func_array["m30"] = ["应用程序","","app"];
func_array["m40"] = ["电子邮箱","content/webMail/welcome.html","email"];
func_array["m50"] = ["日程安排","content/calendar/calendar.html","calendar"];
func_array["m60"] = ["综合查询","content/query/query.html","chart"];
func_array["m70"] = ["统计报表","content/default.html","report"];
func_array["m80"] = ["知识文档","","file"];
func_array["m90"] = ["控制面板","","sys"];


func_array["f31"] = ["战略管理","content/default.html","tactic"];
func_array["f32"] = ["预算管理","content/default.html","budget"];
func_array["f33"] = ["财务管理","content/default.html","finance"];
func_array["f34"] = ["行政管理","@administration","adm"];
func_array["f35"] = ["采购管理","content/default.html","purchase"];
func_array["f36"] = ["市场管理","content/default.html","market"];
func_array["f37"] = ["生产制造","content/default.html","yield"];
func_array["f38"] = ["销售管理","content/portal/portal-menu.html","sale"];
func_array["f39"] = ["服务管理","content/default.html","service"];
func_array["f310"] = ["人力资源","content/default.html","hr"];
func_array["f311"] = ["仓存管理","content/default.html","stock"];
func_array["f312"] = ["研发管理","content/default.html","deve"];
func_array["f313"] = ["工程管理","content/default.html","project"];


func_array["f81"] = ["质量文件","content/default.html","qualiy"];
func_array["f82"] = ["产品知识","content/default.html","product"];
func_array["f83"] = ["个人文件柜","content/default.html","chest"];

func_array["f91"] = ["组织机构","content/default.html","org"];
func_array["f92"] = ["授权管理","content/default.html","lim"];
func_array["f93"] = ["用户账户","content/default.html","user"];
func_array["f94"] = ["系统设置","content/default.html","system"];
func_array["f95"] = ["基础数据","content/default.html","basic"];
func_array["f96"] = ["系统日志","content/default.html","log"];
func_array["f97"] = ["个性设置","content/default.html","person"];

func_array["f3401"] = ["资产管理","content/default.html"];
func_array["f3402"] = ["用品管理","content/default.html"];
func_array["f3403"] = ["公告管理","content/default.html"];
func_array["f3404"] = ["新闻管理","content/default.html"];
func_array["f3405"] = ["公文管理","content/default.html"];
func_array["f3406"] = ["证照管理","content/default.html"];
func_array["f3407"] = ["档案管理","content/default.html"];
func_array["f3408"] = ["邮寄管理","content/default.html"];
func_array["f3409"] = ["印章管理","content/default.html"];
func_array["f3410"] = ["图书管理","content/default.html"];
func_array["f3411"] = ["借用管理","content/default.html"];
func_array["f3412"] = ["知识管理","content/default.html"];

var explain_array = [];
explain_array["0"] = "欢迎使用系统";
explain_array["10"] = "这是工作桌面";
explain_array["20"] = "这是个人办公";
explain_array["40"] = "这是电子邮箱";
explain_array["50"] = "这是日程安排";
explain_array["60"] = "这是综合查询";
explain_array["70"] = "这是统计报表";

//锁定菜单
var lock_array = [];