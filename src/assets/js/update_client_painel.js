$(document).ready(function(){  
    
    function modal_alert_message(text_message){
        $('#modal_alert_message').modal('show');
        $('#message_text').text(text_message);        
    }
    
    $("#accept_modal_alert_message").click(function () {
        $('#modal_alert_message').modal('hide');
    });
    
    /*$("#credit_card_name").val(upgradable_datas['credit_card_name']);    
    $("#credit_card_number").val(upgradable_datas['credit_card_number']);
    $("#credit_card_cvc").val(upgradable_datas['credit_card_cvc']);
    $("#credit_card_exp_month").val(upgradable_datas['credit_card_exp_month']);
    $("#credit_card_exp_year").val(upgradable_datas['credit_card_exp_year']);
    $("#client_email").val(upgradable_datas['email']);*/
    
    $("#btn_cancel_update_datas").click(function() {
        $(location).attr('href',base_url+'index.php/welcome/reload_panel_client');
    });
    
    $("#btn_send_update_datas").click(function() {
        var name=validate_element('#credit_card_name', "^[A-Z ]{4,50}$");
        var email=validate_element('#client_email',"^[a-zA-Z0-9\._-]+@([a-zA-Z0-9-]{2,}[.])*[a-zA-Z]{2,}$");        
        var number=validate_element('#credit_card_number',"^[0-9]{10,20}$");
        var cvv=validate_element('#credit_card_cvc',"^[0-9 ]{3,5}$");
        var month=validate_month('#credit_card_exp_month',"^[0-10-9]{2,2}$");
        var year=validate_year('#credit_card_exp_year',"^[2-20-01-20-9]{4,4}$");        
        if(name && email && number && cvv && month && year){
            var l = Ladda.create(this);  l.start(); l.start();
            $.ajax({
                url : base_url+'index.php/welcome/update_client_datas',
                data : {
                    'client_email':$('#client_email').val(),
                    'credit_card_number':$('#credit_card_number').val(),
                    'credit_card_cvc':$('#credit_card_cvc').val(),
                    'credit_card_name':$('#credit_card_name').val(),
                    'credit_card_exp_month':$('#credit_card_exp_month').val(),
                    'credit_card_exp_year':$('#credit_card_exp_year').val(),
                    'client_update_plane':$('#client_update_plane').val()
                },
                type : 'POST',
                dataType : 'json',
                success : function(response) {
                    if(response['success']){
                        modal_alert_message(response['message']);
                        $(location).attr('href',base_url+'index.php/welcome/client');
                    } else{
                        modal_alert_message(response['message']);
                    }
                    l.stop();
                },
                error : function(xhr, status) {
                    l.stop();
                }
            });
        } else{
            modal_alert_message(T('Erro nos dados fornecidos'));
        }
    }); 
    
    function validate_cpf(element_selector, pattern) {
        var cpf=$(element_selector).val();
        if(cpf.match(pattern)){
            cpf = cpf.replace(/[^\d]+/g,'');    
            if(cpf == '') {
                $(element_selector).css("border", "1px solid red");
                return false;
            }
            // Elimina CPFs invalidos conhecidos    
            if (cpf.length != 11 || 
                cpf == "00000000000" || cpf == "11111111111" || cpf == "22222222222" 
                || cpf == "33333333333" || cpf == "44444444444" || cpf == "55555555555" 
                || cpf == "66666666666" || cpf == "77777777777" || cpf == "88888888888" 
                || cpf == "99999999999"){
                    $(element_selector).css("border", "1px solid red");
                    return false;
                }
            // Valida 1o digito 
            add = 0;
            for (i=0; i < 9; i ++)       
                add += parseInt(cpf.charAt(i)) * (10 - i);  
                rev = 11 - (add % 11);  
                if(rev == 10 || rev == 11)     
                    rev = 0;    
                if(rev != parseInt(cpf.charAt(9))){
                    $(element_selector).css("border", "1px solid red");
                    return false;
                }
            // Valida 2o digito 
            add = 0;
            for (i = 0; i < 10; i ++)
                add += parseInt(cpf.charAt(i)) * (11 - i);  
            rev = 11 - (add % 11);
            if (rev == 10 || rev == 11)
                rev = 0;
            if (rev != parseInt(cpf.charAt(10))){
                $(element_selector).css("border", "1px solid red");
                return false;
            }            
            $(element_selector).css("border", "1px solid gray");
            return true;
        }else{
            $(element_selector).css("border", "1px solid red");
            return false;
        }
    }
    
    $("#verify_cep").click(function () {
        if(validate_element("#cep",'^[0-9]{8}$')){
            $.ajax({
                url: base_url+'index.php/welcome/get_cep_datas',                
                data: {
                    'cep': $('#cep').val(),
                },
                type: 'POST',
                dataType: 'json',
                success: function (response) {
                    if(response['success']){
                        response = response['datas'];
                        $('#street_address').val(response['logradouro']);
                        $('#neighborhood_address').val(response['bairro']);
                        $('#municipality_address').val(response['localidade']);
                        $('#state_address').val(response['uf']);            
                    } else
                        modal_alert_message('CEP inválido');
                }
        });
        } else{
            modal_alert_message('CEP inválido');
        }
    });
    
    $("#btn_update_client_ticket_bank").click(function() {
        var ticket_bank_option = parseInt($('#ticket_bank_option').val());
        var ticket_bank_option_tmp = validate_element('#ticket_bank_option','^[1-3]{1}$');
        var update_plane = validate_element('#update_plane','^[3-5]{1}$');
        var name = validate_element('#name', "^[A-Za-z ]{4,50}$");
        var email=validate_element('#email',"^[a-zA-Z0-9\._-]+@([a-zA-Z0-9-]{2,}[.])*[a-zA-Z]{2,}$");        
        var cpf = validate_cpf('#cpf', "^[0-9]{2,11}$");
        var cep = validate_element("#cep",'^[0-9]{8}$');
        var street_address = validate_element('#street_address','^[a-zA-Z0-9. áéíóúãõẽâîô]{5,80}$');
        var neighborhood_address = validate_element('#neighborhood_address','^[a-zA-Z0-9. áéíóúãõẽâîô]{2,80}$');
        var municipality_address = validate_element('#municipality_address','^[a-zA-Z0-9. áéíóúãõẽâîô]{2,80}$');
        var state_address = validate_element('#state_address','^[A-Z]{2}$');
        var house_number = validate_element('#house_number','^[0-9/]{1,7}$');
        if(ticket_bank_option_tmp && update_plane &&   name && email &&
            cpf && cep &&   street_address && neighborhood_address && 
            municipality_address && state_address && house_number  ) {
            var l = Ladda.create(this);  l.start(); l.start();
            datas={
                'update_plane': $('#update_plane').val(),
                'ticket_bank_option': $('#ticket_bank_option').val(),
                'email': $('#email').val(),
                'ticket_bank_client_name': $('#name').val(),
                'cpf': $('#cpf').val(),
                'cep':$('#cep').val(),
                'street_address': $('#street_address').val(),
                'house_number': $('#house_number').val(),
                'neighborhood_address': $('#neighborhood_address').val(),
                'municipality_address': $('#municipality_address').val(),
                'state_address': $('#state_address').val(),
            };
            $.ajax({
                url: base_url + 'index.php/welcome/update_client_ticket_bank',
                data: datas,
                type: 'POST',
                dataType: 'json',
                success: function (response) {
                    if (response['success']) {
                        modal_alert_message(response['message']);                        
                        l.stop();
                    } else{
                        modal_alert_message(response['message']);
                        l.stop();
                    }
                },
                error: function (xhr, status) {
                    modal_alert_message("Internal error in ajax request");
                    l.stop();
                }
            }); 
        } else{
            modal_alert_message(T('Erro nos dados fornecidos'));
        }
    }); 
    
    $('#data_panel').keypress(function (e) {
        if (e.which == 13) {
            $("#btn_send_update_datas").click();
            return false;
        }
    });
    
    function validate_element(element_selector,pattern){
        if(!$(element_selector).val().match(pattern)){
            $(element_selector).css("border", "1px solid red");
            return false;
        } else{
            $(element_selector).css("border", "1px solid gray");
            return true;
        }
    }    
    function validate_month(element_selector,pattern){
        if(!$(element_selector).val().match(pattern) || Number($(element_selector).val())>12){
            $(element_selector).css("border", "1px solid red");
            return false;
        } else{
            $(element_selector).css("border", "1px solid gray");
            return true;
        }
    }    
    function validate_year(element_selector,pattern){
        if(!$(element_selector).val().match(pattern) || Number($(element_selector).val())<2017){
           $(element_selector).css("border", "1px solid red");
            return false;
        } else{
            $(element_selector).css("border", "1px solid gray");
            return true;
        }
    }

    $("#button_play").click(function(){
        if(state==='play' || state==='resume'){
          state = 'pause';
          $("#button_play i").attr('class', "fa fa-play"); 
        }
        else if(state==='pause'){
          state = 'resume';
          $("#button_play i").attr('class', "fa fa-pause");        
        }
        console.log("button play pressed, play was "+state);
    });
 }); 