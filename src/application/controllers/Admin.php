<?php

ini_set('xdebug.var_display_max_depth', 256);
ini_set('xdebug.var_display_max_children', 256);
ini_set('xdebug.var_display_max_data', 1024);

class Admin extends CI_Controller {

    public function index() {
        var_dump("olaaaa Admin");
    }
    
    public function view_scan_logs(){
        $this->load->model('class/user_role');
        $this->load->model('class/user_status');
        $this->load->model('class/admin_model');
        $this->load->model('class/system_config');
        $GLOBALS['sistem_config'] = $this->system_config->load();
        if ($this->session->userdata('id') && $this->session->userdata('role_id')==user_role::ADMIN) {
            $param = $this->input->post();
            $datas['DATAS'] = $this->admin_model->get_dumbu_statistic($param);
            $data['section1'] = $this->load->view('responsive_views/admin/admin_header_painel', '', true);
            $data['section2'] = $this->load->view('responsive_views/admin/admin_body_painel_view_scan_logs', $datas, true);
            $data['section3'] = $this->load->view('responsive_views/admin/users_end_painel', '', true);
            $this->load->view('view_admin', $data);
        } else{
            echo "Não pode acessar a esse recurso, deve fazer login!!";
        }
    }
    
    public function scan_logs($user_id, $init_date, $end_date){
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
