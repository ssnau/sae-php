<?php
/**
 * Mini2SAE - PHP framework for SAE
 *
 * @author Caleng Tan <tcm1024@gmail.com>
 * @copyright Copyright(C)2010, Caleng Tan
 * @link http://code.google.com/p/fabos/
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 * @version Mini2SAE v0.2
 */

/**
 * Mini2SAE核心类
 * @final
 * @package include
 */
final class App
{
    /**
     * 单例对象
     * @static
     * @access private
     * @var array
     */
    private static $mObject = array();
    /**
     * App配置
     * @static
     * @access public
     * @var array
     */
    public  static $mConfig = array();
    /**
     * App路由列表
     * @static
     * @access private
     * @var array
     */
    private static $mRoute  = array();
    /**
     * 模板参数
     * @static
     * @access private
     * @var array
     */
    private static $mTmpValue = array();
    /**
     * 当前URL地址URI
     * @static
     * @access private
     * @var string
     */
    private static $mCurrUri  = '';
    /**
     * 当前调用方法
     * @static
     * @access private
     * @var string
     */
    private static $mCurrMethod = '';
    /**
     * 当前请求的资源类型
     * @static
     * @access private
     * @var string
     */
    private static $mCurrContentType = '';
    /**
     * 浏览器缓存时间
     * @static
     * @access private
     * @var int
     */
    private static $mCurrCacheTime = 0;
    /**
     * 显示公共模板
     * @static
     * @access private
     * @var boolean
     */
    private static $mCurrLayout = true;
    /**
     * SAE图像实例对象
     * @static
     * @access public
     * @var object
     */
    public static $mImage = null;
    /**
     * SAE数据库实例对象
     * @static
     * @access public
     * @var object
     */
    public static $mDb    = null;
    /**
     * SAE memcache连接标识符
     * @static
     * @access public
     * @var object
     */
    public static $mMemcache = null;
    /**
     * App开始运行时间
     * @static
     * @access private
     * @var int
     */
    private static $mStartTime = 0;

	public static $TEMPLATE_CSS = '';
	public static $TEMPLATE_JS  = '';
	public static $TEMPLATE_META  = '';
    /**
     * App初始化
     * @static
     * @access public
     */
    public static function init()
    {
		self::startTime();
        session_start();
        //获取项目配置
        self::$mConfig = self::config('project');
        if (self::$mConfig['DB_DSN'])   self::connectDb();

        //分析当前URI
        self::analysisUri();
    }

    /**
     * 分析当前请求URL地址，获取URI
     * @static
     * @access private
     */
    private static function analysisUri()
    {
      $pos = strrpos($_SERVER['REQUEST_URI'], '?');//$_SERVER['REQUEST_URI']相当于path,以/开始
        if ($pos !== false) {
            $_SERVER['REQUEST_URI'] = substr($_SERVER['REQUEST_URI'], 0, $pos);
        }
        list(self::$mCurrUri, self::$mCurrContentType) = explode('.', $_SERVER['REQUEST_URI']);
        self::$mCurrMethod = $_SERVER['REQUEST_METHOD'];
        self::$mCurrContentType = empty(self::$mCurrContentType) ? 'html' : self::$mCurrContentType;
    }

    /**
     * 设置APP路由列表
     * Usage:
     * <code>
     *  App::loader('GET /mini', 'test');
     *  App::loader('GET /mini/book', array('class'=>'name'), 'xml', 100);
     * </code>
     * @static
     * @access public
     * @param string $pUri
     * @param string $pAction
     * @param string $pContentType
     * @param int $pCacheTime
     */
    public static function loader($pUri, $pAction, $pContentType = 'html', $pCacheTime = 0)
    {

		//注：$mRoute本身是一个数组，每个元素又是一个数组，每调用一次loader，$mRoute的length就会增1
        self::$mRoute[] = array('uri' => $pUri, 'action' => $pAction,
            'content_type' => $pContentType, 'cache' => $pCacheTime);
    }

