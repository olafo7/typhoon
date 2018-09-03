package org.chencc.controller;

import org.chencc.common.DataWrapper;
import org.chencc.common.HandlerInfo;
import org.chencc.model.MainStation;
import org.chencc.model.MainStruct;
import org.chencc.service.MainStructService;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.stereotype.Controller;
import org.springframework.web.bind.annotation.*;

import java.io.IOException;

/**
 * @Description 组织结构控制器
 * @author:chencc
 * @Date:2018/7/14
 * @Time:下午10:19
 */

@Controller
@RequestMapping("/main/struct")
public class MainStructController {

    @Autowired
    private MainStructService mainStructService;


    /**
     * 跳转至列表页面
     * @return
     */
    @GetMapping(value = "/list")
    public String list(){
        return "main/struct";
    }


    /**
     * 加载组织结构树数据
     * @return
     */
    @RequestMapping(value = "/getStructTree", method = {RequestMethod.POST,RequestMethod.GET})
    @ResponseBody
    public String getStructTree(){
        String structTreeData = "[{\"id\":\"CNOA_main_struct_list_tree_node_1\",\"selfid\":\"1\",\"deptId\":\"1\",\"permit\":1,\"text\":\"\\u534f\\u4f17OA\\u534f\\u540c\\u7ba1\\u7406\\u7cfb\\u7edf-\\u8bd5\\u7528\",\"iconCls\":\"icon-tree-root-cnoa\",\"cls\":\"package\",\"href\":\"javascript:void(0);\",\"leaf\":false,\"children\":[{\"id\":\"CNOA_main_struct_list_tree_node_2\",\"selfid\":\"2\",\"deptId\":\"2\",\"permit\":1,\"text\":\"\\u7efc\\u5408\\u90e8\",\"iconCls\":\"icon-style-page-key\",\"cls\":\"cls\",\"href\":\"javascript:void(0);\",\"leaf\":true,\"disabled\":false,\"ds\":false,\"isClass\":true},{\"id\":\"CNOA_main_struct_list_tree_node_6\",\"selfid\":\"6\",\"deptId\":\"6\",\"permit\":1,\"text\":\"\\u884c\\u653f\\u90e8\",\"iconCls\":\"icon-style-page-key\",\"cls\":\"cls\",\"href\":\"javascript:void(0);\",\"leaf\":true,\"disabled\":false,\"ds\":false,\"isClass\":true},{\"id\":\"CNOA_main_struct_list_tree_node_8\",\"selfid\":\"8\",\"deptId\":\"8\",\"permit\":1,\"text\":\"\\u4eba\\u4e8b\\u90e8\",\"iconCls\":\"icon-style-page-key\",\"cls\":\"cls\",\"href\":\"javascript:void(0);\",\"leaf\":true,\"disabled\":false,\"ds\":false,\"isClass\":true},{\"id\":\"CNOA_main_struct_list_tree_node_3\",\"selfid\":\"3\",\"deptId\":\"3\",\"permit\":1,\"text\":\"\\u8425\\u9500\\u4e2d\\u5fc3\",\"iconCls\":\"icon-style-page-key\",\"cls\":\"package\",\"href\":\"javascript:void(0);\",\"leaf\":false,\"children\":[{\"id\":\"CNOA_main_struct_list_tree_node_12\",\"selfid\":\"12\",\"deptId\":\"12\",\"permit\":1,\"text\":\"A\\u90e8\\u95e8\",\"iconCls\":\"icon-style-page-key\",\"cls\":\"cls\",\"href\":\"javascript:void(0);\",\"leaf\":true,\"disabled\":false,\"ds\":false,\"isClass\":true},{\"id\":\"CNOA_main_struct_list_tree_node_13\",\"selfid\":\"13\",\"deptId\":\"13\",\"permit\":1,\"text\":\"B\\u90e8\\u95e8\",\"iconCls\":\"icon-style-page-key\",\"cls\":\"cls\",\"href\":\"javascript:void(0);\",\"leaf\":true,\"disabled\":false,\"ds\":false,\"isClass\":true},{\"id\":\"CNOA_main_struct_list_tree_node_14\",\"selfid\":\"14\",\"deptId\":\"14\",\"permit\":1,\"text\":\"C\\u90e8\\u95e8\",\"iconCls\":\"icon-style-page-key\",\"cls\":\"cls\",\"href\":\"javascript:void(0);\",\"leaf\":true,\"disabled\":false,\"ds\":false,\"isClass\":true},{\"id\":\"CNOA_main_struct_list_tree_node_16\",\"selfid\":\"16\",\"deptId\":\"16\",\"permit\":1,\"text\":\"D\\u90e8\\u95e8\",\"iconCls\":\"icon-style-page-key\",\"cls\":\"cls\",\"href\":\"javascript:void(0);\",\"leaf\":true,\"disabled\":false,\"ds\":false,\"isClass\":true},{\"id\":\"CNOA_main_struct_list_tree_node_17\",\"selfid\":\"17\",\"deptId\":\"17\",\"permit\":1,\"text\":\"\\u5ba2\\u670d\\u4e2d\\u5fc3\",\"iconCls\":\"icon-style-page-key\",\"cls\":\"cls\",\"href\":\"javascript:void(0);\",\"leaf\":true,\"disabled\":false,\"ds\":false,\"isClass\":true}],\"disabled\":false,\"ds\":false,\"expanded\":false},{\"id\":\"CNOA_main_struct_list_tree_node_4\",\"selfid\":\"4\",\"deptId\":\"4\",\"permit\":1,\"text\":\"\\u5de5\\u7a0b\\u4e2d\\u5fc3\",\"iconCls\":\"icon-style-page-key\",\"cls\":\"cls\",\"href\":\"javascript:void(0);\",\"leaf\":true,\"disabled\":false,\"ds\":false,\"isClass\":true},{\"id\":\"CNOA_main_struct_list_tree_node_5\",\"selfid\":\"5\",\"deptId\":\"5\",\"permit\":1,\"text\":\"\\u751f\\u4ea7\\u4e2d\\u5fc3\",\"iconCls\":\"icon-style-page-key\",\"cls\":\"cls\",\"href\":\"javascript:void(0);\",\"leaf\":true,\"disabled\":false,\"ds\":false,\"isClass\":true},{\"id\":\"CNOA_main_struct_list_tree_node_7\",\"selfid\":\"7\",\"deptId\":\"7\",\"permit\":1,\"text\":\"\\u8d22\\u52a1\\u4e2d\\u5fc3\",\"iconCls\":\"icon-style-page-key\",\"cls\":\"cls\",\"href\":\"javascript:void(0);\",\"leaf\":true,\"disabled\":false,\"ds\":false,\"isClass\":true},{\"id\":\"CNOA_main_struct_list_tree_node_9\",\"selfid\":\"9\",\"deptId\":\"9\",\"permit\":1,\"text\":\"\\u5206\\u516c\\u53f8\",\"iconCls\":\"icon-style-page-key\",\"cls\":\"package\",\"href\":\"javascript:void(0);\",\"leaf\":false,\"children\":[{\"id\":\"CNOA_main_struct_list_tree_node_15\",\"selfid\":\"15\",\"deptId\":\"15\",\"permit\":1,\"text\":\"\\u4e1a\\u52a1\\u90e8\",\"iconCls\":\"icon-style-page-key\",\"cls\":\"cls\",\"href\":\"javascript:void(0);\",\"leaf\":true,\"disabled\":false,\"ds\":false,\"isClass\":true}],\"disabled\":false,\"ds\":false,\"expanded\":false},{\"id\":\"CNOA_main_struct_list_tree_node_11\",\"selfid\":\"11\",\"deptId\":\"11\",\"permit\":1,\"text\":\"\\u4ed3\\u5e93\",\"iconCls\":\"icon-style-page-key\",\"cls\":\"cls\",\"href\":\"javascript:void(0);\",\"leaf\":true,\"disabled\":false,\"ds\":false,\"isClass\":true}],\"disabled\":false,\"ds\":false,\"expanded\":false}]";
        return structTreeData;
    }


