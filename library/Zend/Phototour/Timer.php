<?php
/**
 * Created by JetBrains PhpStorm.
 * User: vikram
 * Date: 3/8/11
 * Time: 9:15 PM
 * To change this template use File | Settings | File Templates.
 */

class  Phototour_Timer extends Zend_Db_Table_Abstract
{

    private $timer = "`timer`";

    public function init()
    {

    }


    function start($id, $name = "")
    {

        $db = $this->getAdapter();
        $date = Zend_Date::now();
        $date = $date->getIso();
        $data = $this->process();
        $profiler = $db->getProfiler();
        $q = "INSERT INTO  $this->timer (`id` ,`pid`,`name` ,`date` ,`start` ,`stop` ,`mysql_times`,`mysql_queries`,`start_memory` ,`stop_memory` ,`start_cpu` ,`stop_cpu`,`priority`) VALUES (NULL ,'$data->id','$name','$date',  '$data->time',  '$data->time','0.0','0', '$data->ram',  '$data->ram',  '$data->cpu',  '$data->cpu','$id');";
        try
        {
            $db->query($q);

        } Catch (Exception $e)
        {
            $profiler->clear();
            return 0;
        }
        $profiler->clear();
        return $db->lastInsertId();
    }

    function begin($name = "default_process", $process = "default", $priority = 1)
    {

        $db = $this->getAdapter();
        $date = Zend_Date::now();
        $date = $date->getIso();
        $data = $this->process();
        $profiler = $db->getProfiler();
        $q = "INSERT INTO  $this->timer (`id` ,`pid`,`name` ,`date` ,`start` ,`stop` ,`mysql_times`,`mysql_queries`,`start_memory` ,`stop_memory` ,`start_cpu` ,`stop_cpu`,`priority`,`process`) VALUES (NULL ,'$data->id','$name','$date',  '$data->time',  '$data->time','0.0','0', '$data->ram',  '$data->ram',  '$data->cpu',  '$data->cpu','$priority','$process');";
        try
        {
            $db->query($q);

        } Catch (Exception $e)
        {
            $profiler->clear();
            return 0;
        }
        $profiler->clear();
        return $db->lastInsertId();
    }

    function stop($id)
    {
        $data = $this->process();
        $db = $this->getAdapter();
        $profiler = $db->getProfiler();

        $time = $profiler->getTotalElapsedSecs();
        $count = $profiler->getTotalNumQueries();
        $q = "UPDATE  $this->timer SET  `stop` =  '$data->time',`stop_memory` =  '$data->ram',`stop_cpu` =  '$data->cpu',`mysql_times`='$time',`mysql_queries`='$count' WHERE  `id` =$id;";
        try
        {
            $db->query($q);

        } Catch (Exception $e)
        {
            $profiler->clear();

        }
        $profiler->clear();
    }

    function process()
    {
        $id = getmypid();
        $pids = new STDClass();
        exec("ps -p $id -o %cpu", $process);
        $pids->id = $id;
        $pids->cpu = $process[1];
        $pids->ram = memory_get_usage();
        $pids->time = microtime(true);
        return $pids;

    }

    function listProcess()
    {
        $q = "SELECT id, pid,process, name, AVG(  `stop_memory` -  `start_memory` ) AS aram, AVG(  `stop` -  `start` ) AS atimes, AVG(  `stop_cpu` ) AS acpu, AVG(  `mysql_times` ) AS amysql FROM timer GROUP BY  `process` ";
        $results = $this->getAdapter()->fetchAll($q);
        $c = count($results);
        $data = new STDClass();
        $data->count = "$c";
        $r = array();
        for ($i = 0; $i < count($results); $i++)
        {
            $s = $results[$i];
            $s['aram'] = abs($s['aram']);
            $data->ram .= "data.setValue($i, 0, '$s[process]'); data.setValue($i, 1, $s[aram]);";
            $data->cpu .= "data.setValue($i, 0, '$s[process]'); data.setValue($i, 1, $s[acpu]);";
            $data->times .= "data.setValue($i, 0, '$s[process]'); data.setValue($i, 1, $s[atimes]);";
            $data->mysql .= "data.setValue($i, 0, '$s[process]'); data.setValue($i, 1, $s[amysql]);";
            array_push($r, $s["process"]);
        }
        $data->list = $r;
        return $data;
    }

