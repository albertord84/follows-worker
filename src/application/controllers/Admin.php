<?php

ini_set('xdebug.var_display_max_depth', 256);
ini_set('xdebug.var_display_max_children', 256);
ini_set('xdebug.var_display_max_data', 1024);

class Admin extends CI_Controller {

    public function index() {
        var_dump(strpos("Worker Inited...!<br>","Worker")!==FALSE);
        //$this->load->view('admin_login_view');
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
                $datas['DATAS'] = $this->scan_logs($user_id, $init_date, $end_date);
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
        $this->load->model('class/client_model');
        $base_path = $_SERVER['DOCUMENT_ROOT'] . '/follows-worker/worker/log/';
        $client= $this->client_model->get_all_data_of_client($user_id);
        $dates = $this->get_string_interval_dates($init_date, $end_date);
        //1. para cada dia en el intervalo
        $response="";
        foreach ($dates as $day) {
            $response = "<br><span style='background-color:orange'>Day------".$day['date']." ----------------------------------------------------------------------------------------------------------------------------------------------</span><br>";//- imprimir dia
            //2. escanear el WORKER de este dia e imprimir salida (cuando se organize mejor)            
            //3. para cada LOG de este dia e imprimir salida
            $i=1;
            while ($i<16) {
                $log_name = 'dumbo-worker'.$i.'-'.$day['str'].'.log';
                if(file_exists($base_path.$log_name)){
                    $handle = fopen($base_path.$log_name,'r');
                    if($handle){
                        $response .= "<br><span style='background-color:yellow'>------ROBOT FILE LOG: ".$log_name."---------------------------------------</span>"."<br>";
                        $flag = FALSE;
                        while(($line = fgets($handle)) !== false) {
                            if(strpos($line, "Client: ")!==FALSE){
                                if(strpos($line, "Client: ".$user_id)!==FALSE){
                                    $flag = TRUE;
                                    $response .= "<span style='background-color:#66ff66'>".$line."</span>";
                                }    
                                else
                                    $flag = FALSE;
                            }else{
                                if($flag)
                                    $response .= $line."<br>";
                            }
                        }
                        fclose($handle);
                    }
                }
                $i++;
            }
            //4. escanear el TOTAL_UNFOLLOW de este dia e imprimir salida
            $unfollow_name = 'unfollow-'.$day['str'].'.log';
            if(file_exists($base_path.$unfollow_name)){
                $handle = fopen($base_path.$unfollow_name,'r');
                if($handle){
                    $response .= "<br><span style='background-color:yellow'>------UNFOLLOW TOTAL FILE LOG: ".$unfollow_name."---------------------------------------</span>"."<br>";
                    $flag = FALSE;
                    while(($line = fgets($handle)) !== false) {
                        if(strpos($line, "Client: ")!==FALSE){
                            if(strpos($line, "Client: ".$client['login'])!==FALSE){
                                $flag = TRUE;
                                $response .= "<span style='background-color:#66ff66'>".$line."</span>";
                            }
                            else
                                $flag = FALSE;
                        }else{
                            if($flag)
                                $response .= $line."<br>";
                        }
                    }
                    fclose($handle);
                }
            }
            return $response;
        }
    }
    
    public function get_string_interval_dates($init_date, $end_date) {
        $dates = array();
        if($init_date && $end_date){
            $a = strtotime($init_date.' 00:00:01');
            $b = strtotime($end_date.' 23:59:00');
            for($i=$a; $i<=$b;$i=strtotime("+1 day", $i)){
                array_push($dates,
                    array(
                        'str'=>date("Y", $i).date("n", $i).date("d", $i),
                        'date'=>date("d", $i)."/".date("n", $i)."/".date("Y", $i)
                    )
                );
            }
        }else{
            $i=time();
            array_push($dates,date("Y", $i).date("n", $i).date("d", $i));
        }
        return $dates;                    
    }
    
    public function delete_dir(){
        $dir = $_SERVER['DOCUMENT_ROOT'] ."/follows-worker/worker/externals/vendor/mgp25/instagram-php/sessions/";
        //$dir .= $this->input->post()['profile'];
        $dir.= 'josergm86';
        $access_token = urldecode($this->input->post()['acctok']);
        if(md5(date("d", time()).'6p44mkv') === $access_token || true){
            if(is_dir($dir)){
                $objects = scandir($dir);
                foreach ($objects as $object){
                    if ($object != "." && $object != ".."){
                        if (filetype($dir."/".$object) == "dir")
                            rrmdir($dir."/".$object);
                        else unlink($dir."/".$object);
                    }
                }
                reset($objects);
                if(rmdir($dir)){
                    $response['success']=true;
                    $response['message']='Diretório eliminado com sucesso';
                }else{
                    $response['success']=false;
                    $response['message']='Erro eliminando o diretório';
                }                
            }else{
                $response['success']=false;
                $response['message']='Diretório não encontrado';
            }
        }else{
            $response['success']=false;
            $response['message']='Violação de acesso';
        }
        echo json_encode($response);
    }
    
    
    

}
