<?php
/**
 * Hush Framework
 *
 * @category   Hush
 * @package    Hush_Cache
 * @author     James.Huang <shagoo@gmail.com>
 * @license    http://www.apache.org/licenses/LICENSE-2.0
 * @version    $Id$
 */
 
/**
 * @package Hush_Cache
 */
abstract class Hush_Cache
{	
	protected $tid = '';
	protected $exp = null;
	protected $data = array();
	protected $conn = null;
	
	public function __construct ($tag, $exp = '')
	{
	    $this->tid = $tag;
	    $this->exp = $exp ? intval($exp) : null;
	}
	
	abstract public function initConnection ();
	
	public function get ($key = '')
	{
	    $this->initConnection();
	    if ($this->tid && $this->conn) {
	        // get old data
	        $this->data = (array) json_decode($this->conn->get($this->tid), 1);
	        if ($key) {
	            // get key value
	            $this->data = (array) $this->data;
	            return $this->data[$key];
	        } else {
	            // get all
	            return $this->data;
	        }
	    } else {
	        return false;
	    }
	}
	
	public function set ($key, $val = null)
	{
	    $this->initConnection();
	    if ($this->tid && $this->conn) {
	        // get old data
	        $this->data = (array) json_decode($this->conn->get($this->tid), 1);
	        if ($val !== null) {
	            // set key value
	            $this->data[$key] = $val;
	        } else {
	            // set key as value
	            if (is_array($key)) {
	                // merge into data
	                $this->data = array_merge($this->data, $key);
	            } else {
	                // save as string
	                $this->data = (string) $key;
	            }
	        }
	        // save data
	        if ($this->exp) {
	            return $this->conn->set($this->tid, json_encode($this->data), $this->exp);
	        } else {
	            return $this->conn->set($this->tid, json_encode($this->data));
	        }
	    } else {
	        return false;
	    }
	}
	
	public function del ($key = '')
	{
	    $this->initConnection();
	    if ($this->tid && $this->conn) {
	        if ($key) {
	            // delete key value
	            $this->data = json_decode($this->conn->get($this->tid), 1);
	            unset($this->data[$key]);
	            return $this->conn->set($this->tid, json_encode($this->data));
	        } else {
	            // delete all
	            $this->data = array();
	            return $this->conn->del($this->tid);
	        }
	    } else {
	        return false;
	    }
	}
	
	public function lpush ($data = '')
	{
	    $this->initConnection();
	    if ($this->tid && $this->conn) {
	        if ($data) {
	            // push data
	            return $this->conn->lPush($this->tid, trim($data));
	        }
	    } else {
	        return false;
	    }
	}

    public function incr ($key = '')
    {
        $this->initConnection();
        if ($this->tid && $this->conn) {
            if ($key) {
                // incr key
                return $this->conn->incr($this->tid, trim($key));
            }
        } else {
            return false;
        }
    }
}
