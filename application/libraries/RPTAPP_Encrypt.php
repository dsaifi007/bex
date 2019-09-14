<?php
/**
 * CodeIgniter
 *
 * An open source application development framework for PHP
 *
 * This content is released under the MIT License (MIT)
 *
 * Copyright (c) 2014 - 2017, British Columbia Institute of Technology
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *
 * @package	CodeIgniter
 * @author	EllisLab Dev Team
 * @copyright	Copyright (c) 2008 - 2014, EllisLab, Inc. (https://ellislab.com/)
 * @copyright	Copyright (c) 2014 - 2017, British Columbia Institute of Technology (http://bcit.ca/)
 * @license	http://opensource.org/licenses/MIT	MIT License
 * @link	https://codeigniter.com
 * @since	Version 1.0.0
 * @filesource
 */
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * CodeIgniter Encryption Class
 *
 * Provides two-way keyed encoding using Mcrypt
 *
 * @package		CodeIgniter
 * @subpackage	Libraries
 * @category	Libraries
 * @author		EllisLab Dev Team
 * @link		https://codeigniter.com/user_guide/libraries/encryption.html
 */
class  RPTAPP_Encrypt extends CI_Encrypt {

    private $CI;
    private $encrypt_key;
    private $enc_iv;

    public function __construct() {
        $this->CI = & get_instance();
        $this->encrypt_key = $this->CI->config->item('encryption_key');
        $this->enc_iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('AES-128-ECB'));
    }

    public function encodeData($value) {
        if (!$value) { return false; }
        return trim($this->safe_b64encode(openssl_encrypt($value,"AES-128-ECB", $this->encrypt_key, 1, $this->enc_iv)));
    }

    public function decodeData($value) {
        if (!$value) { return false; }
        return trim(openssl_decrypt($this->safe_b64decode($value), "AES-128-ECB", $this->encrypt_key, 1, $this->enc_iv));
    }

    public function safe_b64encode($string) {
        return str_replace(array('+', '/', '='), array('-', '_', ''), base64_encode($string));
    }

    public function safe_b64decode($string) {
        $string = str_replace(array('-', '_'), array('+', '/'), $string);
        $mod4 = strlen($string) % 4;
        if ($mod4) {
            $string .= substr('====', $mod4);
        }
        return base64_decode($string);
    }

}
