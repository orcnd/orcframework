<?php if ( ! defined('basepath')) exit('No direct script access allowed');
/**
 *  database result class
 */
class database_result {
    var $res;
    public function __construct($r) {
        $this->res=$r;
    }

    /**
     * gives all for query
     *
     *  @return array result in classes in array
     */
    public function result() {
        return $this->res->fetchAll(PDO::FETCH_CLASS);
    }

    /**
     * gives only one row for query
     *
     *  @return object  result in object
     */
    public function row() {
        return $this->res->fetch(PDO::FETCH_OBJ);
    }

    /**
     * gives number of rows for query
     *
     *  @return int number of rows
     */
    public function num_rows() {
        return $this->res->rowCount();
    }
}

/**
 *  a simple database class depends on pdo
 *  looks a lot like ci database class which is nice i think
 */
class Database {
  private $durum=false;
  private $pdo;
  private $baglanti=false;
  var $lastresult;
  private $active_select=array();
  private $active_where=array();
  private $ayarlar;

	public function __construct($ayar='') {
		if ($this->durum) return;

    if ($ayar!='') {  //specified settings for database config
      global ${$ayar};
      $this->ayarlar=${$ayar};
    }else{  //gets global database connection settings if it is not specified
      global ${defaultDatabaseConnection};
      $this->ayarlar=${defaultDatabaseConnection};
    }

		try {
      $this->pdo = $this->ayarlar->connection;
      $this->baglanti=true;
      $this->durum=true;
      return true;
		} catch ( PDOException $e ){
      print $e->getMessage();
      return false;
		}
	}

  /**
   *  gives pdo object
   *
   *  @return the pdo object
   */
	public function pdo() {
		return $this->pdo;
	}

  /**
   *  inserts data to specified table
   *
   *  @param  string  $table  table for inserting
   *  @param  array   $data   data array this must include colums and data
   *
   *  @return mixed return pdo result object if everyting ok, returns false if something wrong
   */
  public function insert($table,$data) {

      $ps='insert into ' . $this->ayarlar->prefix . $table . ' (' . implode(',',array_keys($data)) . ') values (';
      $pskv=array();
      foreach ($data as $k=>$v) {
          $pskv[]=':' . $k;
      }

      $ps.=implode(',',$pskv) . ')';

      $stmt = $this->pdo()->prepare($ps);
      foreach($data as $k=>$v) {
          $stmt->bindValue(':' . $k, $v);
      }

      try {
          return $stmt->execute();
      }catch (PDOException $e) {
          print $e->getMessage();
          return false;
      }

    }

    /**
     * selects column for sql result
     *
     *  @param  string  $kr column to be selected
     *
     *  @return object  database object itself
     */
    public function select ($kr) {
      $this->active_select[]=$kr;
      return $this;
    }

    /**
     *  sets where condition
     *
     *  @param  string  $col  table column
     *  @param  string  $data data to conditions
     *  @param  string  $cond condition like '= != > <' etc.
     *  @param  string  $op   or and statements
     *
     *  @return object  database object itself
     */
    public function where ($col,$data,$cond='=',$op='or') {
      $this->active_where[]=(object)array('col'=>$col,'data'=>$data,'cond'=>$cond,'op'=>$op);
      return $this;
    }

    /**
     * gets data from table
     *
     *  @param  string  $table  database table name
     *
     *  @return mixed  database result object or boolean false if something is wrong
     */
    public function get($table) {

      $sl='*';
      if (sizeof($this->active_select)>0) {
          $sl='`' . implode('`,`',$this->active_select) . '`';
      }

      $ps='select ' . $sl . ' from ' . $this->ayarlar->prefix . $table;

      if (sizeof($this->active_where)>0) {
          $ps.=' where ';
          $psi=0;
          foreach ($this->active_where as $wh) {
              if ($psi>0) $ps.=' ' . $wh->op;
              $ps.=' `' . $wh->col . '`' . $wh->cond . ':whr' . $psi . $wh->col;
              $psi++;
          }
      }

      $stmt = $this->pdo()->prepare($ps);

      if (sizeof($this->active_where)>0) {
          $psi=0;
          foreach ($this->active_where as $wh) {
              $ps.=' `' . $wh->col . '`' . $wh->cond . ':whr' . $psi . $wh->col;
              $stmt->bindValue(':whr' . $psi . $wh->col, $wh->data);
              $psi++;
          }
      }
      try {
          $stmt->execute();
          $res=new database_result($stmt);
          $this->lastresult=$res;
          return $res;
      }catch (PDOException $e) {
          print $e->getMessage();
          return false;
      }
    }

    /**
     * custom query running
     *
     *  @param  string  $sql  sql string
     *
     *  @return mixed database result object or false if something is wrong
     */
    function query($sql) {
      try {
          $r=$this->pdo()->query($sql);
          $res=new database_result($r);
          $this->lastresult=$res;
          return $res;
      }catch (PDOException $e) {
          print $e->getMessage();
          return false;
      }
    }

    /**
     *  updates table
     *
     *  @param  string  $table  table name
     *  @param  array   $data data to be updated this must be include columns as array key values to updates value
     *
     *  @return mixed pdo object after update or false if something is wrong
     */
    public function update($table,$data) {
      $ps='update ' . $this->ayarlar->prefix . $table;
      $upa=array();
      foreach ($data as $k=>$v) {
          $upa[]=$k . '=:up' . $k;
      }

      $ps.=' set '. implode(',',$upa);

      if (sizeof($this->active_where)>0) {
          $ps.=' where ';
          $psi=0;
          foreach ($this->active_where as $wh) {
              if ($psi>0) $ps.=' ' . $wh->op;
              $ps.=' `' . $wh->col . '`' . $wh->cond . ':whr' . $psi . $wh->col;
              $psi++;
          }
      }

      $stmt = $this->pdo()->prepare($ps);
      if (sizeof($this->active_where)>0) {
          $psi=0;
          foreach ($this->active_where as $wh) {
              $stmt->bindValue(':whr' . $psi . $wh->col, $wh->data);
              $psi++;
          }
      }

      foreach ($data as $k=>$v) {
          $stmt->bindValue(':up' .$k, $v);
      }

      try {
          $stmt->execute();
          return $stmt;
      }catch (PDOException $e) {
          print $e->getMessage();
          return false;
      }
    }
}
