<?php

ini_set('xdebug.var_display_max_depth', 256);
ini_set('xdebug.var_display_max_children', 256);
ini_set('xdebug.var_display_max_data', 1024);

class Admin extends CI_Controller {

    public function index() {
        $this->load->view('admin_login_view');
    }
    
    public function admin_do_login() {
        $this->load->model('class/system_config');
        $GLOBALS['sistem_config'] = $this->system_config->load();
        $datas['SERVER_NAME'] = $GLOBALS['sistem_config']->SERVER_NAME;        
        $datas = $this->input->post();        
        $this->load->model('class/user_model');
        $this->load->model('class/user_status');
        $this->load->model('class/user_role');
        $query = 'SELECT * FROM users'.
                ' WHERE login="' . $datas['user_login'] . '" AND pass="' . md5($datas['user_pass']) .
                '" AND role_id=' . user_role::ADMIN . ' AND status_id=' . user_status::ACTIVE;
        $user = $this->user_model->execute_sql_query($query);
        if(count($user)){
            $this->user_model->set_sesion($user[0]['id'], $this->session, '');
            $result['role'] = 'ADMIN';
            $result['authenticated'] = true;
            echo json_encode($result);
        } else{
            $result['resource'] = 'index#lnk_sign_in_now';
            $result['message'] = 'Credenciais incorretas';
            $result['cause'] = 'signin_required';
            $result['authenticated'] = false;
            echo json_encode($result);
        }
    }
    
    public function view_scan_logs(){
        $this->load->model('class/user_role');
        $this->load->model('class/user_status');
        $this->load->model('class/admin_model');
        $this->load->model('class/system_config');
        $GLOBALS['sistem_config'] = $this->system_config->load();
        //if ($this->session->userdata('id') && $this->session->userdata('role_id')==user_role::ADMIN) {
            $param = $this->input->post();
            $user_id = (isset($param['user_id']))?$param['user_id']:NULL;
            $init_date = (isset($param['date_from']))?$param['date_from']:NULL;
            $end_date = (isset($param['date_to']))?$param['date_to']:NULL;
            if($user_id)
                $datas['DATAS'] = $this->scan_logs($param['user_id'], $init_date, $end_date);
            else
                $datas=NULL;
            $data['section1'] = $this->load->view('responsive_views/admin/admin_header_painel', '', true);
            $data['section2'] = $this->load->view('responsive_views/admin/admin_body_painel_view_scan_logs', $datas, true);
            $data['section3'] = $this->load->view('responsive_views/admin/users_end_painel', '', true);
            $this->load->view('view_admin', $data);
        //} else{
        //    $this->load->view('admin_login_view');
        //}
    }
    
    public function scan_logs($user_id, $init_date, $end_date){
        if($init_date && $end_date){
            $a= explode('/', $init_date);  $init_date=$a[2].$a[1].$a[0];
            $a= explode('/', $end_date);   $end_date=$a[2].$a[1].$a[0];
        }else{
            
        }
        //- para cada dia
            //- imprimir dia
            //- WORKER: escanear el de este dia e imprimir salida
            //- separador
            //- para cada log de cada worker
                //- imprimir nombre del log
                //- LOG: escanear el log e imprimir salida
                //- separador
            //- TOTAL_UNFOLLOW
            //- separador
            //- AUTOLIKE                    
            //- separador
    }

}