    /**
     * 根据URI获取路由列表中对应的方法名
     * @static
     * @access public
     * @param string $pUri
     * @return array
     */
    public static function getAction($pUri = '')
    {
        $action = array();
        $pUri = empty($pUri) ? self::$mCurrUri : $pUri;
		//如果$pUri是以'/'结尾的话，则去掉末尾的'/'
		//mCurrMethod通常是GET或POST
        $pUri = self::$mCurrMethod.' '.
            ($pUri{strlen($pUri) -1} == '/' && strlen($pUri) > 1 ? substr($pUri, 0, -1) : $pUri);
        
		//注意额，在loader函数中对$mRoute赋值是$mRoute[] = array(....);
        foreach (self::$mRoute as $item) {
            if ($pUri == $item['uri'] && self::$mCurrContentType == $item['content_type']) {
                $action['action'] = $item['action'];
                $action['params'] = array();
                self::$mCurrCacheTime = $item['cache'];
                break;
            }
        }

		//如果没有的话，则调用智能方式
		//路径/className/actionName/param1/param2/param3
		//注：当前的$pUri是没有?p1=x&p2=y这些东西的，也不会是以/结尾
		//最后返回className::actionName
        if (empty($action)) {
            //self::redirectError(self::$mConfig['ERR_URL']);
			//去掉最前面的'/'
			$pUri = substr($pUri, strpos($pUri, '/') + 1);

			$uriAction = explode("/", $pUri);
			$className = ucfirst($uriAction[0]. 'Action');
			//获得方法名
			$method = stristr(self::$mCurrMethod, 'get') ? 'act' : self::$mCurrMethod;//把get转化为act
			$method = $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest' ? 'ajax' : $method;//如果是ajax请求，则以ajax为前缀
			$classAction = strtolower($method). ucfirst(isset($uriAction[1]) ? $uriAction[1] : 'default');
			$action = $className. '::'. $classAction;
			//查看相应函数是否存在，如不存在则直接404掉
			if(!method_exists($className, $classAction)) {
				App::redirectError('404');
			}

			$params = count($uriAction) > 2 ? array_splice($uriAction, 2) : array();//参数数组
			$action = array('action' => $action, 'params' => $params);
        }

        return $action;
    }

    /**
     * App运行
     * @static
     * @access public
     */
    public static function run()
    {
        self::call();
    }

    /**
     * 将App ini配置文件转化成数组
     * @static
     * @access public
     * @param string $pFileName
     * @return array
     */
    public static function config($pFileName)
    {
		/* SAE的线上bug, 不能打开ini文件，因此保存了一个无后缀的版本 */
        $pFileName = ROOT_DIR.'config/'.$pFileName;
        if (!file_exists($pFileName)) {
			$pFileName = $pFileName. '.ini';
		}	
        if (file_exists($pFileName)) {
			return parse_ini_file($pFileName);
		}
    }

    /**
     * 递归导入整个目录包括子目录的所有php文件
	 * 自从加入__autoload后，这个函数作废了
     * @static
     * @access public
     * @param string $pPath
     */
    public static function import($pPath)
    {
        $pPath = ROOT_DIR.$pPath; 
        if (is_dir($pPath)) {
            $dir = dir($pPath);//获取当前目录下的所有文件夹和文件（不包括子一级的）
            while (false !== ($entry = $dir->read())) {
                if ($entry != '.' && $entry != '..') {
                    $entry = $pPath.'/'.$entry;
                    if (is_dir($entry)) {
                        self::import($entry);
                    } else {
                        if (substr($entry, strrpos($entry, '.') + 1) == 'php') {
                            include_once $entry;
                        }
                    }
                }
            }
        } else {
            self::redirectError(self::$mConfig['ERR_PATH']);
        }
    }

    /**
     * 显示模板
     * @static
     * @access public
     * @param string $pTempName
     */
    public static function display($pTempName)
    {
        $file_path = ROOT_DIR.'template/'.$pTempName.'.php';
        if (file_exists($file_path)) {
            self::setVar('TEMPLATE_FILE', $file_path);
            extract(self::$mTmpValue);
            
            if (self::$mCurrLayout === true) {
                include_once ROOT_DIR.'template/layout.php';
            } else {
                include_once $file_path;
            }

            self::$mTmpValue = array();
            unset($file_path);
        } else {
            self::redirectError(self::$mConfig['ERR_TEMPLATE']);
        }
    }

    /**
     * 设置模板参数
     * @static
     * @access public
     * @param string $pVar
     * @param mixed $pValue
     */
    public static function setVar($pVar, $pValue)
    {
        self::$mTmpValue[$pVar] = $pValue;
    }

    /**
     * 设置显示layout
     * @static
     * @access public
     * @param boolean  $pIsset
     */
    public static function setLayout($pIsset = true)
    {
        self::$mCurrLayout = (bool) $pIsset;
    }

    /**
     * 设置页面标题
     * @static
     * @access public
     * @param string $pValue
     */
    public static function setTitle($pValue)
    {
        self::setVar('TEMPLATE_TITLE', $pValue);
    }

