package org.chencc.controller;

import org.chencc.common.DataWrapper;
import org.chencc.common.MenuWrapper;
import org.chencc.common.MenuWrapperForSetting;
import org.chencc.service.SystemSettingService;
import org.chencc.service.WelcomeService;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.web.bind.annotation.*;

import javax.servlet.http.HttpServletResponse;
import java.io.IOException;
import java.util.List;
import java.util.Map;

/**
 *
 * @Description 欢迎页控制器，用于获取菜单、提醒、待办、面板等信息
 * @author:chencc
 * @Date:2018/7/11
 * @Time:下午08:42
 *
 * */
@RestController
@RequestMapping("/welcome")
public class WelcomeController {

    @Autowired
    private WelcomeService welcomeService;

    /**
     *获取待办事务一级分类；用于对待办事项进行分类
     * @return
     */
    @PostMapping(value = "/getAllMenuList")
    public String getAllMenuList(){
        String menuData = "{\"total\":0,\"cpage\":1,\"rows\":10,\"state\":null,\"msg\":\"\",\"paterId\":null,\"success\":true,\"data\":[{\"mid\":1,\"name\":\"\\u5de5\\u4f5c\\u8ba1\\u5212\",\"total\":0,\"iconCls\":\"icon-calendar\"},{\"mid\":2,\"name\":\"\\u5de5\\u4f5c\\u4efb\\u52a1\",\"total\":0,\"iconCls\":\"icon-system-s-task\"},{\"mid\":3,\"name\":\"\\u5ba2\\u6237\\u7ba1\\u7406\",\"total\":0,\"iconCls\":\"icon-cnoa-communication\"},{\"mid\":4,\"name\":\"\\u5de5\\u4f5c\\u65e5\\u8bb0\",\"total\":2,\"iconCls\":\"icon-more-diary\"},{\"mid\":8,\"name\":\"\\u4eba\\u4e8b\\u7ba1\\u7406\",\"total\":0,\"iconCls\":\"icon-more-personmgr\"},{\"mid\":9,\"name\":\"\\u5408\\u540c\\u7ba1\\u7406\",\"total\":0,\"iconCls\":\"icon-more-compact\"},{\"mid\":10,\"name\":\"\\u7528\\u54c1\\u7ba1\\u7406\",\"total\":1,\"iconCls\":\"icon-more-swatch\"},{\"mid\":11,\"name\":\"\\u8f66\\u8f86\\u7ba1\\u7406\",\"total\":0,\"iconCls\":\"icon-car\"},{\"mid\":14,\"name\":\"\\u9879\\u76ee\\u7ba1\\u7406\",\"total\":0,\"iconCls\":\"icon-more-project\"},{\"mid\":15,\"name\":\"\\u8003\\u52e4\\u7ba1\\u7406\",\"total\":0,\"iconCls\":\"icon-more-att\"},{\"mid\":16,\"name\":\"\\u4e2a\\u4eba\\u8003\\u52e4\",\"total\":0,\"iconCls\":\"icon-more-attendance\"},{\"mid\":25,\"name\":\"\\u4f1a\\u8bae\\u7ba1\\u7406\",\"total\":0,\"iconCls\":\"icon-briefcase\"},{\"mid\":27,\"name\":\"\\u5185\\u90e8\\u90ae\\u4ef6\",\"total\":0,\"iconCls\":\"icon-more-message\"},{\"mid\":31,\"name\":\"\\u5de5\\u4f5c\\u6d41\\u7a0b\",\"total\":5,\"iconCls\":\"icon-more-wf\"},{\"mid\":32,\"name\":\"\\u67e5\\u770b\\u5ba2\\u6237\",\"total\":0,\"iconCls\":\"icon-more-kehu\"},{\"mid\":33,\"name\":\"\\u67e5\\u770b\\u4fe1\\u606f\",\"total\":0,\"iconCls\":\"icon-more-news\"},{\"mid\":34,\"name\":\"\\u6295\\u7968\",\"total\":0,\"iconCls\":\"icon-more-vote\"},{\"mid\":35,\"name\":\"\\u8bc4\\u9605\\u65e5\\u8bb0\",\"total\":0,\"iconCls\":\"icon-more-diary\"},{\"mid\":36,\"name\":\"\\u5728\\u5c97\\u72b6\\u6001\",\"total\":0,\"iconCls\":\"icon-more-status\"},{\"mid\":37,\"name\":\"\\u85aa\\u916c\\u5f55\\u5165\",\"total\":0,\"iconCls\":\"icon-more-salary\"},{\"mid\":38,\"name\":\"\\u8003\\u8bd5\\u63d0\\u9192\",\"total\":0,\"iconCls\":\"icon-more-notice\"},{\"mid\":39,\"name\":\"\\u95ee\\u5377\\u63d0\\u9192\",\"total\":0,\"iconCls\":\"icon-more-notice\"},{\"mid\":40,\"name\":\"\\u8ba2\\u9910\\u63d0\\u9192\",\"total\":0,\"iconCls\":\"icon-more-notice\"},{\"mid\":41,\"name\":\"\\u67e5\\u770b\\u5ba2\\u6237\",\"total\":0,\"iconCls\":\"icon-more-kehu\"},{\"mid\":42,\"name\":\"\\u77e5\\u8bc6\\u7ba1\\u7406\",\"total\":0,\"iconCls\":\"icon-more-news\"}]}";
        return menuData;
    }

