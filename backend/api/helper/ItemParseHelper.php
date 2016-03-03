<?php 
// item parse helper
class ItemParseHelper
{
    public static $dumpTemp = null;
    public function __construct() { }
	
    /**
    * @desc Clear vars of memory.
    * @return null.
    */
    private static function clearVars()
    {
        self::$dumpTemp['code'] = null;
        self::$dumpTemp['error'] = null;
        self::$dumpTemp['isItem'] = null;
        self::$dumpTemp['isFree'] = null;
        self::$dumpTemp['itemIdIndex'] = null;
        self::$dumpTemp['itemIdSection'] = null;
        self::$dumpTemp['itemIdUnique'] = null;
        self::$dumpTemp['itemLevel'] = null;
        self::$dumpTemp['itemOption'] = null;
        self::$dumpTemp['itemSkill'] = null;
        self::$dumpTemp['itemLuck'] = null;
        self::$dumpTemp['itemDurability'] = null;
        self::$dumpTemp['itemSerial'] = null;
        self::$dumpTemp['itemExcellents'] = array(null, null, null, null, null, null, 0);
        self::$dumpTemp['itemAncient'] = null;
        self::$dumpTemp['itemRefine'] = null;
        self::$dumpTemp['harmonyType'] = null;
        self::$dumpTemp['harmonyLevel'] = null;
        self::$dumpTemp['socket'] = array(null, null, null, null, null);  
        self::$dumpTemp['itemName'] = null;  
        self::$dumpTemp['slot']['i'] = null;
        self::$dumpTemp['slot']['x'] = null;  
        self::$dumpTemp['slot']['y'] = null;  
        self::$dumpTemp['item']['x'] = null;  
        self::$dumpTemp['item']['y'] = null; 
    }
    
    /**
    * @desc Print error.
    * @return string.
    */      
    public static function getError()
    {                    
        return self::$dumpTemp['error']; 
    }
    
    /**
    * @desc get details of index.
    * @return null.
    */      
    protected static function getIndex($hex)
    {   
        switch(strlen($hex))
        {
            case 20:
                $tempId = hexdec(substr($hex, 0, 2));
                self::$dumpTemp['itemIdIndex'] = ($tempId & 0x1F);
                self::$dumpTemp['itemIdSection'] = (($tempId & 0xE0) >> 5);
                $tmpUnique = hexdec(substr($hex, 14, 2)); 
                self::$dumpTemp['itemIdSection'] += (($tmpUnique & 0x80) == 0x80 ? 8 : 0);
                break;
            case 32: 
                self::$dumpTemp['itemIdIndex'] = hexdec(substr($hex, 0, 2));
                self::$dumpTemp['itemIdSection'] = hexdec(substr($hex, 18, 1));
                break;
        }
    }
    
    /**
    * @desc get details of Level / Option / Skill / Luck.
    * @return null.
    */ 
    protected static function getLevelOptionSkillLuck($hex)
    {
        $tempAttr[0] = hexdec(substr($hex, 2, 2));
        $tempAttr[1] = hexdec(substr($hex, 14, 2)); 
        
        self::$dumpTemp['itemSkill'] = (($tempAttr[0] & 0x80) == 0x80);
        self::$dumpTemp['itemOption'] = ($tempAttr[0] & 0x03) + (($tempAttr[1] & 0x40) == 0x40 ? 4 : 0 );
        self::$dumpTemp['itemLuck'] = (($tempAttr[0] & 0x04) == 0x04); 
        self::$dumpTemp['itemLevel'] = (($tempAttr[0] & 0x78) >> 3);
    }
    
    /**
    * @desc get durability.
    * @return null.
    */
    protected static function getDurability($hex)
    {
        self::$dumpTemp['itemDurability'] = hexdec(substr($hex, 4, 2));
    }
    
    /**
    * @desc get serial.
    * @return null.
    */
    protected static function getSerial($hex)
    {
        self::$dumpTemp['itemSerial'] = substr($hex, 6, 8);
    }
    
    /**
    * @desc get details of options excellents.
    * @return null.
    */
    protected static function getExcellents($hex)
    {
        $tempAttr = hexdec(substr($hex, 14, 2));
        self::$dumpTemp['itemExcellents'][5] = (($tempAttr & 0x20) == 0x20); 
        self::$dumpTemp['itemExcellents'][4] = (($tempAttr & 0x10) == 0x10); 
        self::$dumpTemp['itemExcellents'][3] = (($tempAttr & 0x08) == 0x08); 
        self::$dumpTemp['itemExcellents'][2] = (($tempAttr & 0x04) == 0x04); 
        self::$dumpTemp['itemExcellents'][1] = (($tempAttr & 0x02) == 0x02); 
        self::$dumpTemp['itemExcellents'][0] = (($tempAttr & 0x01) == 0x01);
        foreach(self::$dumpTemp['itemExcellents'] as $exc)
            if($exc == true) self::$dumpTemp['itemExcellents'][6]++;
    }
    
