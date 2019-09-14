<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class RPTAPP_Form_validation extends CI_Form_validation
{
    public function validate_url($url) {
        if(!filter_var($url, FILTER_VALIDATE_URL)) {
            $this->CI->form_validation->set_message('validate_url', $this->CI->lang->line('err_invalid_url'));
            return false;
        }
        return true;
    }

    public function validate_date($date, $format='Y-m-d') {
        if(!DateTime::createFromFormat($format, $date)) {
            $this->CI->form_validation->set_message('validate_date', $this->CI->lang->line('err_invalid_date'));
            return false;
        }
        return true;
    }

    public function multi_is_unique($val, $param) {

        $param = json_decode($param, 1);
        if(is_array($param) && count($param) == 3) {
            $primary_key = key($param);

            $this->CI->db->select($primary_key);
            foreach ($param['fields'] as $v) {
                if (!$val) {
                    continue;
                }
                (is_array($this->CI->input->post($v))) ? $this->CI->db->where_in($v, $this->CI->input->post($v)) : $this->CI->db->where($v, $this->CI->input->post($v));

                //$this->CI->db->where($v, $val);
            }
            if ($param[$primary_key] > 0) {
                $this->CI->db->where_not_in($primary_key, $param[$primary_key]);
            }
            $result = $this->CI->db->get($param['table']);

            if ($result->num_rows() > 0) {
                $this->CI->form_validation->set_message('multi_is_unique', $this->CI->lang->line('err_multi_is_unique'));
                $result->free_result();
                return false;
            }
            $result->free_result();
        }
        return true;
    }
}
