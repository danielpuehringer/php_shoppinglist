<?php
/**
 * Created by PhpStorm.
 * User: S1610456027
 * Date: 04.05.2018
 * Time: 07:42
 */

class ShoppingList{//Author: Daniel Pühringer

    private $aEntries;//stores all items

    public function __construct(){
        $this->aEntries = array();
    }

    /**
     * @param $oItem item to add
     */
    public function addItem($oItem){
        array_push($this->aEntries, $oItem);
    }

    /**
     * @param ShoppingListItem $oItemToEdit the item which needs to be edited
     * @param $mAttributeToEdit the value of this attribute gets changed
     * @param $mValueToEdit this is the new value of the changed attribute
     * @throws Exception if the attribute is not found, or the object is null
     */
    public function editItem(ShoppingListItem $oItemToEdit, $mAttributeToEdit, $mValueToEdit){//TODO deprecated
        try{
            if(property_exists("ShoppingListItem", $mAttributeToEdit)){
                if(is_null($oItemToEdit)){
                    throw new Exception("The given object is null!");
                }else{
                    $oItemToEdit->$mAttributeToEdit = $mValueToEdit;
                }
            }else{
                throw new Exception("Setter-Attribute ".$mAttributeToEdit." does not exist!");
            }
        }catch(Exception $e){
            echo("Exception: ". $e->getMessage());
        }
    }

    public function __get($sName){
        return $this->{$sName};
    }//public function __get($sName)


    /**
     * @param ShoppingListItem $oItemToDelete this item gets deleted from shoppingList
     * @throws Exception if the element is not found in the list
     */
    public function deleteItem(ShoppingListItem $oItemToDelete){
        foreach ($this->aEntries as $iKey => $oCurrentItem){
            if($oCurrentItem == $oItemToDelete){
                array_splice($this->aEntries, $iKey, 1);
                return;
            }
        }
        throw new Exception("The item is not in the shopping list!");
    }

    public function checkIfUserCanSeeOtherSharedItems($oItem, $sUserId){
        $aSharedUserForItem = $oItem->aSharedUserIds;
        foreach ($aSharedUserForItem as $iKey => $mValue){
            if($mValue == $sUserId){
                return true;
            }
        }
        return false;
    }

    /**
     * @return string returns all stored items of the list
     */
    public function  __toString():string{
        $sResult = "<h5><span class='center light-blue-text'><i class='material-icons'>add_shopping_cart</i></span>Shopping List</h5><ul class='collapsible'>";
        foreach ($this->aEntries as $iKey => $oCurrentItem){
            $sResult.= $oCurrentItem;
        }
        return $sResult."<br/><br/></ul>";
    }

    public function printShoppingListForSpecificUser($sIdCreator){
        $sResult = "<h5><span class='center light-blue-text'><i class='material-icons'>add_shopping_cart</i></span>Shopping List</h5><ul class='collapsible'>";
        //get rows
        foreach ($this->aEntries as $iKey => $oCurrentItem){
            if($oCurrentItem->sUserIdCreator == $sIdCreator || $this->checkIfUserCanSeeOtherSharedItems($oCurrentItem, $sIdCreator)){
                $sResult.= $oCurrentItem;
            }
        }
        return $sResult."<br/><br/></ul>";
    }

    public function getItemById(string $sItemIdOfSearchedItem){
        $oSearchedItem = "";
        foreach ($this->aEntries as $iKey => $oCurrentItem){
            if($oCurrentItem->sId == $sItemIdOfSearchedItem){
             return $oCurrentItem;
            }
        }
        return $oSearchedItem;
    }

    public function refreshObject($oShoppinglistItem){
        for($i = 0; $i < count($this->aEntries); $i++){
            if($oShoppinglistItem->sId == $this->aEntries[$i]->sId){
                $this->aEntries[$i] = $oShoppinglistItem;
                return;
            }
        }
    }

}