<?php
/*
|--------------------------------------------------------------------------
| D3D Vars
|--------------------------------------------------------------------------
|
| user_id  -> User Login
| group_id -> Group Login
| 
|
*/

global $d3d;
$d3d['back']       = 1;
$d3d['user_id']    = 0;
$d3d['log_id']     = 0;
$d3d['avatar']     = "";
$d3d["page_act"]   = "";
$d3d["group_act"]  = "";
$d3d["page_title"] = "Portal TI";
$d3d["user_name"]  = "";

/* Javascript Functions *******************************************************************/

if (! function_exists('jsAlertD3D')) {
   function jsAlertD3D( $msg ) {
      echo '<script>alert("'.$msg.'");</script>';
      return null;
   }
}

if (! function_exists('jsRedirectD3D')) {
   function jsRedirectD3D( $go ) {
      echo '<script>window.location = "'.$go.'";</script>';
      return null;
   }
}

/* Vars Functions *************************************************************************/
if (! function_exists('getD3D')) {
   function getD3D( $var ) {
      global $d3d;
      if (isset($d3d[$var])) {
         return $d3d[$var];
      }
      return null;
   }
}

if (! function_exists('echoD3D')) {
   function echoD3D( $var ) {
      global $d3d;
      if (isset($d3d[$var])) {
         echo $d3d[$var];
      }
      return null;
   }
}

if (! function_exists('setD3D')) {
   function setD3D( $var, $value ) {
      global $d3d;
      $d3d[$var] = $value; 
      return null;
   }
}

/* Session Functions **********************************************************************/
if (! function_exists('initSessionD3D')) {
   function initSessionD3D( ) {
 //   session_name('Global'); 
 //   session_id('TEST');
      session_start ();
      initVarsD3D();
      return null;
   }
}

if (! function_exists('openSessionD3D')) {
   function openSessionD3D( $user ) {
      $_SESSION["user_id"]    = $user->id_user;
      $_SESSION["log_id"]     = $user->id_user;
      $_SESSION["user_name"]  = $rest = substr($user->fname, 0, 20);
      $_SESSION["so_act"]     = $user->id_sujeto_obligado;
      $_SESSION["so_tipo"]    = $user->id_so_atribucion;
      initVarsD3D();

      $CI =& get_instance();
      $CI->load->model("Solicitud_model", "log_ing");
      $CI->log_ing->initialize("sys_log");        
      $data_log = array();
      $data_log['id_log']       = 0;
      if (function_exists('getD3D')===true) {
//         $data_log['id_user']   = getD3D('user_id');
         $data_log['id_user']   = 0;
      } else {
         $data_log['id_user']   = 0;
      }
      $data_log['id_bis']       = 0;
      $data_log['type']         = 'login';
      $data_log['log']          = 'opensession';         
      $data_log['log_coments']  = '';
//      $data_log['id_type']      = '';
//      $data_log['log_status_change'] = '';
      $data_log['log_ip']            = $_SERVER['REMOTE_ADDR'];
//      $CI->log_ing->insert( $data_log ); 
      return null;
   }
}

if (! function_exists('closeSessionD3D')) {
   function closeSessionD3D( ) {
      session_start();
      session_unset(); 
      session_destroy();     
      setD3D("user_id", 0);
      setD3D("log_id",  0);
      return null;
   }
}

if (! function_exists('initVarD3D')) {
   function initVarD3D( $var, $vartype='n' ) {
      if (isset($_SESSION[$var])) { 
         setD3D($var, $_SESSION[$var]); 
      } else {
         if ($vartype = 'n') {
            setD3D($var, 0); 
         } else {
            setD3D($var, '');
         }
      }
   }

   function initVarsD3D( ) {
      initVarD3D("log_id");
      initVarD3D("user_name", 's');
      initVarD3D("user_id");
      initVarD3D("so_act");
      initVarD3D("so_tipo");
      return null;
   }
}


