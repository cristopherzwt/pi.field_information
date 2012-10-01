<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


$plugin_info = array(
	'pi_name'			=> 'Get field information',
	'pi_version'		=> '1.0',
	'pi_author'			=> 'Victor van der Stoep',
	'pi_author_url'		=> 'http://www.dtpn.nl/',
	'pi_description'	=> 'Extracts field information from DB',
	'pi_usage'			=> Field_information::usage()
);

/**
 * Field_information Class
 *
 * @package     ExpressionEngine
 * @category    Plugin
 * @author      Victor van der Stoep
 * 
 */
		
class Field_information {
    
	public $return_data;
   
	function Field_information() {
		$this->EE =& get_instance();

		$field_name    = $this->EE->TMPL->fetch_param("field_name");
        $field_channel = $this->EE->TMPL->fetch_param("channel");
        $information   = $this->EE->TMPL->fetch_param("information");
		
		if(empty($field_name) || empty($field_channel) || empty($information)) return false;
		
        $field_name    = $this->EE->db->escape_str($field_name);
        $field_channel = $this->EE->db->escape_str($field_channel);
        $information   = $this->EE->db->escape_str($information);
        
        if($information != "field_name" && $information != "field_label" && $information != "field_instructions") return false;
	    
	    
	    // Get field group
        $results       = $this->EE->db->query("SELECT field_group FROM {$this->EE->db->dbprefix}channels WHERE channel_name = '$field_channel'");
        
        if ($results->num_rows() == 0) return false;                 
        
        $field_group   = $results->row("field_group");
	    
        $results       = $this->EE->db->query("SELECT $information FROM {$this->EE->db->dbprefix}channel_fields WHERE group_id = $field_group");
	    
 		
        if ($results->num_rows() == 0) return false;
         
        $information       = $results->row($information);
 		$this->return_data = $information;
	}

	// Plugin usage
	function usage()
	{
		ob_start(); 
		?>
		
		Usage: 
		 
		{exp:field_information channel="news" field_name="news_content" information="field_label"}
		{exp:field_information channel="news" field_name="news_content" information="field_instructions"}
		
		<?php
		$buffer = ob_get_contents();
	
		ob_end_clean(); 

		return $buffer;
	}
	
	// --------------------------------------------------------------------

}
// END CLASS

/* End of file pi.field_information.php */
/* Location: ./system/expressionengine/field_information/pi.pi_field_information.php  */