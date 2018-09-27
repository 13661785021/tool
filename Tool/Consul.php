<?php
namespace Tool;

/**
 * Consul 工具类 
 */
class Consul
{	
	/**
	 * 服务地址
	 */
	private $ipAddress;

	/**
	 * 服务端口
	 */
	private $port;

	/**
	 * 对象容器
	 */
	static $instance = null;

	/**
	 * 请求地址
	 * @var string
	 */
	public $requestUrl = '';

	/**
	 * [$interfaceArr 接口池] 接口不全面，用到可以再次进行补充
	 * @var [type]
	 */
	private $interfaceArr = [
		'checks'				=> '/v1/agent/checks', 						//返回本地agent注册的所有检查(包括配置文件和HTTP接口)
		'services'				=> '/v1/agent/services',					//返回本地agent注册的所有 服务
		'members'				=> '/v1/agent/members', 					//返回agent在集群的gossip pool中看到的成员
		'self'					=> '/v1/agent/self', 						//返回本地agent的配置和成员信息
		'join'					=> '/v1/agent/join',						//触发本地agent加入node
		'forceLeave'			=> '/v1/agent/force-leave', 				//强制删除node
		'check_register'		=> '/v1/agent/check/register', 				//在本地agent增加一个检查项，使用PUT方法传输一个json格式的数据
		'check_deregister'		=> '/v1/agent/check/deregister', 			//注销一个本地agent的检查项
		'pass'					=> '/v1/agent/check/pass', 					//设置一个本地检查项的状态为passing
		'warn'					=> '/v1/agent/check/warn', 					//置一个本地检查项的状态为warning
		'fail'					=> '/v1/agent/check/fail', 					//设置一个本地检查项的状态为critical
		'service_register'		=> '/v1/agent/service/register', 			//在本地agent增加一个新的服务项，使用PUT方法传输一个json格式的数据
		'service_deregister'	=> '/v1/agent/service/deregister', 			//注销一个本地agent的服务项
		'catalog_register'		=> '/v1/catalog/register', 					//注册一个新节点、服务或检查
		'catalog_deregister'	=> '/v1/catalog/deregister', 				//注销节点、服务或检查
		'catalog_datacenters'	=> '/v1/catalog/datacenters'				//列出已知数据中心
		'catalog_nodes'			=> '/v1/catalog/nodes', 					//列出给定DC中的节点 或者给定节点 
		'catalog_services'		=> '/v1/catalog/services', 					//列出给定DC中的服务'catalog_service_node'	=> '/v1/catalog/service'//列出给定服务中的节点
		
	];	


 	/**	
 	 * [__construct 防止外部继承]
 	 * @author yangxiaogang 2018-09-27
 	 * @param  string $ipAddress [服务node 地址]
 	 * @param  int    $port  [服务端口]
 	 */
	private function __construct($ipAddress,$port)
	{	
		//服务地址
		$this->ipAddress = $ipAddress;

		//服务端口
		$this->port 	 = $port;
	}

	/**
	 * [__clone 防止外部克隆]
	 * @author yangxiaogang 2018-09-27
	 * @return [type] [description]
	 */
	private function __clone(){  }

	/**
	 * [__wakeup 防止被反序列化]
	 * @author yangxiaogang 2018-09-27
	 */
	private function __wakeup(){  }

	/**
	 * [getInstance 服务单例对象]
	 * @author yangxiaogang 2018-09-27
	 * @param  string $ipAddress [服务地址]
	 * @param  int    $port  [description]
	 * @return [type]        [description]
	 */
	static function getInstance($ipAddress,$port)
	{
		if( is_null( self::$instance ) )
		{
			self::$instance = new self($ipAddress,$port);
		} 
		return self::$instance;
	}

	/**
	 * [save   设置接口地址]
	 * @author yangxiaogang 2018-09-27
	 * @param  string $interfaceFind [接口地址]
	 * @param  [type] $param         [入参]
	 * @return [type]                [description]
	 */
	function setInterfaceAddress($interfaceFind)
	{	
		//接口地址
		$this->requestUrl = 'http://'.$this->ipAddress.':'.$this->port.($this->interfaceArr)[$interfaceFind];

	 	return $this;
	}

	 /**
     * PUT请求
     * @param $request_uri
     * @param $data
     * @return mixed
     */
    function curlPut($data)
    {
        $ch = curl_init();
        $header[] = "Content-type:application/json";

        curl_setopt($ch,CURLOPT_URL,$this->requestUrl);
        curl_setopt($ch,CURLOPT_CUSTOMREQUEST,"PUT");
        curl_setopt($ch,CURLOPT_HEADER,0);
        curl_setopt($ch,CURLOPT_HTTPHEADER,$header);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch,CURLOPT_POSTFIELDS,$data);

        $res = curl_exec($ch);
        curl_close($ch);

        return $res;
    }

 	

}