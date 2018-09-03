package org.chencc.controller;

import com.github.pagehelper.PageInfo;
import org.chencc.common.CurrentUserWrapper;
import org.chencc.common.DataListWrapper;
import org.chencc.common.DataWrapper;
import org.chencc.config.CurrentUser;
import org.chencc.model.AssetsHistorical;
import org.chencc.model.SystemPagesize;
import org.chencc.service.AssetsHistoricalService;
import org.chencc.service.CommonService;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.data.redis.core.RedisTemplate;
import org.springframework.data.redis.core.ValueOperations;
import org.springframework.stereotype.Controller;
import org.springframework.web.bind.annotation.*;

import java.io.IOException;
import java.util.List;
import java.util.Map;

/**
 * @Description 历史记录控制器
 * @author:chencc
 * @Date:2018/7/25
 * @Time:下午9:56
 */
@Controller
@RequestMapping("/assets/historical")
public class AssetsHistoricalController {

    @Autowired
    private AssetsHistoricalService assetsHistoricalService;
    @Autowired
    private CommonService commonService;
    @Autowired
    private RedisTemplate redisTemplate;
    @Autowired
    private ValueOperations<String,Object> valueOperations;

    /**
     * 跳转至列表页面
     * @return
     */
    @GetMapping(value = "/list")
    public String list(){
        return "assets/historical";
    }



    /**
     * 加载历史记录数据
     * @return
     */
    @RequestMapping(value = "/getHistoricalList", method = {RequestMethod.POST,RequestMethod.GET})
    @ResponseBody
    public DataListWrapper getHistoricalList(@CurrentUser CurrentUserWrapper currentUser,@RequestParam(value="start",required=false,defaultValue="0") Integer start,
                                             @RequestParam(value="limit",required=false,defaultValue="15") Integer limit, String searchOperator, String searchOperation, String searchAssetsName,
                                             String searchAssetsNum, String searchReturnStatus, String searchNumber,
                                             String searchsturnover, String searcheturnover){

        DataListWrapper dataListWrapper = new DataListWrapper();

        dataListWrapper.setCpage(1);
        dataListWrapper.setRows(10);
        dataListWrapper.setState("");
        dataListWrapper.setPaterId("");
        dataListWrapper.setPageId("assets_assets_historical");
        ValueOperations<String,Object> operations = redisTemplate.opsForValue();
        SystemPagesize systemPagesize = (SystemPagesize)operations.get("user:"+currentUser.getUid()+":pagesize.assets_assets_historical");
        if(limit != systemPagesize.getPagesize()){
            redisTemplate.delete("user:"+currentUser.getUid()+":pagesize.assets_assets_historical");
            valueOperations.set("user:"+currentUser.getUid()+":pagesize.assets_assets_historical",systemPagesize);
            //更新数据库中存储的记录
            commonService.updatePagesize(currentUser.getUid(),"assets_assets_historical",limit);
        }else{
            limit = systemPagesize.getPagesize();
        }
        start = (start/limit)+1;
        dataListWrapper.setLimit(limit);

        try{
            PageInfo<AssetsHistorical> historicalList = assetsHistoricalService.getHistoricalList(start,limit,searchOperator,searchOperation,searchAssetsName,
                    searchAssetsNum,searchReturnStatus,searchNumber,
                    searchsturnover,searcheturnover);
            dataListWrapper.setTotal(historicalList.getTotal());
            dataListWrapper.setData(historicalList.getList());
            dataListWrapper.setSuccess(true);
            dataListWrapper.setMsg("操作成功！");

        }catch (Exception ex){
            dataListWrapper.setSuccess(false);
            dataListWrapper.setMsg("操作失败，请联系系统管理员！");
            ex.printStackTrace();
        }

        return dataListWrapper;
    }



    /**
     * 获取存在的资产编号数据
     * @return
     * @throws IOException
     */
    @GetMapping(value = "/cnumstore")
    @ResponseBody
    public DataWrapper cnumstore(){
        DataWrapper dataWrapper = new DataWrapper();

        dataWrapper.setTotal(1);
        dataWrapper.setCpage(1);
        dataWrapper.setRows(10);
        dataWrapper.setState("");
        dataWrapper.setPaterId("");

        try{
            List<Map> cnumList = assetsHistoricalService.getCnumList();

            dataWrapper.setSuccess(true);
            dataWrapper.setData(cnumList);
            dataWrapper.setMsg("操作成功！");
        }catch (Exception ex){
            dataWrapper.setSuccess(false);
            dataWrapper.setMsg("操作失败，请联系系统管理员！");
            ex.printStackTrace();
        }

        return dataWrapper;
    }
}
