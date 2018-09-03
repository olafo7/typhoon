package org.chencc.controller;

import org.chencc.common.DataListWrapper;
import org.chencc.common.DataWrapper;
import org.chencc.common.HandlerInfo;
import org.chencc.model.MainStation;
import org.chencc.service.MainStationService;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.stereotype.Controller;
import org.springframework.web.bind.annotation.*;

import javax.servlet.http.HttpServletRequest;
import java.io.IOException;
import java.util.ArrayList;
import java.util.List;

/**
 * @Description 岗位管理控制器
 * @author:chencc
 * @Date:2018/7/17
 * @Time:下午2:05
 */
@Controller
@RequestMapping("/main/station")
public class MainStationController {
    @Autowired
    private MainStationService mainStationService;


    /**
     * 跳转至列表页面
     * @return
     */
    @GetMapping(value = "/list")
    public String list(){
        return "main/station";
    }


    /**
     * 加载列表数据
     * @return
     */
    @RequestMapping(value = "/getJsonData", method = {RequestMethod.POST,RequestMethod.GET})
    @ResponseBody
    public DataListWrapper getJsonData(){
        DataListWrapper dataWrapper = new DataListWrapper();

        dataWrapper.setCpage(1);
        dataWrapper.setRows(10);
        dataWrapper.setState("");
        dataWrapper.setPaterId("");

        try{
            List<MainStation> stationList = mainStationService.getStationList();
            dataWrapper.setTotal(stationList.size());
            dataWrapper.setData(stationList);
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
     * 保存添加的岗位数据
     * @param name
     * @param about
     * @return
     * @throws IOException
     */
    @PostMapping(value = "/add")
    @ResponseBody
    public HandlerInfo addSave(String name,String about){

        HandlerInfo handlerInfo = new HandlerInfo();
        try{
            mainStationService.saveNewRecord(name,about);
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
     * 加载需要修改的数据
     * @param sid
     *
     * @return
     */
    @RequestMapping(value = "/loadFormData", method = {RequestMethod.POST,RequestMethod.GET})
    @ResponseBody
    public DataWrapper loadFormData(int sid){
        DataWrapper dataWrapper = new DataWrapper();

        dataWrapper.setTotal(1);
        dataWrapper.setCpage(1);
        dataWrapper.setRows(10);
        dataWrapper.setState("");
        dataWrapper.setPaterId("");

        try{
            MainStation mainStation = mainStationService.loadStationById(sid);

            dataWrapper.setSuccess(true);
            dataWrapper.setData(mainStation);
            dataWrapper.setMsg("操作成功！");
        }catch (Exception ex){
            dataWrapper.setSuccess(false);
            dataWrapper.setMsg("操作失败，请联系系统管理员！");
            ex.printStackTrace();
        }

        return dataWrapper;
    }


    /**
     * 保存添加的岗位数据
     * @param sid
     * @param name
     * @param about
     * @return
     * @throws IOException
     */
    @PostMapping(value = "/edit")
    @ResponseBody
    public HandlerInfo editSave(int sid,String name,String about){

        HandlerInfo handlerInfo = new HandlerInfo();
        try{
            mainStationService.saveEditRecord(sid,name,about);
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
     * 删除选择的岗位
     * @param sid
     * @return
     * @throws IOException
     */
    @PostMapping(value = "/delete")
    @ResponseBody
    public HandlerInfo delete(int sid){

        HandlerInfo handlerInfo = new HandlerInfo();
        try{
            mainStationService.deleteRecord(sid);
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
     * 更新岗位排序数据
     * @param sid
     * @param value
     * @return
     * @throws IOException
     */
    @PostMapping(value = "/updateSort")
    @ResponseBody
    public HandlerInfo updateSort(int sid,int value){

        HandlerInfo handlerInfo = new HandlerInfo();
        try{
            mainStationService.updateSort(sid,value);
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
