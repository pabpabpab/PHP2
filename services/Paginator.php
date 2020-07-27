<?php

namespace App\services;

// use App\entities\Entity;
use App\repositories\GoodRepository as GoodRepository;
use App\repositories\UserRepository;

class Paginator
{
    protected $items = [];
    protected $baseRoute;
    protected $quantityPerPage;
    protected $repositoryName;

    public function __construct($entityName, $quantityPerPage)
    {
        $this->quantityPerPage = $quantityPerPage;
        $this->repositoryName = 'App\\repositories\\' . ucfirst($entityName) . 'Repository';
        $this->baseRoute = "/{$entityName}/all";
    }

    public function setItems($pageNumber = 1)
    {
        $this->items = (new $this->repositoryName())->getAllByPage($pageNumber, $this->quantityPerPage);
    }

    public function getItems(): array
    {
        return $this->items;
    }

    public function getUrls()
    {
        $pagesQuantity = (new $this->repositoryName())->getPagesQuantity($this->quantityPerPage);

        $urls = [];
        for ($i = 1; $i <= $pagesQuantity; $i++) {
            $urls[$i] = $this->baseRoute . '?page=' . $i;
        }

        return $urls;
    }
}
