<?php

namespace Core;


class View
{
    /**
     * @param string $view
     * @param array $args
     * @return \Exception|null
     * @throws \Exception
     */
    static public function render(string $view, array $args = array())
    {
        extract($args);

        $file = dirname(__DIR__) . '\App\Views\\' . $view;

        if (is_readable($file)) {
            require $file;
        } else {
            throw new \Exception($file . ' not found!');
        }

    }

}