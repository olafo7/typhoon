package org.chencc.controller;

import org.apache.commons.lang.StringUtils;
import org.chencc.common.DataWrapper;
import org.chencc.common.HandlerInfo;
import org.chencc.model.AssetsNumber;
import org.chencc.model.MainStation;
import org.chencc.service.AssetsNumberSetService;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.stereotype.Controller;
import org.springframework.web.bind.annotation.GetMapping;
import org.springframework.web.bind.annotation.PostMapping;
import org.springframework.web.bind.annotation.RequestMapping;
import org.springframework.web.bind.annotation.ResponseBody;

import java.io.IOException;

/**
 * @Description 编号设置控制器
 * @author:chencc
 * @Date:2018/7/24
 * @Time:下午11:24
 */
@Controller
@RequestMapping("/assets/numberSet")
public class AssetsNumberSetController {
    @Autowired
    private AssetsNumberSetService assetsNumberSetService;

    /**
     * 跳转至列表页面
     * @return
     */
    @GetMapping(value = "/setting")
    public String setting(){

        return "assets/number_setting";
    }



    /**
     * 获取默认的编号规则数据
     * @return
     * @throws IOException
     */
    @PostMapping(value = "/getSortList")
    @ResponseBody
    public DataWrapper getSortList(){
        DataWrapper dataWrapper = new DataWrapper();

        dataWrapper.setTotal(1);
        dataWrapper.setCpage(1);
        dataWrapper.setRows(10);
        dataWrapper.setState("");
        dataWrapper.setPaterId("");

        try{
            AssetsNumber assetsNumber = assetsNumberSetService.getSortList();

            dataWrapper.setSuccess(true);
            dataWrapper.setData(assetsNumber);
            dataWrapper.setMsg("操作成功！");
        }catch (Exception ex){
            dataWrapper.setSuccess(false);
            dataWrapper.setMsg("操作失败，请联系系统管理员！");
            ex.printStackTrace();
        }

        return dataWrapper;
    }


    /**
     * 保存修改的编号设置
     * @param status
     * @param zimu
     * @param fuhao
     * @param num
     * @param zimuCheck
     * @param fuhaoCheck
     * @param nowNum
     * @param numShow
     * @return
     */
    @PostMapping(value = "/saveEdit")
    @ResponseBody
    public HandlerInfo saveEdit(Integer status,String zimu,String fuhao,String num,
                              String zimuCheck,String fuhaoCheck,String nowNum,String numShow){

        HandlerInfo handlerInfo = new HandlerInfo();
        try{
            if("on".equals(zimuCheck)){
                zimuCheck = "1";
            }
            if("on".equals(fuhaoCheck)){
                fuhaoCheck = "1";
            }
            assetsNumberSetService.saveEditRecord(status,zimu,fuhao,num,Integer.parseInt(zimuCheck),
                    Integer.parseInt(fuhaoCheck),nowNum,numShow);

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
