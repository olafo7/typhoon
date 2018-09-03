package org.chencc.service.impl;

import org.chencc.common.MenuWrapper;
import org.chencc.common.SubMenuWrapper;
import org.chencc.mapper.WelcomeMapper;
import org.chencc.model.SystemMenu;
import org.chencc.model.SystemPagesize;
import org.chencc.service.WelcomeService;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.stereotype.Service;
import org.springframework.transaction.annotation.Transactional;

import java.util.ArrayList;
import java.util.List;

/**
 * @Description
 * @author:chencc
 * @Date:2018/7/24
 * @Time:下午10:15
 */
@Service
@Transactional
public class WelcomeServiceImpl implements WelcomeService {

    @Autowired
    private WelcomeMapper welcomeMapper;

    @Override
    public List<MenuWrapper> getSystemMenuList() {
        List<MenuWrapper> menuList = new ArrayList<MenuWrapper>();

        //一级菜单
        String firstSql = "select * from db_system_menu where db_system_menu.pid = 0 and db_system_menu.show=1 order by db_system_menu.order";
        List<SystemMenu> firstMenuList = welcomeMapper.getSystemMenuList(firstSql);
        for(SystemMenu firstSystemMenu : firstMenuList){
            MenuWrapper menuWrapper = new MenuWrapper();
            menuWrapper.setId(firstSystemMenu.getMenuId());
            menuWrapper.setText(firstSystemMenu.getText());
            menuWrapper.setNewtext(firstSystemMenu.getNewText());
            menuWrapper.setCls(firstSystemMenu.getCls());
            menuWrapper.setIconCls(firstSystemMenu.getIconCls());
            menuWrapper.setExpanded(firstSystemMenu.getExpanded()==0?false:true);
            menuWrapper.setSingleClickExpand(String.valueOf(firstSystemMenu.getSingleClickExpand()));
            menuWrapper.setIsIsClass(String.valueOf(firstSystemMenu.getIsClass()));
            menuWrapper.setOrder(String.valueOf(firstSystemMenu.getOrder()));
            menuWrapper.setIsCustom(false);

            if(firstSystemMenu.getLeaf() == 0){  //说明不是叶子节点，继续循环下级菜单(二级菜单)
                String secondSql = "select * from db_system_menu where db_system_menu.pid = "+firstSystemMenu.getId()+" and db_system_menu.show=1 order by db_system_menu.order";
                List<SystemMenu> secondMenuList = welcomeMapper.getSystemMenuList(secondSql);
                List<SubMenuWrapper> menuList2 = new ArrayList<SubMenuWrapper>();

                for(SystemMenu secondSystemMenu : secondMenuList){
                    SubMenuWrapper secondMenuWrapper = new SubMenuWrapper();

                    secondMenuWrapper.setId(secondSystemMenu.getMenuId());
                    secondMenuWrapper.setText(secondSystemMenu.getText());
                    secondMenuWrapper.setNewtext(secondSystemMenu.getNewText());
                    secondMenuWrapper.setCls(secondSystemMenu.getCls());
                    secondMenuWrapper.setIconCls(secondSystemMenu.getIconCls());
                    secondMenuWrapper.setLeaf(secondSystemMenu.getLeaf()==0?false:true);
                    secondMenuWrapper.setExpanded(secondSystemMenu.getExpanded()==0?false:true);
                    secondMenuWrapper.setSingleClickExpand(secondSystemMenu.getSingleClickExpand()==0?false:true);
                    secondMenuWrapper.setForceRefresh(secondSystemMenu.getForceRefresh()==0?false:true);
                    secondMenuWrapper.setIsClass(secondSystemMenu.getIsClass()==0?false:true);
                    secondMenuWrapper.setCode(secondSystemMenu.getCode());
                    secondMenuWrapper.setAutoLoadUrl(secondSystemMenu.getAutoLoadUrl());
                    secondMenuWrapper.setHref(secondSystemMenu.getHref());
                    secondMenuWrapper.setSystem(String.valueOf(secondSystemMenu.getSystem()));
                    secondMenuWrapper.setClickEvent(secondSystemMenu.getClickEvent());
                    secondMenuWrapper.setOrder(String.valueOf(secondSystemMenu.getOrder()));
                    secondMenuWrapper.setIsCustom(false);
                    secondMenuWrapper.setIsNewLabel(false);

                    if(secondSystemMenu.getLeaf()==0) {  //说明不是叶子节点，继续循环下级菜单(三级菜单)
                        String thirdSql = "select * from db_system_menu where db_system_menu.pid = "+secondSystemMenu.getId()+" and db_system_menu.show=1 order by db_system_menu.order";
                        List<SystemMenu> thirdMenuList = welcomeMapper.getSystemMenuList(thirdSql);
                        List<SubMenuWrapper> menuList3 = new ArrayList<SubMenuWrapper>();

                        for(SystemMenu thirdSystemMenu : thirdMenuList) {
                            SubMenuWrapper thirdMenuWrapper = new SubMenuWrapper();

                            thirdMenuWrapper.setId(thirdSystemMenu.getMenuId());
                            thirdMenuWrapper.setText(thirdSystemMenu.getText());
                            thirdMenuWrapper.setNewtext(thirdSystemMenu.getNewText());
                            thirdMenuWrapper.setCls(thirdSystemMenu.getCls());
                            thirdMenuWrapper.setIconCls(thirdSystemMenu.getIconCls());
                            thirdMenuWrapper.setLeaf(thirdSystemMenu.getLeaf()==0?false:true);
                            thirdMenuWrapper.setExpanded(thirdSystemMenu.getExpanded()==0?false:true);
                            thirdMenuWrapper.setSingleClickExpand(thirdSystemMenu.getSingleClickExpand()==0?false:true);
                            thirdMenuWrapper.setForceRefresh(thirdSystemMenu.getForceRefresh()==0?false:true);
                            thirdMenuWrapper.setIsClass(thirdSystemMenu.getIsClass()==0?false:true);
                            thirdMenuWrapper.setCode(thirdSystemMenu.getCode());
                            thirdMenuWrapper.setAutoLoadUrl(thirdSystemMenu.getAutoLoadUrl());
                            thirdMenuWrapper.setHref(thirdSystemMenu.getHref());
                            thirdMenuWrapper.setSystem(String.valueOf(thirdSystemMenu.getSystem()));
                            thirdMenuWrapper.setClickEvent(thirdSystemMenu.getClickEvent());
                            thirdMenuWrapper.setOrder(String.valueOf(thirdSystemMenu.getOrder()));
                            thirdMenuWrapper.setIsCustom(false);
                            thirdMenuWrapper.setIsNewLabel(false);

                            menuList3.add(thirdMenuWrapper);
                        }
                        secondMenuWrapper.setChildren(menuList3);
                        menuList2.add(secondMenuWrapper);

                    }else{
                        menuList2.add(secondMenuWrapper);
                    }
                }

                menuWrapper.setChildren(menuList2);
                menuList.add(menuWrapper);
            }else{
                menuList.add(menuWrapper);
            }
        }

        return menuList;
    }

    @Override
    public List<SystemPagesize> getPageSize(){
        return welcomeMapper.getPagesize();
    }
}