    /**
     * 获取当前服务器时间毫秒数
     * @return
     */
    @PostMapping(value = "/getServerTime13")
    public long getServerTime13(){
        return System.currentTimeMillis();
    }


    /**
     * 获取主面板导航菜单数据
     * @return
     */
    @GetMapping(value = "/getMainPanelTreeData2")
    public List<MenuWrapper> getMainPanelTreeData2(){

        List<MenuWrapper> menuList = null;
        try{
            menuList = welcomeService.getSystemMenuList();
        }catch (Exception ex){
            ex.printStackTrace();
        }
        return menuList;
    }


    /**
     * 显示个人首页
     * @return
     */
    @GetMapping(value = "/myPortals")
    public String myPortals(){
        return "main/portals";
    }


    /**
     * 加载首页面板信息
     * @return
     */
    @RequestMapping(value ="/welcomePanel", method = { RequestMethod.GET,RequestMethod.POST })
    public void welcomePanel(HttpServletResponse response)throws IOException {
        StringBuffer panelInfo = new StringBuffer("<script language=\"JavaScript\">" +
                "Ext.namespace(\"CNOA.main.common.index\");" +
                "CNOA.main.common.index.parentID = \"docs-CNOA_WELCOM_PANEL\";" +
                "</script>" +
                "<script language=\"JavaScript\">" +
                "CNOA.main.common.index.DestTopAppList = '[{\"code\":\"CNOA_MAIN_DESKTOP_COMM_MESSAGE_INBOX\",\"name\":\"\\u6211\\u7684\\u6536\\u4ef6\\u7bb1\",\"about\":\"\\u663e\\u793a\\u6700\\u65b0\\u63a5\\u6536\\u76845\\u6761\\u5185\\u90e8\\u90ae\\u4ef6\",\"column\":\"1\",\"position\":\"0\",\"inuse\":1},{\"code\":\"CNOA_MAIN_DESKTOP_USER_RELEASE\",\"name\":\"\\u5ba2\\u6237\\u91ca\\u653e\\u516c\\u6d77\\u6570(\\u56fe)\",\"about\":\"\\u91ca\\u653e\\u5230\\u516c\\u6d77\\u5ba2\\u6237\\u91cc\\u7684\\u6570\\u91cf\",\"column\":\"0\",\"position\":\"0\",\"inuse\":0},{\"code\":\"CNOA_MAIN_DESKTOP_MAIN_OUTLINK\",\"name\":\"\\u5916\\u90e8\\u94fe\\u63a5\",\"about\":\"\\u663e\\u793a\\u5916\\u90e8\\u94fe\\u63a5\",\"column\":\"1\",\"position\":\"0\",\"inuse\":1},{\"code\":\"CNOA_MAIN_DESKTOP_USER_RECEIVE\",\"name\":\"\\u5ba2\\u6237\\u516c\\u6d77\\u9886\\u7528\\u6570(\\u56fe)\",\"about\":\"\\u4ece\\u516c\\u6d77\\u5ba2\\u6237\\u91cc\\u9886\\u7528\\u7684\\u6570\\u91cf\",\"column\":\"0\",\"position\":\"0\",\"inuse\":0},{\"code\":\"CNOA_MAIN_DESKTOP_USER_TASK\",\"name\":\"\\u4efb\\u52a1\\u770b\\u677f\",\"about\":\"\",\"column\":\"0\",\"position\":\"0\",\"inuse\":1},{\"code\":\"CNOA_MAIN_DESKTOP_USER_STATISTIC\",\"name\":\"\\u5ba2\\u6237\\u7edf\\u8ba1\\u8ddf\\u8e2a\",\"about\":\"\\u5ba2\\u6237\\u7ba1\\u7406\\u7684\\u7edf\\u8ba1\\u8ddf\\u8e2a\",\"column\":\"1\",\"position\":\"1\",\"inuse\":1},{\"code\":\"CNOA_MAIN_DESKTOP_BIRTHDAY\",\"name\":\"\\u4eba\\u4e8b\\u751f\\u65e5\\u5f85\\u529e\",\"about\":\"\\u663e\\u793a\\u516c\\u53f8\\u4eba\\u4e8b\\u751f\\u65e5\\u7684\\u4eba\",\"column\":\"0\",\"position\":\"0\",\"inuse\":1},{\"code\":\"CNOA_MAIN_DESKTOP_CONTRACT\",\"name\":\"\\u4eba\\u4e8b\\u5408\\u540c\\u5f85\\u529e\",\"about\":\"\\u663e\\u793a\\u516c\\u53f8\\u4eba\\u4e8b\\u5408\\u540c\\u5230\\u671f\\u7684\\u7528\\u6237\",\"column\":\"0\",\"position\":\"0\",\"inuse\":0},{\"code\":\"CNOA_MAIN_DESKTOP_LINCES\",\"name\":\"\\u4eba\\u4e8b\\u8bc1\\u4e66\\u5f85\\u529e\",\"about\":\"\\u663e\\u793a\\u4eba\\u4e8b\\u8bc1\\u4e66\\u5230\\u671f\\u7684\\u7528\\u6237\",\"column\":\"0\",\"position\":\"0\",\"inuse\":0},{\"code\":\"CNOA_MAIN_DESKTOP_WF\",\"name\":\"\\u6211\\u7684\\u6d41\\u7a0b\\u6536\\u85cf\",\"about\":\"\\u663e\\u793a\\u6211\\u6536\\u85cf\\u7684\\u6d41\\u7a0b\",\"column\":\"0\",\"position\":\"0\",\"inuse\":0},{\"code\":\"CNOA_MAIN_DESKTOP_WF2\",\"name\":\"\\u6d41\\u7a0b\\u529e\\u7406\\u6392\\u540d\",\"about\":\"\\u663e\\u793a\\u516c\\u53f8\\u6d41\\u7a0b\\u529e\\u7406\\u65f6\\u95f4\\u6392\\u540d\",\"column\":\"0\",\"position\":\"0\",\"inuse\":0},{\"code\":\"CNOA_MAIN_DESKTOP_CUSTOMER_PRESALES\",\"name\":\"\\u552e\\u524d\\u8ddf\\u8e2a\",\"about\":\"\\u663e\\u793a\\u9700\\u8981\\u8ddf\\u8fdb\\u7684\\u8bb0\\u5f55\",\"column\":1,\"position\":\"0\",\"inuse\":1},{\"code\":\"CNOA_MAIN_DESKTOP_USER_NOFOLLOW\",\"name\":\"\\u8fc7\\u671f\\u672a\\u8ddf\\u8fdb\\u5ba2\\u6237(\\u56fe)\",\"about\":\"\\u8fc7\\u671f\\u672a\\u8ddf\\u8fdb\\u7684\\u5ba2\\u6237\",\"column\":\"0\",\"position\":\"0\",\"inuse\":0},{\"code\":\"CNOA_MAIN_DESKTOP_USER_FOLLOWUP\",\"name\":\"\\u8ddf\\u8fdb\\u5ba2\\u6237\\u6570(\\u56fe)\",\"about\":\"\\u8ddf\\u8fdb\\u7684\\u5ba2\\u6237\\u6570\",\"column\":\"0\",\"position\":\"0\",\"inuse\":0},{\"code\":\"CNOA_MAIN_DESKTOP_USER_MYCUSTOMERS\",\"name\":\"\\u5ba2\\u6237\\u6570\\u91cf(\\u56fe)\",\"about\":\"\\u6211\\u7684\\u5ba2\\u6237\",\"column\":\"0\",\"position\":\"0\",\"inuse\":0},{\"code\":\"CNOA_MAIN_DESKTOP_USER_NEWCUSTOMERS\",\"name\":\"\\u65b0\\u589e\\u5ba2\\u6237\\u6570(\\u56fe)\",\"about\":\"\\u65b0\\u589e\\u52a0\\u7684\\u5ba2\\u6237\\u6570\\u91cf\",\"column\":\"0\",\"position\":\"0\",\"inuse\":0},{\"code\":\"CNOA_MAIN_DESKTOP_USER_PRESALEROWS\",\"name\":\"\\u552e\\u524d\\u8ddf\\u8e2a\\u6b21\\u6570(\\u56fe)\",\"about\":\"\\u552e\\u524d\\u8ddf\\u8e2a\\u6b21\\u6570\",\"column\":\"0\",\"position\":\"0\",\"inuse\":0},{\"code\":\"CNOA_MAIN_DESKTOP_USER_SALETOTAL\",\"name\":\"\\u9500\\u552e\\u603b\\u989d(\\u56fe)\",\"about\":\"\\u9500\\u552e\\u603b\\u989d\",\"column\":\"0\",\"position\":\"0\",\"inuse\":0},{\"code\":\"CNOA_MAIN_DESKTOP_USER_CUSTOMERTYPE\",\"name\":\"\\u6211\\u7684\\u5ba2\\u6237\\u7c7b\\u578b(\\u6f0f\\u6597\\u56fe)\",\"about\":\"\\u6211\\u7684\\u5ba2\\u6237\\u7c7b\\u522b\\u6570\\u91cf\\u6f0f\\u6597\\u56fe\",\"column\":\"0\",\"position\":\"0\",\"inuse\":0},{\"code\":\"CNOA_MAIN_DESKTOP_USER_CUSTOMERDEGREE\",\"name\":\"\\u5ba2\\u6237\\u7a0b\\u5ea6(\\u6f0f\\u6597\\u56fe)\",\"about\":\"\\u5ba2\\u6237\\u7a0b\\u5ea6\",\"column\":\"0\",\"position\":\"0\",\"inuse\":0},{\"code\":\"CNOA_MAIN_DESKTOP_JOBSTATUS\",\"name\":\"\\u4eba\\u4e8b\\u5728\\u804c\\u72b6\\u6001(\\u56fe)\",\"about\":\"\\u663e\\u793a\\u5728\\u804c\\u72b6\\u6001\\u6bd4\\u4f8b\",\"column\":\"0\",\"position\":\"0\",\"inuse\":0},{\"code\":\"CNOA_MAIN_DESKTOP_EDBACKGROUND\",\"name\":\"\\u4eba\\u4e8b\\u5b66\\u5386(\\u56fe)\",\"about\":\"\\u663e\\u793a\\u5b66\\u5386\\u6bd4\\u4f8b\",\"column\":\"0\",\"position\":\"0\",\"inuse\":0},{\"code\":\"CNOA_MAIN_DESKTOP_AGEBRACKET\",\"name\":\"\\u4eba\\u4e8b\\u5e74\\u9f84(\\u56fe)\",\"about\":\"\\u663e\\u793a\\u5e74\\u9f84\\u6bd4\\u4f8b\",\"column\":\"0\",\"position\":\"0\",\"inuse\":0},{\"code\":\"CNOA_MAIN_DESKTOP_NEWS_NOTICE\",\"name\":\"\\u516c\\u544a\\/\\u901a\\u77e5\",\"about\":\"\\u663e\\u793a\\u6211\\u7684\\u516c\\u544a\\/\\u901a\\u77e5\",\"column\":\"2\",\"position\":\"3\",\"inuse\":0},{\"code\":\"CNOA_MAIN_DESKTOP_USRE_HR_CLOCK\",\"name\":\"\\u4e2a\\u4eba\\u8003\\u52e4\",\"about\":\"\\u4e2a\\u4eba\\u8003\\u52e4\",\"column\":\"0\",\"position\":\"2\",\"inuse\":1},{\"code\":\"CNOA_MAIN_DESKTOP_NEWS_NEWS_SORT_1\",\"name\":\"\\u516c\\u53f8\\u65b0\\u95fb\",\"about\":\"\",\"column\":\"0\",\"position\":\"0\",\"inuse\":0},{\"code\":\"CNOA_MAIN_DESKTOP_NEWS_NEWS_SORT_2\",\"name\":\"\\u7126\\u70b9\\u65b0\\u95fb\",\"about\":\"\",\"column\":\"0\",\"position\":\"0\",\"inuse\":0},{\"code\":\"CNOA_MAIN_DESKTOP_NEWS_NEWS_SORT_3\",\"name\":\"\\u6d41\\u7a0b\\u793a\\u4f8b\",\"about\":\"\",\"column\":\"0\",\"position\":\"0\",\"inuse\":0},{\"code\":\"CNOA_MAIN_DESKTOP_COMM_ODOC_READ_SEND\",\"name\":\"\\u53d1\\u6587\\u9605\\u8bfb\",\"about\":\"\",\"column\":\"0\",\"position\":\"0\",\"inuse\":0},{\"code\":\"CNOA_MAIN_DESKTOP_COMM_ODOC_READ_RECEIVE\",\"name\":\"\\u6536\\u6587\\u9605\\u8bfb\",\"about\":\"\",\"column\":\"0\",\"position\":\"0\",\"inuse\":0},{\"code\":\"CNOA_MAIN_DESKTOP_WF_READ\",\"name\":\"\\u6d41\\u7a0b\\u9605\\u8bfb\",\"about\":\"\",\"column\":\"0\",\"position\":\"0\",\"inuse\":0},{\"code\":\"CNOA_MAIN_DESKTOP_ATT_REGISTER\",\"name\":\"\\u4e2a\\u4eba\\u8003\\u52e4(\\u65b0)\",\"about\":\"\\u4e2a\\u4eba\\u8003\\u52e4(\\u65b0)\",\"column\":\"0\",\"position\":\"0\",\"inuse\":0},{\"code\":\"CNOA_MAIN_DESKTOP_NEWS_BBS_NEWPOST\",\"name\":\"\\u6700\\u65b0\\u5e16\\u5b50\",\"about\":\"\",\"column\":\"0\",\"position\":\"0\",\"inuse\":0},{\"code\":\"CNOA_MAIN_DESKTOP_NEWS_BBS_NEWREPLY\",\"name\":\"\\u6700\\u65b0\\u56de\\u590d\",\"about\":\"\",\"column\":\"0\",\"position\":\"0\",\"inuse\":0},{\"code\":\"CNOA_MAIN_DESKTOP_NEWS_BBS_WEEKHOT\",\"name\":\"\\u672c\\u5468\\u70ed\\u95e8\",\"about\":\"\",\"column\":\"0\",\"position\":\"0\",\"inuse\":1}]';" +
                "</script>" +
                "<script type='text/javascript' src='cnoa/file/webcache/common_index.js?version=20140318&rand=5461120'></script>");

        response.getWriter().print(panelInfo);
    }


