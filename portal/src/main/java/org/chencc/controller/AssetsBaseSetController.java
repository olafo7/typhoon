package org.chencc.controller;

import org.apache.commons.lang.StringUtils;
import org.chencc.common.DataListWrapper;
import org.chencc.common.HandlerInfo;
import org.chencc.model.AssetsBaseDropdown;
import org.chencc.model.MainMessage;
import org.chencc.service.AssetsBaseSetService;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.stereotype.Controller;
import org.springframework.ui.Model;
import org.springframework.web.bind.annotation.*;

import java.io.IOException;
import java.util.List;

/**
 * @Description 基础设置控制器
 * @author:chencc
 * @Date:2018/7/23
 * @Time:下午6:22
 */
@Controller
@RequestMapping("/assets/baseSet")
public class AssetsBaseSetController {

    @Autowired
    private AssetsBaseSetService assetsBaseSetService;

    /**
     * 跳转至列表页面
     * @return
     */
    @GetMapping(value = "/setting")
    public String setting(){

        return "assets/base_setting";
    }


    /**
     * 加载基础设置数据
     * @return
     */
    @RequestMapping(value = "/getDropdown", method = {RequestMethod.POST,RequestMethod.GET})
    @ResponseBody
    public DataListWrapper getDropdown(Integer type){
        DataListWrapper dataWrapper = new DataListWrapper();

        dataWrapper.setCpage(1);
        dataWrapper.setRows(10);
        dataWrapper.setState("");
        dataWrapper.setPaterId("");

        try{
            List<AssetsBaseDropdown> dropdownList = assetsBaseSetService.getDropdownByType(type);
            dataWrapper.setTotal(dropdownList.size());
            dataWrapper.setData(dropdownList);
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
     * 保存新增或修改的数据
     * @param type
     * @param value
     * @param id
     * @return
     * @throws IOException
     */
    @PostMapping(value = "/saveOrUpdate")
    @ResponseBody
    public HandlerInfo saveOrUpdate(Integer type, String value, String id) {

        HandlerInfo handlerInfo = new HandlerInfo();
        try{
            if(StringUtils.isNotBlank(id)){
                assetsBaseSetService.saveEditRecord(type,value,Integer.parseInt(id));
            }else{
                assetsBaseSetService.saveNewRecord(type,value);
            }

            handlerInfo.setSuccess(true);
            handlerInfo.setMsg("操作成功！");
        }catch (Exception ex){
            handlerInfo.setSuccess(false);
            handlerInfo.setMsg("操作失败，请联系系统管理员！");
            ex.printStackTrace();
        }

        return handlerInfo;
    }



    /**
     * 删除数据
     * @param id
     * @return
     * @throws IOException
     */
    @PostMapping(value = "/delete")
    @ResponseBody
    public HandlerInfo delete(Integer id) {

        HandlerInfo handlerInfo = new HandlerInfo();
        try{
            assetsBaseSetService.deleteRecord(id);

            handlerInfo.setSuccess(true);
            handlerInfo.setMsg("操作成功！");
        }catch (Exception ex){
            handlerInfo.setSuccess(false);
            handlerInfo.setMsg("操作失败，请联系系统管理员！");
            ex.printStackTrace();
        }

        return handlerInfo;
    }

}
