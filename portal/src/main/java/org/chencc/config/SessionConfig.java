package org.chencc.config;

import org.aspectj.lang.ProceedingJoinPoint;
import org.aspectj.lang.annotation.Around;
import org.aspectj.lang.annotation.Aspect;
import org.chencc.common.CurrentUserWrapper;
import org.springframework.stereotype.Component;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;
import javax.servlet.http.HttpSession;

/**
 * @Description 系统session aop控制层
 * @author:chencc
 * @Date:2018/8/30
 * @Time:下午10:13
 */
@Component
@Aspect
public class SessionConfig {
    @Around(value = "@annotation(org.chencc.config.CurrentUser)")
    public Object aroundManager(ProceedingJoinPoint point) throws Exception {
        HttpServletRequest request = SystemContent.getRequest();
        HttpServletResponse response = SystemContent.getResponse();
        HttpSession session = SystemContent.getSession();

        String path = request.getContextPath();
        String basePath = request.getScheme() + "://" + request.getServerName()
                + ":" + request.getServerPort() + path + "/";

        //SessionType type = this.getSessionType(point);
        //if (type == null) {
            //throw new Exception("The value of CurrentUser is must.");
        //}

        CurrentUserWrapper currentUser = (CurrentUserWrapper)session.getAttribute("currentUser");
        //if (currentUser != null) {
        //    return currentUser;
        //}
        /*
        throw new MissingServletRequestPartException("currentUser");

        Object uobj = session.getAttribute("user");
        Object mobj = session.getAttribute("manager");

        boolean isUser = type == SessionType.USER && uobj != null;
        boolean isManager = type == SessionType.MANAGER && mobj != null;
        boolean isUserOrManager = type == SessionType.OR&& (mobj != null || uobj != null);
        */
        try {
            if (null != currentUser) {
                return point.proceed();
            } else { // 会话过期或是session中没用户
                if (request.getHeader("x-requested-with") != null
                        && request.getHeader("x-requested-with").equalsIgnoreCase(    //ajax处理
                        "XMLHttpRequest")) {
                    response.addHeader("sessionstatus", "timeout");
                    // 解决EasyUi问题
                    //response.getWriter().print("{\"rows\":[],\"success\":false,\"total\":0}");
                }else{//http跳转处理
                    response.sendRedirect(basePath + "error/nosession");
                }
            }
        } catch (Throwable e) {
            // TODO Auto-generated catch block
            e.printStackTrace();
        }
        return null;
    }

    /*private SessionType getSessionType(ProceedingJoinPoint pj) {
        // 获取切入的 Method
        MethodSignature joinPointObject = (MethodSignature) pj.getSignature();
        Method method = joinPointObject.getMethod();
        boolean flag = method.isAnnotationPresent(CurrentUser.class);
        if (flag) {
            CurrentUser currentUser = method.getAnnotation(CurrentUser.class);
            return currentUser;
        }
        return null;
    }*/

}

