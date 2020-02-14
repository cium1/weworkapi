<?php
/**
 * Author:  Yejia
 * Email:   ye91@foxmail.com
 */

namespace Cium\WeWorkApi\api\struct;


class Redis
{
    public $host = '127.0.0.1';
    public $port = '6379';
    public $password = '';
    public $timeout = 0;
    
    //实例化的时候只需要传入redis的配置的数组
    public function __construct($config=[]){
		$this->host=$config['host']??$this->host;
		$this->port=$config['port']??$this->port;
		$this->password=$config['password']??$this->password;
		$this->timeout=$config['timeout']??$this->timeout;
	}
}
