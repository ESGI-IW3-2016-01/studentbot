<?php
namespace AppBundle\Controller;

trait TraitYoutube
{
    private function youtube($chaine) {
        
        $tab = explode(" ", $chaine);
        $recherche = "https://m.youtube.com/results?q=";
        foreach($tab as $word) {
            if ($word != "\xf0\x9f\x8e\xbc") {
                $recherche .= $word . "+";
            }
        }

        $htmlPage = file_get_contents($recherche);
        
        $link = explode("/watch?v=", $htmlPage);
        $realLink = explode('" ', $link[1]);
        
        $res = "https://m.youtube.com/watch?v=" . $realLink[0] ;

        return $res;
    }
}
