<?php

namespace Fizzday\Database;

use PDO;

class db
{
    protected static $pdo = '';

    protected static $pk = array(
        'key' => 'id',
        'value' => ''
    );

    protected static $table = '';  // 默认是 AR 的调用模式, 获取当前 AR 类名,即为表名
    protected static $where = '';
    protected static $orWhere = '';

    /**
     * 查询独有的sql字段
     * @var array
     */
    protected static $sqlSelect = array(
        'fields' => '',
        'group' => '',
        'order' => '',
        'limit' => ''
    );

    /**
     * 插入或修改的保存数据
     * @var array
     */
    protected static $saveData = [];

    /**
     * pdo处理绑定value值
     * @var array
     */
    protected static $bindValue = [];

    protected static $lastSql = '';

    public static function lastSql()
    {
        return static::$lastSql;
    }

    /**
     * 原始 pdo 查询语句
     * @param string $sql
     * @param string $param
     * @return mixed
     */
    public static function query($sql='', $param=[])
    {
        if (!$sql) die('sql语句不能为空');
        // 检查是否为select语句
        if (strtoupper(substr(trim($sql), 0, 6)) != 'SELECT') die('query() 方法只能执行 select 查询');

        if (empty(static::$pdo)) static::getConnection('', false);

        $stmt = static::$pdo->prepare($sql);
        $res = $stmt->execute($param);
        if ($res) $res = $stmt->fetchAll(PDO::FETCH_OBJ);

        // 初始化
        self::reset($sql);

        return $res;
    }

    /**
     * 执行原生 pdo 增删改操作
     * @param string $sql
     * @param array $param
     * @return mixed
     */
    public static function execute($sql='', $param=[])
    {
        if (!$sql) die('sql语句不能为空');
        // 检查是否为select语句
        if (strtoupper(substr(trim($sql), 0, 6)) == 'SELECT') die('excute() 方法只能执行 select 查询之外的操作');

        if (empty(static::$pdo)) static::getConnection('', false);

        $stmt = static::$pdo->prepare($sql);
        $res = $stmt->execute($param);

        // 初始化
        self::reset($sql);

        return $res;
    }

    /**
     * 按主键查询你单条记录
     * @param string $pkValue
     * @param bool $return
     * @return mixed
     */
    public static function find ($pkValue='')
    {
        if ($pkValue) {
            static::$pk['value'] = $pkValue;
//            $condition = [static::$pk['key'], '=', static::$pk['value']];
//            static::where($condition, '', false);
//
//            if (empty(static::$pdo)) static::getConnection('', false);
//
//            $sql = static::buildSql();
//
//            $stmt = static::$pdo->prepare($sql);
//
//            $res = $stmt->execute(static::$bindValue);
//
//            if ($res) {
//                $res = $stmt->fetch(PDO::FETCH_OBJ);
//                if (!empty($res)) {
//                    $pk = static::$pk['key'];
//                    static::$pk['value'] = $res->$pk;
//                }
//            }
            return new static();

        } else return static::getOne();
    }

    /**
     * 单条记录查询
     * @param string $tab
     * @param string $condition
     * @return mixed
     */
    public static function getOne ($tab='', $condition = '')
    {
        if ($tab) {
            if (is_array($tab)) $condition = $tab;
            else {
                if (is_numeric($tab)) $condition = [static::$pk['key'], '=', $tab];
                else static::table($tab, false);
            }
        }

        static::where($condition, '', false);

        if (empty(static::$pdo)) static::getConnection('', false);

        $sql = static::buildSql();

        $stmt = static::$pdo->prepare($sql);

        $res = $stmt->execute(static::$bindValue);

        if ($res) {
            $res = $stmt->fetch(PDO::FETCH_OBJ);
            if (!empty($res)) {
                $pk = static::$pk['key'];
                static::$pk['value'] = $res->$pk;
            }
        }

        // 初始化
        self::reset($sql);

        return $res;
    }

    /**
     * 单条记录查询
     * @param string $tab
     * @param string $condition
     * @return mixed
     */
    public static function get ($tab='', $condition = '')
    {
        if ($tab) {
            if (is_array($tab)) $condition = $tab;
            else static::table($tab, false);
        }

        static::where($condition, '', false);

        if (empty(static::$pdo)) static::getConnection('', false);

        $sql = static::buildSql();

        $stmt = static::$pdo->prepare($sql);

        $res = $stmt->execute(static::$bindValue);

        if ($res)  $res = $stmt->fetchAll(PDO::FETCH_OBJ);
        // 初始化
        self::reset($sql);

        return $res;
    }