    /**
     * 加载需要修改组织结构的数据
     * @param id
     * @return
     */
    @PostMapping(value = "/loadData")
    @ResponseBody
    public DataWrapper loadData(Integer id){
        DataWrapper dataWrapper = new DataWrapper();
        dataWrapper.setTotal(0);
        dataWrapper.setCpage(1);
        dataWrapper.setRows(10);
        dataWrapper.setState("");
        dataWrapper.setPaterId("");
        try{
            MainStruct mainStruct = mainStructService.loadStructById(id);
            dataWrapper.setData(mainStruct);
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
     * 删除选择的组织结构数据
     * @param id
     * @return
     * @throws IOException
     */
    @PostMapping(value = "/delete")
    @ResponseBody
    public HandlerInfo delete(Integer id){

        HandlerInfo handlerInfo = new HandlerInfo();
        try{
            mainStructService.deleteRecord(id);
            handlerInfo.setSuccess(true);
            handlerInfo.setMsg("操作成功！");
        }catch (Exception ex){
            handlerInfo.setSuccess(false);
            handlerInfo.setMsg("操作失败，请联系系统管理员！");
            ex.printStackTrace();
        }

        return handlerInfo;
    }



    @PostMapping(value = "/submit")
    @ResponseBody
    public HandlerInfo submit(String action,Integer id,String name,Integer order,
                              Integer fid,String path,String about){

        HandlerInfo handlerInfo = new HandlerInfo();
        try{
            if("add".equals(action)){
                mainStructService.saveNewRecord(name,order,fid,path,about);
            }else{
                mainStructService.saveEditRecord(id,name,order,fid,path,about);
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
}
