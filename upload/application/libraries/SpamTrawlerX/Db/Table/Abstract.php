<?php
/**
 * Created by SpamTrawler.
 * User: griddie
 * Date: 14/06/14
 * Time: 15:24
 * Copyright (c) 2014 Oliver Putzer (SpamTrawler)
 */
class SpamTrawlerX_Db_Table_Abstract extends SpamTrawler_Db_Table_Abstract
{
    public $TableName;

    public function getCount()
    {
        $select = $this->select();
        $select->from($this, array('count(*) as amount'));
        $rows = $this->fetchAll($select);

        return($rows[0]->amount);
    }

    protected function _setupTableName()
    {
        parent::_setupTableName();
        $prefix = SpamTrawler::$Config->database->table->prefix;
        $this->_name = $prefix . '_' . $this->_name;
        $this->TableName = $this->_name;
    }

    public function getTableName(){

    }

    public function getCached(){
        if(!$aRows = SpamTrawler::$Registry['oCache']->load($this->_name)) {
            $aRows = $this->fetchAll();


            SpamTrawler::$Registry['oCache']->save($aRows, $this->_name);
        }

        return $aRows;
    }

    public function getJSONP($aWhereParam = array())
    {
        $oFields = NULL;
        if(isset($_POST['fields'])){
            $oFields = json_decode($_POST['fields']);
        }

        //Instantiate Select
        $oSelect = $this->select();

        //Allow where parameter to be passed when calling function from php directly
        if(is_array($aWhereParam) && !empty($aWhereParam)){
            foreach($aWhereParam as $key => $value){
                if(is_array($aWhereParam[$key]) && isset($aWhereParam[$key]['field']) && isset($aWhereParam[$key]['value'])){
                    $oSelect->where($aWhereParam[$key]['field'] . ' = ?', $aWhereParam[$key]['value']);
                }
            }
        }

        //Filter
        //->where('bug_status = ?', 'NEW')
        if(isset($oFields->filter) && !empty($oFields->filter)){
            $aLogicWhitelist = array('and', 'or');
            $sFilterLogic = $oFields->filter->logic;

            foreach($oFields->filter->filters as $sFiltersKey => $aFiltersValue){
                $sFilterField = $oFields->filter->filters[$sFiltersKey]->field;
                $sFilterOperator = $oFields->filter->filters[$sFiltersKey]->operator;
                $sFilterValue = $oFields->filter->filters[$sFiltersKey]->value;

                //Check if filtering regex field
                if($sFilterField == 'isregex'){
                    if($sFilterValue == '1'){
                        $sFilterValue = 'true';
                    } elseif($sFilterValue == '0'){
                        $sFilterValue = 'false';
                    }
                }

                //Check if we have an odd or even number and use filter logic accordingly
                //odd is always and
                /*
                 * Odd/Even Check using bitwise
                 * if ( $sFiltersKey & 1 ) {
                 * //odd
                 *} else {
                 *  //even
                 *}
                 *
                 * Using Modulo
                 * if($sFiltersKey % 2 == 0){
                 * //even
                 * } else {
                 * //odd
                 * }
                 */
                if($sFiltersKey == 0 || !($sFiltersKey & 1)){
                    switch ($sFilterOperator) {
                        case 'eq':
                            $oSelect->where($sFilterField . ' = ?', $sFilterValue);
                            break;
                        case 'neq':
                            $oSelect->where($sFilterField . ' != ?', $sFilterValue);
                            break;
                        case 'startswith':
                            $oSelect->where($sFilterField . ' LIKE ?', $sFilterValue . '%');
                            break;
                        case 'contains':
                            $oSelect->where($sFilterField . ' LIKE ?', '%' . $sFilterValue . '%');
                            break;
                        case 'doesnotcontain':
                            $oSelect->where($sFilterField . ' NOT LIKE ?', '%' . $sFilterValue . '%');
                            break;
                        case 'endswith':
                            $oSelect->where($sFilterField . ' LIKE ?', '%' . $sFilterValue);
                            break;
                    }
                } else {
                    if(in_array($sFilterLogic, $aLogicWhitelist)){
                        if($sFilterLogic == 'and'){
                            switch ($sFilterOperator) {
                                case 'eq':
                                    $oSelect->where($sFilterField . ' = ?', $sFilterValue);
                                    break;
                                case 'neq':
                                    $oSelect->where($sFilterField . ' != ?', $sFilterValue);
                                    break;
                                case 'startswith':
                                    $oSelect->where($sFilterField . ' LIKE ?', $sFilterValue . '%');
                                    break;
                                case 'contains':
                                    $oSelect->where($sFilterField . ' LIKE ?', '%' . $sFilterValue . '%');
                                    break;
                                case 'doesnotcontain':
                                    $oSelect->where($sFilterField . ' NOT LIKE ?', '%' . $sFilterValue . '%');
                                    break;
                                case 'endswith':
                                    $oSelect->where($sFilterField . ' LIKE ?', '%' . $sFilterValue);
                                    break;
                            }
                        } else {
                            switch ($sFilterOperator) {
                                case 'eq':
                                    $oSelect->orWhere($sFilterField . ' = ?', $sFilterValue);
                                    break;
                                case 'neq':
                                    $oSelect->orWhere($sFilterField . ' != ?', $sFilterValue);
                                    break;
                                case 'startswith':
                                    $oSelect->orWhere($sFilterField . ' LIKE ?', $sFilterValue . '%');
                                    break;
                                case 'contains':
                                    $oSelect->orWhere($sFilterField . ' LIKE ?', '%' . $sFilterValue . '%');
                                    break;
                                case 'doesnotcontain':
                                    $oSelect->orWhere($sFilterField . ' NOT LIKE ?', '%' . $sFilterValue . '%');
                                    break;
                                case 'endswith':
                                    $oSelect->orWhere($sFilterField . ' LIKE ?', '%' . $sFilterValue);
                                    break;
                            }
                        }
                    }
                }
            }
            //$oSelect->where('referrer LIKE ?', '%{' . SpamTrawler_VisitorDetails_Referrer_Referrer::get() . '}%')
        }

        //Order By
        $aOrderDirectionsWhitelist = array('asc', 'desc');
        if(isset($oFields->sort[0]->field) && isset($oFields->sort[0]->dir) && ctype_alpha($oFields->sort[0]->field) && in_array($oFields->sort[0]->dir, $aOrderDirectionsWhitelist)){
            $sOrderField = $oFields->sort[0]->field;
            $sOrderDirection = $oFields->sort[0]->dir;
        } else {
            $sOrderField = 'updated';
            $sOrderDirection = 'desc';
        }

        //Add Order By
        $order = (array($sOrderField . ' ' . $sOrderDirection));
        $oSelect->order($order);

        $aRows = $this->fetchAll($oSelect)->toArray();

        // Return only chunk of data
        if(isset($oFields)){
            $count  = $oFields->take;
            $offset = $oFields->skip;
            $aOutput = array_slice($aRows, $offset, $count);
        } else {
            $aOutput = $aRows;
        }

        array_walk_recursive($aOutput, function (&$value) {
          $value = utf8_encode($value);
        });

        exit(json_encode(array("data" => $aOutput, "total" => count($aRows))));
    }

