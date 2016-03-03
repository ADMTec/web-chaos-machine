<?php
// inventory helper
class InventoryHelper
{
    public $itemsDump, $codeGroup;
    public function __construct($itemsDump) 
    {
        $this->clearVars();
        $this->itemsDump = $itemsDump;
        $this->cutCode();
        $this->structureInventory();
    }
    private function clearVars()
    {
        $this->itemsDump = NULL;
        $this->codeGroup = array();
    }
    public function cutCode()
    {
        $codeGroup = array();

        $codeLength = strlen($this->itemsDump);
        if($codeLength == (10 * 76 * 2)) {
            $codeGroup = str_split($this->itemsDump, 20);
        } else if($codeLength == (16 * 108 * 2) || $codeLength == (16 * (108 + 128) * 2)) {
            $codeGroup = str_split($this->itemsDump, 32);
        } else {
            throw new Exception("error, invalid inventory size");
        }

        foreach($codeGroup as $slot => $code)
        {
            ItemParseHelper::parseHexItem($code);
            ItemParseHelper::getPositionBySlot($slot, $slot >= 12);
            array_push($this->codeGroup, ItemParseHelper::$dumpTemp);
        }
    } 
    public function structureInventory()
    {
        $slot = 12;
        for($y = 0; $y < 8; $y++)
        {
            for($x = 0; $x < 8; $x++)
            {
                if($this->codeGroup[$slot]['isItem'] == true)
                {
                    for($cY = 0; $cY < $this->codeGroup[$slot]['item']['y']; $cY++)
                    {
                        $this->codeGroup[$slot+($cY*8)]['isFree'] = false; 
                        for($cX = 0; $cX < $this->codeGroup[$slot]['item']['x']; $cX++)
                        {
                            $this->codeGroup[$slot+($cY*8)+$cX]['isFree'] = false;
                        }
                    }     
                }
                $slot++;
            }       
        }
    } 
    public function searchSlotsInInventory($sX, $sY)
    {
        $slot = 12;
        for($y = 0; $y < 8; $y++)
        {
            for($x = 0; $x < 8; $x++)
            {
                if($this->codeGroup[$slot]['isFree'] == true)
                {
                    $free = true;
                    if($y+$sY <= 8 && $x+$sX <= 8) 
                    {
                        for($cY = 0; $cY < $sY; $cY++)
                        {
                            if($this->codeGroup[$slot+($cY*8)]['isFree'] == false) $free = false; 
                            for($cX = 0; $cX < $sX; $cX++)
                            {
                                if($this->codeGroup[$slot+($cY*8)+$cX]['isFree'] == false) $free = false;
                            }
                        }
                        if($free == true) return $slot;
                    }
                }
                $slot++;
            }       
        }
        return -1;
    }
    public function insertItemInSlot($hex, $slot)
    {
        ItemParseHelper::parseHexItem($hex);
        ItemParseHelper::getPositionBySlot($slot, $slot >= 12);
        $this->codeGroup[$slot] = ItemParseHelper::$dumpTemp;
    }
    public function exportDump() {
        $dump = null;
        foreach($this->codeGroup as $slot) {
            $dump .= $slot["code"];
        }
        return $dump;
    }
}