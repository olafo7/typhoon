package org.chencc.controller;

import org.chencc.common.DataListWrapper;
import org.chencc.model.MainMessage;
import org.chencc.model.MainStation;
import org.chencc.service.MainMessageService;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.stereotype.Controller;
import org.springframework.web.bind.annotation.GetMapping;
import org.springframework.web.bind.annotation.RequestMapping;
import org.springframework.web.bind.annotation.RequestMethod;
import org.springframework.web.bind.annotation.ResponseBody;

import java.util.List;

/**
 * @Description 内部通知控制器
 * @author:chencc
 * @Date:2018/7/21
 * @Time:下午11:51
 */
@Controller
@RequestMapping("/main/message")
public class MainMessageController {

    @Autowired
    private MainMessageService mainMessageService;

    /**
     * 跳转至列表页面
     * @return
     */
    @GetMapping(value = "/list")
    public String list(){
        return "main/message";
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
            List<MainMessage> messagesList = mainMessageService.getMessageList();
            dataWrapper.setTotal(messagesList.size());
            dataWrapper.setData(messagesList);
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
