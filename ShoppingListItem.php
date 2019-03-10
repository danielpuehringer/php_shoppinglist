<?php
/**
 * Created by PhpStorm.
 * User: S1610456027
 * Date: 03.05.2018
 * Time: 20:14
 */

class ShoppingListItem{//Author: Daniel Pühringer

    private $sId;
    private $dCreation;
    private $sUserIdCreator;
    private $sTitle;
    private $sDescr;
    private $dLastMod;
    private $sUserIdLastMod;
    private $bState;

    private $aSharedUserIds;


    /**
     * ShoppingListItem constructor.
     * @param string $sId id of the item
     * @param date $dCreation date of creation
     * @param string $sUserIdCreator name of user who created this item
     * @param string $sTitle title of the item
     * @param date $dLastMod date of last modification
     * @param string $sUserIdLastMod user of last modification
     * @param bool $bState state(active or not active) of the item; true->item is active; false->item is not active
     * @param string $sDescr is an optional parameter description of the item --> is the only optional attribute of this class!
     */
    public function __construct(string $sId, DateTime $dCreation, string $sUserIdCreator, string $sTitle,
                                DateTime $dLastMod = NULL,
                                string $sUserIdLastMod ="tutor",
                                bool $bState = true,
                                string $sDescr = "Item hat keinen Beschreibungstext",
                                $aSharedUserIds = array()){

        $this->sId = $sId;
        $this->dCreation = $dCreation;
        $this->sUserIdCreator = $sUserIdCreator;
        $this->sTitle = $sTitle;
        if($dLastMod == NULL){
            $this->dLastMod = new DateTime();
        }else{
            $this->dLastMod = $dLastMod;
        }
        $this->sUserIdLastMod = $sUserIdLastMod;
        $this->bState = $bState;
        $this->sDescr = $sDescr;
        $this->aSharedUserIds = $aSharedUserIds;//TODO
    }

    public function addUserForSharing($sUserId){
        array_push($this->aSharedUserIds, $sUserId);
    }

    /**
     * @return string of current item
     */
    public function __toString(): string
    {
        $sResult = "<li class='shoppingListItem'>";
        if($this->bState){
            $sResult.="<div class='collapsible-header'><span class='center light-green-text'><i class='material-icons'>shopping_basket</i></span>".$this->sId.": ".$this->sTitle."</div>";
        }else{
            $sResult.="<div class='collapsible-header'><i class='material-icons'>shopping_basket</i>".$this->sId.": ".$this->sTitle."</div>";
        }
        $sResult .= "<div class='collapsible-body'><form>";
        if(!$this->bState){//items which are still active should not be displayed as checked
            $sResult .= "<label for='".$this->sTitle."'><input type='checkbox' class='filled-in' id='".$this->sTitle."' name='sState' value='notActive' checked='checked'/>";
            $sResult .= "<span>Erledigt</span></label>";
        }else{
            $sResult .= "<label for='".$this->sTitle."'><input type='checkbox' class='filled-in' id='".$this->sTitle."' name='sState' value='active' />";
            $sResult .= "<span>Noch nicht erledigt</span></label>";
        }

        $sResult .= "<p>Erstellt am " . date_format($this->dCreation, "Y/m/d");
        $sResult .= " von " . $this->sUserIdCreator . "</p>";

        if($this->dLastMod != NULL && self::isSecondDateAfterFirstDate($this->dCreation, $this->dLastMod)){
            $sResult.="<p>Zuletzt geändert am ".date_format($this->dLastMod, "Y/m/d");
            $sResult.=" von ".$this->sUserIdLastMod."</p>";
        }
        $sResult.="<p>Das Item können folgende User noch sehen: <br/>";
        $aSharedUserArray = self::loadSharedUserArray($this->sId);
        $aAllUserArray = self::loadAllUserArray();
        if($aAllUserArray == null || $aSharedUserArray == null){
            return null;
        }
        foreach($aAllUserArray as $iKey => $oCurrentUser){
            $bPrintCurrentUserAsChecked = false;
            if(in_array($oCurrentUser, $aSharedUserArray)){
                $bPrintCurrentUserAsChecked = true;
            }
            if($bPrintCurrentUserAsChecked){
                if($oCurrentUser != $_SESSION["username"] && $oCurrentUser != $this->sUserIdCreator){
                    $sResult .= "<label for='".$oCurrentUser."_".$this->sId."'><input type='checkbox' class='filled-in' id='".$oCurrentUser."_".$this->sId."' name='aUserIds[]' value='".$oCurrentUser."' checked='checked'/>";
                    $sResult .= "<span>".$oCurrentUser."</span></label><br/>";
                }else {
                    if ($oCurrentUser != $this->sUserIdCreator) {
                        $sResult .= "<label for='".$oCurrentUser."_".$this->sId."'><input type='checkbox' class='filled-in' id='".$oCurrentUser."_".$this->sId."' name='aUserIds[]' value='" . $oCurrentUser . "' checked='checked'/>";
                        $sResult .= "<span>" . $oCurrentUser . " (Das sind Sie!)</span></label><br/>";
                    } else {
                        //No printing because it is the creator of the item!
                    }
                }
            }else{
                if($oCurrentUser != $this->sUserIdCreator){
                    $sResult .= "<label for='".$oCurrentUser."_".$this->sId."'><input type='checkbox' class='filled-in' id='".$oCurrentUser."_".$this->sId."' name='aUserIds[]' value='".$oCurrentUser."'/>";
                    $sResult .= "<span>".$oCurrentUser."</span></label><br/>";
                }else{
                    //No printing because it is the creator of the item!
                }
            }
        }
        $sResult.="</p>";
        $sResult.= "<label for='".$this->sTitle."'>Titel</label>";
        $sResult.= "<input type='text' value='".$this->sTitle."' name='sTitle' id='".$this->sTitle."'/>";
        $sResult.= "<br/>";

        $sResult.= "<label for='".$this->sDescr."'>Beschreibung</label>";
        $sResult.= "<input type='text' value='".$this->sDescr."' name='sDescr' id='".$this->sDescr."'/>";
        $sResult.= "<br/>";


        $sResult.= "<input type='hidden' name='itemId' value='".$this->sId."'/>";
        $sResult.= "<input type='hidden' name='action' value='editItem'/>";
        $sResult.= "<input class='btn' type='submit' value='Änderungen speichern' />";

        return $sResult."</form></div></li>";
    }