    /**
     * 更改数据
     * @param string $tab
     * @param array $data   ['a'=>1, 'b'=>2]
     * @param array $condition
     * @return mixed
     */
    public static function update($tab='', $data='', $condition=[])
    {
        if ($tab) {
            // 兼容 AR
            if (is_array($tab) || is_object($tab)) {
                $saveData = $tab;
                $condition = $data;
            } else {
                $saveData = $data;
                static::table($tab, false);
            }
        }

        // 处理保存数据
        if (empty($saveData)) {
            die('暂无更新数据');
        }

        $fields = [];
        foreach ($saveData as $k=>$v) {
            $fields[] = addQuotes($k)." = ? ";
            static::$bindValue = array_merge(static::$bindValue, [$v]);
        }
        $update = implode(', ', $fields);

        static::where($condition, '', false);

        static::checkTable();

        $sql = "UPDATE ".static::$table." SET ".$update." WHERE ".static::$where.static::$orWhere;

        if (empty(static::$pdo)) static::getConnection('', false);

        $stmt = static::$pdo->prepare($sql);

        $res = $stmt->execute(static::$bindValue);

        // 初始化
        self::reset($sql);

        return $res;
    }

    /**
     * 插入数据
     * @param string $tab
     * @param array $data   ['a'=>1, 'b'=>2]
     * @param array $condition
     * @return mixed
     */
    public static function insert($tab='', $data='')
    {
        if ($tab) {
            // 兼容 AR
            if (is_array($tab) || is_object($tab)) {
                $data = $tab;
            }
            else static::table($tab, false);
        }

        // 处理保存数据
        if (empty($data)) {
            die('暂无插入数据');
        }

        $fields = [];
        foreach ($data as $k=>$v) {
            $fields[] = addQuotes($k)." = ? ";
            static::$bindValue = array_merge(static::$bindValue, [$v]);
        }
        $update = implode(', ', $fields);

        static::checkTable();

        $sql = "INSERT INTO ".static::$table." SET ".$update;

        if (empty(static::$pdo)) static::getConnection('', false);

        $stmt = static::$pdo->prepare($sql);

        $res = $stmt->execute(static::$bindValue);

        // 初始化
        self::reset($sql);

        return $res;
    }

    /**
     * 删除数据
     * @param string $tab
     * @param string $condition
     * @return mixed
     */
    public static function delete($tab='', $condition='')
    {
        if ($tab) {
            // 兼容 AR
            if (is_array($tab)) {
                $condition = $tab;
            }
            else static::table($tab, false);
        }

        static::where($condition, '', false);

        static::checkTable();

        $sql = "DELETE FROM ".static::$table." WHERE ".static::$where.static::$orWhere;

        if (empty(static::$pdo)) static::getConnection('', false);

        $stmt = static::$pdo->prepare($sql);

        $res = $stmt->execute(static::$bindValue);

        // 初始化
        self::reset($sql);

        return $res;
    }

    /**
     * where条件
     * @param $condition // ['id', '=', 2] or [ ['id', '>', 1], ['id', '<=', 7], ['name', 'bdm'] ]
     * @param null $params
     */
    public static function where($condition, $params = '', $return = true)
    {
        if (empty($params)) {
            $params = [];
        }

        if (!empty($condition)) {
            $where = '';

            // 判断是否是二维数组
            if (count($condition) != count($condition, 1)) {
                // $condition = [ ['id', '>', 1], ['id', '<=', 7], ['name', 'bdm'] ]
                foreach ($condition as $v) {
                    if (count($condition) == 3) {
                        // $v = ['id', '>', 1]
                        $where .= ' and '.addQuotes($v[0]).' '.$v[1].' ? ';
                        array_push($params, $v[2]);
                    } elseif(count($condition) == 2) {
                        // $v = ['name', 'bdm']
                        $where .= ' and '.addQuotes($v[0]).' = ? ';
                        array_push($params, $v[1]);
                    } else {
                        // $condition = ['id'=>2]
                        $where .= ' and '.addQuotes(key($v)).' = ? ';
                        array_push($params, $v[key($v)]);
                    }
                }
            } else {
                if (count($condition) == 3) {
                    // $condition = ['id', '>', 2]
                    $where .= ' and '.addQuotes($condition[0]).' '.$condition[1].' ? ';
                    array_push($params, $condition[2]);
                } elseif(count($condition) == 2) {
                    // $condition = ['id', 2]
                    $where .= ' and '.addQuotes($condition[0]).' = ? ';
                    array_push($params, $condition[1]);
                } else {
                    // $condition = ['id'=>2]
                    $where .= ' and '.addQuotes(key($condition)).' = ? ';
                    array_push($params, $condition[key($condition)]);
                }
            }

            $sw = static::$where;
            $sw .= $where;

            static::$where = ltrim(trim($sw), 'and');

            static::$bindValue = array_merge(static::$bindValue, $params);
        }

        if ($return) return new static();
    }

