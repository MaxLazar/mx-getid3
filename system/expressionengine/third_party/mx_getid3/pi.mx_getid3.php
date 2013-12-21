<?php

require_once PATH_THIRD . 'mx_getid3/config.php';

if ( ! class_exists('getID3'))
{
	require_once PATH_THIRD.'mx_getid3/getid3/getid3.php';
}


/**
 *  MX GetId3 Class for ExpressionEngine2
 *
 * @package  ExpressionEngine
 * @subpackage Plugins
 * @category Plugins
 * @author    Max Lazar <max@eec.ms>
 * @Commercial - please see LICENSE file included with this distribution
 */

$plugin_info = array(
	'pi_name' => MX_GETID3_NAME,
	'pi_version' => MX_GETID3_VER,
	'pi_author' => MX_GETID3_AUTHOR,
	'pi_author_url' => MX_GETID3_DOCS,
	'pi_description' => MX_GETID3_DESC,
	'pi_usage' => mx_getid3::usage()
);

class Mx_getid3
{
	var $return_data = "";
	var $out = array();
	var $level  = 0;
	var $name_val = "";
	var $global_array = array ();

	public $cache_lifetime = 1440;

	/**
	 * Mx_getid3 function.
	 *
	 * @access public
	 * @return void
	 */
	function Mx_getid3()
	{

		$this->EE =& get_instance();

		$conds = array();
		$file       = (!$this->EE->TMPL->fetch_param('file')) ? FALSE : $this->EE->TMPL->fetch_param('file') ;
		$tagdata    = $this->EE->TMPL->tagdata;
		$this->cache_lifetime = $this->EE->TMPL->fetch_param('refresh', $this->cache_lifetime) * 60;

		if ($tagdata != '' && $file)
		{

			$debug = (!$this->EE->TMPL->fetch_param('debug')) ? FALSE : TRUE;

			if ($debug || !$this->objTmp = $this->_readCache(md5($file)))
			{

				$getID3 = new getID3;

				$response = $getID3->analyze($file);


				$this->objTmp = self::_force_array($response);


/*
				foreach ($conds[0] as $k => $v)
				{
					if (is_array($v))
					{
						array_walk_recursive($v, create_function('&$v, $k, $t', 'if (!isset($t->aFlat[0][strtolower($k)])) {$t->flat[0][strtolower($k)] = $v;} else {$t->flat[0][strtolower($k)."_1"] = $v;}'),
							$tmp_obj
						);
						$this->objTmp[0][$k]  = $tmp_obj->flat;
					}else
					{
						$this->objTmp[0][strtolower($k)] = $v;
					}
				}
*/
				$this->_createCacheFile(json_encode($this->objTmp), md5($file));

			}
			else
			{
				$this->objTmp = json_decode($this->objTmp, true);

			}

			if ($debug)
			{
				$this->return_data .= $this->debug_table();
			}
//self::_force_array($response)
			return $this->return_data .= $this->EE->TMPL->parse_variables($tagdata, $this->objTmp);
		}
		return;
	}

	/**
	 * Force Array
	 *
	 * Take a totally mixed item and parse it into an array compatible with EE's Template library
	 *
	 * @access	private
	 * @param	mixed
	 * @return	string
	 */
	private function _force_array($var, $level = 1)
	{
		if (is_object($var))
		{
			$var = (array) $var;
		}

		if ($level == 1 && ! isset($var[0]))
		{
			$var = array($var);
		}

		if (is_array($var))
		{
			// Make sure everything else is array or single value
			foreach($var as $index => &$child)
			{
				$child = self::_force_array($child, $level + 1);

				if (is_object($child))
				{
					$child = (array) $child;
				}

				// Format dates to unix timestamps
				elseif (isset($this->EE->TMPL->date_vars[$index]) and ! is_numeric($child))
				{
					$child = strtotime($child);
				}

				// Format for EE syntax looping
				if (is_array($child) && ! is_int($index) && ! isset($child[0]))
				{
					$child = array($child);
				}
			}
		}

		return $var;
	}