    /**
     * 跳转至修改个人资料页面
     * @return
     */
    @RequestMapping(value ="/myInfo", method = { RequestMethod.GET,RequestMethod.POST })
    public void myInfo(HttpServletResponse response)throws IOException {
        StringBuffer panelInfo = new StringBuffer("<script language=\"JavaScript\">" +
                "Ext.namespace(\"CNOA.my.index.info\");" +
                "CNOA.my.index.info.parentID = \"docs-CNOA_MENU_MY_INFO\";" +
                "Ext.onReady(function() {" +
                "loadScripts(\"sm_my_myInfo\", \"cnoa/app/my/scripts/cnoa.myInfo.js\", function(){" +
                "if(CNOA_my_info == null){" +
                "CNOA_my_info = new CNOA_my_infoClass();" +
                "CNOA_my_info.show();" +
                "}" +
                "Ext.getCmp(CNOA.my.index.info.parentID).on(\"close\", function(){" +
                "try{" +
                "CNOA_my_info.close();" +
                "}catch(e){}" +
                "});" +
                "});" +
                "});" +
                "</script>");




        response.getWriter().print(panelInfo);
    }



    /**
     * 加载个人资料信息
     * @return
     * @throws IOException
     */
    @GetMapping(value = "/editLoadFormDataInfo")
    @ResponseBody
    public DataWrapper editLoadFormDataInfo(String sname){
        DataWrapper dataWrapper = new DataWrapper();

        dataWrapper.setTotal(1);
        dataWrapper.setCpage(1);
        dataWrapper.setRows(10);
        dataWrapper.setState("");
        dataWrapper.setPaterId("");


        /*select true_name,dept_id,job_id,sex,personSign,birthday,mobile,partTimeJob,workphone,qq,email,
        address,face,qyusername,jobname,deptname

        "truename": "超级管理员",
                "deptId": "1",
                "jobId": "1",
                //"sex": "1",
                //"personSign": "",
                //"birthday": "2011-01-01",
                //"mobile": "18666666666",
                //"partTimeJob": "",
                //"workphone": "02085631005",
                //"qq": "718903805",
                "email": "admin@cnoa.cn",
                //"address": "广州市协众软件科技有限公司",
                //"face": ".\/resources\/images\/default.jpg",
                //"qyusername": "",
                "jobname": "超级管理员",
                "deptname": "协众OA协同管理系统-试用"
*/

        try{
            //List<Map> sortList = flowSettingService.getSortList(sname);

            dataWrapper.setSuccess(true);
            //dataWrapper.setData(sortList);
            dataWrapper.setMsg("操作成功！");
        }catch (Exception ex){
            dataWrapper.setSuccess(false);
            dataWrapper.setMsg("操作失败，请联系系统管理员！");
            ex.printStackTrace();
        }

        return dataWrapper;
    }


}
