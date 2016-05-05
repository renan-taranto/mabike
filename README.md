mabike
======

A Symfony project created on February 26, 2016, 9:08 pm.

1-A HACK WAS MADE ON THE FOLLOWING JMSSERIALIZER's CLASSES IN ORDER TO REMOVE DOCTRINE's DISCRIMINATOR FIELD FOR ENTITIES WITH INHERITANCE.:
    1.1-JsonSerializationVisitor.php on lines 69 and 32 the following funcion is called:
        private function removeDiscriminatorIfNeeded(&$array = null)
	{
	    if (isset($array['type'])) {
	        unset($array['type']);
    	    }
        }
    1.1.2- This is a cleaner solution: https://github.com/schmittjoh/JMSSerializerBundle/issues/479 and should be implemented.
	
    1.2-XmlSerializationVisitor on line 313:
        if ($element->nodeName != 'type') {
            $this->currentNode->appendChild($element);
        }
