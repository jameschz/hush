<?php
/**
 * Hush Framework
 *
 * @category   Hush
 * @package    Hush_Tree
 * @author     James.Huang <shagoo@gmail.com>
 * @license    http://www.apache.org/licenses/LICENSE-2.0
 * @version    $Id$
 */
 
/**
 * Implement some tree relevant functions
 * @package Hush_Tree
 */
class Tree
{
    /**
     * Description
     * @var      
     * @since     1.0
     * @access    private
     */
    var $data    = array();
   
    /**
     * Description
     * @var      
     * @since     1.0
     * @access    private
     */
    var $child    = array(-1=>array());
   
    /**
     * Description
     * @var      
     * @since     1.0
     * @access    private
     */
    var $layer    = array(-1=>-1);
   
    /**
     * Description
     * @var      
     * @since     1.0
     * @access    private
     */
    var $parent    = array();

    /**
     * Short description.
     *
     * Detail description
     * @param      none
     * @global     none
     * @since      1.0
     * @access     private
     * @return     void
     * @update     date time
    */
    function Tree ($value = null)
    {
        $this->setNode(0, -1, $value);
    } // end func

    /**
     * Short description.
     *
     * Detail description
     * @param      none
     * @global     none
     * @since      1.0
     * @access     private
     * @return     void
     * @update     date time
    */
    function setNode ($id, $parent, $value)
    {
        $parent = $parent?$parent:0;

        $this->data[$id]            = $value;
        $this->child[$id]            = array();
        $this->child[$parent][]        = $id;
        $this->parent[$id]            = $parent;

        if (!isset($this->layer[$parent]))
        {
            $this->layer[$id] = 0;
        }
        else
        {
            $this->layer[$id] = $this->layer[$parent] + 1;
        }
    } // end func
   
    /**
     * Short description.
     *
     * Detail description
     * @param      none
     * @global     none
     * @since      1.0
     * @access     private
     * @return     void
     * @update     date time
    */
    function getList (&$tree, $root= 0)
    {
        foreach ($this->child[$root] as $key=>$id)
        {
            $tree[] = $id;

            if ($this->child[$id]) $this->getList($tree, $id);
        }
    } // end func

    /**
     * Short description.
     *
     * Detail description
     * @param      none
     * @global     none
     * @since      1.0
     * @access     private
     * @return     void
     * @update     date time
    */
    function getValue ($id)
    {
        return $this->data[$id];
    } // end func

    /**
     * Short description.
     *
     * Detail description
     * @param      none
     * @global     none
     * @since      1.0
     * @access     private
     * @return     void
     * @update     date time
    */
    function getLevel ($id)
    {
        return $this->layer[$id];
    } // end func

    /**
     * Short description.
     *
     * Detail description
     * @param      none
     * @global     none
     * @since      1.0
     * @access     private
     * @return     void
     * @update     date time
    */
    function getLayer ($id, $space = false)
    {
        return $space?str_repeat($space, $this->layer[$id]):$this->layer[$id];
    } // end func

    /**
     * Short description.
     *
     * Detail description
     * @param      none
     * @global     none
     * @since      1.0
     * @access     private
     * @return     void
     * @update     date time
    */
    function getParent ($id)
    {
        return isset($this->parent[$id]) ? $this->parent[$id] : array();
    } // end func
   
    /**
     * Short description.
     *
     * Detail description
     * @param      none
     * @global     none
     * @since      1.0
     * @access     private
     * @return     void
     * @update     date time
    */
    function getParents ($id)
    {
        while ($this->parent[$id] != -1)
        {
            $id = $parent[$this->layer[$id]] = $this->parent[$id];
        }

        ksort($parent);
        reset($parent);

        return $parent;
    } // end func
   
    /**
     * Short description.
     *
     * Detail description
     * @param      none
     * @global     none
     * @since      1.0
     * @access     private
     * @return     void
     * @update     date time
    */
    function getChild ($id)
    {
        return isset($this->child[$id]) ? $this->child[$id] : array();
    } // end func

   
    /**
     * Short description.
     *
     * Detail description
     * @param      none
     * @global     none
     * @since      1.0
     * @access     private
     * @return     void
     * @update     date time
    */
    function getChilds ($id)
    {
        $child = array($id);
        $this->getList($child, $id);

        return $child;
    } // end func
} // end class