	/**
	 * _createCacheFile function.
	 *
	 * @access private
	 * @param mixed $data

	 * @param mixed $key
	 * @return void
	 */
	private function _createCacheFile($data, $key)
	{
		$cache_path = APPPATH.'cache/' . MX_GETID3_KEY;
		$filepath = $cache_path ."/". $key;

		if (! is_dir($cache_path))
		{
			mkdir($cache_path . "", 0777, TRUE);
		}
		if (! is_really_writable($cache_path))
		{
			return;
		}
		if ( ! $fp = fopen($filepath, FOPEN_WRITE_CREATE_DESTRUCTIVE))
		{
			log_message('error', "Unable to write cache file: ".$filepath);
			return;
		}

		flock($fp, LOCK_EX);
		fwrite($fp, $data);
		flock($fp, LOCK_UN);
		fclose($fp);
		chmod($filepath, DIR_WRITE_MODE);

		log_message('debug', "Cache file written: " . $filepath);
	}

	/**
	 * _readCache function.
	 *
	 * @access private
	 * @param mixed $key
	 * @return void
	 */
	private function _readCache($key)
	{
		$cache = FALSE;
		$cache_path = APPPATH.'cache/' . MX_GETID3_KEY;
		$filepath = $cache_path ."/". $key;

		if ( ! file_exists($filepath))
		{
			return FALSE;
		}
		if ( ! $fp = fopen($filepath, FOPEN_READ))
		{
			@unlink($filepath);
			log_message('debug', "Error reading cache file. File deleted");
			return FALSE;
		}
		if ( ! filesize($filepath))
		{
			@unlink($filepath);
			log_message('debug', "Error getting cache file size. File deleted");
			return FALSE;
		}

		$cache_timeout = $this->cache_lifetime + (rand(0, 10) * 3600);

		if ( (filemtime($filepath) + $cache_timeout) < time() )
		{
			@unlink($filepath);
			log_message('debug', "Cache file has expired. File deleted");
			return FALSE;
		}

		flock($fp, LOCK_SH);
		$cache = fread($fp, filesize($filepath));
		flock($fp, LOCK_UN);
		fclose($fp);

		return $cache;
	}

	/**
	 * Generate the list of possible tags.
	 *
	 * @access public
	 * @return void
	 */
	function debug_table()
	{
		$r = '';

		$r .= "<table>";
		$r .= '<tr><td colspan="2">Tag</td><td>Value</td></tr>';
		foreach ($this->objTmp[0] as $v => $k)
		{
			if (is_array($k))
			{
				$r .= '<tr><td colspan="2">{'.$v.'}</td></tr>';

				foreach ($k[0] as $v1 => $k1)
				{
					$r .= '<tr><td width="50px;"></td><td>{'.$v1.'}</td><td>'.$k1.'</td></tr>';

				}
			}else
			{
				$r .= '<tr><td colspan="2">{'.$v.'}</td><td>'.$k.'</td></tr>';
			}

		}
		$r .= "</table>";
		return $r;
	}

	// ----------------------------------------
	//  Plugin Usage
	// ----------------------------------------

	// This function describes how the plugin is used.
	//  Make sure and use output buffering

	function usage()
	{
		ob_start();
?>
For full list of tags which you can use with file, you need to start plugin with parameter
debug='on'

Example:
{exp:mx_getid3 file='/mp3/dream.mp3'}

	{filesize}<BR/>
	{filename}<BR/>
	{filepath}<BR/>
	{filenamepath}<BR/>
	{avdataoffset}<BR/>
	{avdataend}<BR/>
	{fileformat}<BR/>
	{bitrate}<BR/>
	{playtime_string}<BR/>
{playtime_seconds}<BR/>

{id3v1}
			{title} {artist} {album} {year} {comment} {track} {genre} {padding_valid} {tag_offset_end} {tag_offset_start} {encoding}

{/id3v1}
{/exp:mx_getid3}



<?php
		$buffer = ob_get_contents();

		ob_end_clean();

		return $buffer;
	}


	/* END */

}

/* End of file pi.mx_getid3.php */
/* Location: ./system/expressionengine/third_party/mx_getid3/pi.mx_getid3.php */
