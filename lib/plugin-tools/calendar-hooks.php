<?php
namespace MmmPluginToolsNamespace;

    function Ajax_Calendar_Insert($input) {
        try {
        global $wpdb;
        
        /*
        array('user_id'         => 1,
              'title'           => $Name,
              'start'           => $Start,
              'end'             => $End,
              'category_id'     => 1,
              'description'     => $Desc,
              'link' => $Url,
            );
        */
        
        $result = $wpdb->insert($wpdb->prefix . 'aec_event',
                                array('user_id'         => $input->user_id,
                                      'title'           => $input->title,
                                      'start'           => $input->start,
                                      'end'             => $input->end,
                                      'allDay'          => 1,
                                      'repeat_freq'     => $input->repeat_freq,
                                      'repeat_int'      => $input->repeat_int,
                                      'repeat_end'      => $input->repeat_end,
                                      'category_id'     => $input->category_id,
                                      'description'     => $input->description,
                                      'link'            => $input->link,
                                      'venue'           => $input->venue,
                                      'address'         => $input->address,
                                      'city'            => $input->city,
                                      'state'           => $input->state,
                                      'zip'             => $input->zip,
                                      'country'         => $input->country,
                                      'contact'         => $input->contact,
                                      'contact_info'    => $input->contact_info,
                                      'access'          => $input->access,
                                      'rsvp'            => $input->rsvp
                                    ),
                                array('%d',             // user_id
                                      '%s',             // title
                                      '%s',             // start
                                      '%s',             // end
                                      '%d',             // allDay
                                      '%d',             // repeat_freq
                                      '%d',             // repeat_int
                                      '%s',             // repeat_end
                                      '%d',             // category_id
                                      '%s',             // description
                                      '%s',             // link
                                      '%s',             // venue
                                      '%s',             // address
                                      '%s',             // city
                                      '%s',             // state
                                      '%s',             // zip
                                      '%s',             // country
                                      '%s',             // contact
                                      '%s',             // contact_info
                                      '%d',             // access
                                      '%d'              // rsvp
                                    )
                            );
                            
        return $wpdb->insert_id;
        } catch (Exception $e)
        {}
        return -1;
    }

    function Ajax_Calendar_Update_Name($ProductID, $Name) {
        try {
        global $wpdb;
        
        $product = GetProductById($ProductID);
        
        $result = $wpdb->update($wpdb->prefix . 'aec_event' ,
                                array('title'           => $Name),
                                array('id'              => $product->intExternalID),
                                '%s',               // title),
                                '%d'            // id
                            );
        } catch (Exception $e)
        {}
    }

    function Ajax_Calendar_Update($input) {
        try {
        global $wpdb;
        
        $product = GetProductById($input->id);
        
        $result = $wpdb->update($wpdb->prefix . 'aec_event' ,
                                array('user_id'         => $input->user_id,
                                      'title'           => $input->title,
                                      'start'           => $input->start,
                                      'end'             => $input->end,
                                      'allDay'          => 1    ,
                                      'repeat_freq'     => $input->repeat_freq,
                                      'repeat_int'      => $input->repeat_int,
                                      'repeat_end'      => $input->repeat_end,
                                      'category_id'     => $input->category_id,
                                      'description'     => $input->description,
                                      'link'            => $input->link,
                                      'venue'           => $input->venue,
                                      'address'         => $input->address,
                                      'city'            => $input->city,
                                      'state'           => $input->state,
                                      'zip'             => $input->zip,
                                      'country'         => $input->country,
                                      'contact'         => $input->contact,
                                      'contact_info'    => $input->contact_info,
                                      'access'          => $input->access,
                                      'rsvp'            => $input->rsvp
                                    ),
                                array('id'              => $product->intExternalID),
                                array('%d',             // user_id
                                      '%s',             // title
                                      '%s',             // start
                                      '%s',             // end
                                      '%d',             // allDay
                                      '%d',             // repeat_freq
                                      '%d',             // repeat_int
                                      '%s',             // repeat_end
                                      '%d',             // category_id
                                      '%s',             // description
                                      '%s',             // link
                                      '%s',             // venue
                                      '%s',             // address
                                      '%s',             // city
                                      '%s',             // state
                                      '%s',             // zip
                                      '%s',             // country
                                      '%s',             // contact
                                      '%s',             // contact_info
                                      '%d',             // access
                                      '%d'              // rsvp
                                    ),
                                array ('%d')            // id
                            );
        } catch (Exception $e)
        {}
    }

    function Ajax_Calendar_Delete($eid) {
        try {
        $sql = sprintf('DELETE FROM wp_aec_event WHERE id = %s', $eid);
        
        ExecuteStatement($sql);
        } catch (Exception $e)
        {}
    }
    
    function GetCalendarUrl($EventID)
    {
        try {
        $query = sprintf("SELECT * FROM wp_aec_event WHERE id = %s", $EventID);
        $CalEvent = ExecuteQuery($query);
        
        //print_r($CalEvent);
        
        return $CalEvent[0]->link;
        } catch (Exception $e)
        {}
    }