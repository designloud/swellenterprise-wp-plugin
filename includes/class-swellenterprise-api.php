<?php

/**
 * Integrate with SWELLEnterprise API
 *
 * @link       http://joe.szalai.org
 * @since      1.0.0
 *
 * @package    Exopite_Portfolio
 * @subpackage Exopite_Portfolio/includes
 */
if( !class_exists('SWELLEnterprise_API') ) {
class SWELLEnterprise_API {

    protected $leads, $contacts, $clients;

    public function __construct($leads = null, $contacts = null, $clients = null, $options = null) {

        $this->options = $options;
        $this->leads = $leads;
        $this->clients = $clients;
        $this->contacts = $contacts;
    }

    private function authenticate_swell_request($method, $endpoint) {

        try {

            if (!isset($this->options) || empty($this->options))
                $this->options = get_exopite_sof_option('swellenterprise');
        } catch (HttpException $httpException) {

            $this->swell_admin_notice('danger', 'There is an error: Not Authorized');
        }

        $url = SWELLENTERPRISE_BASE_URL . 'api/v2/' . $endpoint;

        $wp_request_headers = array(
            'Content-type' => 'application/json',
            'Authorization' => 'Basic ' . base64_encode($this->options['username'] . ':' . $this->options['password'])
        );

        $response = wp_remote_request(
                $url,
                array(
                    'method' => $method,
                    'headers' => $wp_request_headers,
                )
        );

        if (wp_remote_retrieve_response_code($response) === 200) {
            $response_body = wp_remote_retrieve_body($response);
            return $response_body;
        }
    }

    private function register_swell_webhook($method, $event, $url) {

        ini_set("log_errors", 1);
        ini_set("error_log", plugin_dir_path(__DIR__) . "/swell.txt");
        error_log(json_encode(array($method, $event, $url)));

        try {

            if (!isset($this->options) || empty($this->options))
                $this->options = get_exopite_sof_option('swellenterprise');
        } catch (HttpException $httpException) {
            error_log('error-----');
            $this->swell_admin_notice('danger', 'There is an error: Not Authorized');
        }


        //No transient so call the API and create one
        $create_webhook = get_option('swell_' . $event . '_webhook');
        error_log('$create_webhook' . $create_webhook);
        if (!($create_webhook )) {
            $wp_request_headers = array(
                //'Content-type' => 'application/json',
                'Authorization' => 'Basic ' . base64_encode($this->options['username'] . ':' . $this->options['password'])
            );
            // Registering webhook.
            $endpoint = SWELLENTERPRISE_BASE_URL . 'api/subscribe';
            $response = wp_remote_request(
                    $endpoint,
                    array(
                        'method' => $method,
                        'headers' => $wp_request_headers,
                        'body' => array(
                            'url' => $url,
                            'event' => $event,
                            'tenant_id' => '3123'
                        ),
                    )
            );
            


            ini_set("log_errors", 1);
            ini_set("error_log", plugin_dir_path(__DIR__) . "/swell.txt");
            error_log('method: ' . $method . '<br />event: ' . $event . '<br />url: ' . $url);
            
            error_log(json_encode($response));

            if (wp_remote_retrieve_response_code($response) === 200) {
                $response_body = wp_remote_retrieve_body($response);

                $response_obj = json_decode($response_body);
                add_option('swell_' . $event . '_webhook', $response_obj->id);
                error_log($response_body);
                return $response_obj->id;
            } else {

                $this->swell_admin_notice('danger', 'There is an error: 1' . wp_remote_retrieve_response_message($response));
            }
        } else {
            return $create_webhook;
        }
        $this->closeConnection();
    }

    public function delete_swell_webhook($id, $option_name = NULL) {

        try {

            if (!isset($this->options) || empty($this->options))
                $this->options = get_exopite_sof_option('swellenterprise');
        } catch (HttpException $httpException) {

            $this->swell_admin_notice('danger', 'There is an error: Not Authorized');
        }
        if( empty($this->options['username']) || empty($this->options['password']) ){
            return ;
        }
        // Delete options 
        $result = delete_option($option_name);
        // Delete Webhooks from swell system.
        $endpoint = SWELLENTERPRISE_BASE_URL . 'api/subscribe/' . $id;
        $wp_request_headers = array(
            'Content-type' => 'application/json',
            'Authorization' => 'Basic ' . base64_encode($this->options['username'] . ':' . $this->options['password'])
        );
        $response = wp_remote_request(
                $endpoint,
                array(
                    'method' => 'DELETE',
                    'headers' => $wp_request_headers
                )
        );
        if (wp_remote_retrieve_response_code($response) === 200) {

            $response_body = wp_remote_retrieve_body($response);

            return $response_body;
            error_log(json_encode($response_body));
        } else {

            //$this->swell_admin_notice('danger', 'There is an error: ' . wp_remote_retrieve_response_message( $response ));
        }
        $this->closeConnection();
    }

    public function init_services() {

        try {

            if (!isset($this->options) || empty($this->options))
                $this->options = get_exopite_sof_option('swellenterprise');
        } catch (HttpException $httpException) {

            $this->swell_admin_notice('danger', 'There is an error: Not Authorized');
        }


        if (isset($this->options['contact_switcher']) && $this->options['contact_switcher'] === 'yes') {

            $contacts = $this->authenticate_swell_request('GET', 'contacts');

            if (!empty($contacts)) {

                $this->contacts = json_decode($contacts, true);


                $this->init_contact_service();
            } else {

                //$this->swell_admin_notice('danger', 'There is an error getting your contacts');
            }
        } else {

//			$this->swell_admin_notice('danger', 'Contacts is turned off in your settings.');
        }

        if (isset($this->options['lead_switcher']) && $this->options['lead_switcher'] === 'yes') {

            $leads = $this->authenticate_swell_request('GET', 'leads');

            if (!empty($leads)) {

                $this->leads = json_decode($leads, true);
                $this->init_lead_service();
            } else {

                $this->swell_admin_notice('danger', 'There is an error:' . $leads);
            }
        } else {
//			$this->swell_admin_notice('danger', 'Leads is turned off in your settings.');
        }

        if (isset($this->options['client_switcher']) && $this->options['client_switcher'] === 'yes') {

            $clients = $this->authenticate_swell_request('GET', 'clients');

            if (!empty($clients)) {
                $this->clients = json_decode($clients, true);

                $this->init_client_service();
            } else {
                $this->swell_admin_notice('danger', 'There is an error:' . $clients);
            }
        } else {
//			$this->swell_admin_notice('danger', 'Clients is turned off in your settings.');
        }

        $this->closeConnection();
    }

    //Register Webhooks
    public function registerWebhooks() {
        //** Leads ***/
        ini_set("log_errors", 1);
        ini_set("error_log", plugin_dir_path(__DIR__) . "/swell.txt");
        // Writing logs in file.
        $fp = fopen(dirname( __FILE__ )."/swell.txt", 'a');
        $this->options = get_exopite_sof_option('swellenterprise');
        if( !empty( $this->options['username']) && !empty($this->options['password'] ) ) {
            ini_set("log_errors", 1);
            ini_set("error_log", plugin_dir_path(__DIR__) . "/swell.txt");
            error_log(('Webhooks Registered'));
            // Writing logs in file.
            $fp = fopen(dirname( __FILE__ )."/swell.txt", 'a');
            fwrite($fp, json_encode('Webhooks Registered').PHP_EOL);
            fclose($fp);
             $lead_create_id = $this->register_swell_webhook('POST', 'lead.create', site_url() . '/wp-json/swell/v1/leads');

            $lead_update_id = $this->register_swell_webhook('POST', 'lead.update', site_url() . '/wp-json/swell/v1/leads');
            
            $lead_delete_id = $this->register_swell_webhook('POST', 'lead.delete', site_url() . '/wp-json/swell/v1/leads-delete');
            $lead_destroy_id = $this->register_swell_webhook('POST', 'lead.destroy', site_url() . '/wp-json/swell/v1/leads-delete'); 
            
            $client_create_id = $this->register_swell_webhook('POST', 'client.create', site_url() . '/wp-json/swell/v1/clients');
            
            $client_update_id = $this->register_swell_webhook('POST', 'client.update', site_url() . '/wp-json/swell/v1/clients');
            
            $client_delete_id = $this->register_swell_webhook('POST', 'client.delete', site_url() . '/wp-json/swell/v1/clients-delete');
            $client_destroy_id = $this->register_swell_webhook('POST', 'client.destroy', site_url() . '/wp-json/swell/v1/clients-delete');
            
            $contact_create_id = $this->register_swell_webhook('POST', 'contact.create', site_url() . '/wp-json/swell/v1/contacts');
            
            $contact_update_id = $this->register_swell_webhook('POST', 'contact.update', site_url() . '/wp-json/swell/v1/contacts');
            
            $contact_delete_id = $this->register_swell_webhook('POST', 'contact.delete', site_url() . '/wp-json/swell/v1/contacts-delete');
            
            $contact_destroy_id = $this->register_swell_webhook('POST', 'contact.destroy', site_url() . '/wp-json/swell/v1/contacts-delete');        
            
            $note_create_id = $this->register_swell_webhook('POST', 'note.create', site_url() . '/wp-json/swell/v1/notes');
            
            $note_update_id = $this->register_swell_webhook('POST', 'note.update', site_url() . '/wp-json/swell/v1/notes');
            
            $note_delete_id = $this->register_swell_webhook('POST', 'note.delete', site_url() . '/wp-json/swell/v1/notes-delete');
            
            $note_destroy_id = $this->register_swell_webhook('POST', 'note.destroy', site_url() . '/wp-json/swell/v1/notes-delete');

            /** Tasks * */
            $task_create_id = $this->register_swell_webhook('POST', 'task.create', site_url() . '/wp-json/swell/v1/tasks');
            
            $task_update_id = $this->register_swell_webhook('POST', 'task.update', site_url() . '/wp-json/swell/v1/tasks');
            
            $task_destroy_id = $this->register_swell_webhook('POST', 'task.destroy', site_url() . '/wp-json/swell/v1/tasks-delete');
            
            $task_delete_id = $this->register_swell_webhook('POST', 'task.delete', site_url() . '/wp-json/swell/v1/tasks-delete');    
            // die();

        }
    }

    public function init_contact_service() {
        //$this->register_swell_webhook('POST', 'ContactCreated', site_url().'/wp-json/swell/v1/leads' );
    }

    public function init_lead_service() {
        //$this->register_swell_webhook('POST', 'LeadCreated', site_url().'/wp-json/swell/v1/leads' );
    }

    public function init_client_service() {
        //$this->register_swell_webhook('POST', 'ClientCreated', site_url().'/wp-json/swell/v1/leads' );
    }

    public function swell_admin_notice($type = NULL, $message = NULL) {
        global $pagenow;

        if ($pagenow == 'options-general.php') {

            // if($type !== NULL && $message !== NULL){
            //      echo '<div class="notice notice-' . $type . ' is-dismissible">
            //          <p>' . $message . '</p>
            //          <button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button>
            //      </div>';
            //  }
        }
    }

    public function swellWebhooks($method, $endpoint, $url, $event) {
        //$clients = $this->register_swell_webhook('POST', 'subscribe', $event);
    }

    public function closeConnection() {
        return new WP_REST_Response('success', 200);
    }

}
}
