package org.chencc.controller;

import com.google.gson.Gson;
import org.apache.commons.lang.StringUtils;
import org.chencc.common.AssetsSortWrapper;
import org.chencc.common.HandlerInfo;
import org.chencc.model.AssetsSort;
import org.chencc.service.AssetsSortSetService;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.stereotype.Controller;
import org.springframework.web.bind.annotation.*;

import java.io.IOException;
import java.util.HashMap;
import java.util.List;
import java.util.Map;

/**
 * @Description 资产分类设置控制器
 * @author:chencc
 * @Date:2018/7/25
 * @Time:下午1:11
 */
@Controller
@RequestMapping("/assets/sortSet")
public class AssetsSortSetController {
    @Autowired
    private AssetsSortSetService assetsSortSetService;

    /**
     * 跳转至列表页面
     * @return
     */
    @GetMapping(value = "/setting")
    public String list(){
        return "assets/sort_setting";
    }



    /**
     * 加载分类结构树数据
     * @return
     */
    @RequestMapping(value = "/getSortList", method = {RequestMethod.POST,RequestMethod.GET})
    @ResponseBody
    public List<AssetsSortWrapper> getSortList(String type){

        List<AssetsSortWrapper> sortWrapperList = null;
        try{
            sortWrapperList = assetsSortSetService.getAssetsSortList(type);
        }catch (Exception ex){
            ex.printStackTrace();
        }
        return sortWrapperList;
    }


    /**
     * 加载选中的分类数据
     * @return
     */
    @PostMapping(value = "/loadFname")
    @ResponseBody
    public String loadFname(Integer id){
        String fname = assetsSortSetService.getNameById(id);
        Map<String,String> map = new HashMap<String,String>();
        map.put("fname",fname);
        Gson gson = new Gson();
        return gson.toJson(map);
    }




    /**
     * 删除选择的分类数据
     * @param id
     * @return
     * @throws IOException
     */
    @PostMapping(value = "/deleteSort")
    @ResponseBody
    public HandlerInfo deleteSort(Integer id){

        HandlerInfo handlerInfo = new HandlerInfo();
        try{
            assetsSortSetService.deleteRecord(id);
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
     * 保存新增或修改的数据
     * @param id
     * @param name
     * @param fid
     * @param order
     * @param about
     * @return
     * @throws IOException
     */
    @PostMapping(value = "/saveOrUpdate")
    @ResponseBody
    public HandlerInfo saveOrUpdate(String id, String name, Integer fid,Integer order,String about) {

        HandlerInfo handlerInfo = new HandlerInfo();
        try{
            if(StringUtils.isNotBlank(id)){
                assetsSortSetService.saveEditRecord(name,fid,order,about,Integer.parseInt(id));
            }else{
                assetsSortSetService.saveNewRecord(name,fid,order,about);
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
