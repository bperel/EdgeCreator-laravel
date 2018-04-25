<?php
namespace App\Helpers;

class Publications {
    private $dmClient;

    public function __construct(DmClient $dmClient) {
        $this->dmClient = $dmClient;
    }

    function assignPublicationNames(&...$arrays) {
        $publicationList = [];
        foreach ($arrays as &$array) {
            $publicationList = array_merge($publicationList, array_map(function ($resultat) {
                return implode('/', [$resultat->pays, $resultat->magazine]);
            }, $array));
        }

        $publicationList = array_unique($publicationList);

        if (count($publicationList) > 0) {
            $noms_magazines = $this->dmClient->getServiceResults('GET', '/coa/list/publications', [implode(',', array_unique($publicationList))], 'edgecreator');

            foreach ($arrays as &$array) {
                foreach ($array as &$resultat) {
                    $publicationCode = implode('/', [$resultat->pays, $resultat->magazine]);
                    $resultat->magazine_complet = $noms_magazines->{$publicationCode};
                }
            }
        }
    }
}
