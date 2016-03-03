<?php 
// item create helper
class ItemCreateHelper
{
    private static $dumpFinalItem, $ruleHex = null, $databaseModel = null, $properties = null;
	public function __construct() { }
	
    /**
    * @desc Check the databaseModel and creates the mask of the hex.
    * @return BOOL.
    */
    private static function databaseModelRule()
    {
        if(self::$databaseModel < 1 || self::$databaseModel > 3) return false;
        switch(self::$databaseModel)
        {
            case 1: self::$ruleHex = "%02X%02X%02X%08X%02X0%01X00"; 
                    /* Item ID, Level/Option/Skill/Luck, Durability, Serial, Unique/Excellents, NOP, Ancient, NOPs */ break;   
            case 2: case 3: self::$ruleHex = "%02X%02X%02X%08X%02X0%01X%01X%01X%01X%01X%02X%02X%02X%02X%02X";
                    /* Item ID, Level/Option/Skill/Luck, Durability, Serial, Excellents, NOP, Ancient, Id Categorie, Refine (380), Harmony Type, Harmony Level, Socket Op1, Socket Op2, Socket Op3, Socket Op4, Socket Op5  */  break;   
        }
        return true;
    }
    
    /**
    * @desc Checks if the properties are correct in accordance with the databaseModel.
    * @return BOOL.
    */
    private static function checkProperties()
    {
        switch(self::$databaseModel)
        {
            case 1:
                if(isset(self::$properties['level']) == false) return false;
                if(isset(self::$properties['option']) == false) return false;
                if(isset(self::$properties['skill']) == false) return false;
                if(isset(self::$properties['luck']) == false) return false;
                if(isset(self::$properties['durability']) == false) return false;
                if(isset(self::$properties['excellent'][0]) == false || isset(self::$properties['excellent'][1]) == false || isset(self::$properties['excellent'][2]) == false || isset(self::$properties['excellent'][3]) == false || isset(self::$properties['excellent'][4]) == false || isset(self::$properties['excellent'][5]) == false) return false;
                if(isset(self::$properties['ancient']) == false) return false;
                break;
            case 2: case 3:
                if(isset(self::$properties['level']) == false) return false;
                if(isset(self::$properties['option']) == false) return false;
                if(isset(self::$properties['skill']) == false) return false;
                if(isset(self::$properties['luck']) == false) return false;
                if(isset(self::$properties['durability']) == false) return false;
                if(isset(self::$properties['excellent'][0]) == false || isset(self::$properties['excellent'][1]) == false || isset(self::$properties['excellent'][2]) == false || isset(self::$properties['excellent'][3]) == false || isset(self::$properties['excellent'][4]) == false || isset(self::$properties['excellent'][5]) == false) return false;
                if(isset(self::$properties['ancient']) == false) return false;
                if(isset(self::$properties['refine']) == false) return false;
                if(isset(self::$properties['harmonyType']) == false) return false;
                if(isset(self::$properties['harmonyLevel']) == false) return false;
                if(isset(self::$properties['socketOption'][0]) == false || isset(self::$properties['socketOption'][1]) == false || isset(self::$properties['socketOption'][2]) == false || isset(self::$properties['socketOption'][3]) == false || isset(self::$properties['socketOption'][4]) == false) return false;
                break;
            default: return false;
        }
        return true;   
    }  
    
