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
if( !class_exists('SWELLEnterprise_API_Services') ) {
class SWELLEnterprise_API_Services {

    protected $leads, $contacts, $clients;

    public function __construct($leads = null, $contacts = null, $clients = null, $options = null, $notes = null, $tasks = null) {

        $this->options = $options;
        $this->leads = $leads;
        $this->clients = $clients;
        $this->contacts = $contacts;
        $this->notes = $notes;
        $this->tasks = $tasks;
    }

    public function sync_alldata(){
        $swell_syen_all_items_on_activiation = get_option('swell_syen_all_items_on_activiation');
        if( !$swell_syen_all_items_on_activiation ){
            $this->options = get_exopite_sof_option('swellenterprise');
            if( !empty($this->options['username']) && !empty($this->options['password']) ){
                $this->swell_get_data(1);
            }
            
        }
    }
    
    public function check_if_post_exists($post_type, $key = 'hash_id', $value) {
        $args = array('post_type' => $post_type, 'meta_key' => $key, 'meta_value' => $value);
        $posts = get_posts($args);
        if (empty($posts)) {
            return 0;
        } else {
            return $posts[0]->ID;
        }
    }


    public function swell_get_data($process_all = 0) {
        if ($process_all) {
            $this->sync_clients();
            $this->sync_contacts();
            $this->sync_notes();
            $this->sync_tasks();
            $this->sync_leads();
            update_option('swell_syen_all_items_on_activiation', 1);
        }
        if (isset($_POST['syncData'])) {
            $sync = sanitize_text_field( $_POST['syncData'] );
            if ($sync === 'Clients') {
                $this->sync_clients();
            } else if ($sync === 'Contacts') {
                $this->sync_contacts();
            } else if ($sync === 'Notes') {
                $this->sync_notes();
            } else if ($sync === 'Tasks') {
                $this->sync_tasks();
            } else if ($sync === 'Forms') {
                $this->sync_forms();
            } else {
                $this->sync_leads();
            }
        }
    }

    private function authenticate_swell_request($method, $endpoint) {

        try {

            if (!isset($this->options) || empty($this->options))
                $this->options = get_exopite_sof_option('swellenterprise');
        } catch (HttpException $httpException) {

            $this->swell_admin_notice('danger', 'There is an error: Not Authorized');
        }

        if( empty($this->options['username']) || empty($this->options['password']) ){
            return ;
        }

        $url = SWELLENTERPRISE_BASE_URL . 'api/v2/' . $endpoint;

        //No transient so call the API and create one

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

        try {

            if (!isset($this->options) || empty($this->options))
                $this->options = get_exopite_sof_option('swellenterprise');
        } catch (HttpException $httpException) {

            $this->swell_admin_notice('danger', 'There is an error: Not Authorized');
        }

        $endpoint = SWELLENTERPRISE_BASE_URL . 'api/subscribe';

        //No transient so call the API and create one

        $wp_request_headers = array(
            'Content-type' => 'application/json',
            'Authorization' => 'Basic ' . base64_encode($this->options['username'] . ':' . $this->options['password'])
        );



        $response = wp_remote_request(
                $endpoint,
                array(
                    'method' => $method,
                    'headers' => $wp_request_headers,
                    'body' => array(
                        'url' => $url,
                        'event' => $event
                    ),
                )
        );

        if (wp_remote_retrieve_response_code($response) === 200) {
            $response_body = wp_remote_retrieve_body($response);
            return $response_body;
        } else {

            //$this->swell_admin_notice('danger', 'There is an error: ' . wp_remote_retrieve_response_message( $response ));
        }
    }

    //*** Get Contacts ***/
    public function get_contacts() {

        try {

            if (!isset($this->options) || empty($this->options))
                $this->options = get_exopite_sof_option('swellenterprise');
        } catch (HttpException $httpException) {

            $this->swell_admin_notice('danger', 'There is an error: Not Authorized');
        }
        if ($this->options['contact_switcher'] === 'yes') {

            $contacts = $this->authenticate_swell_request('GET', 'contacts');


            if (!empty($contacts)) {

                $this->contacts = json_decode($contacts, true);
            } else {

                $this->swell_admin_notice('danger', 'There is an error getting your contacts');
            }
        } else {

            $this->swell_admin_notice('danger', 'Contacts is turned off in your settings.');
        }
    }

    //*** Get Notes ***/
    public function get_notes() {
        try {

            if (!isset($this->options) || empty($this->options))
                $this->options = get_exopite_sof_option('swellenterprise');
        } catch (HttpException $httpException) {

            $this->swell_admin_notice('danger', 'There is an error: Not Authorized');
        }
        if ($this->options['note_switcher'] === 'yes') {
            $notes = $this->authenticate_swell_request('GET', 'notes');
            if (!empty($notes)) {

                $this->notes = json_decode($notes, true);
            } else {

                $this->swell_admin_notice('danger', 'There is an error getting your contacts');
            }
        } else {

            $this->swell_admin_notice('danger', 'Contacts is turned off in your settings.');
        }
    }

    /** Get Tasks * */
    public function get_tasks() {
        try {

            if (!isset($this->options) || empty($this->options))
                $this->options = get_exopite_sof_option('swellenterprise');
        } catch (HttpException $httpException) {

            $this->swell_admin_notice('danger', 'There is an error: Not Authorized');
        }
        if ($this->options['task_switcher'] === 'yes') {
            $tasks = $this->authenticate_swell_request('GET', 'tasks');
            if (!empty($tasks)) {

                $this->tasks = json_decode($tasks, true);
            } else {

                $this->swell_admin_notice('danger', 'There is an error getting your contacts');
            }
        } else {

            $this->swell_admin_notice('danger', 'Contacts is turned off in your settings.');
        }
    }

    public function create_contact($contact = NULL, $post_id = 0, $ignore_wp_insert = 0) {

        if( !$ignore_wp_insert ) {
            $args = array(
                'ID' => $post_id,
                'post_status' => 'publish',
                'post_type' => 'contact',
                'post_title' => (isset( $contact['first_name'] )? sanitize_text_field( $contact['first_name'] ) : '') . ' ' . (isset( $contact['last_name'] )? sanitize_text_field( $contact['last_name'] ): '') . ' - ' . (isset( $contact['organization'] )? sanitize_text_field( $contact['organization'] ): ''),
                'meta_input' => array(
                    'first_name' => isset( $contact['first_name'] )? sanitize_text_field( $contact['first_name'] ):'',
                    'last_name' => isset($contact['last_name']) ? sanitize_text_field( $contact['last_name'] ) : '',
                    'organization' => isset($contact['organization']) ? sanitize_text_field( $contact['organization'] ) : '',
                    'email' => isset($contact['email'])? sanitize_email( $contact['email'] ):'',
                    'phone_number' => isset($contact['phone_number'])? sanitize_text_field( $contact['phone_number'] ):'',
                    'address' => isset($contact['address'])? sanitize_text_field( $contact['address'] ):'',
                    'city' => isset($contact['city'])? sanitize_text_field($contact['city'] ):'',
                    'state' => isset($contact['state'])? sanitize_text_field( $contact['state'] ):'',
                    'zip' => isset($contact['zip'])? sanitize_text_field($contact['zip'] ):'',
                    'hash_id' => isset($contact['id'])? sanitize_text_field( $contact['id']):'',
                    'custom_fields' => isset($contact['custom_fields']) ? $contact['custom_fields'] : '',
                /* 'nick_name' => $contact['custom_fields']['Nickname_6'],
                  'date_of_birth' => $contact['custom_fields']['DOB_7'], */
                ),
            );
            $post = wp_insert_post($args);
        }
        
        update_post_meta($post_id, 'first_name', isset($contact['first_name']) ? sanitize_text_field( $contact['first_name'] ) : '');
        update_post_meta($post_id, 'last_name', isset($contact['last_name']) ? sanitize_text_field( $contact['last_name'] ) : '');
        update_post_meta($post_id, 'organization', isset($contact['organization']) ? sanitize_text_field( $contact['organization'] ) : '');
        update_post_meta($post_id, 'email', isset($contact['email']) ? sanitize_email( $contact['email'] ) : '');
        update_post_meta($post_id, 'phone_number', isset($contact['phone_number']) ? sanitize_text_field( $contact['phone_number'] ) : '');
        update_post_meta($post_id, 'address', isset($contact['address']) ? sanitize_textarea_field( $contact['address'] ) : '');
        update_post_meta($post_id, 'city', isset($contact['city']) ? sanitize_text_field( $contact['city'] ) : '');
        update_post_meta($post_id, 'state', isset($contact['state']) ? sanitize_text_field( $contact['state'] ) : '');
        update_post_meta($post_id, 'zip', isset($contact['zip']) ? sanitize_text_field( $contact['zip'] ) : '');
        update_post_meta($post_id, 'hash_id', isset($contact['id']) ? sanitize_text_field( $contact['id'] ) : '');
        update_post_meta( $post_id, 'custom_fields', isset($contact['custom_fields']) ? $contact['custom_fields']: '' );
      
    }

  
    /** Sync Contacts * */
    public function sync_contacts() {

        $this->get_contacts();

        if (isset($this->contacts)) {
            foreach ($this->contacts as $contact) {
                if (isset($contact['id'])) { //check the key exists in the array
                    if ($this->check_if_post_exists('contact', 'hash_id', $contact['id']) === 0) {
                        $this->create_contact($contact);
                    } else {
                        $id = $this->check_if_post_exists('contact', 'hash_id', $contact['id']);
                        $this->create_contact($contact, $id);
                    }
                }
            }
        }
    }

    /** Sync Notes * */
    public function sync_notes() {
        $this->get_notes();
        if (isset($this->notes)) {
            foreach ($this->notes as $note) {
                if (isset($note['id'])) { //check the key exists in the array
                    if ($this->check_if_post_exists('contact', 'hash_id', $note['id']) === 0) {
                        $this->create_note($note);
                    } else {
                        $id = $this->check_if_post_exists('contact', 'hash_id', $note['id']);
                        $this->create_note($note, $id);
                    }
                }
            }
        }
    }

    public function sync_forms(){

    }

    /** Sync Notes * */
    public function sync_tasks() {
        $this->get_tasks();
        if (isset($this->tasks)) {
            foreach ($this->tasks as $task) {
                if (isset($task['id'])) { //check the key exists in the array
                    if ($this->check_if_post_exists('contact', 'hash_id', $task['id']) === 0) {
                        $this->create_task($task);
                    } else {
                        $id = $this->check_if_post_exists('contact', 'hash_id', $task['id']);
                        $this->create_task($task, $id);
                    }
                }
            }
        }
    }

    public function create_client($client = NULL, $post_id = 0, $ignore_wp_insert = 0 ) {

        if( !$ignore_wp_insert ) {
            $args = array(
                'ID' => $post_id,
                'post_status' => 'publish',
                'post_type' => 'client',
                'post_title' => (isset( $client['first_name'] )? sanitize_text_field( $client['first_name'] ) : '') . ' ' . (isset( $client['last_name'] )? sanitize_text_field( $client['last_name'] ): '') . ' - ' . (isset( $client['organization'] )? sanitize_text_field( $client['organization'] ): ''),
                'meta_input' => array(
                    'first_name' => isset( $client['first_name'] )? sanitize_text_field ( $client['first_name'] ):'',
                    'last_name' => isset( $client['last_name'] )? sanitize_text_field( $client['last_name'] ):'',
                    'organization' => isset( $client['organization'] )? sanitize_text_field( $client['organization'] ):'',
                    'email' => isset( $client['email'] )? sanitize_email( $client['email'] ):'',
                    'phone' => isset( $client['phone_number'] )? sanitize_text_field( $client['phone_number'] ):'',
                    'address' => isset( $client['address'] )? sanitize_textarea_field( $client['address'] ):'',
                    'city' => isset( $client['city'] )? sanitize_text_field( $client['city'] ):'',
                    'state' => isset( $client['state'] )? sanitize_text_field( $client['state'] ):'',
                    'zip' => isset( $client['zip'] )? sanitize_text_field( $client['zip'] ):'',
                    'hash_id' => isset( $client['id'] )? sanitize_text_field( $client['id'] ):'',
                ),
            );
            $post_id = wp_insert_post($args);
            $hash_id = get_post_meta($post_id, 'hash_id', true);
            $hash_id = intval($hash_id);
        }
        if( isset ($client['custom_fields'] )){


        $custom_fields = $client['custom_fields'];
        // creating/ updating custom fields.
        //$fields = json_encode($custom_fields);
        update_post_meta( $post_id, 'custom_fields', $custom_fields );
        }
        
        //will execute these statements in case of updation
         //if( $hash_id === $client['id'] ) {
          update_post_meta( $post_id, 'first_name', isset( $client['first_name'] )? sanitize_text_field( $client['first_name'] ) : '' );
          update_post_meta( $post_id, 'last_name', isset( $client['last_name'] )? sanitize_text_field( $client['last_name'] ) : '' );
          update_post_meta( $post_id, 'organization', isset( $client['organization'] )? sanitize_text_field( $client['organization'] ) : '' );
          update_post_meta( $post_id, 'email', isset( $client['email'] )? sanitize_text_field( $client['email'] ) : '' );
          update_post_meta( $post_id, 'phone_number', isset( $client['phone_number'] )? sanitize_text_field( $client['phone_number'] ) : '' );
          update_post_meta( $post_id, 'address', isset( $client['address'] )? sanitize_text_field( $client['address'] ) : '' );
          update_post_meta( $post_id, 'city', isset( $client['city'] )? sanitize_text_field( $client['city'] ) : '' );
          update_post_meta( $post_id, 'state', isset( $client['state'] )? sanitize_text_field( $client['state'] ) : '' );
          update_post_meta( $post_id, 'zip', isset( $client['zip'] )? sanitize_text_field( $client['zip'] ) : '' );
          update_post_meta( $post_id, 'hash_id', isset( $client['id'] )? sanitize_text_field( $client['id'] ) : '' );
         
    }

    public function create_lead($lead = NULL, $post_id = 0, $ignore_wp_insert = 0 ) {
          if( !$ignore_wp_insert ){
            if( !empty($lead['id']) && !( $post_id  )) {
                $post_id = $this->get_post_id_by_meta_key_and_value( 'hash_id', $lead['id'] );
            }
            $args = array(
                'ID' => $post_id,
                'post_status' => 'publish',
                'post_type' => 'lead',
                'post_title' => (isset( $lead['first_name'] )? sanitize_text_field( $lead['first_name'] ) : '') . ' ' . (isset( $lead['last_name'] )? sanitize_text_field( $lead['last_name'] ): '') . ' - ' . (isset( $lead['organization'] )? sanitize_text_field( $lead['organization'] ): ''),
                'meta_input' => array(
                    'first_name' => isset( $lead['first_name'] )? sanitize_text_field( $lead['first_name'] ) : '',
                    'last_name' => isset( $lead['last_name'] ) ? sanitize_text_field( $lead['last_name'] ) : '',
                    'email' => isset($lead['email']) ? sanitize_email( $lead['email'] ): '',
                    'phone' => isset($lead['phone_number']) ? sanitize_text_field( $lead['phone_number'] ): '',
                    'address' => isset($lead['address']) ? sanitize_textarea_field( $lead['address'] ): '',
                    'city' => isset($lead['city']) ? sanitize_text_field( $lead['city'] ): '',
                    'state' => isset($lead['state']) ? sanitize_text_field( $lead['state'] ): '',
                    'zip' => isset($lead['zip']) ? sanitize_text_field( $lead['zip'] ): '',
                    'organization' => isset($lead['organization']) ? sanitize_text_field( $lead['organization'] ): '',
                    'hash_id' => isset($lead['id']) ? sanitize_text_field( $lead['id'] ): '',
                ),
            );
            $post_id = wp_insert_post($args);
        }
            // $ignore_wp_insert = 1;        
        if ( isset( $lead['status'] ) ) {
            update_post_meta( $post_id, 'swell_lead_status', $lead['status']);
        }
        if( isset($lead['custom_fields'])){
            $custom_fields = $lead['custom_fields'];
            update_post_meta($post_id, 'custom_fields', $custom_fields);    
        }
        
        // code for updating post.

        update_post_meta($post_id, 'first_name', isset( $lead['first_name'] ) ? sanitize_text_field( $lead['first_name'] ) : '');
        update_post_meta($post_id, 'last_name', isset( $lead['last_name'] ) ? sanitize_text_field( $lead['last_name'] ) : '');
        update_post_meta($post_id, 'organization', isset( $lead['organization'] ) ? sanitize_text_field( $lead['organization'] ) : '');
        update_post_meta($post_id, 'email', isset( $lead['email'] ) ? sanitize_email( $lead['email'] ) : '');
        update_post_meta($post_id, 'phone_number', isset( $lead['phone_number'] ) ? sanitize_text_field( $lead['phone_number'] ) : '');
        update_post_meta($post_id, 'address', isset( $lead['address'] ) ? sanitize_text_field( $lead['address'] ) : '');
        update_post_meta($post_id, 'city', isset( $lead['city'] ) ? sanitize_text_field( $lead['city'] ) : '');
        update_post_meta($post_id, 'state', isset( $lead['state'] ) ? sanitize_text_field( $lead['state'] ) : '');
        update_post_meta($post_id, 'zip', isset( $lead['zip'] ) ? sanitize_text_field( $lead['zip'] ) : '');
        update_post_meta($post_id, 'hash_id', isset( $lead['id'] ) ? sanitize_text_field( $lead['id'] ) : '');
    }

    // Get associated post_id's of notes and tasks by key and value.
    public function get_associated_post_id_by_meta_key_and_value($key, $value) {
        global $wpdb;
        $meta = $wpdb->get_results("SELECT post_id FROM " . $wpdb->prefix . "postmeta WHERE meta_key='" . $key . "' AND meta_value='" . $value . "'");
        if (is_array($meta) && !empty($meta)) {
            return $meta;
        }
    }

    // Get id of post_id by key and value.
    public function get_post_id_by_meta_key_and_value($key, $value) {
        global $wpdb;
        $meta = $wpdb->get_results("SELECT post_id FROM " . $wpdb->prefix . "postmeta WHERE meta_key='" . $key . "' AND meta_value='" . $value . "'", ARRAY_A);
        if (is_array($meta) && !empty($meta) ) {
            return $meta['0']['post_id'];
        }
    }

    public function get_deletion_id($key, $note_id) {
        global $wpdb;
        $meta = $wpdb->get_results("SELECT post_id FROM " . $wpdb->prefix . "postmeta WHERE meta_key='" . $key . "' AND meta_value='" . $note_id . "'");
        if (is_array($meta) && !empty($meta) && isset($meta[0])) {
            $meta = $meta[0];
            return $meta;
        } else {
            return false;
        }
    }

    /*     * * Delete Lead ** */

    public function delete_lead($lead = NULL) {
        // Get lead id
        $lead_id = $this->get_post_id_by_meta_key_and_value('hash_id', $lead['id']);
        if ($lead_id) {
            // Get associated data of lead and delete associated posts.
            $deleted_ids = $this->get_associated_post_id_by_meta_key_and_value('lead', $lead['id']);
            // delete associated notes and tasks of lead.
            foreach ($deleted_ids as $key => $value) {
                $deleted_lead = wp_delete_post($value->post_id);
            }
            // delete Lead.
            $deleted_lead = wp_delete_post($lead_id);
        }
    }

    /*     * * Delete Client ** */

    public function delete_client($client = NULL) {
        // Get client id
        $client_id = $this->get_post_id_by_meta_key_and_value('hash_id', $client['id']);
        if ($client_id) {
            // Get associated data of lead and delete associated posts.
            $deleted_ids = $this->get_associated_post_id_by_meta_key_and_value('client', $client['id']);
            // delete associated notes and tasks of client.
            if( is_array( $deleted_ids ) ) {
                foreach ($deleted_ids as $key => $value) {
                    $deleted_lead = wp_delete_post($value->post_id);
                }    
            }
            // delete Lead.
            $deleted_lead = wp_delete_post($client_id);
        }
    }

    /*     * * Delete Contact ** */

    public function delete_contact($contact = NULL) {
        // Get client id
        $contact_id = $this->get_post_id_by_meta_key_and_value('hash_id', $contact['id']);
        if ($contact_id) {
            // Get associated data of lead and delete associated posts.
            $deleted_ids = $this->get_associated_post_id_by_meta_key_and_value('contact', $contact['id']);
            // delete associated notes and tasks of client.
            foreach ($deleted_ids as $key => $value) {
                $deleted_lead = wp_delete_post($value->post_id);
            }
            // delete Lead.
            $deleted_lead = wp_delete_post($contact_id);
        }
    }

    /*     * * Delete Note ** */

    public function delete_note($note = NULL, $post_id = 0) {
        if ($note['id']) {
            $post = $this->get_deletion_id('hash_id', $note['id']);
            $post_deleted = wp_delete_post($post->post_id);
        }
    }

    /*     * * Delete Task ** */

    public function delete_task($task = NULL, $post_id = 0) {
        if ($task['id']) {
            $post = $this->get_deletion_id('hash_id', $task['id']);
            $post_deleted = wp_delete_post($post->post_id);
        }
    }

    // Sync Clients.
    public function sync_clients() {
        $this->get_clients();
        if (isset($this->clients)) {
            foreach ($this->clients as $client) {
                if (isset($client['id'])) { //check the key exists in the array
                    if ($this->check_if_post_exists('client', 'hash_id', $client['id']) === 0) {
                        $this->create_client($client);
                    } else {
                        $id = $this->check_if_post_exists('client', 'hash_id', $client['id']);
                        $this->create_client($client, $id);
                    }
                }
            }
        }
    }

    public function get_clients() {
        $clients = $this->authenticate_swell_request('GET', 'clients');
        if ($this->options['client_switcher'] === 'yes') {
            if (!empty($clients)) {
                $this->clients = json_decode($clients, true);
            } else {
                $this->swell_admin_notice('danger', 'There is an error:' . $clients);
            }
        } else {
            $this->swell_admin_notice('danger', 'Clients is turned off in your settings.');
        }
    }

    // Sync Leads.
    public function sync_leads() {
        $this->get_leads();
        if (isset($this->leads)) {
            foreach ($this->leads as $lead) {
                if (isset($lead['id'])) { //check the key exists in the array
                    if ($this->check_if_post_exists('lead', 'hash_id', $lead['id']) === 0) {
                        $this->create_lead($lead);
                    } else {
                        $id = $this->check_if_post_exists('lead', 'hash_id', $lead['id']);
                        $this->create_lead($lead, $id);
                    }
                }
            }
        }
    }

    public function get_leads() {
        $leads = $this->authenticate_swell_request('GET', 'leads');
        if (!empty($leads)) {
            $this->leads = json_decode($leads, true);
        } else {

            $this->swell_admin_notice('danger', 'There is an error:' . $leads);
        }
        $this->closeConnection();
    }

    public function create_note($note, $relation = null, $relation_id = null, $post_id = 0) {
        //add if ($this->options['note_switcher'] === 'yes') {
        if ($relation === 'leads') {
            $relation = 'lead';
        }
        if ($relation === 'clients') {
            $relation = 'client';
        }
        if ($relation === 'contacts') {
            $relation = 'contact';
        }
        $note_args = array(
            'ID' => $post_id,
            'post_status' => 'publish',
            'post_type' => 'note',
            'post_title' => isset($note['title'])? sanitize_title( $note['title'] ) :'',
            'post_content' => isset($note['description'])? sanitize_textarea_field( $note['description'] ):'',
            'post_author' => isset($note['user_id'])? sanitize_text_field ( $note['user_id'] ):'',
            'meta_input' => array(
                'created_at' => isset($note['created_at'])? sanitize_text_field( $note['created_at'] ) : '',
                'updated_at' => isset($note['updated_at'])? sanitize_text_field( $note['updated_at'] ) : '',
                'hash_id' => isset($note['id'])? sanitize_text_field( $note['id'] ) : '',
                $relation => $relation_id,
            ),
        );
        $thisNote = wp_insert_post($note_args);
        update_post_meta($thisNote, 'created_at', isset( $note['created_at'] )? sanitize_text_field( $note['created_at'] ): '' );
        update_post_meta($thisNote, 'updated_at', isset( $note['updated_at'] )? sanitize_text_field( $note['updated_at'] ): '' );
        update_post_meta($thisNote, 'hash_id', isset( $note['id'] )? sanitize_text_field( $note['id'] ): '');
        update_post_meta($thisNote, $relation . '_id', $relation_id);
    }

    public function create_task($task, $relation = null, $relation_id = null, $post_id = 0) {
        if ($relation === 'leads') {
            $relation = 'lead';
        }
        if ($relation === 'clients') {
            $relation = 'client';
        }
        if ($relation === 'contacts') {
            $relation = 'contact';
        }
        $task_args = array(
            'ID' => $post_id,
            'post_status' => 'publish',
            'post_type' => 'task',
            'post_title' => isset($task['title'])? sanitize_title( $task['title'] ) :'',
            'post_content' => isset($task['details'])? sanitize_textarea_field( $task['details'] ) :'',
            'post_author' => isset($task['user_id'])? sanitize_text_field( $task['user_id'] ) :'',
            'meta_input' => array(
                'created_at' => isset($task['created_at'])? sanitize_text_field( $task['created_at'] ) :'',
                'updated_at' => isset($task['updated_at'])? sanitize_text_field( $task['updated_at'] ) :'',
                'start_date' => isset($task['start'])? sanitize_text_field( $task['start'] ) :'',
                'start_time' => isset($task['start_time'])? sanitize_text_field( $task['start_time'] ) :'',
                'end_date' => isset($task['end'])? sanitize_text_field( $task['end'] ) :'',
                'end_time' => isset($task['end_time'])? sanitize_text_field( $task['end_time'] ) :'',
                'hash_id' => isset($task['id'])? sanitize_text_field( $task['id'] ) :'',
                $relation => $relation_id,
            ),
        );

        $thisTask = wp_insert_post($task_args);

        if (isset($task['status_name']) && isset($task['status'])) {
            // wp_set_object_terms($thisTask, $task['status'], 'task_status');
            update_post_meta( $thisTask, 'swell_task_status', sanitize_text_field($task['status']) );
        }

        update_post_meta($thisTask, 'created_at', isset( $task['created_at'] )? sanitize_text_field($task['created_at']):'' );
        update_post_meta($thisTask, 'updated_at', isset( $task['updated_at'] )? sanitize_text_field($task['updated_at']):'' );
        update_post_meta($thisTask, 'start_time', isset( $task['start_time'] )? sanitize_text_field($task['start_time']):'' );
        update_post_meta($thisTask, 'end_time', isset( $task['end_time'] )? sanitize_text_field($task['end_time']):'' );
        update_post_meta($thisTask, 'hash_id', isset( $task['id'] )? sanitize_text_field($task['id']):'' );
        update_post_meta($thisTask, $relation . '_id', $relation_id);
    }

    public function swell_admin_notice($type = NULL, $message = NULL) {
        global $pagenow;

        // if ( $pagenow == 'options-general.php' ) {
        // if($type !== NULL && $message !== NULL){
        //      echo '<div class="notice notice-' . $type . ' is-dismissible">
        //          <p>' . $message . '</p>
        //          <button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button>
        //      </div>';
        //  }
        // }
    }

    public function swellWebhooks($method, $endpoint, $url, $event) {
        $clients = $this->register_swell_webhook('POST', 'subscribe', $event);
    }

    public function closeConnection() {
        return new WP_REST_Response('success', 200);
    }

    public function rs_remote_request( $url, $method = 'GET', $body_fields = array(), $url_args = array() ) {
        try {

            if (!isset($this->options) || empty($this->options))
                $this->options = get_exopite_sof_option('swellenterprise');
        } catch (HttpException $httpException) {

            $this->swell_admin_notice('danger', 'There is an error: Not Authorized');
        }
        
        //No transient so call the API and create one.
        if( isset( $this->options['username'] ) && isset( $this->options['password'] ) ) {
        $headers = array(
            'Content-type' => 'application/json',
            'Authorization' => 'Basic ' . base64_encode($this->options['username'] . ':' . $this->options['password'])
        );
        // Add url args (get parameters) to the main url
        if ( $url_args ) $url = add_query_arg( $url_args, $url );

        // Prepare arguments for wp_remote_request
        $args = array();

        if ( $method ) $args['method'] = $method;
        if ( $headers ) $args['headers'] = $headers;
        if ( $body_fields ) $args['body'] = json_encode( $body_fields );
        // Make the request
        $response = wp_remote_request( $url, $args );
        // Get the results
         $response_code = wp_remote_retrieve_response_code( $response );
         $response_message = wp_remote_retrieve_response_message( $response );
         $response_body = wp_remote_retrieve_body( $response );

        //Decode the JSON in the body, if it is json
        if ( $response_body ) {
        $j = json_decode( $response_body );

        if ( $j ) $response_body = $j;
        }

      // Return this information in the same format for success or error. Includes debugging information.
      return array(
        'response_body' => $response_body,
        'response_code' => $response_code,
        'response_message' => $response_message,
        'response' => $response,
        'debug' => array(
          'file' => __FILE__,
          'line' => __LINE__,
          'function' => __FUNCTION__,
          'args' => array(
            'url' => $url,
            'method' => $method,
           // 'url_args' => $url_args,
            'body_fields' => $body_fields,
            'headers' => $headers,
          ),
        )
      );
    }
      
    }

}
}
