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
if ( ! class_exists( 'SWELLEnterprise_RestApis' ) ) {
class SWELLEnterprise_RestApis extends WP_REST_Controller {
    /**
     * Register the routes for the objects of the controller.
     */
    public function register_routes() {
      $version = '1';
      $namespace = 'swell/v' . $version;
      

      $base = 'contacts';
      register_rest_route( $namespace, '/' . $base, array(
          array(
            'methods'             => WP_REST_Server::READABLE,
            'callback'            => array( $this, 'create_item_'.$base ),
            'permission_callback' => false  //  array( $this, 'create_item_permissions_check' )
            ),
          array(
            'methods'             => WP_REST_Server::CREATABLE,
            'callback'            => array( $this, 'create_item_'.$base ),
            'permission_callback' => false  //  array( $this, 'create_item_permissions_check' )
            ),
          array(
              'methods'             => WP_REST_Server::EDITABLE,
              'callback'            => array( $this, 'create_item_'.$base ),
              'permission_callback' => false,
            ),
        ) 
      );
      $base = 'contacts-delete';
      register_rest_route( $namespace, '/' . $base, array(
        array(
          'methods'             => WP_REST_Server::CREATABLE,
          'callback'            => array( $this, 'delete_item_'.'contacts' ),
          'permission_callback' => false  //  array( $this, 'create_item_permissions_check' )
        ),
        
      ) );

      $base = 'clients';
      register_rest_route( $namespace, '/' . $base, array(
        array(
          'methods'             => WP_REST_Server::READABLE,
          'callback'            => array( $this, 'create_item_'.$base ),
          'permission_callback' => false  //  array( $this, 'create_item_permissions_check' )
        ),
        array(
          'methods'             => WP_REST_Server::CREATABLE,
          'callback'            => array( $this, 'create_item_'.$base ),
          'permission_callback' => false  //  array( $this, 'create_item_permissions_check' )
        ),
        array(
          'methods'             => WP_REST_Server::EDITABLE,
          'callback'            => array( $this, 'create_item_'.$base ),
          'permission_callback' => false  //  array( $this, 'create_item_permissions_check' )
        ),
      ) );
      $base = 'clients-delete';
      register_rest_route( $namespace, '/' . $base, array(
        array(
          'methods'             => WP_REST_Server::CREATABLE,
          'callback'            => array( $this, 'delete_item_'.'clients' ),
          'permission_callback' => false  //  array( $this, 'create_item_permissions_check' )
        ),
        
      ) );
      $base = 'leads';
      register_rest_route( $namespace, '/' . $base, array(
        array(
          'methods'             => WP_REST_Server::READABLE,
          'callback'            => array( $this, 'create_item_'.$base ),
          'permission_callback' => false  //  array( $this, 'create_item_permissions_check' )
        ),
        array(
          'methods'             => WP_REST_Server::CREATABLE,
          'callback'            => array( $this, 'create_item_'.$base ),
          'permission_callback' => false  //  array( $this, 'create_item_permissions_check' )
        ),
      ) );

      $base = 'leads-delete';
      register_rest_route( $namespace, '/' . $base, array(
        array(
          'methods'             => WP_REST_Server::CREATABLE,
          'callback'            => array( $this, 'delete_item_'.'leads' ),
          'permission_callback' => false  //  array( $this, 'create_item_permissions_check' )
        ),
      ) );

      $base = 'notes';
      register_rest_route( $namespace, '/' . $base, array(
        array(
          'methods'             => WP_REST_Server::READABLE,
          'callback'            => array( $this, 'create_item_'.$base ),
          'permission_callback' => false  //  array( $this, 'create_item_permissions_check' )
        ),
        array(
          'methods'             => WP_REST_Server::CREATABLE,
          'callback'            => array( $this, 'create_item_'.$base ),
          'permission_callback' => false  //  array( $this, 'create_item_permissions_check' )
        ),
        
      ) );

       $base = 'notes-delete';
      register_rest_route( $namespace, '/' . $base, array(
        array(
          'methods'             => WP_REST_Server::CREATABLE,
          'callback'            => array( $this, 'delete_item_'.'notes' ),
          'permission_callback' => false  //  array( $this, 'create_item_permissions_check' )
        ),
        
      ) );


      $base = 'tasks';
      register_rest_route( $namespace, '/' . $base, array(
        array(
          'methods'             => WP_REST_Server::READABLE,
          'callback'            => array( $this, 'create_item_'.$base ),
          'permission_callback' => false  //  array( $this, 'create_item_permissions_check' )
        ),
        array(
          'methods'             => WP_REST_Server::CREATABLE,
          'callback'            => array( $this, 'create_item_'.$base ),
          'permission_callback' => false  //  array( $this, 'create_item_permissions_check' )
        ),
        
      ) );
      $base = 'tasks-delete';
      register_rest_route( $namespace, '/' . $base, array(
        array(
          'methods'             => WP_REST_Server::CREATABLE,
          'callback'            => array( $this, 'delete_item_'.'tasks' ),
          'permission_callback' => false  //  array( $this, 'create_item_permissions_check' )
        ),
        
      ) );
      
    }
  
    /**
     * Create one item from the collection
     *
     * @param WP_REST_Request $request Full data about the request.
     * @return WP_Error|WP_REST_Response
     */
    public function create_item_contacts( $request ) {
          $contact = $request->get_json_params();
          if(isset($contact['payload']['id'])){ //check the key exists in the array
              $plugin_services = new SWELLEnterprise_API_Services();
              if($plugin_services->check_if_post_exists('contact', 'hash_id', $contact['payload']['id']) === 0){
                  $plugin_services->create_contact($contact['payload']);
              } else {
                  $id = $plugin_services->check_if_post_exists('contact', 'hash_id', $contact['payload']['id']);
                  $plugin_services->create_contact( $contact['payload'], $id );
              }
          }
    }

    public function create_item_clients( $request ) {
       
        $client = $request->get_json_params();
        if(isset($client['payload']['id'])){ //check the key exists in the array
            $plugin_services = new SWELLEnterprise_API_Services();
            if($plugin_services->check_if_post_exists('client', 'hash_id', $client['payload']['id']) === 0){
                $plugin_services->create_client($client['payload']);
            } else {
                $id = $plugin_services->check_if_post_exists('client', 'hash_id', $client['payload']['id']);
                $plugin_services->create_client($client['payload'], $id );
            }
        }
    }

    public function create_item_leads( $request ) {

        $lead = $request->get_json_params();
        if(isset($lead['payload']['id'])){ //check the key exists in the array
            $plugin_services = new SWELLEnterprise_API_Services();
            if($plugin_services->check_if_post_exists('lead', 'hash_id', $lead['payload']['id']) === 0){
                $plugin_services->create_lead($lead['payload']);
            } else {
                $id = $plugin_services->check_if_post_exists('lead', 'hash_id', $lead['payload']['id']);
                $plugin_services->create_lead($lead['payload'], $id);
            }
        }
    }


    public function delete_item_tasks( $request ) {
      
      $delete_post_hash_id = $request->get_json_params();
      $plugin_services = new SWELLEnterprise_API_Services();
      $plugin_services->delete_task( $delete_post_hash_id );
    }

    // Delete Leads
    public function delete_item_leads( $request ) {
      $delete_post_hash_id = $request->get_json_params();
      $plugin_services = new SWELLEnterprise_API_Services();
      $plugin_services->delete_lead( $delete_post_hash_id['payload'] );
    }
    // Delete Clients
    public function delete_item_clients( $request ) {
      $delete_post_hash_id = $request->get_json_params();
      $plugin_services = new SWELLEnterprise_API_Services();
      $plugin_services->delete_client( $delete_post_hash_id['payload'] );
    }

    // Delete Contacts
    public function delete_item_contacts( $request ) {
      $delete_post_hash_id = $request->get_json_params();
      $plugin_services = new SWELLEnterprise_API_Services();
      $plugin_services->delete_contact( $delete_post_hash_id['payload'] );
    }
    // notes creation callback.
    public function create_item_notes( $request ) {
      $note = $request->get_json_params();
      if(isset($note['payload']['id'])) { //check the key exists in the array
          $plugin_services = new SWELLEnterprise_API_Services();
          if($plugin_services->check_if_post_exists('note', 'hash_id', $note['payload']['id']) === 0){
              // Create note if it's not created.
              $plugin_services->create_note( $note['payload'], $note['payload']['attach'] ,  $note['payload']['attach_id'] , 0 );
          } else {
              // Get id and update note.
              $note_id = $plugin_services->check_if_post_exists('note', 'hash_id', $note['payload']['id']);
              $plugin_services->create_note( $note['payload'], $note['payload']['attach'] ,  $note['payload']['attach_id'] , $note_id );
          }
      }
    }
    // Delete notes callback.
    public function delete_item_notes( $request ) {
          $delete_post_hash_id = $request->get_json_params();
          $plugin_services = new SWELLEnterprise_API_Services();
          $plugin_services->delete_note( $delete_post_hash_id['payload'] );
    }

    // Tasks creation and updation callback.
    public function create_item_tasks( $request ) {

      $task = $request->get_json_params();
      if(isset($task['payload']['id'])){ //check the key exists in the array
          $plugin_services = new SWELLEnterprise_API_Services();
          if($plugin_services->check_if_post_exists('task', 'hash_id', $task['payload']['id']) === 0){
             $plugin_services->create_task( $task['payload'], $task['payload']['attach'] ,  $task['payload']['attach_id'] , 0 );
          } else {
              $id = $plugin_services->check_if_post_exists('task', 'hash_id', $task['payload']['id']);
              $plugin_services->create_task( $task['payload'], $task['payload']['attach'] ,  $task['payload']['attach_id'] , $id );
          }
      }
    }
  
    /**
     * Update one item from the collection
     *
     * @param WP_REST_Request $request Full data about the request.
     * @return WP_Error|WP_REST_Response
     */
    public function update_item( $request ) {
      $item = $this->prepare_item_for_database( $request );
  
        $data = slug_some_function_to_update_item( $item );
        if ( is_array( $data ) ) {
          return new WP_REST_Response( $data, 200 );
        }
      return new WP_Error( 'cant-update', __( 'message', 'text-domain' ), array( 'status' => 500 ) );
    }

    /**
   * Delete one item from the collection
   *
   * @param WP_REST_Request $request Full data about the request.
   * @return WP_Error|WP_REST_Response
   */
    public function delete_item( $request ) {
      $item = $this->prepare_item_for_database( $request );
  
        $deleted = slug_some_function_to_delete_item( $item );
        if ( $deleted ) {
          return new WP_REST_Response( true, 200 );
        }
   
      return new WP_Error( 'cant-delete', __( 'message', 'text-domain' ), array( 'status' => 500 ) );
    }

    /**
     * Check if a given request has access to get items
     *
     * @param WP_REST_Request $request Full data about the request.
     * @return WP_Error|bool
     */
    public function get_items_permissions_check( $request ) {
      //return true; <--use to make readable by all
      return current_user_can( 'edit_something' );
    }

    /**
     * Check if a given request has access to get a specific item
     *
     * @param WP_REST_Request $request Full data about the request.
     * @return WP_Error|bool
     */
    //public function get_item_permissions_check( $request ) {
    //  return $this->get_items_permissions_check( $request );
    //}
  
    /**
     * Check if a given request has access to create items
     *
     * @param WP_REST_Request $request Full data about the request.
     * @return WP_Error|bool
     */
    public function create_item_permissions_check( $request ) {
      return current_user_can( 'edit_something' );
    }
  
    /**
     * Check if a given request has access to update a specific item
     *
     * @param WP_REST_Request $request Full data about the request.
     * @return WP_Error|bool
     */
    public function update_item_permissions_check( $request ) {
      return $this->create_item_permissions_check( $request );
    }
 
    /**
     * Check if a given request has access to delete a specific item
     *
     * @param WP_REST_Request $request Full data about the request.
     * @return WP_Error|bool
     */
    public function delete_item_permissions_check( $request ) {
      return $this->create_item_permissions_check( $request );
    }

    /**
     * Prepare the item for create or update operation
     *
     * @param WP_REST_Request $request Request object
     * @return WP_Error|object $prepared_item
     */
    protected function prepare_item_for_database( $request ) {
      return array();
    }
    /**
     * Prepare the item for the REST response
     *
     * @param mixed $item WordPress representation of the item.
     * @param WP_REST_Request $request Request object.
     * @return mixed
     */
    public function prepare_item_for_response( $item, $request ) {
      return array();
    }

    /**
     * Get the query params for collections
     *
     * @return array
     */
    public function get_collection_params() {
      return array(
        'page'     => array(
          'description'       => 'Current page of the collection.',
          'type'              => 'integer',
          'default'           => 1,
          'sanitize_callback' => 'absint',
        ),
        'per_page' => array(
          'description'       => 'Maximum number of items to be returned in result set.',
          'type'              => 'integer',
          'default'           => 10,
          'sanitize_callback' => 'absint',
        ),
        'search'   => array(
          'description'       => 'Limit results to those matching a string.',
          'type'              => 'string',
          'sanitize_callback' => 'sanitize_text_field',
        ),
      );
    }

    /**
     * Get the .
     *
     * @return array
     */
    public function swell_get_post_data( $post_ID, $post, $update ) {
        if( isset( $_POST['post_type']) ) {
          $post_type = sanitize_text_field( $_POST['post_type'] );
          // get hash_id.
          $id = get_post_field( 'hash_id', $post_ID );
          $url = '';
          $url_args = array();
          if( !empty( $id ) ) {
            $method = 'PUT';
            $case = 'Update';
            if( $post_type === 'contact' ) {
              $url = SWELLENTERPRISE_BASE_URL . 'api/v3/contacts/'.$id;
            }
            if( $post_type === 'client' ) {
              $url = SWELLENTERPRISE_BASE_URL . 'api/v3/clients/'.$id;
            }
            if( $post_type === 'lead' ) {
              $url = SWELLENTERPRISE_BASE_URL . 'api/v3/leads/'.$id;
            }
            if( $post_type === 'note' ) {
              $url = SWELLENTERPRISE_BASE_URL . 'api/v3/notes/'.$id; 
            }
            if( $post_type === 'task' ) {
              $url = SWELLENTERPRISE_BASE_URL . 'api/v3/tasks/'.$id; 
            }
          } else {
              $method = 'POST';
              $case = 'Add';
              if( $post_type === 'contact' ) {
                $url = SWELLENTERPRISE_BASE_URL . 'api/v3/contacts/';
              }
              if( $post_type === 'client' ) {
                $url = SWELLENTERPRISE_BASE_URL . 'api/v3/clients/';
              }
              if( $post_type === 'lead' ) {
                $url = SWELLENTERPRISE_BASE_URL . 'api/v3/leads/';
              }
              if( $post_type === 'note' ) {
                $url = SWELLENTERPRISE_BASE_URL . 'api/v3/notes/'; 
              }
              if( $post_type === 'task' ) {
                $url = SWELLENTERPRISE_BASE_URL . 'api/v3/tasks/'; 
              }
          }

          // In case post types are client, lead and contact.
          if( $post_type === 'client' || $post_type === 'lead' || $post_type === 'contact' ) {
            if( $case === 'Add' ) {
              $body_fields = $this->get_body_fields( $post_type, $post_ID );
            } else {
              $body_fields = $this->get_body_fields( $post_type, $post_ID ); 
            }
          }
          // In case post type is note.
          if( $post_type === 'note' ) {
            $id = get_post_meta( $post_ID, 'hash_id', true );
            if( !empty( $id ) ) {
              $method = 'PATCH';
              $url = SWELLENTERPRISE_BASE_URL . 'api/v3/notes/'.$id; 
            } else {
              $method = 'POST';
            }
            $body_fields = $this->get_notes_body_fields( $post_ID, $method );
          }
          // In case when post_type is task.
          if( $post_type === 'task' ) {
            $id = get_post_meta( $post_ID, 'hash_id', true );
            if( !empty( $id ) ) {
              $method = 'PATCH';
              $url = SWELLENTERPRISE_BASE_URL . 'api/v3/tasks/'.$id;
            } else {
              $method = 'POST';
            }
            $body_fields = $this->get_task_body_fields( $post_ID );          
          }

          $plugin_services = new SWELLEnterprise_API_Services();
          
          if( isset($result['response_body']->id) ){
            $body_fields['id'] = $result['response_body']->id;
          }

          if( $post_type === 'lead' ) {
            
            $plugin_services->create_lead($body_fields,$post_ID, 1 );
          }
          if ( $post_type === 'contact' ) {
            $plugin_services->create_contact($body_fields,$post_ID, 1 );
          }
          if ( $post_type === 'client' ) {
            $plugin_services->create_client($body_fields,$post_ID, 1 ); 
          }
          // Saving note id against note for updation.
          if( $post_type === 'note' ) {
            add_post_meta( $post_ID, 'hash_id', $result['response_body']->id );
          }
          // Saving task id against task for updation.
          if( $post_type === 'task' ) {
            add_post_meta( $post_ID, 'hash_id', $result['response_body']->id );
          }    
          // if( $result && $result['response_code'] === 201 ) {
            if( $post_type === 'lead' || $post_type === 'client' || $post_type === 'contact' ) {
              $url = get_site_url()."/wp-admin/edit.php?post_type=".$post_type;
              wp_safe_redirect( $url );
              exit();
            } 
        }
    }

    public function increase_request_http_request_timeout_filter( ) {
      $timeout_value = 800;
      // filter...
      return $timeout_value;
    }

    // Get Notes body fields.
    public function get_notes_body_fields( $post_ID ) {
        global $_POST;
        if ( isset( $_POST['attached_post_id'] ) ) {
          // $id = 0;
          $attached_id = sanitize_text_field( $_POST['attached_post_id'] );
          $attach_id = sanitize_text_field( get_post_field( 'hash_id', $attached_id ) );
          if( isset( $_POST['attached_post_id'] ) ) {
            $attached_id = sanitize_text_field( $_POST['attached_post_id'] );
            $attchment_post_type = get_post_type( $attached_id );
            $resource = ucfirst( $attchment_post_type );
            update_post_meta( $post_ID, $attchment_post_type, $attach_id );
            update_post_meta( $post_ID, $attchment_post_type.'_id', $attach_id );
            update_post_meta( $post_ID, 'resource', $resource );
          }
        } else {
          $attach_id = '';
          $resource  = '';
        }
        $body_fields = array(
          // 'id'          => $id,
          'title'       => sanitize_title( $_POST['post_title'] ),
          'description' => sanitize_textarea_field( $_POST['content'] ),
          'attach_id' => $attach_id,
          'resource' => $resource,
        );
      return $body_fields;
    }

    // Get task body fields.
    public function get_task_body_fields( $post_ID ) {
      global $_POST;
      if( isset( $_POST['attached_post_id'] ) ) {
        // $id = 0;
        $attached_post_id = sanitize_text_field( $_POST['attached_post_id'] );
        if( isset( $attached_post_id ) ) {
          $attach_id = get_post_field( 'hash_id', $attached_post_id );
          $attchment_post_type = get_post_type( $attached_post_id );
          $resource = ucfirst( $attchment_post_type );
          update_post_meta( $post_ID, $attchment_post_type, $attach_id );
          update_post_meta( $post_ID, $attchment_post_type.'_id', $attach_id );
          update_post_meta( $post_ID, 'resource', $resource );
        }
      } else {
        $attach_id = '';
        $resource = '';
      }
      // If task status is set.
      if( isset( $_POST['swell_task_status_field'] ) ) {
        $status = sanitize_text_field($_POST['swell_task_status_field']);
        update_post_meta(
            $post_ID,
            'swell_task_status',
            $status
        );
      } else {
        $status = '';
      }

      $body_fields = array(
        // 'id'    => $id,
        'title' => isset( $_POST['post_title'] )? sanitize_title( $_POST['post_title'] ) : null,
        'details' => isset( $_POST['content'] )? sanitize_textarea_field( $_POST['content'] ) : null,
        'start' => isset( $_POST['swellenterprise-meta']['start_date'] )? sanitize_text_field ( $_POST['swellenterprise-meta']['start_date'] ): null,
        'end' => isset( $_POST['swellenterprise-meta']['end_date'] )? sanitize_text_field( $_POST['swellenterprise-meta']['end_date'] ): null,
        'start_time' => isset( $_POST['swellenterprise-meta']['start_time'] ) ? sanitize_text_field( $_POST['swellenterprise-meta']['start_time'] ): null,
        'end_time' => isset( $_POST['swellenterprise-meta']['end_time'] )? sanitize_text_field( $_POST['swellenterprise-meta']['end_time'] ): null,
        'status' => $status,
        'attach_id' => $attach_id,
        'resource' => $resource,
       );
      // Adding task fields data in postmeta.

      update_post_meta( $post_ID, 'start_date', sanitize_text_field( $_POST['swellenterprise-meta']['start_date'] ) );
      update_post_meta( $post_ID, 'end_date', sanitize_text_field( $_POST['swellenterprise-meta']['end_date'] ) );
      update_post_meta( $post_ID, 'start_time', sanitize_text_field( $_POST['swellenterprise-meta']['start_time'] ) );
      update_post_meta( $post_ID, 'end_time', sanitize_text_field( $_POST['swellenterprise-meta']['end_time'] ) );
      return $body_fields;
    }

    // Get client/ contact/ lead form fields.
    public function get_body_fields ( $post_type, $post_id ) {
      global $_POST;
      $custom_fields = array();
      // get hash_id.
      $id        = get_post_field( 'hash_id', $post_id );      
      $post_data = isset( $_POST['swellenterprise-meta'] )?  $_POST['swellenterprise-meta'] : null;

      // Setting lead status if post type is lead.
      if( $post_type === 'lead' && isset( $_POST['swell_lead_status_field'] ) ) {
          $status = sanitize_text_field( $_POST['swell_lead_status_field'] );
          update_post_meta(
              $post_id,
              'swell_lead_status',
              $status
          );
      } else {
        $status = '';
      }
      if( isset( $post_data['custom_field'] ) ) {
          $custom_fields = sanitize_text_field($post_data['custom_field']);
      } else {
        $custom_fields = '';
      }
   
      $form_data = array( 
        'id'         => $id,
        'first_name' => isset($post_data['first_name'] )? sanitize_text_field( $post_data['first_name'] ):null,
        'last_name' => isset($post_data['last_name'] )? sanitize_text_field( $post_data['last_name'] ):null,
        'organization' => isset($post_data['organization'] )? sanitize_text_field( $post_data['organization'] ):null,
        'address' => isset($post_data['address'] )? sanitize_textarea_field( $post_data['address'] ):null,
        'city' => isset($post_data['city'] )? sanitize_text_field( $post_data['city'] ):null,
        'state' => isset($post_data['state'] )? sanitize_text_field( $post_data['state'] ):null,
        'zip' => isset($post_data['zip'] )? sanitize_text_field( $post_data['zip'] ):null,
        'phone_number' => isset($post_data['phone'] )? sanitize_text_field( $post_data['phone'] ):null,
        'email' => isset($post_data['email'] )? sanitize_email( $post_data['email']):null,
        'status_id' => $status,
        'custom_fields' => $custom_fields
      );
      
      return $form_data;
    }

    // custom function.
    function array_push_assoc($array, $key, $value){
     $array[$key] = $value;
     return $array;
    }

    /**
     * Get the post against hash_id.
     *
     * @return array
     */
    public function swell_trash_post ($postid , $force_delete = false ) {
      $hash_id   = get_post_meta( $postid, 'hash_id' , true);
      $post_type = get_post_field( 'post_type', $postid );
      if( !empty( $hash_id ) ) {
        $method = 'DELETE';
        if( $post_type === 'contact' ) {
          $url = SWELLENTERPRISE_BASE_URL . 'api/v3/contacts/'.$hash_id;
          $result_code = $this->delete_related_data( $hash_id, 'contact' );
        }
        if( $post_type === 'client' ) {
          $url = SWELLENTERPRISE_BASE_URL . 'api/v3/clients/'.$hash_id;
          $result_code = $this->delete_related_data( $hash_id, 'client' );
        }
        if( $post_type === 'lead' ) {
          $url = SWELLENTERPRISE_BASE_URL . 'api/v3/leads/'.$hash_id;
          $result_code = $this->delete_related_data( $hash_id, 'lead' );
        }
        if( $post_type === 'note' ) {
          $hash_id   = get_post_meta( $postid, 'hash_id' , true);
          $url = SWELLENTERPRISE_BASE_URL . 'api/v3/notes/'.$hash_id;
        }
        if( $post_type === 'task' ) {
          $hash_id   = get_post_meta( $postid, 'hash_id' , true);
          $url = SWELLENTERPRISE_BASE_URL . 'api/v3/tasks/'.$hash_id;
        }
          $plugin_services = new SWELLEnterprise_API_Services();
          $result = $plugin_services->rs_remote_request( $url, $method );
      }
    }

    /**
     * Delete related data of lead/ client/ contact against hash_id.
     *
     * @return array
     */
    public function delete_related_data ( $hash_id, $related_type) {
      $method = 'DELETE';
      $args = array(
            'numberposts'      => 5,
            'orderby'          => 'ID',
            'order'            => 'ASC',
            'meta_key'         => $related_type,
            'meta_value'       => $hash_id,
            'post_type'        => array('note','task'),
          );
      $related_tasks_notes = get_posts( $args );
      if ( sizeof( $related_tasks_notes ) > 0 ) {
          foreach ( $related_tasks_notes as $related_data ) {
            $related_data_hash_id   = get_post_meta( $related_data->ID , 'hash_id' , true);
            $related_data_post_type = get_post_field( 'post_type', $related_data->ID );
            // die();      
            if ( $related_data_post_type === 'task' ) {
              $related_url = SWELLENTERPRISE_BASE_URL . 'api/v3/tasks/'.$related_data_hash_id;
              $plugin_services = new SWELLEnterprise_API_Services();
              // Delete task from Wordpress.
              wp_delete_post( $related_data->ID );
              // Deleting task from System.
              $delete_related_task = $plugin_services->rs_remote_request( $related_url, $method );
            } 

            if ( $related_data_post_type === 'note' ) {
              $related_url = SWELLENTERPRISE_BASE_URL . 'api/v3/notes/'.$related_data_hash_id;
              $plugin_services = new SWELLEnterprise_API_Services();
              // Delete note from Wordpress.
              wp_delete_post( $related_data->ID );
              // Deleting note from system.
              $delete_related_note = $plugin_services->rs_remote_request( $related_url, $method );
            }
          }
      } else {
        return 0;
      }
    }

    /**
     * Get task statuses.
     *
     * @return array
     */
    public function swell_save_task_statuses () {
        if( false == ( $service_data = get_transient( "swell_task_statuses" ) ) ) 
        {
          $task_status_url = SWELLENTERPRISE_BASE_URL . 'api/statuses/task';
          $method = 'GET';
          $plugin_services = new SWELLEnterprise_API_Services();
          $result = $plugin_services->rs_remote_request(  $task_status_url, $method);
          //Success (200 = changes ok, 204 = no change needed)
          if ( isset( $result['response_code'] ) ) {
            if ( $result['response_code'] == 200 || $result['response_code'] == 204 ) {
              $task_status_data = $result['response_body'];
              set_transient( "swell_task_statuses", $task_status_data, 180 );
            }
          }
        }
    }

     /**
     * Get lead statuses.
     *
     * @return array
     */
      public function swell_save_lead_statuses () {
        if( false == ( $service_data = get_transient( "swell_lead_statuses" ) ) ) 
          {
            $lead_status_url = SWELLENTERPRISE_BASE_URL . 'api/statuses/lead';
            $method = 'GET';
            $plugin_services = new SWELLEnterprise_API_Services();
            $result = $plugin_services->rs_remote_request(  $lead_status_url, $method);
            //Success (200 = changes ok, 204 = no change needed)
            if ( isset( $result['response_code'] ) ) {
              if ( $result['response_code'] == 200 || $result['response_code'] == 204 ) {
                  $lead_status_data = $result['response_body'];
                  set_transient( "swell_lead_statuses", $lead_status_data, 180 );
              }
            }
          }
      }

    /**
     * Get lead custom fields.
     *
     * @return array
     */
    public function swell_save_lead_custom_fields () {
      if( false == ( $service_data = get_transient( "swell_lead_custom_fields" ) ) ) 
        {
          $lead_custom_fields_url = SWELLENTERPRISE_BASE_URL . 'api/v3/customfields/lead';
          $method = 'GET';
          $plugin_services = new SWELLEnterprise_API_Services();
          $result = $plugin_services->rs_remote_request( $lead_custom_fields_url, $method);
          //Success (200 = changes ok, 204 = no change needed)
          if ( isset( $result['response_code'] ) ) {
            if ( $result['response_code'] === 201 || $result['response_code'] === 204 ) {
                $lead_custom_fields_data = $result['response_body'];
                set_transient( "swell_lead_custom_fields", $lead_custom_fields_data, 180 );
            }
          }
        }
    }
    /**
     * Get lead custom fields.
     *
     * @return array
     */
    public function swell_save_contact_custom_fields () {
      if( false == ( $service_data = get_transient( "swell_contact_custom_fields" ) ) ) 
        {
          $contact_custom_fields_url = SWELLENTERPRISE_BASE_URL . 'api/v3/customfields/contact';
          $method = 'GET';
          $plugin_services = new SWELLEnterprise_API_Services();
          $result = $plugin_services->rs_remote_request( $contact_custom_fields_url, $method);
          //Success (200 = changes ok, 204 = no change needed)
          if ( isset( $result['response_code'] ) ) {
            if ( $result['response_code'] == 201 || $result['response_code'] == 204 ) {
              $contact_custom_fields_data = $result['response_body'];
              set_transient( "swell_contact_custom_fields", $contact_custom_fields_data, 180 );
            }
          }
        }
    }
    /**
     * Get lead custom fields.
     *
     * @return array
     */
    public function swell_save_client_custom_fields () {
      if( false == ( $service_data = get_transient( "swell_client_custom_fields" ) ) ) 
        {
          $client_custom_fields_url = SWELLENTERPRISE_BASE_URL . 'api/v3/customfields/client';
          $method = 'GET';
          $plugin_services = new SWELLEnterprise_API_Services();
          $result = $plugin_services->rs_remote_request(  $client_custom_fields_url, $method);
          //Success (200 = changes ok, 204 = no change needed)
          if ( isset( $result['response_code'] ) ) {
            if ( $result['response_code'] == 201 || $result['response_code'] == 204 ) {
                $client_custom_fields_data = $result['response_body'];
                set_transient( "swell_client_custom_fields", $client_custom_fields_data, 180 );
            }
          }
        }
    }
    /**
     * Create task status meta box.
     *
     * @return array
     */
    public function task_status_meta_box () {
      add_meta_box(
          'swell_task_status',                 // Unique ID
          'Select Task Status',      // Box title
          array( $this, 'swell_task_status_callback' ),  // Content callback, must be of type callable
          'task'                            // Post type
      );
    }
    /**
     * Task status callback.
     *
     * @return array
     */
    public function swell_task_status_callback( $post) {
      $get_task_statuses = get_transient('swell_task_statuses');
      $saved_task_status = get_post_meta( $post->ID, 'swell_task_status', true );
      $saved_task_status = sanitize_text_field( $saved_task_status );
      if ( $get_task_statuses ) {
      ?>
      <label for="swell_task_status_field">Select Task Status</label>
      <select name="swell_task_status_field" id="swell_task_status_field" class="postbox">
        <?php foreach ( $get_task_statuses as $get_task_status ) {?> 
          <option value="<?php echo esc_attr( $get_task_status->id ); ?>" <?php selected( $saved_task_status, $get_task_status->id ); ?> ><?php echo esc_attr( $get_task_status->label ); ?></option>
          <?php } ?>
      </select>
      <?php }
    }
    /**
     * Create lead status meta box.
     *
     * @return array
     */
    public function lead_status_meta_box () {
      add_meta_box(
          'swell_lead_status',                 // Unique ID
          'Select Lead Status',      // Box title
          array( $this, 'swell_lead_status_callback' ),  // Content callback, must be of type callable
          'lead'                            // Post type
      );
    }
    /**
     * Create lead status meta box callback.
     *
     * @return array
     */
    public function swell_lead_status_callback( $post ) {
      $swell_lead_statuses = get_transient('swell_lead_statuses');
      $saved_lead_status = get_post_meta( $post->ID, 'swell_lead_status', true );
      $saved_lead_status = sanitize_text_field( $saved_lead_status );
      if ( $swell_lead_statuses ) {
      ?>
      <label for="swell_lead_status_field">Select Lead Status</label>
      <select name="swell_lead_status_field" id="swell_lead_status_field" class="postbox">
        <?php foreach ( $swell_lead_statuses as $swell_lead_status ) { ?>
          <option value="<?php echo esc_attr($swell_lead_status->id); ?>" <?php selected( $saved_lead_status, $swell_lead_status->id ); ?>><?php echo esc_attr($swell_lead_status->label); ?></option>
        <?php } ?>
      </select>
      <?php
      }
    }
    /**
     * Create lead status meta box callback.
     *
     * @return array
     */
    public function swell_edit_form_callback ( $post ) {
      if( isset( $_GET['attached_post_id'] ) ) {
        if( $post->post_type === 'note' || $post->post_type === 'task' ) {
          $get_attached_id = sanitize_text_field( $_GET['attached_post_id'] ); ?>
          <input type="hidden" id="<?php echo esc_attr($get_attached_id); ?>" name="attached_post_id" value="<?php echo esc_attr($get_attached_id); ?>">
          <?php
        }  
      } 
    }
  }
 }