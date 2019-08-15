<?php
class User_Db_Tables_User extends SpamTrawlerX_Db_Table_Abstract
{
    protected $_name = 'user';

    public function getCount()
    {
        $select = $this->select();
        $select->from($this, array('count(*) as amount'));
        $rows = $this->fetchAll($select);

        return($rows[0]->amount);
    }

    public function returnJSONP($aWhereParam = array())
    {
        $sTablePrefix = SpamTrawler::$Config->database->table->prefix . '_';

        $oFields = NULL;
        if(isset($_POST['fields'])){
            $oFields = json_decode($_POST['fields']);
        }

        //Instantiate Select
        $oSelect = $this->select(SpamTrawler_Db_Table::SELECT_WITH_FROM_PART)
            ->setIntegrityCheck(false);
        $oSelect->reset(SpamTrawler_Db_Select::COLUMNS);
        $oSelect->columns(array('id' , 'username', 'email', 'comment', 'twofactor', 'updated'));

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
        $order = (array( $sTablePrefix . 'user.' . $sOrderField . ' ' . $sOrderDirection));
        $oSelect->order($order);

        //Join User Groups
        //$oSelect->join($sTablePrefix . 'user_groups', $sTablePrefix . 'user_groups.group_id = ' . $sTablePrefix . 'user.group_id');
        $oSelect->join($sTablePrefix . 'user_groups', $sTablePrefix . 'user_groups.group_id = ' . $sTablePrefix . 'user.group_id', array('group_id','group_name'));
        //$oSelect->columns(array($sTablePrefix . 'user_groups.group_name'));

        $aRows = $this->fetchAll($oSelect)->toArray();

        // Return only chunk of data
        $count  = $oFields->take;
        $offset = $oFields->skip;
        $aOutput = array_slice($aRows, $offset, $count);

        //htmlentities($s, ENT_COMPAT, 'UTF-8');

        /* array_walk_recursive($aOutput, function (&$value) {
            $value = htmlentities($value);
            $value = htmlentities($value, ENT_COMPAT, 'UTF-8');
        }); */

        array_walk_recursive($aOutput, function (&$value) {
            $value = utf8_encode($value);
            //$value = SpamTrawler::getHTMLPurifier()->purify($value);
            //$value = htmlentities($value);
            //$value = htmlentities($value, ENT_COMPAT, 'UTF-8');
        });

        /*
        foreach($aOutput as $key => $row){
            $aOutput[$key] = array_map('utf8_encode', $row);
            //$row[$key] = utf8_encode($value);
        }
        */

        //exit(json_encode($aRows));
        return json_encode(array("data" => $aOutput, "total" => count($aRows)));
    }
}
