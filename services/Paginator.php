<?php

namespace App\services;

// use App\entities\Entity;
use App\repositories\GoodRepository as GoodRepository;
use App\repositories\UserRepository;

class Paginator extends Service
{
    protected $items = [];
    protected $baseRoute;
    protected $quantityPerPage;
    protected $repository;

    public function setItems($entityName, $quantityPerPage, $pageNumber = 1)
    {
        $this->baseRoute = "/{$entityName}/all";

        $repositoryName = $entityName . 'Repository';
        $this->repository = $this->container->$repositoryName;

        $this->quantityPerPage = $quantityPerPage;

        $this->items = $this->repository->getAllByPage($pageNumber, $quantityPerPage);
    }

    public function getItems(): array
    {
        return $this->items;
    }

    public function getUrls()
    {
        $pagesQuantity = $this->repository->getPagesQuantity($this->quantityPerPage);

        $urls = [];
        for ($i = 1; $i <= $pagesQuantity; $i++) {
            $urls[$i] = $this->baseRoute . '?page=' . $i;
        }

        return $urls;
    }
}