    /**
    * @desc Calculate the durability of the standard item.
    * @param indexSection Id of the category to which the item belongs.
    * @param indexItem Id item.
    * @param durBase Default durability of the item.
    * @param itemLevel Item Level.
    * @param excellentItem If the item is excellent or not.
    * @param setItem If the item is ancient or not
    * @return Returns the durability (int).
    */
    private static function itemGetDurability($indexSection, $indexItem, $durBase, $itemLevel, $excellentItem, $setItem)
    {
        if($indexSection == 14 && $indexItem == 27 && $itemLevel == 3) {
            $itemLevel = 0;
        }
        if($indexSection == 14 && $indexItem == 29) {
            return 1;
        }
        $durability = 0;
        if($itemLevel < 5) {
            $durability = $durBase + $itemLevel;
        }
        else {
            switch($itemLevel) {
                case 10: $durability = $durBase + $itemLevel*2-3; break;
                case 11: $durability = $durBase + $itemLevel*2-1; break;
                case 12: $durability = $durBase + $itemLevel*2+2; break;
                case 13: $durability = $durBase + $itemLevel*2+6; break;
                case 14: $durability = $durBase + $itemLevel*2+11; break;
                case 15: $durability = $durBase + $itemLevel*2+17; break;
                default: $durability = $durBase + $itemLevel*2-4; break;
            }
        }

        if(( $indexSection == 12 && $indexItem == 3 ) == false && ( $indexSection == 12 && $indexItem == 4 ) == false && ( $indexSection == 12 && $indexItem == 5 ) == false && ( $indexSection == 12 && $indexItem == 6 ) == false && ( $indexSection == 12 && $indexItem == 36 ) == false && ( $indexSection == 12 && $indexItem == 37 ) == false && ( $indexSection == 12 && $indexItem == 38 ) == false && ( $indexSection == 12 && $indexItem == 39 ) == false && ( $indexSection == 12 && $indexItem == 40 ) == false && ( $indexSection == 12 && $indexItem == 43 ) == false && ( $indexSection == 0 && $indexItem == 19 ) == false && ( $indexSection == 4 && $indexItem == 18 ) == false && ( $indexSection == 5 && $indexItem == 10 ) == false && ( $indexSection == 2 && $indexItem == 13 ) == false && ( $indexSection == 13 && $indexItem == 30 ) == false) {
            if($setItem != 0) {
                $durability += 20;
            }
            elseif($excellentItem != 0) {
                $durability += 15;  
            }
        }
        if($durability > 255) {
            $durability = 255;
        }
        return $durability;     
    } 
    
    /**
    * @desc Get serial.
    * @return Int value with serial.
    */
    private static function itemGetSerial()
    {
        return hexdec(self::$properties["serial"]);
    }                
    
