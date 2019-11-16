<?php


namespace Mail;


class BaseFolderMapper extends MapperInterface
{

    protected function onMap($item)
    {
        return $item->name;
    }
}