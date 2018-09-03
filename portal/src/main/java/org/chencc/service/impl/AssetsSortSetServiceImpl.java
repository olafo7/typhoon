package org.chencc.service.impl;

import org.chencc.common.AssetsSortWrapper;
import org.chencc.mapper.AssetsSortSetMapper;
import org.chencc.model.AssetsSort;
import org.chencc.service.AssetsSortSetService;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.stereotype.Service;
import org.springframework.transaction.annotation.Transactional;

import java.util.ArrayList;
import java.util.List;

/**
 * @Description
 * @author:chencc
 * @Date:2018/7/25
 * @Time:下午1:12
 */
@Service
@Transactional
public class AssetsSortSetServiceImpl implements AssetsSortSetService {
    @Autowired
    private AssetsSortSetMapper assetsSortSetMapper;

    @Override
    public List<AssetsSortWrapper> getAssetsSortList(String type) {
        List<AssetsSortWrapper> sortWrapperList = new ArrayList<AssetsSortWrapper>();

        AssetsSort assetsSort = assetsSortSetMapper.getTopLevelSort();
        AssetsSortWrapper topSortWrapper = new AssetsSortWrapper();

        topSortWrapper.setId(String.valueOf(assetsSort.getId()));
        topSortWrapper.setText(assetsSort.getName());
        topSortWrapper.setFid(String.valueOf(assetsSort.getFid()));
        topSortWrapper.setOrder(String.valueOf(assetsSort.getOrder()));
        topSortWrapper.setIconCls("icon-tree-root-cnoa");
        topSortWrapper.setAbout(assetsSort.getAbout());
        /*if("tree".equals(type)){
            topSortWrapper.setChecked(false);
        }*/

        List<AssetsSortWrapper> subSortList = getAssetsSortListByFid(type,assetsSort.getId());
        if(subSortList.isEmpty()){
            topSortWrapper.setLeaf(true);
            topSortWrapper.setChildren(null);
        }else{
            topSortWrapper.setLeaf(false);
            topSortWrapper.setChildren(subSortList);
        }

        sortWrapperList.add(topSortWrapper);

        return sortWrapperList;
    }

    @Override
    public AssetsSort getAssetsSortById(Integer id) {
        return assetsSortSetMapper.getAssetsSortById(id);
    }

    @Override
    public void deleteRecord(Integer id) {
        assetsSortSetMapper.deleteRecord(id);
    }

    @Override
    public void saveEditRecord(String name, Integer fid, Integer order, String about, Integer id) {
        assetsSortSetMapper.saveEditRecord(name,fid,order,about,id);
    }

    @Override
    public void saveNewRecord(String name, Integer fid, Integer order, String about) {
        assetsSortSetMapper.saveNewRecord(name,fid,order,about);
    }

    @Override
    public String getNameById(Integer id) {
        return assetsSortSetMapper.getNameById(id);
    }


    public List<AssetsSortWrapper> getAssetsSortListByFid(String type,Integer fid){
        List<AssetsSortWrapper> sortWrapperList = new ArrayList<AssetsSortWrapper>();

        List<AssetsSort> assetsSortList = assetsSortSetMapper.getAssetsSortListByFid(fid);
        for(AssetsSort assetsSort : assetsSortList){
            AssetsSortWrapper sortWrapper = new AssetsSortWrapper();

            sortWrapper.setId(String.valueOf(assetsSort.getId()));
            sortWrapper.setText(assetsSort.getName());
            sortWrapper.setFid(String.valueOf(assetsSort.getFid()));
            sortWrapper.setOrder(String.valueOf(assetsSort.getOrder()));
            sortWrapper.setIconCls("icon-style-page-key");
            sortWrapper.setAbout(assetsSort.getAbout());
            /*if("tree".equals(type)){
                sortWrapper.setChecked(false);
            }*/

            List<AssetsSort> subSortList = assetsSortSetMapper.getAssetsSortListByFid(assetsSort.getId());
            if(subSortList.isEmpty()){
                sortWrapper.setLeaf(true);
                sortWrapper.setChildren(null);
                sortWrapperList.add(sortWrapper);
            }else{
                sortWrapper.setLeaf(false);
                sortWrapper.setChildren(getAssetsSortListByFid(type,assetsSort.getId()));
                sortWrapperList.add(sortWrapper);
            }
        }
        return sortWrapperList;
    }
}
