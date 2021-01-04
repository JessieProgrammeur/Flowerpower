<?php

    class Helper{
        
        public function field_validation($fields){
            
            if(is_array($fields)){
                $error = false;
                
                foreach($fields as $fieldname){
                    if(!isset($_POST[$fieldname]) || empty($fieldname)){
                        
                        $error = true;
                        echo "De volgende velden hebben een waarde nodig: $fieldname";
                    }
                }

                if(!$error){
                    return true;
                }

                return false;
            }else{
                echo "Velden kunnen niet doorlopen worden.";
            }
        }
    }
?>