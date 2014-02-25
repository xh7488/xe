<?php
namespace Xe;
/*
 ----------------------------------------------------------------------------------------------------------------
A Simple Database Access Class Depend On PDO
Author: Zhang Junlei (zhangjunlei26@gmail.com)
----------------------------------------------------------------------------------------------------------------
//实例化DB
$db=get_db(); // or $db=new DBA($dsn,$user,$password,$driver_options);

//读接口
$sql="SELECT * FROM countries";//取多条记录
$rs=$db->query($sql);

$sql="SELECT * FROM countries WHERE country_abbr=? AND currency=? LIMIT 1";//带limit 1,只取1条记录
$rs=$db->query($sql,'CN','CNY');

$sql="SELECT count(0) FROM countries";//取一个字段
$rows=$db->query($sql);

//写接口
 
$sql = "INSERT INTO sys_logs (user_id,action_id,content,ip) VALUES (?,?,?,?)";//如果字段为自增，返回被插入自动ID值
$rs=$db->exec($sql,1,1,'test',12345);

$sql="UPDATE * FROM `goods` SET `Price`=`Price`*0.9 WHERE goods_id in(1,2,3,4,5)";//返回影响的记录数
$rs=$db->exec($sql);

//快速存数组接口save，将数据快速保存到表对应的字段
$arr = array(
    //'id'=>3,
	'user_id' => 1, 
	'action_id' => 2, 
	'content' => 'test2999999999999', 
	'ip' => 99989,
);
$rs=$db->save('sys_logs',$arr,'id');//如果key在$arr中存在则为更新，不存在为插入数据，多key以逗点分隔

----------------------------------------------------------------------------------------------------------------
*/
class Dba
{

	protected $handler;

	protected $dsn;

	protected $user;

	protected $password;
	
	protected $prefix;

	protected $driver_options = array();

	protected $current_stmt;

	function __construct ($dsn ,$user ,$password = "" ,$prefix='',$driver_options = array())
	{
		$this->dsn = $dsn;
		$this->user = $user;
		$this->password = $password;
		$this->prefix=$prefix;
		$this->driver_options = $driver_options;
	}
	function getTable($table)
	{
		return "`{$this->prefix}{$table}`";
	}

	/**
	 * 创建连接
	 * 
	 * @return DBA
	 */
	function connect ()
	{
		$this->handler = new \PDO($this->dsn, $this->user, $this->password, $this->driver_options);
		if ($this->driver_options)
		{
			foreach ($this->driver_options as $k => $v)
			{
				$this->handler->setAttribute($k, $v);
			}
		}
		return $this;
	}

	/**
	 * 执行查询类语句
	 * 
	 * @param $sql string       	
	 */
	public function query ($sql)
	{
		if (! $this->handler) $this->connect();
		$this->current_stmt = NULL;
		$args = func_get_args();
		if (1 < count($args))
		{
			$stmt = $this->get_pdo_statment($args);
			if (! $stmt) return FALSE;
		}
		else
		{
			$stmt = $this->handler->query($sql);
			if (! $stmt) return FALSE;
		}
		if (preg_match('/limit 1;?$/i', $sql))
		{
			$rs = $stmt->fetch(\PDO::FETCH_ASSOC);
			if (is_array($rs) && 1 === count($rs))
			{
				$rs = current($rs);
			}
		}
		else
		{
			$rs = $stmt->fetchAll(\PDO::FETCH_ASSOC);
		}
		return $rs;
	}
	/**
	 * 设定事务隔离级别，默认为未提交读共享
	 *
	 * @param $level string
	 */
	public function set_level ($level = "READ UNCOMMITTED")
	{
		return $this->exec("SET SESSION TRANSACTION ISOLATION LEVEL {$level};");
	}
	/**
	 * 以指定字段值为下标方式取出结果，现仅支持三层
	 * @param string | Array $keys 注意，该方法因偷懒,最多只能处理三层key
	 * @param string $sql
	 */
	public function query_by($key,$sql)
	{
		if(is_string($key))$key=explode(',', $key);
		$args=func_get_args();
        $cnt=count($args);
        if($cnt===2)
        {
        	$rs=$this->query($sql);
        }
        elseif($cnt===3)
        {
        	$rs=$this->query($sql,$args[2]);
        }
        elseif($cnt===4)
        {
        	$rs=$this->query($sql,$args[2],$args[3]);
        }
        elseif($cnt===5)
        {
        	$rs=$this->query($sql,$args[2],$args[3],$args[4]);
        }
        else{
        	array_shift($args);
        	//三个以上参数，性能不用再考虑
            $rs=call_user_func_array(array($this,'query'), $args);
        }
        if(!$rs)return $rs;
        
        $cnt_key=count($key);
        $ret = array();
        foreach ($rs as $v)
        {
        	$k1 = $v[$key[0]];
        	if($cnt_key==1)
        	{
        		$ret[$k1] = $v;
        	}
        	elseif($cnt_key==2)
        	{
        		$k2=$v[$key[1]];
        		$ret[$k1][$k2] = $v;
        	}
        	elseif($cnt_key==3){
        		$k2=$v[$key[1]];
        		$k3=$v[$key[2]];
        		$ret[$k1][$k2][$k3] = $v;
        	}
        }
        return $ret;
	}