/* Debug Functions *********************************************************************/
if (! function_exists('doDebugD3D')) {
   function doDebugD3D( $textdebug='*' ) {
      if (DEBUGER==='Y') {
         $archivos = unserialize (DEBUGER_FILES);
         $go = false;
         foreach ($archivos as &$valor) {
            $encuentra = strpos($textdebug, $valor);
            if ($encuentra===0) {
               $go = true;
               break;
            }
         }
         if ($go === true) {
            $CI =& get_instance();
            $CI->load->model("Solicitud_model", "debug");      
            $CI->debug->initialize("sys_debug");
            $data = array("debug"   => $textdebug );
            $CI->debug->insert($data);
         } 
      }
   }	
}

if (! function_exists('resetDebugD3D')) {
   function resetDebugD3D( ) {
      if (DEBUGER==='Y') {
         $CI =& get_instance();
         $CI->load->model("Solicitud_model", "debug");      
         $CI->debug->initialize("sys_debug");
         $CI->debug->delDebug();
      }
   }	
}

if (! function_exists('getPermsFileD3D')) {
   function getPermsFileD3D( $permisos = '/' ) {
      $permisos = DIR_DATA . $permisos;
      $permisos = str_replace("//", "/", $permisos);
      $info = substr(sprintf('%o', fileperms( $permisos )), -4);
//echo $info;
      return $info;
   }
}

/* Security Functions *********************************************************************/
if (! function_exists('doCreateNameD3D')) {
   function doCreateNameD3D( $nombre='nombre', $apep='apep' ) {
      $username = str_replace(" ", "", strtolower($nombre . substr($apep, 0, 2)));
      $CI =& get_instance();
      $CI->load->model("Solicitud_model", "users");      
      $CI->users->initialize("sec_users");
      $repetido = $CI->users->countAllResults( 'username', $username  );
      if ($repetido > 0) {
         $total = $CI->users->countAll( );
         $username = $username . $total+1;
      }
      return $username;
   }	
}

if (! function_exists('doCreateClaveD3D')) {
   function doCreateClaveD3D( $long=7 ) {
      $clave = str_shuffle("abcdefghijklmnopqrstuvwxyz0123456789".uniqid());
      $clave = substr( $clave, 1, $long);
      return $clave;
   }	
}

if (! function_exists('getTokenD3D')) {
   function getTokenD3D( ) {
      $t = str_shuffle("abcdefghijklmnopqrstuvwxyz0123456789".uniqid());
      $t = substr( $t, 1, 40);
      return $t;
   }
}

if (! function_exists('putParamsD3D')) {
   function setParamsD3D( $params ) {
      $t = getTokenD3D( );
      $CI =& get_instance();
      $CI->load->model("Solicitud_model", "params");      
      $CI->params->initialize("sys_params");
      $data = array("id_token"   => $t, "params" => $params );
      $CI->params->insert($data);
      return $t;
   }
}

if (! function_exists('getParamsD3D')) {
   function getParamsD3D( $t ) {
      $CI =& get_instance();
      $CI->load->model("Solicitud_model", "params");      
      $CI->params->initialize("sys_params");
      $params = $CI->params->find1("id_token", '"' . $t . '"', "id_token");
      $paramstxt = '';
      foreach($params as $param) {
         $paramstxt = $param->params; 
      }      
      $CI->params->delParam( $t  );
      $params_array = parse_url($paramstxt, PHP_URL_QUERY);
      return $params_array;
   }
}

