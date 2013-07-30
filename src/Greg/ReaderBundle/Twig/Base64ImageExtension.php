<?php
/**
 * Description of BlobTwigExtension
 *
 * @author greg
 */

namespace Greg\ReaderBundle\Twig;

use Symfony\Component\HttpFoundation\File\File;

class Base64ImageExtension extends \Twig_Extension {
    
    public function getFilters() {
        return array(
            'base64_encode' => new \Twig_Filter_Method($this, 'base64Encode'),
            'base64_decode' => new \Twig_Filter_Method($this, 'base64Decode'),
        );
    }
    
    public function getFunctions() {
        return array(
            'image64' => new \Twig_Function_Method($this, 'image64'),
        );
    }
    
    public function getName() {
        return 'base64';
    }
    
    public function image64($path)
    {
        $file = new File($path, false);
        
        if (!$file->isFile() || 0 !== strpos($file->getMimeType(), 'image/')) {
            return;
        }
        
        $binary = file_get_contents($path);
        
        return sprintf('data:image/%s;base64,%s', $file->guessExtension(), base64_encode($binary));
    }
    
    public function base64Encode($str) {
        return base64_encode($str);
    }
    
    public function base64Decode($str) {
        return base64_decode($str);
    }
}

?>