    /**
    * @desc get details of option ancient.
    * @return null.
    */
    protected static function getAncient($hex)
    {
        self::$dumpTemp['itemAncient'] = hexdec(substr($hex, 17, 1)); 
    }
    
    /**
    * @desc get details of option refine.
    * @return null.
    */
    protected static function getRefine($hex)
    {
        self::$dumpTemp['itemRefine'] = hexdec(substr($hex, 19, 1)); 
    }
    
    /**
    * @desc get harmony options.
    * @return null.
    */
    protected static function getHarmony($hex)
    {
        $tempAttr = hexdec(substr($hex, 20, 2));
        self::$dumpTemp['harmonyType'] = (($tempAttr & 0xF0) >> 4);
        self::$dumpTemp['harmonyLevel'] = ($tempAttr & 0x0F);  
    }
    
    /**
    * @desc get socket options.
    * @return null.
    */
    protected static function getSocket($hex)
    {
        self::$dumpTemp['socket'][0] = hexdec(substr($hex, 22, 2));                    
        self::$dumpTemp['socket'][1] = hexdec(substr($hex, 24, 2));                    
        self::$dumpTemp['socket'][2] = hexdec(substr($hex, 26, 2));                    
        self::$dumpTemp['socket'][3] = hexdec(substr($hex, 28, 2));                    
        self::$dumpTemp['socket'][4] = hexdec(substr($hex, 30, 2));                  
    }
    
    /**
    * @desc get properties from database.
    * @return null.
    */
    protected static function getPropertiesFromDatabase()
    {
        self::$dumpTemp['itemName'] = ItemDatabaseHelper::$dbItem[ self::$dumpTemp['itemIdSection'] ][ self::$dumpTemp['itemIdIndex'] ]['name'];                  
        self::$dumpTemp['item']['x'] = ItemDatabaseHelper::$dbItem[ self::$dumpTemp['itemIdSection'] ][ self::$dumpTemp['itemIdIndex'] ]['x'];                  
        self::$dumpTemp['item']['y'] = ItemDatabaseHelper::$dbItem[ self::$dumpTemp['itemIdSection'] ][ self::$dumpTemp['itemIdIndex'] ]['y'];                  
    }
    
    /**
    * @desc is item.
    * @return boll.
    */
    protected static function isItem($hex)
    {
        switch(strlen($hex))
        {
            case 20:
                if(str_pad("", 10*2, "F") == strtoupper($hex))
                {
                    self::$dumpTemp['isItem'] = false;
                    self::$dumpTemp['isFree'] = true;
                } 
                else
                {
                    self::$dumpTemp['isItem'] = true;
                    self::$dumpTemp['isFree'] = false;
                }  
                break;
            case 32:   
                if(str_pad("", 16*2, "F") == $hex)
                {
                    self::$dumpTemp['isItem'] = false;
                    self::$dumpTemp['isFree'] = true;
                } 
                else
                {
                    self::$dumpTemp['isItem'] = true;
                    self::$dumpTemp['isFree'] = false;
                }    
                break; 
        }
    }
    /**
    * @desc is item.
    * @return boll.
    */
    public static function getPositionBySlot($slot, $inventory = false)
    {
        self::$dumpTemp['slot']['i'] = $slot;
        if($inventory == true) $slot -= 12;
        self::$dumpTemp['slot']['x'] = $slot % 8;
        self::$dumpTemp['slot']['y'] = floor($slot / 8);
    }
    
    /**
    * @desc Reading of the hex a particular item.
    * @param hexItem hexadicimal item.
    * @param dbVersion dbVersion of item.
    * @return true/false.
    */
    public static function parseHexItem($hexItem)
	{
        self::clearVars();
        self::$dumpTemp['code'] = $hexItem;

        switch(strlen($hexItem))
        {
            case 20:
                self::isItem($hexItem);
                if(self::$dumpTemp['isItem'] == false) return true;
                self::getIndex($hexItem);
                self::getLevelOptionSkillLuck($hexItem);
                self::getDurability($hexItem);
                self::getSerial($hexItem);
                self::getExcellents($hexItem);
                self::getAncient($hexItem);
                self::getPropertiesFromDatabase(); 
                break;
            case 32:
                self::isItem($hexItem);
                if(self::$dumpTemp['isItem'] == false) return true;
                self::getIndex($hexItem);
                self::getLevelOptionSkillLuck($hexItem);
                self::getDurability($hexItem);
                self::getSerial($hexItem);
                self::getExcellents($hexItem);
                self::getAncient($hexItem);
                self::getRefine($hexItem);
                self::getHarmony($hexItem);
                self::getSocket($hexItem);
                self::getPropertiesFromDatabase(); 
                break;   
        }

        return true;
	}
}