    /**
     * 设置页面MATE
     * Usage:
     * <code>
     *  App::setMeta(array('keywords'=>'content', 'description'=>'content'));
     * </code>
     * @static
     * @access public
     * @param array $pMeta
     */
    public static function setMeta($pMeta = array())
    {
		self::$TEMPLATE_META['keywords'] = self::$mConfig['TEMPLATE_KEYWORDS'];
		self::$TEMPLATE_META['description'] = self::$mConfig['TEMPLATE_DESCRIPTION'];
        array_merge(self::$TEMPLATE_META, $pMeta); //后面的会自动覆盖前面的
    }

    /**
     * 设置载入CSS
     * @static
     * @access public
     * @param string $pCssFileArray 多个CSS文件名数组
     */
    public static function setCss($pCssFileArray = '')
    {
        if (!empty($pCssFileArray)) {
			self::$TEMPLATE_CSS = $pCssFileArray;
        }
    }

    /**
     * 设置载入JS
     * @static
     * @access public
     * @param array $pJsFileArray 多个JS文件名数组
     */
    public static function setJs($pJsFileArray = '')
    {
        if (!empty($pJsFileArray)) {
			self::$TEMPLATE_JS = $pJsFileArray;
        }
    }

    /**
     * 输出META、CSS、JS的HTML内容
     * @static
     * @access public
     * @param string $pType META|JS|CSS
     * @param array $pFiles  如果为空，则调用默认
     */
    public static function outputCssJsMeta($pType, $pFiles = array())
    {
		if ($pType == 'CSS') {
			$html  = '<link rel="stylesheet" type="text/css" href="';
			$files = count($pFiles) ? $pFiles : self::$TEMPLATE_CSS;
			if (!$files) return;
			$html .= self::urlStatic("css/request.css?file=". join(',', $files). "&v=". VERSION.(IS_DEBUG ? "&debug=kaiqi" : "")).'" />'."\n";
			echo $html;
		} else if ($pType == 'JS') {
			$html  = '<script type="text/javascript" src="';
			$files = count($pFiles) ? $pFiles : self::$TEMPLATE_JS;
			if (!$files) return;
			$html .= self::urlStatic("js/request.js?file=". join(',', $files). "&v=". VERSION.(IS_DEBUG ? "&debug=kaiqi" : "")). '"></script>'."\n";
			echo $html;
		} else if ($pType == 'META') {
			self::setMeta($pFiles); //设置Meta
			foreach(self::$TEMPLATE_META as $k => $v) {
				if (strlen($v)) { //只要$v不为空，则设置
					$html  = '<meta name="'. $k. '" content="';
					$html .= $v.'" />'."\n";
				}
			}
            echo $html;
        }
    }

    /**
	 * [作废,现仅用来返回模板目录]
     * 获取template下js、css、image资源url地址
     * Usage:
     * <code>
     *  App::urlTemplate('js/filename.js');
     *  App::urlTemplate('css/filename.css');
     *  App::urlTemplate('image/filename.jpg');
     * </code>
     * @static
     * @access public
     * @param string $pPath
     * @return string
     */
    public static function urlTemplate($pPath)
    {
        return 'template/'.$pPath;
    }

	 /*
     * 获取static下js、css、image资源url地址
     * Usage:
     * <code>
     *  App::urlStatic('js/filename.js');
     *  App::urlStatic('css/filename.css');
     *  App::urlStatic('image/filename.jpg');
     * </code>
     * @static
     * @access public
     * @param string $pPath
     * @return string
     */
    public static function urlStatic($pPath)
    {
        return '/static/'.$pPath;
    }
    /**
     * 页面跳转
     * @static
     * @access public
     * @param string $pUrl
     * @param string $pMode LOCATION|REFRESH|META|JS
     */
    public static function redirect($pUrl = '', $pMode = 'LOCATION')
    {
        $url = $pUrl;
        switch ($pMode) {
            case 'LOCATION':
                if (headers_sent()) {
                    self::redirectError(self::$mConfig['ERR_HEADERSENT']);
                } else {
                    header("Location: {$url}");
                    exit;
                }
            case 'REFRESH':
                if (headers_sent()) {
                    self::redirectError(self::$mConfig['ERR_HEADERSENT']);
                } else {
                    header("Refresh: 0; url='".$url."'");
                    exit;
                }
            case 'META':
                echo "<mate http-equiv='refresh' content='0; url='".$url."' />";
                exit;
            case 'JS':
                echo "<script type='text/javascript'>";
                echo "window.location.href='".$url."';";
                echo "</script>";
                exit;
        }
    }

