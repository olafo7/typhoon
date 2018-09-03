package org.chencc.controller;

import org.chencc.common.DataListWrapper;
import org.chencc.common.DataWrapper;
import org.chencc.common.MenuWrapperForSetting;
import org.chencc.model.SystemConfig;
import org.chencc.model.SystemOutlink;
import org.chencc.service.SystemSettingService;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.stereotype.Controller;
import org.springframework.ui.Model;
import org.springframework.web.bind.annotation.GetMapping;
import org.springframework.web.bind.annotation.RequestMapping;
import org.springframework.web.bind.annotation.RequestMethod;
import org.springframework.web.bind.annotation.ResponseBody;

import java.util.List;
import java.util.Map;

/**
 * @Description 系统核心设置控制器
 * @author:chencc
 * @Date:2018/7/22
 * @Time:上午9:45
 */
@Controller
@RequestMapping("/main/system")
public class SystemSettingController {

    @Autowired
    private SystemSettingService systemSettingService;

    /**
     * 跳转至列表页面
     * @return
     */
    @GetMapping(value = "/setting")
    public String list(Model model){

        return "main/system_setting";
    }


    /**
     * 加载需要修改的系统核心配置数据
     *
     * @return
     */
    @RequestMapping(value = "/editLoadFormDataInfo", method = {RequestMethod.POST,RequestMethod.GET})
    @ResponseBody
    public DataWrapper editLoadFormDataInfo(){
        DataWrapper dataWrapper = new DataWrapper();

        dataWrapper.setTotal(1);
        dataWrapper.setCpage(1);
        dataWrapper.setRows(10);
        dataWrapper.setState("");
        dataWrapper.setPaterId("");

        try{
            SystemConfig systemConfig = systemSettingService.getSystemConfig();

            dataWrapper.setSuccess(true);
            dataWrapper.setData(systemConfig);
            dataWrapper.setMsg("操作成功！");
        }catch (Exception ex){
            dataWrapper.setSuccess(false);
            dataWrapper.setMsg("操作失败，请联系系统管理员！");
            ex.printStackTrace();
        }

        return dataWrapper;
    }


    /**
     * 加载功能菜单数据(系统控制菜单最多只能有三级)
     *
     * @return
     */
    @RequestMapping(value = "/getMenuTreeList", method = {RequestMethod.POST,RequestMethod.GET})
    @ResponseBody
    public List<MenuWrapperForSetting> getMenuTreeList(){
        List<MenuWrapperForSetting> menuList = null;
        try{
            menuList = systemSettingService.getSystemMenuList();
        }catch (Exception ex){
            ex.printStackTrace();
        }
        return menuList;
    }


    /**
     * 加载外部链接列表数据
     * @return
     */
    @RequestMapping(value = "/getOutlinkData", method = {RequestMethod.POST,RequestMethod.GET})
    @ResponseBody
    public DataListWrapper getOutlinkData(){
        DataListWrapper dataWrapper = new DataListWrapper();

        dataWrapper.setCpage(1);
        dataWrapper.setRows(10);
        dataWrapper.setState("");
        dataWrapper.setPaterId("");
        dataWrapper.setLimit(15);
        dataWrapper.setPageId("main_user_list_getJsonData");

        try{
            List<SystemOutlink> outlinkList = systemSettingService.getOutlinkList();
            dataWrapper.setTotal(outlinkList.size());
            dataWrapper.setData(outlinkList);
            dataWrapper.setSuccess(true);
            dataWrapper.setMsg("操作成功！");

        }catch (Exception ex){
            dataWrapper.setSuccess(false);
            dataWrapper.setMsg("操作失败，请联系系统管理员！");
            ex.printStackTrace();
        }

        return dataWrapper;
    }


    /**
     * 加载在职用户数据
     * @return
     */
    @RequestMapping(value = "/getUsersJsonData", method = {RequestMethod.POST,RequestMethod.GET})
    @ResponseBody
    public DataListWrapper getUsersJsonData(String type){
        DataListWrapper dataWrapper = new DataListWrapper();

        dataWrapper.setCpage(1);
        dataWrapper.setRows(10);
        dataWrapper.setState("");
        dataWrapper.setPaterId("");

        try{
            List<Map> userInfoList = systemSettingService.getUserListByType(type);
            dataWrapper.setTotal(userInfoList.size());
            dataWrapper.setData(userInfoList);
            dataWrapper.setSuccess(true);
            dataWrapper.setMsg("操作成功！");

        }catch (Exception ex){
            dataWrapper.setSuccess(false);
            dataWrapper.setMsg("操作失败，请联系系统管理员！");
            ex.printStackTrace();
        }

        return dataWrapper;
    }
}
