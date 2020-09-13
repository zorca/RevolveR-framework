<?php

 /*
  *
  * RevolveR 
  *
  * Contents Management Framework
  *
  * v.1.9.2
  * 
  *
  *
  *
  *
  *                   ^
  *                  | |
  *                @#####@
  *              (###   ###)-.
  *            .(###     ###) \
  *           /  (###   ###)   )
  *          (=-  .@#####@|_--"
  *          /\    \_|l|_/ (\
  *         (=-\     |l|    /
  *          \  \.___|l|___/
  *          /\      |_|   /
  *         (=-\._________/\
  *          \             /
  *            \._________/
  *              #  ----  #
  *              #   __   #
  *              \########/
  *
  *
  *
  * Developer: Dmitry Maltsev
  *
  * License: Apache 2.0
  *
  */

#
# Allow strict
#
# To run framework with developers
# use strict_types = 1 bellow
#

declare( ticks = 0, strict_types = 0 );

define('StartTime', microtime(true));

define('MemoryStart', memory_get_usage());

//if( is_readable('./kernel.php') ) {

  //require_once('./kernel.php');

//}

define('KERNEL_CONFIG', [

  'Parts' => [

    'Kernel#0' => [

      'Countries list' => [

        'directory' => './private/',
        'file'    => 'Countries',

      ],

      'Translations of interface' => [

        'directory' => './private/',
        'file'    => 'Translations',

      ],

      'Structures of DataBase' => [

        'directory' => './Kernel/Structures/',
        'file'    => 'DataBase',

      ],

      'Kernel HTML Forms API' => [

        'directory' => './Kernel/Modules/',
        'file'    => 'HTMLFormBuilder',

      ],

      'Kernel Calendar' => [

        'directory' => './Kernel/Modules/',
        'file'    => 'Calendar',

      ],

      'Kernel User Agent Detection' => [

        'directory' => './Kernel/Modules/',
        'file'    => 'DetectUserAgent',

      ],

      'Kernel Notifications' => [

        'directory' => './Kernel/Modules/',
        'file'    => 'Notifications',

      ],

      'Kernel Parser' => [

        'directory' => './Kernel/Modules/',
        'file'    => 'Parse',

      ],

      'Kernel Markup' => [

        'directory' => './Kernel/Modules/',
        'file'    => 'Markup',

      ],

      'Kernel Optimizers' => [

        'directory' => './Kernel/Modules/',
        'file'    => 'Minifier',

      ],

      'Kernel Language' => [

        'directory' => './Kernel/Modules/',
        'file'    => 'Language',

      ],

      'Kernel Captcha' => [

        'directory' => './Kernel/Modules/',
        'file'    => 'Captcha',

      ],

      'Kernel Data Base API' => [

        'directory' => './Kernel/Modules/',
        'file'    => 'DataBaseX',

      ],

      'Kernel Cipher' => [

        'directory' => './Kernel/Modules/',
        'file'    => 'Cipher',

      ],

      'Kernel Auth' => [

        'directory' => './Kernel/Modules/',
        'file'    => 'Auth',

      ],

      'Kernel Menu' => [

        'directory' => './Kernel/Modules/',
        'file'    => 'Menu',

      ],

      'Kernel Route' => [

        'directory' => './Kernel/Modules/',
        'file'    => 'Route',

      ],

      'Kernel Node' => [

        'directory' => './Kernel/Modules/',
        'file'    => 'Node',

      ],

      'Kernel Variables' => [

        'directory' => './Kernel/Modules/',
        'file'    => 'Vars',

      ],

      'Kernel Mail' => [

        'directory' => './Kernel/Modules/',
        'file'    => 'Mail',

      ],

      'Kernel Models' => [

        'directory' => './Kernel/Modules/',
        'file'    => 'Model',

      ],

      'Kernel Statistics' => [

        'directory' => './Kernel/Modules/',
        'file'    => 'Statistics',

      ],

      'Kernel Extra MMDB Decoder' => [

        'directory' => './Kernel/Modules/Extra/',
        'file'    => 'MMDBDecoder',

      ],

      'Kernel Extra MMDB Reader' => [

        'directory' => './Kernel/Modules/Extra/',
        'file'    => 'MMDBReader',

      ],

      'Kernel Resolve' => [

        'directory' => './Kernel/Modules/',
        'file'    => 'Conclude',

      ],

      'Kernel Core' => [

        'directory' => './Kernel/',
        'file'    => 'Kernel',

      ],

    ]

  ]

]);


# READ KERNEL CONFIG
foreach( KERNEL_CONFIG['Parts']['Kernel#0'] as $KPart ) {

  	require_once( $KPart['directory'] . $KPart['file'] .'.php' );

}

?>