    /**
     * 载入指定URI内容
     * @static
     * @access public
     * @param string $pUri
     */
    public static function call($pUri = '')
    {
		if (LOG_SOURCE) self::logSource();
        try {
			$action = self::getAction($pUri);
			call_user_func_array($action['action'], $action['params']);
        } catch (Exception $e) {
            self::redirectError(self::$mConfig['ERR_METHOD']);
        }
    }

    /**
     * 单例对象
     * @static
     * @access public
     * @param string $pClassName
     * @return object
     */
    public static function getInstance($pClassName)
    {
        if (!array_key_exists($pClassName, self::$mObject)
            && class_exists($pClassName)) {
            self::$mObject[$pClassName] = new $pClassName;
        }
        return self::$mObject[$pClassName];
    }

    /**
     * 页面开始执行时间
     * @static
     * @access public
     */
    public static function startTime()
    {
        self::$mStartTime = microtime(true);
    }

    /**
     * 计算页面总执行时间
     * @static
     * @access public
     * @return float
     */
    public static function runTime()
    {
        return sprintf('%.6f', microtime(true) - self::$mStartTime);
    }

    /**
     * 连接SAE数据库
     * @static
     * @access private
     */
    private static function connectDb()
    {
        if (IS_SAE_ENV) {
            self::$mDb = self::getInstance('SaeMysql');
            self::$mDb->setCharset(self::$mConfig['DB_CHARSET']);
        } else {
            try {
                self::$mDb = new PDO(self::$mConfig['DB_MASTER_DSN'], 
                        self::$mConfig['DB_MASTER_USER'], self::$mConfig['DB_MASTER_PASSWORD']);
				self::$mDb->exec("set names utf8");
            } catch (PDOException $e) {
                self::redirectError($e->getMessage());
            }
        }
    }

    /**
     * 关闭SAE数据库连接
     * @static
     * @access public
     */
    public static function closeDb()
    {
        self::$mDb->closeDb();
    }

    /**
     * 数据表的增C、删D、改U
     * Usage:
     * <code>
     *  $id = App::exec('C', 'table', array(k=>v));
     *  App::exec('U', 'table', array(k=>v), 'condition');
     *  App::exec('D', 'table', '', 'condition');
     * </code>
     * @static
     * @access public
     * @param array $pArr 支持一维数组
     * @param string $pCond
     * @return int|boolean
     */
    public static function exec($pMethod, $pTab, $pArr = array(), $pCond = '')
    {
        if (!empty($pArr) && is_array($pArr)) {
            $str = '';
            foreach ($pArr as $k => $v) $str .= $k.'="'.$v.'",';
            $str = substr($str, 0, -1);
        }

        switch ($pMethod) {
            case 'C':
                $sql = 'INSERT INTO '.$pTab.' SET '.$str;
                break;
            case 'U':
                $sql  = 'UPDATE '.$pTab.' SET '.$str;
                $sql .= empty($pCond) ? '' : (' WHERE '.$pCond);
                break;
            case 'D':
                $sql = 'DELETE FROM '.$pTab.' WHERE '.$pCond;
                break;
        }

		if(IS_SAE_ENV) {
			$runSql = 'runSql';
			$errno = 'errno';
			$errmsg = 'errmsg';
			$lastId = 'lastId';
		} else {
			$runSql = 'exec';
			$errno = 'errorCode';
			$errmsg = 'errorInfo';
			$lastId = 'lastInsertId';
		}
		self::$mDb->$runSql($sql);
		if (self::$mDb->$errno() != 0) {
			self::redirectError(self::$mDb->$errmsg());
		} else {
			return $pMethod == 'C' ? self::$mDb->$lastId() : true;
		}
    }

