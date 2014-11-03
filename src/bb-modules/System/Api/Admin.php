<?php
/**
 * BoxBilling
 *
 * @copyright BoxBilling, Inc (http://www.boxbilling.com)
 * @license   Apache-2.0
 *
 * Copyright BoxBilling, Inc
 * This source file is subject to the Apache-2.0 License that is bundled
 * with this source code in the file LICENSE
 */

/**
 * System management methods 
 */

namespace Box\Mod\System\Api;

class Admin extends \Api_Abstract
{
    /**
     * Returns licensing information
     * 
     * @return array
     */
    public function license_info($data)
    {
       return $this->getService()->getLicenseInfo($data);
    }

    /**
     * Return system setting param
     * @deprecated
     * @param string $key - parameter key name
     * @return string
     */
    public function param($data)
    {
        if(!isset ($data['key'])) {
            throw new \Box_Exception('Parameter key is missing');
        }
        return $this->getService()->getParamValue($data['key']);
    }

    /**
     * Get all defined system params
     * 
     * @return array
     */
    public function get_params($data)
    {
        return $this->getService()->getParams($data);
    }

    /**
     * Updated parameters array with new values. Creates new setting if it was 
     * not defined earlier. You can create new parameters using this method.
     * This method accepts any number of parameters you pass.
     * 
     * @param string $key - name of the parameter to be changed/created
     * 
     * @return bool
     */
    public function update_params($data)
    {
        return $this->getService()->updateParams($data);
    }
    
    /**
     * System messages about working environment.
     * 
     * @param string $type - messages type to be returned: info
     * 
     * @return array
     */
    public function messages($data)
    {
        $type = isset($data['type']) ? $data['type'] : 'info';
        return $this->getService()->getMessages($type);
    }
    
    /**
     * Check if passed file name template exists for admin area
     * 
     * @param string $file - template file name, example: mod_index_dashboard.phtml
     * @return bool
     */
    public function template_exists($data)
    {
        if(!isset($data['file'])) {
            return false;
        }
        
        return $this->getService()->templateExists($data['file'], $this->getIdentity());
    }
    
    /**
     * Parse string like BoxBilling template
     * 
     * @param string $_tpl - Template text to be parsed
     * 
     * @optional bool $_try - if true, will not throw error if template is not valid, returns _tpl string
     * @optional int $_client_id - if passed client id, then client API will also be available
     * 
     * @return string
     */
    public function string_render($data)
    {
        if(!isset($data['_tpl'])) {
            error_log('_tpl parameter not passed');
            return '';
        }
        $tpl = $data['_tpl'];
        $try_render = isset($data['_try']) ? $data['_try'] : false;

        $vars = $data;
        unset($vars['_tpl'], $vars['_try']);
        return $this->getService()->renderString($tpl, $try_render, $vars);
    }
    
    /**
     * Returns system environment information. 
     * 
     * @return array
     */
    public function env($data)
    {
        $ip = isset($data['ip']) ? $data['ip'] : null;
        return $this->getService()->getEnv($ip);
    }

    /**
     * Method to check if staff member has permission to access module
     * 
     * @param string $mod - module name
     * 
     * @optional string $f - module method name
     * 
     * @return bool
     * @throws \Box_Exception
     */
    public function is_allowed($data)
    {
        if(!isset($data['mod'])) {
            throw new \Box_Exception('mod parameter not passed');
        }
        
        $f = isset($data['f']) ? $data['f'] : null;
        return $this->getService()->staffHasPermissions($this->getIdentity(), $data['mod'], $f);
    }

    /**
     * Clear system cache
     *
     * @return bool
     */
    public function clear_cache()
    {
        return $this->getService()->clearCache();
    }
}