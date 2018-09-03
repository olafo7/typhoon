package org.chencc.config;

import org.apache.log4j.Logger;
import org.aspectj.lang.ProceedingJoinPoint;
import org.aspectj.lang.annotation.Around;
import org.aspectj.lang.annotation.Aspect;
import org.aspectj.lang.annotation.Pointcut;
import org.springframework.stereotype.Component;
import org.springframework.web.context.request.RequestContextHolder;
import org.springframework.web.context.request.ServletRequestAttributes;

import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;
import javax.servlet.http.HttpSession;
import java.io.IOException;

/**
 * @Description 登录超时验证，若超时则跳转回登录页面重新登录
 * @author:chencc
 * @Date:2018/7/29
 * @Time:下午5:39
 */
@Aspect
@Component
public class SessionTimeOutAspect {
    private static Logger logger = Logger.getLogger(SessionTimeOutAspect.class);

    public SessionTimeOutAspect() {
    }
    @Pointcut("execution(public * org.chencc.controller..*.*(..))")
    public void controllerPointcut(){
    }
    @Pointcut("execution(public * org.chencc.controller.LoginController.*(..))")
    public void rootPointcut(){//登录登出功能不需要Session验证
    }
    @Pointcut("controllerPointcut()&&(!rootPointcut())")
    public void sessionTimeOutPointcut(){
    }
    @Around("sessionTimeOutPointcut()")
    public Object sessionTimeOutAdvice(ProceedingJoinPoint pjp) throws IOException {
        Object result = null;
        String targetName = pjp.getTarget().getClass().getSimpleName();
        String methodName = pjp.getSignature().getName();
        logger.info("----------------执行方法-----------------");
        logger.info("类名："+targetName+" 方法名："+methodName);
        HttpServletResponse response = null;
        for (Object param : pjp.getArgs()) {
            if (param instanceof HttpServletResponse) {
                response = (HttpServletResponse) param;
            }
        }
        HttpServletRequest request = ((ServletRequestAttributes) RequestContextHolder.getRequestAttributes()).getRequest();
        logger.info("sessionTimeout："+request.getSession().getMaxInactiveInterval());

        if(null != request){
            try {
                result = pjp.proceed();
            } catch (Throwable e) {
                e.printStackTrace();
            }
            return result;
        }else{
            logger.debug("Session已超时，正在跳转回登录页面");
            response.sendRedirect("/");
        }
        HttpSession session = request.getSession();
        /*if(session.getAttribute("currentUser")!=null){
            try {
                result = pjp.proceed();
            } catch (Throwable e) {
                e.printStackTrace();
            }
            return result;
        } else{
            logger.debug("Session已超时，正在跳转回登录页面");
            response.sendRedirect("/");
        }*/
        return result;
    }

}
