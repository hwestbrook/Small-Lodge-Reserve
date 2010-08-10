<?

/**
Validate an email address.
Provide email address (raw input)
Returns true if the email address has the email 
address format and the domain exists.
*/

function validEmail($email)
{
   $isValid = true;
   
   $atIndex = strrpos($email, "@");
      if (is_bool($atIndex) && !$atIndex)
      {
         $isValid = false;
      }
      else
      {
         $domain = substr($email, $atIndex+1);
         $local = substr($email, 0, $atIndex);
         $localLen = strlen($local);
         $domainLen = strlen($domain);
         if ($localLen < 1 || $localLen > 64)
         {
            // local part length exceeded
            $isValid = false;
         }
         else if ($domainLen < 1 || $domainLen > 255)
         {
            // domain part length exceeded
            $isValid = false;
         }
         else if ($local[0] == '.' || $local[$localLen-1] == '.')
         {
            // local part starts or ends with '.'
            $isValid = false;
         }
         else if (preg_match('/\\.\\./', $local))
         {
            // local part has two consecutive dots
            $isValid = false;
         }
         else if (!preg_match('/^[A-Za-z0-9\\-\\.]+$/', $domain))
         {
            // character not valid in domain part
            $isValid = false;
         }
         else if (preg_match('/\\.\\./', $domain))
         {
            // domain part has two consecutive dots
            $isValid = false;
         }
         else if (!preg_match('/^(\\\\.|[A-Za-z0-9!#%&`_=\\/$\'*+?^{}|~.-])+$/', str_replace("\\\\","",$local)))
         {
            // character not valid in local part unless 
            // local part is quoted
            if (!preg_match('/^"(\\\\"|[^"])+"$/',
                str_replace("\\\\","",$local)))
            {
               $isValid = false;
            }
         }
         if ($isValid && !(checkdnsrr($domain,"MX") || checkdnsrr($domain,"A")))
         {
            // domain not found in DNS
            $isValid = false;
         }
      }
   
   return $isValid;
}

/* Cleans phone numbers and strips extensions
 *
 *
 * Returns array [number,extension]
 * 
 */
function phoneClean($data){

   $pattern = '/\D*\(?(\d{3})?\)?\D*(\d{3})\D*(\d{4})\D*(\d{1,8})?/';
   if (preg_match($pattern, $data, $match)){
      if ($match[3]){
         if ($match[1]){
            $num = $match[1].'-'.$match[2].'-'.$match[3];
         }
         else{
            $num = $match[2].'-'.$match[3];
         }
      }
      else{
         $num = NULL;
      }
      $match[4] ? $ext = $match[4] : $ext = NULL;
   }
   else{
      $num = NULL;
      $ext = NULL;
   }
   $clean = array($num,$ext);
   return $clean;
}

/* Validates zip code for US
 *
 *
 * Returns true or false
 * 
 */
function validateUSAZip($zip_code)
{
  if(preg_match("/^([0-9]{5})(-[0-9]{4})?$/i",$zip_code))
    return true;
  else
    return false;
}
?>