	/*
     * 数据表的查询
     * Usage:
     * <code>
     *  $id = App::exec('C', 'table', array(k=>v));
     *  App::exec('U', 'table', array(k=>v), 'condition');
     *  App::exec('D', 'table', '', 'condition');
     * </code>
     * @static
     * @access public
     * @param string $pTab 
     * @param array $pColums 要选择的列
     * @param string $pCond  WHERE子句
     * @return int|boolean
	 */
    public static function select($pTab, $pColums='*', $pCond = '')
	{
        if (is_array($pColums)) {
            $str = '';
            foreach ($pColums as $k => $v) $str = $str.",".$v;
            $str = substr($str, 1);//去掉第一个逗号
        }
		if ($pCond) {
			$sql = 'SELECT '. $str .' FROM '.$pTab.' WHERE '.$pCond;
		} else {
			$sql = 'SELECT '. $str .' FROM '.$pTab;
		}
		return self::querySql($sql);
	}
    public static function selectFirst($pTab, $pColums='*', $pCond = '')
	{
		$result = self::select($pTab, $pColums, $pCond);
		if ($result) {
			return  @reset($result);
		} else {
			return false;
		}
	}
	public static function querySql($sql) {
		if(IS_SAE_ENV) {
			$runSql = 'getData';
			$errno = 'errno';
			$errmsg = 'errmsg';
			$lastId = 'lastId';
		} else {
			$runSql = 'query';
			$errno = 'errorCode';
			$errmsg = 'errorInfo';
			$lastId = 'lastInsertId';
		}
		$result = self::$mDb->$runSql($sql);
		if(IS_SAE_ENV) {
			return $result ? $result : false;
		} else {
			return $result ? $result->fetchAll() : false;
		}
	}
    /**
     * 错误处理页面
     * @static
     * @access public
     * @param string $pErrStr
     */
    public static function redirectError($pErrStr = '')
    {
        if (IS_DEBUG) {
            $html  = "<html>\n<head>\n<title>$pErrStr</title>\n";
            $html .= "<style type='text/css'>\n";
            $html .= "body{font-family:Arial;font-size:14px} ";
            $html .= "h2{color:#F00;border-bottom:1px solid #F00;line-height:30px} ";
            $html .= "span{color:#666;border-top:1px dashed #666;display:block;margin-top:20px}";
            $html .= "</style>\n</head>\n<body>";
            $html .= "<h2>ERROR: $pErrStr</h2>\n";
            $html .= self::backtrace();
			$html .= '<span>Time: '.self::runTime()."MS, Memory: ".(memory_get_usage()/1024)."KB, ";
            $html .= 'Power by '.self::$mConfig['VERSION']."</span>\n";
            $html .= "</body>\n</html>";
            exit($html);
        } else if($pErrStr == '404'){
			echo "404..很抱歉哈..没有相应的页面或页面已移除..";
			exit;
		} else {
			exit;
		}
    }

    /**
     * 追溯PHP程序执行顺序
     * @static
     * @access private
     * @return string
     */
    private static function backtrace()
    {
        $output = "Backtrace:\n";
        $backtrace = debug_backtrace();

        foreach ($backtrace as $bt) {
            $args = '';
            foreach ($bt['args'] as $a) {
                if (!empty($args)) {
                    $args .= ', ';
                }
                switch (gettype($a)) {
                    case 'integer':
                    case 'double':
                        $args .= $a;
                        break;
                    case 'string':
                        $a = htmlspecialchars(substr($a, 0, 64)).((strlen($a) > 64) ? '...' : '');
                        $args .= "\"$a\"";
                        break;
                    case 'array':
                        $args .= 'Array('.count($a).')';
                        break;
                    case 'object':
                        $args .= 'Object('.get_class($a).')';
                        break;
                    case 'resource':
                        $args .= 'Resource('.strstr($a, '#').')';
                        break;
                    case 'boolean':
                        $args .= $a ? 'True' : 'False';
                        break;
                    case 'NULL':
                        $args .= 'Null';
                        break;
                    default:
                        $args .= 'Unknown';
                }
            }
            $output .= "<br />\n#{$bt['line']} Call: ";
            $output .= "{$bt['class']}{$bt['type']}{$bt['function']}($args) - {$bt['file']}<br />\n";
        }
        return $output;
    }
	public static function mylog($str) {
        if (IS_SAE_ENV) {
			sae_set_display_errors(false);//关闭信息输出到屏幕，仅后台记录
			sae_debug($str);//记录日志
			sae_set_display_errors(true);//记录日志后再打开信息输出，否则会阻止正常的错误信息的显示
		} else {
			ToolLib::mylog($str);
		}
	}
	public static function logSource() {
		$src = ToolLib::request('src'); //来源
		$str = substr($str, 0, 20); //最多取前20位
		$source = array('rr' => '人人', 'qt' => '腾讯微博', 'qz' =>'QZone', 'ff' => '饭否',
			'tw'=>'twitter', 'wb' => '新浪微博', 'db' => '豆瓣', 'fb' => 'facebook', 'qq' => 'QQ');
		if ($src) {
			if (!isset($source[$src])) {
				$src = $src;
			} else {
				$src = $source[$src];
			}
			$ip = $_SERVER['REMOTE_ADDR'];
			$request_uri = $_SERVER['REQUEST_URI'];
			//if ($ip === '115.154.72.95') return; //如果是我的ip,则忽略..
			$time = time();
			$visit = new VisitEntity();
			$visit->set(array('source' => $src, 'ip' => $ip, 'request_uri' => $request_uri, 'time' => time()));
			$visit->insert();
		}
	}
}
