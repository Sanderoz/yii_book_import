<?php

namespace common\components\traits;

trait Tree
{
    function buildTree(array $data, $parent_id = 0, $parent_key = 'parent')
    {
        $array = $data;
        return $this->getChilds($array);
    }

    private function getChilds(&$data, $parent_id = 0, $parent_key = 'parent')
    {
        $childs = [];
        foreach ($data as $index => $item) {
            if ($item[$parent_key] === $parent_id) {
                $temp = $item;
                unset($data[$index]);
                $temp['childs'] = $this->getChilds($data, $temp['id']);
                $childs[] = $temp;
            }
        }
        return $childs;
    }
}