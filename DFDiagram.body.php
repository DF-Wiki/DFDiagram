<?php

class DFDHooks {
    static public function init ($parser) {
        $parser->setHook('diagram', 'DFDHooks::newDiagram');
    }
    static public function newDiagram ($text, $args, $parser, $frame) {
        $diagram = new DFDDiagram($text, $args);
        return $diagram->renderHTML();
    }
    static public function includeModules ($outPage, $skin) {
        /*
         * Include the resources in $wgResourceModules
         */
        $user = $skin->getContext()->getUser();

        $outPage->addModuleStyles(array('ext.DFDiagram'));
        if($user->getOption('dfdiagram-use-canvas')) {
            $outPage->addModules('ext.DFDiagram.canvas');
        }

        return true;
    }
    static public function getPreferences ($user, &$preferences) {
        // Create preferences
        $preferences['dfdiagram-use-canvas'] = array(
            'type' => 'toggle',
            'label-message' => 'dfdiagram-use-canvas',
            'section' => 'dfdiagram/dfdiagram-canvas'
        );
        return true;
    }

}