	/**
	 * 执行增、删、改语句
	 * 
	 * @param $sql string       	
	 * @return Ambigous integer|boolean
	 */
	public function exec ($sql)
	{
		if (! $this->handler) $this->connect();
		$this->current_stmt = NULL;
		$args = func_get_args();
		$is_insert = strncasecmp($sql, 'INSERT', 6) === 0;
		if (1 === count($args))
		{
			$rs = $this->handler->exec($sql);
			$insert_id=$this->handler->lastInsertId();
			return ($is_insert && $insert_id) ? $insert_id : $rs;
		}
		else
		{
			$stmt = $this->get_pdo_statment($args);
			if (! $stmt) return FALSE;
			$insert_id=$this->handler->lastInsertId();
			return ($is_insert && $insert_id) ? $insert_id : $stmt->rowCount();
		}
	}
	/**
	 * 过滤不需要的字段
	 * @param array $data
	 * @param string $remove_fields
	 * @return array
	 */
	protected function remove_fields(array $data,$remove_fields=NULL)
	{
		if($remove_fields && is_string($remove_fields))
		{
			$remove_fields=explode(',',$remove_fields);
			foreach($remove_fields as $k)
			{
				unset($data[$k]);
			}
		}
		return $data;
	}
	/**
	 * 保存数据到表
	 * @param string $table    要保存的表名
	 * @param array $data    要保存的数组
	 * @param string $key              表的主键，默认为id
	 * @param string $remove_fields    需要删除过滤掉的字段
	 * @param boolean $force_insert    强制插入操作
	 */
	public function save($table, array $data, $key='id', $remove_fields=NULL, $force_insert=FALSE)
	{
		//删除需要过滤掉的字段
		if($remove_fields)$data=$this->remove_fields($data,$remove_fields);
		if($force_insert)
		{
			return $this->_insert($table, $data);
		}else{
			if(is_string($key))$key=array_flip(explode(',',$key));
			$is_update=TRUE;
			foreach($key as $k=>$v)
			{
				if(!isset($data[$k]))
				{
					$is_update=FALSE;
					break;
				}
			}
			if($is_update)
			{
				return $this->_update($table, $data, $key);
			}else{
				return $this->_insert($table, $data);
			}
		}
	}
	/**
	 * 向表中插入数据
	 * @param string $table
	 * @param array $data
	 */
	protected function _insert($table, array $data)
	{
		if(!$table || !$data || !is_array($data))return FALSE;
		$fields=implode("`,`",array_keys($data));
		$fields="`{$fields}`";
		$value_fill=substr(str_repeat('?,', count($data)),0,-1);
		$sql="INSERT INTO `{$table}` ({$fields}) VALUES ({$value_fill});";
		return $this->exec($sql,array_values($data));
	}
	protected function _update($table,array $data,$key='id')
	{
		if(!$table || !$data || !is_array($data))return FALSE;
		//过滤key的合法性
		if(is_string($key))$key=array_flip(explode(',',$key));
		foreach($key as $k=>$v)
		{
			if(!isset($data[$k]))
			{
				unset($key[$k]);
			}else{
				$key[$k]=$data[$k];
			}
		}
		
		//得到需要更新的字段
		$data2=array_diff_assoc($data, $key);
		$fields=array();
		foreach($data2 as $k=>$v)$fields[]="`{$k}`=?";
		if(!$fields)return 0;
		$fields=implode(',',$fields);
		//得到条件
		$where=array();
		foreach($key as $k=>$v)$where[]="`{$k}`=?";
		$where=implode(' AND ',$where);
		$sql="UPDATE `{$table}` SET {$fields} WHERE {$where};";
		$args=array_merge($data2,$key);
		return $this->exec($sql,array_values($args));
	}

	/**
	 * 取错误信息
	 */
	public function get_error ()
	{
		if (! $this->handler) return FALSE;
		$err_info = $this->handler->errorInfo();
		if ($err_info[0] === '00000' && $this->current_stmt)
		{
			$err_info = $this->current_stmt->errorInfo();
		}
		if ($err_info[0] === '00000') return FALSE;
		$err = "PDO:{$err_info['2']}(code:{$err_info['0']})";
		return $err;
	}

	/**
	 * 取错误代码
	 */
	public function get_error_code ()
	{
		if (! $this->handler) return FALSE;
		$err_code = $this->handler->errorCode();
		if ($err_code !== '00000') return $err_code;
		if ($this->current_stmt) $err_code = $this->current_stmt->errorCode();
		return $err_code;
	}

	/**
	 * 为多参数类sql生成stmt
	 * 
	 * @param $args array       	
	 */
	protected function get_pdo_statment ($args)
	{
		if (! $this->handler) $this->connect();
		$sql = array_shift($args);
		if ($args && is_array($args[0])) $args = $args[0];
		$stmt = $this->handler->prepare($sql);
		if (! $stmt) return FALSE;
		$rs = $stmt->execute($args);
		if (! $rs)
		{
			$this->current_stmt = $stmt;
			return FALSE;
		}
		return $stmt;
	}

	/**
	 * 取PDO对象
	 */
	public function get_pdo ()
	{
		if (! $this->handler) $this->connect();
		return $this->handler;
	}

	/**
	 * 直接调用PDO的方法
	 * 
	 * @param $call string
	 * @param $args array       	
	 */
	public function __call ($call ,$args = array())
	{
		if (! $this->handler) $this->connect();
		if (! method_exists('PDO', $call)) return FALSE;
		if ($args)
		{
			return call_user_func_array(array(
				$this->handler, 
				$call
			), $args);
		}
		else
		{
			return $this->handler->$call();
		}
	}
}