    function showProcess($process = 'default')
    {


        $db = $this->getAdapter();
        $sql = "SELECT DISTINCT `name` FROM  $this->timer WHERE process='$process'";
        $results = $db->fetchAll($sql);
        $r = "";
        $c = count($results);
        $data = new STDClass();
        $data->count = "$c";
        $r = array();

        for ($i = 0; $i < count($results); $i++)
        {
            $p = $results[$i];
            $sql = "SELECT id, pid, name, AVG(  `stop_memory` -  `start_memory` ) AS aram, AVG(  `stop` -  `start` ) AS atimes, AVG(  `stop_cpu` ) AS acpu,AVG(`mysql_times`) AS amysql FROM  $this->timer WHERE name LIKE '$p[name]'";
            $s = $db->fetchAll($sql);
            $s = $s[0];
            $s['aram'] = abs($s['aram']);
            $data->ram .= "data.setValue($i, 0, '$p[name]'); data.setValue($i, 1, $s[aram]);";
            $data->cpu .= "data.setValue($i, 0, '$p[name]'); data.setValue($i, 1, $s[acpu]);";
            $data->times .= "data.setValue($i, 0, '$p[name]'); data.setValue($i, 1, $s[atimes]);";
            $data->mysql .= "data.setValue($i, 0, '$p[name]'); data.setValue($i, 1, $s[amysql]);";
            array_push($r, $s["name"]);

        }
        $data->list = $r;
        return $data;
    }

    function display()
    {


        $db = $this->getAdapter();
        $sql = "SELECT DISTINCT `name` FROM  $this->timer ";
        $results = $db->fetchAll($sql);
        $r = array();
        $c = count($results);
        $data = new STDClass();
        $data->count = "$c";


        for ($i = 0; $i < count($results); $i++)
        {
            $p = $results[$i];

            $sql = "SELECT id, pid, name, AVG(  `stop_memory` -  `start_memory` ) AS aram, AVG(  `stop` -  `start` ) AS atimes, AVG(  `stop_cpu` ) AS acpu,AVG(`mysql_times`) AS amysql FROM  $this->timer WHERE name LIKE '$p[name]'";
            $s = $db->fetchAll($sql);
            $s = $s[0];
            array_push($r, $s["name"]);
            $s['aram'] = abs($s['aram']);
            $data->ram .= "data.setValue($i, 0, '$p[name]'); data.setValue($i, 1, $s[aram]);";
            $data->cpu .= "data.setValue($i, 0, '$p[name]'); data.setValue($i, 1, $s[acpu]);";
            $data->times .= "data.setValue($i, 0, '$p[name]'); data.setValue($i, 1, $s[atimes]);";
            $data->mysql .= "data.setValue($i, 0, '$p[name]'); data.setValue($i, 1, $s[amysql]);";


        }
        $data->list = $r;
        return $data;
    }


    function chart($name)
    {

        $db = $this->getAdapter();
        $data = new STDClass();
        $sql = "SELECT id, pid,mysql_queries, name, AVG(  `stop_memory` -  `start_memory` ) AS aram, AVG(  `stop` -  `start` ) AS atimes, AVG(  `stop_cpu` ) AS acpu, AVG(  `mysql_times` ) AS amysql  FROM  $this->timer WHERE name LIKE '$name'";
        $s = $db->fetchAll($sql);
        $s = $s[0];
        $data->aram = $s['aram'];
        $data->atimes = $s['atimes'];
        $data->acpu = $s['acpu'];
        $data->amysql = $s['amysql'];
        $data->mysqlq = $s['mysql_queries'];

        $sql = "SELECT id, pid, name,mysql_times,`stop_memory` -  `start_memory` AS ram,  `stop` -  `start` AS times,  `stop_cpu` AS cpu FROM  $this->timer WHERE name LIKE '$name'";

        $results = $db->fetchAll($sql);
        for ($i = 0; $i < count($results); $i++)
        {
            $r = $results[$i];
            $r['ram'] = abs($r['ram']);
            $data->ram .= "{c:[{v: '$r[id]'}, {v:$r[ram] , f: '$r[ram]'}]},";
            $data->cpu .= "{c:[{v: '$r[id]'}, {v:$r[cpu] , f: '$r[cpu]'}]},";
            $data->time .= "{c:[{v: '$r[id]'}, {v:$r[times] , f: '$r[times]'}]},";
            $data->mysql .= "{c:[{v: '$r[id]'}, {v:$r[mysql_times] , f: '$r[mysql_times]'}]},";
        }


        $data->ram = "{cols: [{id: 'A', label: 'A-label', type: 'string'},
         {id: 'B', label: 'RAM', type: 'number'},
        ],
  rows: [$data->ram
        ]
        }";

        $data->cpu = "{cols: [{id: 'A', label: 'A-label', type: 'string'},
         {id: 'B', label: 'CPU', type: 'number'},
        ],
  rows: [$data->cpu
        ]
        }";
        $data->time = "{cols: [{id: 'A', label: 'A-label', type: 'string'},
         {id: 'B', label: 'TIMES', type: 'number'},
        ],
  rows: [$data->time
        ]
        }";
        $data->mysql = "{cols: [{id: 'A', label: 'A-label', type: 'string'},
         {id: 'B', label: 'TIMES', type: 'number'},
        ],
  rows: [$data->mysql
        ]
        }";
        return $data;

    }


}