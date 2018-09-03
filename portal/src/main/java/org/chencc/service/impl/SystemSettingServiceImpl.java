package org.chencc.service.impl;

import org.chencc.common.MenuWrapperForSetting;
import org.chencc.mapper.SystemSettingMapper;
import org.chencc.model.SystemConfig;
import org.chencc.model.SystemMenu;
import org.chencc.model.SystemOutlink;
import org.chencc.service.SystemSettingService;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.stereotype.Service;
import org.springframework.transaction.annotation.Transactional;

import java.util.ArrayList;
import java.util.List;
import java.util.Map;

/**
 * @Description
 * @author:chencc
 * @Date:2018/7/22
 * @Time:下午3:30
 */
@Service
@Transactional
public class SystemSettingServiceImpl implements SystemSettingService {

    @Autowired
    private SystemSettingMapper systemSettingMapper;

    @Override
    public SystemConfig getSystemConfig() {
        return systemSettingMapper.getSystemConfig();
    }

    @Override
    public List<MenuWrapperForSetting> getSystemMenuList() {
        List<MenuWrapperForSetting> menuList = new ArrayList<MenuWrapperForSetting>();

        //一级菜单
        String firstSql = "select * from db_system_menu where db_system_menu.pid = 0 and db_system_menu.show=1 order by db_system_menu.order";
        List<SystemMenu> firstMenuList = systemSettingMapper.getSystemMenuList(firstSql);
        for(SystemMenu firstSystemMenu : firstMenuList){
            MenuWrapperForSetting firstMenuWrapper = new MenuWrapperForSetting();
            firstMenuWrapper.setId(String.valueOf(firstSystemMenu.getId()));
            firstMenuWrapper.setPid("0");
            firstMenuWrapper.setMenuId(firstSystemMenu.getMenuId());
            firstMenuWrapper.setLanguage(firstSystemMenu.getLanguage());
            firstMenuWrapper.setText(firstSystemMenu.getText());
            firstMenuWrapper.setNewText(firstSystemMenu.getNewText());
            firstMenuWrapper.setCls(firstSystemMenu.getCls());
            firstMenuWrapper.setIconCls(firstSystemMenu.getIconCls());
            firstMenuWrapper.setLeaf(firstSystemMenu.getLeaf()==0?false:true);
            firstMenuWrapper.setAbout(firstSystemMenu.getAbout());
            firstMenuWrapper.setSystem(String.valueOf(firstSystemMenu.getSystem()));
            firstMenuWrapper.setBlong(firstSystemMenu.getBlong());
            firstMenuWrapper.setIsCustom(false);
            firstMenuWrapper.setUiProvider("col");
            firstMenuWrapper.setChecked(true);

            if(firstSystemMenu.getLeaf() == 0){  //说明不是叶子节点，继续循环下级菜单(二级菜单)
                String secondSql = "select * from db_system_menu where db_system_menu.pid = "+firstSystemMenu.getId()+" and db_system_menu.show=1 order by db_system_menu.order";
                List<SystemMenu> secondMenuList = systemSettingMapper.getSystemMenuList(secondSql);
                List<MenuWrapperForSetting> menuList2 = new ArrayList<MenuWrapperForSetting>();

                for(SystemMenu secondSystemMenu : secondMenuList){
                    MenuWrapperForSetting secondMenuWrapper = new MenuWrapperForSetting();
                    secondMenuWrapper.setId(String.valueOf(secondSystemMenu.getId()));
                    secondMenuWrapper.setPid(String.valueOf(firstSystemMenu.getId()));
                    secondMenuWrapper.setMenuId(secondSystemMenu.getMenuId());
                    secondMenuWrapper.setLanguage(secondSystemMenu.getLanguage());
                    secondMenuWrapper.setText(secondSystemMenu.getText());
                    secondMenuWrapper.setNewText(secondSystemMenu.getNewText());
                    secondMenuWrapper.setCls(secondSystemMenu.getCls());
                    secondMenuWrapper.setIconCls(secondSystemMenu.getIconCls());
                    secondMenuWrapper.setLeaf(secondSystemMenu.getLeaf()==0?false:true);
                    secondMenuWrapper.setAbout(secondSystemMenu.getAbout());
                    secondMenuWrapper.setSystem(String.valueOf(secondSystemMenu.getSystem()));
                    secondMenuWrapper.setBlong(secondSystemMenu.getBlong());
                    secondMenuWrapper.setIsCustom(false);
                    secondMenuWrapper.setUiProvider("col");
                    secondMenuWrapper.setChecked(true);

                    if(secondSystemMenu.getLeaf()==0) {  //说明不是叶子节点，继续循环下级菜单(三级菜单)
                        String thirdSql = "select * from db_system_menu where db_system_menu.pid = "+secondSystemMenu.getId()+" and db_system_menu.show=1 order by db_system_menu.order";
                        List<SystemMenu> thirdMenuList = systemSettingMapper.getSystemMenuList(thirdSql);
                        List<MenuWrapperForSetting> menuList3 = new ArrayList<MenuWrapperForSetting>();

                        for(SystemMenu thirdSystemMenu : thirdMenuList) {
                            MenuWrapperForSetting thirdMenuWrapper = new MenuWrapperForSetting();
                            thirdMenuWrapper.setId(String.valueOf(thirdSystemMenu.getId()));
                            thirdMenuWrapper.setPid(String.valueOf(secondSystemMenu.getId()));
                            thirdMenuWrapper.setMenuId(thirdSystemMenu.getMenuId());
                            thirdMenuWrapper.setLanguage(thirdSystemMenu.getLanguage());
                            thirdMenuWrapper.setText(thirdSystemMenu.getText());
                            thirdMenuWrapper.setNewText(thirdSystemMenu.getNewText());
                            thirdMenuWrapper.setCls(thirdSystemMenu.getCls());
                            thirdMenuWrapper.setIconCls(thirdSystemMenu.getIconCls());
                            thirdMenuWrapper.setLeaf(thirdSystemMenu.getLeaf()==0?false:true);
                            thirdMenuWrapper.setAbout(thirdSystemMenu.getAbout());
                            thirdMenuWrapper.setSystem(String.valueOf(thirdSystemMenu.getSystem()));
                            thirdMenuWrapper.setBlong(thirdSystemMenu.getBlong());
                            thirdMenuWrapper.setIsCustom(false);
                            thirdMenuWrapper.setUiProvider("col");
                            thirdMenuWrapper.setChecked(true);

                            menuList3.add(thirdMenuWrapper);
                        }
                        secondMenuWrapper.setChildren(menuList3);
                        menuList2.add(secondMenuWrapper);

                    }else{
                        menuList2.add(secondMenuWrapper);
                    }
                }

                firstMenuWrapper.setChildren(menuList2);
                menuList.add(firstMenuWrapper);
            }else{
                menuList.add(firstMenuWrapper);
            }
        }

        return menuList;
    }

    @Override
    public List<SystemOutlink> getOutlinkList() {
        return systemSettingMapper.getOutlinkList();
    }

    @Override
    public List<Map> getUserListByType(String type) {
        return systemSettingMapper.getUserListByType(type);
    }
}
