<?php
declare(strict_types=1);


namespace Mail;


abstract class MapperInterface
{
    public function map(array $data) {
        return array_map(function($item) {
            return $this->onMap($item);
        }, $data);
    }

    abstract protected function onMap($item);
}