if (! function_exists('doMenuD3D')) {
   function doMenuD3D( ) {
      $CI =& get_instance();
      $CI->load->model("Dologin_model", "count");
      $CI->load->model("Sec_menu_model", "menus");
      $sections = $CI->menus->doSections();
// Open TOP Menu
      echo '<div id="sidebar-menu" class="main_menu_side hidden-print main_menu" style="margin-top:100px;">';

//echo 'Sections<br>';
      foreach($sections as $section) {
         echo '<div class="menu_section">';                            
         echo '<h3>'.$section->label.'</h3>';
         echo '<ul class="nav side-menu">';
 
         $menus = $CI->menus->doMenus( $section->id_option );
//echo '<br>Menus<br>';
         foreach($menus as $menu) {
            $menu_option = trim( $menu->option);
            $menu_len = strlen($menu_option);
            if ($menu_len == 0) {
               echo '<li><a><i class="'.$menu->icon.'"></i> '.$menu->label. '<span class="fa fa-chevron-down"></span></a>';
               echo '<ul class="nav child_menu" style="display: none">';
               $options = $CI->menus->doOptions( $menu->id_option );
//echo '<br>Options<br>';
               foreach($options as $option) {
	          if ( strlen($option->icon) > 0 ) {
                     $total =  ' ( ' . $CI->count->doCount($option->icon) . ' )';
                  } else {
                     $total =  '';
                  }
	          if ( strlen($option->parameters) > 0 ) {
                     echo '<li><a href="Sys_Hub?v='.$option->option.'&g='.$option->modul.'&' 
                          . $option->parameters.'&o='.base64_encode ($option->label) . '">'.$option->label . $total .' </a></li>';
	          } else {
                     echo '<li><a href="Sys_Hub?v='.$option->option.'&g='.$option->modul.'">'.$option->label . $total .' </a></li>';
	       }
            }
            echo '</ul>';                            
            echo '</li>';                            
         } else {
            echo '<li><a href="'.$menu->option.'"><i class="'.$menu->icon.'"></i> '.$menu->label.' </a></li>';
         }
      }
      echo '</ul>';                            
      echo '</div>';                            
    }
// Close TOP Menu
    echo '</div>';
    return null;
  }
}

if (! function_exists('encryptD3D')) {
   function encryptD3D($string, $key) {
      $result = '';
      for($i=0; $i<strlen($string); $i++) {
         $char = substr($string, $i, 1);
         $keychar = substr($key, ($i % strlen($key))-1, 1);
         $char = chr(ord($char)+ord($keychar));
         $result.=$char;
      }
      return base64_encode($result);
   }
}

if (! function_exists('decryptD3D')) {
   function decryptD3D($string, $key) {
      $result = '';
      $string = base64_decode($string);
      for($i=0; $i<strlen($string); $i++) {
         $char = substr($string, $i, 1);
         $keychar = substr($key, ($i % strlen($key))-1, 1);
         $char = chr(ord($char)-ord($keychar));
         $result.=$char;
      }
      return $result;
   }
}

/* Tools Functions ************************************************************************/
if (! function_exists('getAvatarD3D')) {
   function getAvatarD3D( ) {
      global $d3d;
      if ($d3d['avatar']=='') {
         $d3d['avatar'] = URL_AVATAR . $d3d['user_id'] . '.png';
            if (! file_exists(DIR_AVATAR . $d3d['user_id'] . '.png')) {
               $d3d['avatar'] = URL_AVATAR . $d3d['user_id'] . '.jpg';                    
               if (! file_exists(DIR_AVATAR . $d3d['user_id'] . '.jpg')) {
                  $d3d['avatar'] = URL_AVATAR . '0.png';   
               }
            }
      }
      return $d3d['avatar'];
   }
}

if (! function_exists('comprobarD3D')) {
   function comprobarD3D($cadena) { 
      if (strlen($cadena)<3 || strlen($cadena)>20){ 
         return false; 
      } 

      $permitidos = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789-_@."; 
      for ($i=0; $i<strlen($cadena); $i++){ 
         if (strpos($permitidos, substr($cadena,$i,1))===false){ 
            return false; 
         } 
      } 
      return true; 
   }
}