    /**
     * 要查询的字段
     * @param string $fields = "id,name,age"
     * @return null|static
     */
    public static function fields($fields='', $return=true)
    {
        if ($fields) {
            $fields_arr = explode(',', $fields);
            $fields_new = '';
            foreach ($fields_arr as $v) {
                $fields_new .= addQuotes(trim($v)).',';
            }
        } else {
            $fields_new = '*';
        }

        static::$sqlSelect['fields'] = rtrim($fields_new, ',');
        if($return) return new static();
    }

    public static function group($field='', $return=true)
    {
        if ($field) {
            static::$sqlSelect['group'] = $field;
        }

        if($return) return new static();
    }

    public static function order($field='', $return=true)
    {
        if ($field) {
            static::$sqlSelect['order'] = $field;
        }

        if($return) return new static();
    }

    public static function limit($limit='', $offset=0, $return=true)
    {
        if ($limit) {
            static::$sqlSelect['limit'] = $offset. ',' .$limit;
        }

        if($return) return new static();
    }

    /**
     * 直接使用db类,不新建[table]类时, 指定table
     * @param null $tab
     * @return static
     * @throws Exception
     */
    public static function table($tab = '', $return = true)
    {
        if ($tab) {
            static::$table = addQuotes(strtolower($tab));
            if($return) return new static();
            return null;
        }

        return "table name is needed";
    }

    /**
     * 获取默认的数据库连接
     * @return PDO
     */
    public static function getConnection($con = '', $return = true)
    {
//        $config = load('config', CONFIG_PATH);
//        $dbconf = load('database', CONFIG_PATH);
//        if (empty($con)) $con = $config['db_default'];
        $dbconf = load('database', CONFIG_PATH);
        $con = 'default';

        $dsn = "mysql:host=". $dbconf['db'][$con]['host'] .";dbname=". $dbconf['db'][$con]['dbname']. ";charset=utf8";

        static::$pdo = new \PDO($dsn, $dbconf['db'][$con]['username'], $dbconf['db'][$con]['password']);

        if($return == true) return new static();
    }

    public function __set($name, $value) {
        static::$saveData[$name] = $value;
    }

    public static function save()
    {
        // 检查设定的数组中, 是否存在自增的主键和值
        if (in_array(static::$pk['key'], array_keys(static::$saveData)) && !empty(static::$saveData[static::$pk['key']])) {
            // 设定 $pk 的值
            static::$pk['value'] = static::$saveData[static::$pk['key']];
            // 去除主键及值
            unset(static::$saveData[static::$pk['key']]);
        }
        // 判断是否存在主键值, 存在则为修改, 不存在则为插入
        if (static::$pk['value']) {
            static::update(static::$saveData, [static::$pk['key'], '=', static::$pk['value']]);
        } else {
            static::insert(static::$saveData);
        }
    }

    /**
     * 构建sql语句前, 检查表名是否定义
     */
    private static function checkTable()
    {
        // 检查是否有table
        if (empty(static::$table)) {
            $table = get_called_class();

            // 获取当前class的名字, 检查是否为 AR 类名
            if ($table == 'db') {
                echo '未定义的 table';
                return null;
            }

            static::table($table);
        }
    }

    /**
     * 构建最终的查询sql
     * @return null|string
     * @throws Exception
     */
    private static function buildSql()
    {
        static::checkTable();

        $sql_all = static::$sqlSelect;

        if (empty($sql_all['fields'])) $sql_all['fields'] = '*';

        $sql_str = '';
        $sql_str .= 'SELECT '.$sql_all['fields'].' FROM '.static::$table;

        if (static::$where) $sql_str .= ' WHERE '.static::$where;
        if (static::$orWhere) if (static::$where) $sql_str .= ' OR '.trim(trim(static::$orWhere), 'or');

        if ($sql_all['group']) $sql_str .= ' GROUP BY '.$sql_all['group'];
        if ($sql_all['order']) $sql_str .= ' ORDER BY '.$sql_all['order'];
        if ($sql_all['limit']) $sql_str .= ' LIMIT '.$sql_all['limit'];

        return $sql_str;
    }

    public static function reset($sql='')
    {
        // 保存最后的数据库操作语句
        if ($sql) static::$lastSql = $sql;

        static::$pdo = '';

        static::$pk = array(
            'key' => 'id',
            'value' => ''
        );

        static::$table = '';  // 默认是 AR 的调用模式, 获取当前 AR 类名,即为表名
        static::$where = '';
        static::$orWhere = '';

        static::$sqlSelect = array(
            'fields' => '*',
            'group' => '',
            'order' => '',
            'limit' => ''
        );

        static::$saveData = [];

        static::$bindValue = [];
    }
}