    /**
     * @desc Generates the binary code of a particular item.
     * @param indexItem Item Id.
     * @param indexSection Id of the category to which the item belongs.
     * @param databaseModel Version database: 1 = (10 bytes, not personal store), 2/3 = (16 bytes, with personal store and harmony)
     * @param properties Properties of the item, level, excellent, etc.
     * @return String.
     */ 
    public static function createItem($databaseModel, $indexSection, $indexItem, $properties)
    {
        self::$databaseModel = $databaseModel;
        self::$properties = $properties;
        if(self::databaseModelRule() == false) return false;
        if(self::checkProperties() == false) return false;
        
        $dumpTemp['itemId'] = null;
        $dumpTemp['itemLOSL'] = null;
        $dumpTemp['itemDurability'] = null;
        $dumpTemp['itemSerial'] = null;
        $dumpTemp['itemExcellents'] = null;
        $dumpTemp['itemAncient'] = null;
        $dumpTemp['categorieId'] = null;
        $dumpTemp['itemRefine'] = null;
        $dumpTemp['harmonyType'] = null;
        $dumpTemp['harmonyLevel'] = null;
        $dumpTemp['sockect'] = array(null, null, null, null, null);
        
        switch(self::$databaseModel)
        {
            case 1:
                $dumpTemp['itemId'] = ((($indexItem & 0x1F) | (($indexSection << 5) & 0xE0)) & 0xFF); 
                $dumpTemp['itemLOSL'] = (int)(self::$properties['level']*8) + (self::$properties['skill'] == true ? 128 : 0) + (self::$properties['luck'] == true ? 4 : 0) + (self::$properties['option'] < 4 ? self::$properties['option'] : (self::$properties['option'] - 4));
                $dumpTemp['itemDurability'] = self::itemGetDurability($indexItem, $indexSection, self::$properties['durability'], self::$properties['level'], (self::$properties['excellent'][0] == true || self::$properties['excellent'][1] == true || self::$properties['excellent'][2] == true || self::$properties['excellent'][3] == true || self::$properties['excellent'][4] == true || self::$properties['excellent'][5] == true ? 1 : 0) , self::$properties['ancient']);   
                $dumpTemp['itemSerial'] = self::itemGetSerial();
                $dumpTemp['itemExcellents'] = (int)(($indexSection * 32) > 255 ? 128 : 0) + (self::$properties['option'] >= 4 ? 64 : 0) + (self::$properties['excellent'][0] == true ? 1 : 0) + (self::$properties['excellent'][1] == true ? 2 : 0) + (self::$properties['excellent'][2] == true ? 4 : 0) + (self::$properties['excellent'][3] == true ? 8 : 0) + (self::$properties['excellent'][4] == true ? 16 : 0) + (self::$properties['excellent'][5] == true ? 32 : 0);
                $dumpTemp['itemAncient'] = (int)(self::$properties['ancient'] == 1 ? 5 : 0) + (self::$properties['ancient'] == 2 ? 9 : 0);

                $hexItem = sprintf(self::$ruleHex, $dumpTemp['itemId'], $dumpTemp['itemLOSL'], $dumpTemp['itemDurability'], $dumpTemp['itemSerial'], $dumpTemp['itemExcellents'], $dumpTemp['itemAncient']);
                break;
            case 2: case 3: 
                $dumpTemp['itemId'] = $indexItem; 
                $dumpTemp['itemLOSL'] = (int)(self::$properties['level']*8) + (self::$properties['skill'] == true ? 128 : 0) + (self::$properties['luck'] == true ? 4 : 0) + (self::$properties['option'] < 4 ? self::$properties['option'] : (self::$properties['option'] - 4));
                $dumpTemp['itemDurability'] = self::itemGetDurability($indexItem, $indexSection, self::$properties['durability'], self::$properties['level'], (self::$properties['excellent'][0] == true || self::$properties['excellent'][1] == true || self::$properties['excellent'][2] == true || self::$properties['excellent'][3] == true || self::$properties['excellent'][4] == true || self::$properties['excellent'][5] == true ? 1 : 0) , self::$properties['ancient']);   
                $dumpTemp['itemSerial'] = self::itemGetSerial();
                $dumpTemp['itemExcellents'] = (int)(self::$properties['option'] >= 4 ? 64 : 0) + (self::$properties['excellent'][0] == true ? 1 : 0) + (self::$properties['excellent'][1] == true ? 2 : 0) + (self::$properties['excellent'][2] == true ? 4 : 0) + (self::$properties['excellent'][3] == true ? 8 : 0) + (self::$properties['excellent'][4] == true ? 16 : 0) + (self::$properties['excellent'][5] == true ? 32 : 0);
                $dumpTemp['itemAncient'] = (int)(self::$properties['ancient'] == 1 ? 5 : 0) + (self::$properties['ancient'] == 2 ? 10 : 0);
                $dumpTemp['categorieId'] = $indexSection;
                $dumpTemp['itemRefine'] = (int)(self::$properties['refine'] == true ? 8 : 0);
                $dumpTemp['harmonyType'] = (int)self::$properties['harmonyType'];
                $dumpTemp['harmonyLevel'] = (int)self::$properties['harmonyLevel'];
                $dumpTemp['socket'][0] = (int)self::$properties['socketOption'][0];
                $dumpTemp['socket'][1] = (int)self::$properties['socketOption'][1];
                $dumpTemp['socket'][2] = (int)self::$properties['socketOption'][2];
                $dumpTemp['socket'][3] = (int)self::$properties['socketOption'][3];
                $dumpTemp['socket'][4] = (int)self::$properties['socketOption'][4];

                $hexItem = sprintf(self::$ruleHex, $dumpTemp['itemId'], $dumpTemp['itemLOSL'], $dumpTemp['itemDurability'], $dumpTemp['itemSerial'], $dumpTemp['itemExcellents'], $dumpTemp['itemAncient'], $dumpTemp['categorieId'], $dumpTemp['itemRefine'], $dumpTemp['harmonyType'], $dumpTemp['harmonyLevel'], $dumpTemp['socket'][0], $dumpTemp['socket'][1], $dumpTemp['socket'][2], $dumpTemp['socket'][3], $dumpTemp['socket'][4]);
                break;
        }
        return $hexItem;
    }

	/**
	 * @desc Generates the binary code of a empty item.
     * @param databaseModel Version database: 1 = (10 bytes, not personal store), 2/3 = (16 bytes, with personal store and harmony)
     * @return String.
	 */ 
	public static function createEmptyItem($databaseModel)
	{
        return str_pad("", ($databaseModel == 1 ? 20 : 32), "F");
	}
}