    public function returnJSONP($aWhereParam = array())
    {
        $oFields = NULL;
        if(isset($_POST['fields'])){
            $oFields = json_decode($_POST['fields']);
        }

        //Instantiate Select
        $oSelect = $this->select();

        //Allow where parameter to be passed when calling function from php directly
        if(is_array($aWhereParam) && !empty($aWhereParam)){
            foreach($aWhereParam as $key => $value){
                if(is_array($aWhereParam[$key]) && isset($aWhereParam[$key]['field']) && isset($aWhereParam[$key]['value'])){
                    $oSelect->where($aWhereParam[$key]['field'] . ' = ?', $aWhereParam[$key]['value']);
                }
            }
        }

        //Filter
        if(isset($oFields->filter) && !empty($oFields->filter)){
            $aLogicWhitelist = array('and', 'or');
            $sFilterLogic = $oFields->filter->logic;

            foreach($oFields->filter->filters as $sFiltersKey => $aFiltersValue){
                $sFilterField = $oFields->filter->filters[$sFiltersKey]->field;
                $sFilterOperator = $oFields->filter->filters[$sFiltersKey]->operator;
                $sFilterValue = $oFields->filter->filters[$sFiltersKey]->value;

                //Check if filtering regex field
                if($sFilterField == 'isregex'){
                    if($sFilterValue == '1'){
                        $sFilterValue = 'true';
                    } elseif($sFilterValue == '0'){
                        $sFilterValue = 'false';
                    }
                }

                if($sFiltersKey == 0 || !($sFiltersKey & 1)){
                    switch ($sFilterOperator) {
                        case 'eq':
                            $oSelect->where($sFilterField . ' = ?', $sFilterValue);
                            break;
                        case 'neq':
                            $oSelect->where($sFilterField . ' != ?', $sFilterValue);
                            break;
                        case 'startswith':
                            $oSelect->where($sFilterField . ' LIKE ?', $sFilterValue . '%');
                            break;
                        case 'contains':
                            $oSelect->where($sFilterField . ' LIKE ?', '%' . $sFilterValue . '%');
                            break;
                        case 'doesnotcontain':
                            $oSelect->where($sFilterField . ' NOT LIKE ?', '%' . $sFilterValue . '%');
                            break;
                        case 'endswith':
                            $oSelect->where($sFilterField . ' LIKE ?', '%' . $sFilterValue);
                            break;
                    }
                } else {
                    if(in_array($sFilterLogic, $aLogicWhitelist)){
                        if($sFilterLogic == 'and'){
                            switch ($sFilterOperator) {
                                case 'eq':
                                    $oSelect->where($sFilterField . ' = ?', $sFilterValue);
                                    break;
                                case 'neq':
                                    $oSelect->where($sFilterField . ' != ?', $sFilterValue);
                                    break;
                                case 'startswith':
                                    $oSelect->where($sFilterField . ' LIKE ?', $sFilterValue . '%');
                                    break;
                                case 'contains':
                                    $oSelect->where($sFilterField . ' LIKE ?', '%' . $sFilterValue . '%');
                                    break;
                                case 'doesnotcontain':
                                    $oSelect->where($sFilterField . ' NOT LIKE ?', '%' . $sFilterValue . '%');
                                    break;
                                case 'endswith':
                                    $oSelect->where($sFilterField . ' LIKE ?', '%' . $sFilterValue);
                                    break;
                            }
                        } else {
                            switch ($sFilterOperator) {
                                case 'eq':
                                    $oSelect->orWhere($sFilterField . ' = ?', $sFilterValue);
                                    break;
                                case 'neq':
                                    $oSelect->orWhere($sFilterField . ' != ?', $sFilterValue);
                                    break;
                                case 'startswith':
                                    $oSelect->orWhere($sFilterField . ' LIKE ?', $sFilterValue . '%');
                                    break;
                                case 'contains':
                                    $oSelect->orWhere($sFilterField . ' LIKE ?', '%' . $sFilterValue . '%');
                                    break;
                                case 'doesnotcontain':
                                    $oSelect->orWhere($sFilterField . ' NOT LIKE ?', '%' . $sFilterValue . '%');
                                    break;
                                case 'endswith':
                                    $oSelect->orWhere($sFilterField . ' LIKE ?', '%' . $sFilterValue);
                                    break;
                            }
                        }
                    }
                }
            }
        }

        //Order By
        $aOrderDirectionsWhitelist = array('asc', 'desc');
        if(isset($oFields->sort[0]->field) && isset($oFields->sort[0]->dir) && ctype_alpha($oFields->sort[0]->field) && in_array($oFields->sort[0]->dir, $aOrderDirectionsWhitelist)){
            $sOrderField = $oFields->sort[0]->field;
            $sOrderDirection = $oFields->sort[0]->dir;
        } else {
            $sOrderField = 'updated';
            $sOrderDirection = 'desc';
        }

        //Add Order By
        $order = (array($sOrderField . ' ' . $sOrderDirection));
        $oSelect->order($order);

        $aRows = $this->fetchAll($oSelect)->toArray();

        // Return only chunk of data
        $count  = $oFields->take;
        $offset = $oFields->skip;
        $aOutput = array_slice($aRows, $offset, $count);

        array_walk_recursive($aOutput, function (&$value) {
            $value = utf8_encode($value);
        });

        return json_encode(array("data" => $aOutput, "total" => count($aRows)));
    }

    /**
     * Remove all contents of the table
     * @return this
     */
    public function truncate()
    {
        $this->getAdapter()->query('TRUNCATE TABLE `' . $this->_name . '`');

        return $this;
    }
}