if (! function_exists('forceDownloadD3D')) {
   function forceDownloadD3D($Source_File, $Download_Name, $mime_type='') {
      /*
         $Source_File = path to a file to output  
         $Download_Name = filename that the browser will see 
         $mime_type = MIME type of the file (Optional)

         USE set_time_limit(0);  
         $file_path="phpgang.csv";
         forceDownloadD3D($file_path, 'phpgang.csv', 'application/csv');
      */

      if(!is_readable($Source_File)) die('File not found or inaccessible!'); 
      $size = filesize($Source_File);
      $Download_Name = rawurldecode($Download_Name);

      /* Figure out the MIME type (if not specified) */
      $known_mime_types=array(
         "pdf" => "application/pdf",
         "csv" => "application/csv",
         "txt" => "text/plain",
         "html" => "text/html",
         "htm" => "text/html",
         "exe" => "application/octet-stream",
         "zip" => "application/zip",
         "doc" => "application/msword",
         "xls" => "application/vnd.ms-excel",
         "ppt" => "application/vnd.ms-powerpoint",
         "gif" => "image/gif",
         "png" => "image/png",
         "jpeg"=> "image/jpg",
         "jpg" =>  "image/jpg",
         "php" => "text/plain"
      );
 
      if($mime_type==''){
         $file_extension = strtolower(substr(strrchr($Source_File,"."),1));
         if(array_key_exists($file_extension, $known_mime_types)) {
            $mime_type=$known_mime_types[$file_extension];
         } else {
            $mime_type="application/force-download";
         };
      };
 
      @ob_end_clean(); //off output buffering to decrease Server usage
  
      // if IE, otherwise Content-Disposition ignored
      if(ini_get('zlib.output_compression'))
         ini_set('zlib.output_compression', 'Off');
         header('Content-Type: ' . $mime_type);
         header('Content-Disposition: attachment; filename="'.$Download_Name.'"');
         header("Content-Transfer-Encoding: binary");
         header('Accept-Ranges: bytes');
         header("Cache-control: private");
         header('Pragma: private');
         header("Expires: Thu, 26 Jul 2012 05:00:00 GMT");
         // multipart-download and download resuming support
         if(isset($_SERVER['HTTP_RANGE'])) {
            list($a, $range) = explode("=",$_SERVER['HTTP_RANGE'],2);
            list($range) = explode(",",$range,2);
            list($range, $range_end) = explode("-", $range);
            $range=intval($range);
            if(!$range_end) {
               $range_end=$size-1;
            } else {
               $range_end=intval($range_end);
            }
            $new_length = $range_end-$range+1;
            header("HTTP/1.1 206 Partial Content");
            header("Content-Length: $new_length");
            header("Content-Range: bytes $range-$range_end/$size");
         } else {
            $new_length=$size;
            header("Content-Length: ".$size);
         }
         /* output the file itself */
         $chunksize = 1*(1024*1024); //you may want to change this
         $bytes_send = 0;
         if ($Source_File = fopen($Source_File, 'r')) {
            if(isset($_SERVER['HTTP_RANGE'])) fseek($Source_File, $range);
            while(!feof($Source_File) && (!connection_aborted()) && ($bytes_send<$new_length)) {
               $buffer = fread($Source_File, $chunksize);
               print($buffer); //echo($buffer); // is also possible
               flush();
               $bytes_send += strlen($buffer);
            }
            fclose($Source_File);
         } else die('Error - can not open file.');
         die();
      }
}

if (! function_exists('logWriteD3D')) {
   function logWriteD3D($cadena,$tipo) {
      $arch = fopen(realpath( '.' )."/milog_".date("Y-m-d").".txt", "a+"); 
      fwrite($arch, "[".date("Y-m-d H:i:s.u")." ".$_SERVER['REMOTE_ADDR']." ". $_SERVER['HTTP_X_FORWARDED_FOR']." - $tipo ] ".$cadena."\n");
      fclose($arch);
   }
}

?>