    /** loads the array of shared users for a given product id
     * @param int $sId
     * @return array
     */
    public static function loadSharedUserArray(int $sId){
        $sSelectQuery = "SELECT username FROM user_task INNER JOIN product USING(id) WHERE id = '".$sId."';";
        $oResult = Database::selectQuery($sSelectQuery);
        $aResultArrayWithNamesOfSharedUsers = array();
        if($oResult && $oResult->num_rows > 0) {
            while ($aRow = $oResult->fetch_assoc()) {
                array_push($aResultArrayWithNamesOfSharedUsers, $aRow["username"]);
            }
        }
        return $aResultArrayWithNamesOfSharedUsers;
    }

    /** returns all users which are stored in the database within the table "user"
     * @return array
     */
    public static function loadAllUserArray(){
        $sSelectQuery = "SELECT username FROM user;";
        $oResult = Database::selectQuery($sSelectQuery);
        $aResultArrayWithAllRegisteredUsers = array();
        if($oResult && $oResult->num_rows > 0) {
            while ($aRow = $oResult->fetch_assoc()) {
                array_push($aResultArrayWithAllRegisteredUsers, $aRow["username"]);
            }
        }
        return $aResultArrayWithAllRegisteredUsers;
    }

    /** gets creator of certain product by productid
     * @param $iId
     * @return string
     */
    public static function getCreatorOfProduct($iId):string{
        $sSelectQuery = "SELECT usernameCreator FROM product WHERE id='".$iId."';";
        $oResult = Database::selectQuery($sSelectQuery);
        if($oResult && $oResult->num_rows > 0) {
            return $oResult->fetch_assoc()["usernameCreator"];
        }
    }

    /** returns id of product which is searched by title and description
     * @param $sTitle
     * @param $sDescr
     * @return int
     */
    public static function getIdOfProduct($sTitle, $sDescr):int{
        $sSelectQuery = "SELECT id FROM product WHERE title='".$sTitle."' AND descr='".$sDescr."';";
        $oResult = Database::selectQuery($sSelectQuery);
        if($oResult && $oResult->num_rows > 0) {
            return $oResult->fetch_assoc()["id"];
        }
    }

    /**
     * @param $name name of the attribute which value should be returned
     * @return mixed value of attribute
     * @throws Exception if attribute does not exist
     */
    public function __get($name){
        if(property_exists("ShoppingListItem", $name)) {
            return $this->$name;
        }else{
            throw new Exception("Getter-Attribute ".$name." does not exist!");
        }
    }

    /**
     * @param $name name of the attribute which needs to be set
     * @param $value value which the attribute should get
     * @throws Exception if the attribute does not exist
     */
    public function __set($name, $value){
        if(property_exists("ShoppingListItem", $name)){
            $this->$name = $value;
        }else{
            throw new Exception("Setter-Attribute ".$name." does not exist!");
        }
    }

    /**
     * @param datetime $oFirstDate
     * @param datetime $oSecondDate
     * @return bool true if the second date is after the first date; otherwise false
     */
    public static function isSecondDateAfterFirstDate(datetime $oFirstDate, datetime $oSecondDate):bool{
        return $oFirstDate < $oSecondDate;
    }

}